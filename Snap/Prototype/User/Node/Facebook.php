<?php

namespace Snap\Prototype\User\Node;

class Facebook extends \Snap\Node\Core\Block 
	implements \Snap\Node\Core\Producer {
		
	protected 
		$rtn_url, 
		$state, 
		$logoutRow, 
		$logoutButton, 
		$loginLink, 
		$processResult = false, 
		$streamer = null;
	
	public function __construct( $settings = array() ){
		$this->stream = USER_FB_FIELD;
		
		$rtn = (!empty($_SERVER['HTTPS']))
			? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] 
			: "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			
		$this->rtn_url = preg_replace('/&?state=[^&]*/', '', preg_replace('/&?code=[^&]*/', '', $rtn));
		$l = strlen($this->rtn_url);
		if ( $this->rtn_url{strlen($this->rtn_url) - 1} == '?' ){
			$this->rtn_url = substr($this->rtn_url, 0, $l - 1);
		}
		
		$sess = new \Snap\Lib\Core\Session($this->stream);
		$this->state = $sess->getVar('fb_state');
		
		if ( $this->state == null ){
			$this->state = md5( uniqid(rand(), TRUE) ); //CSRF protection
			$sess->setVar('fb_state', $this->state);
		}
		
		$dialog_url = "http://www.facebook.com/dialog/oauth?"
			. "client_id=" . USER_FB_ID 
			. "&redirect_uri=" . urlencode($this->rtn_url)
			. "&state=". $this->state;
		
		$this->loginLink = new \Snap\Node\Core\Text($dialog_url);
			
		// $this->loginLink->write('Facebook', 'login-text');
		
		parent::__construct( $settings );
	}
	
	public function getOuputStream(){
		return $this->stream;	
	}
	
	public function build(){
		$this->append( $this->loginLink );
	}
	
	protected function _process(){
		$proc = null;
		
		$sess = new \Snap\Lib\Core\Session($this->stream);
			
		if( $tmp = $sess->getVar('fb_login') ){
			$proc = $tmp;
		}elseif( isset($_GET['state']) && isset($_GET['code']) ){
			if ( $_GET['state'] == $this->state ) {
				try {
					$token_url = "https://graph.facebook.com/oauth/access_token?"
						. "client_id=" . USER_FB_ID 
						. "&redirect_uri=" . urlencode($this->rtn_url)
						. "&client_secret=" . USER_FB_SECRET 
						. "&code=" . $_GET['code'];
				
					$response = @file_get_contents($token_url);
					
					if ( $response ){
						$params = null;
						parse_str($response, $params);
				
						$graph_url = "https://graph.facebook.com/me?"
							. "access_token=" . $params['access_token'];
					
						if ( !($proc = json_decode(@file_get_contents($graph_url), true)) ){
							$proc = null;
						}else{
							$sess->setVar('fb_login', $proc);
							
							header( 'Location: '.$this->rtn_url ) ;
						}
					}
				}catch( Exception $ex ){
					$this->debug( 'error 2' );
					// error, not sure what I want to do
				}
			}
		}
		
		$this->processResult = $proc;
	}
	
	public function setStreamer( \Snap\Lib\Streams\Streamer $streamer ){
		$this->streamer = $streamer;
	}
	
	public function hasStreamer(){
		return !is_null($this->streamer);
	}
	
	public function hasProduced(){
		return $this->processResult !== false;
	}
	
	public function produceStream(){
		if ( $this->processResult === false ){
			$this->_process();
		}
		
		return $this->processResult;
	}
	
	protected function _finalize(){
		parent::_finalize();
		
		if ( \Snap\Prototype\User\Lib\Current::loggedIn() ){
			$sess = new \Snap\Lib\Core\Session($this->stream);
			$sess->unsetVar('fb_login');
			
			$this->addClass('facebook-login-inactive');
		}else{
			$this->addClass('facebook-login-active');
		}
	}
}