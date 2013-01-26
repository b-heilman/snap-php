<?php

namespace Snap\Prototype\User\Node;

use \Snap\Adapter\Db\Mysql;

class External extends \Snap\Node\Core\Form 
	implements \Snap\Node\Core\Consumer {
		
	protected 
		$external,
		$display,
		$stream,
		$consumed = false,
		$waitingQueue = null;
	
	/**
	 * Expect externals like:
	 * array( 'stream' => array( field => mapTo ) )
	 */
	protected function parseSettings( $settings = array() ){
		parent::parseSettings( $settings );
		
		$this->external = isset($settings['external']) ? $settings['external'] : array();
	}

	public static function getSettings(){
		return parent::getSettings() + array(
			'external' => 'the external streams to register to'
		);
	}

	public function needsData(){
		return true;
	}
	
	public function getStreamRequest(){
		return new \Snap\Lib\Streams\Request( array('user_src' => $this->external['stream']), $this );
	}
	
	public function isWaiting(){
		return $this->waitingQueue != null;
	}
	
	public function setWaitingQueue( \Snap\Lib\Streams\WaitingQueue $queue ){
		$this->waitingQueue = $queue;
	}
	
	public function hasConsumed(){
		return $this->consumed;
	}
	
	public function consumeRequest( \Snap\Lib\Streams\Request $request ){
		$this->consumed = true;
		
		$rtn = null; 
		$info = null; 
		$src = $this->external['stream'];
		$sess = new \Snap\Lib\Core\Session($this->external['stream']);
		
		$data = $request->getStreamData('user_src');
		
		if ( $data != null ){
			$external = $this->external['map'];
			$user_info = $data->get(0);
			
			$info = $this->pullData( $external, $user_info );
		}
			
		if ( $info != null ){
			if ( !isset($info['_id']) ){
				throw new \Exception("snap_add_external_user needs an _id field set.\n"
					. "This field will not get passed to the db.");
			}
			
			$id = $info['_id'];
			unset($info['_id']);
			
			$user = \Snap\Prototype\User\Lib\Element::get( array($src => $id) );
			
			if ( $user ){
				$this->loginUser( $user );
				$sess->clear();
			}else{
				$info[$src] = $id; // the field must be the same as the stream
				$info['external_login'] = 1; // let the system know this is external
				
				if ( $this->wasSubmitted() ){
					$proc = $this->getInput();
					
					$info[USER_DISPLAY] = $proc->getValue(USER_DISPLAY);
					
					$this->messaging = new \Snap\Node\Core\Block( array('tag' => 'div') );
	        		if ( $rtn = $this->insertUser($id, $src, $info, $this->messaging ) ){
	        			$sess->clear();
	        		}
				}
				
				if ( isset($info[USER_DISPLAY]) ){
					$this->display->input->changeValue( $info[USER_DISPLAY] );
				}
				
				if ( !$rtn ){
					$this->addClass( 'add-external-user' );
				}
			}
		}
		
		return $rtn;
	}
	
	protected function pullData( $paths, $data){
		$res = array();
		
		foreach( $paths as $key => $map ){
			if ( is_array($data) && isset($data[$key]) ){
				if ( is_array($map) ){
					$res += $this->pullData($map, $data[$key]);
				}else{
					$res[$map] = $data[$key];
				}
			}elseif( is_array($map) && isset($map[0]) ){
				foreach( $map as $m ){
					$res[$m] = $data;
				}
			}elseif( isset($map) && isset($data) && !empty($data) ){
				$res[$map] = $data;
			}
		}
		
		return $res;
	}
	
	protected function loginUser( $user ){
		\Snap\Prototype\User\Lib\Current::login( new \Snap\Prototype\User\Lib\Element($user) );
	}
	
	protected function insertUser($id, $src, $data, $notes){
		$login = $src.'_'.$id;
        $password = $src.'_'.$id;

        if ( $id = \Snap\Prototype\User\Lib\Element::create($login, $password, $data) ){
        	$notes->write('The Account Has Been Created');
        	
        	$this->loginUser( $id );
        	
        	return $id;
    	}else{
			// TODO : this is hard coded for Mysql, need to change that
    		if ( strpos(Mysql::lastError(), 'Duplicate entry') !== false ){
    			if ( strpos(Mysql::lastError(), USER_DISPLAY) ){
    				$notes->write('That '.strtolower(USER_DISPLAY_LABEL).' exists already!');
    			}else{
    				$notes->write(Mysql::lastError(), 'error');
    				$notes->write('That just will not work', 'error');
    			}
    		}
    		
    		return null;
    	}
	}
}