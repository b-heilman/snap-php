<?php

namespace Snap\Prototype\Analytics\Node;

class Base extends \Snap\Node\Comment 
	implements \Snap\Node\Consumer {
		
	protected 
		$note = null, 
		$consumed = false,
		$waitingQueue = null;
	
	public function __construct( $settings  = array() ){
		$this->note = isset($settings['note']) ? $settings['note'] : null;
		if ( !isset($settings['comment']) ){
			$settings['comment'] = 'analytics';
		}
		
		parent::__construct( $settings );
	}

	public static function getSettings(){
		return self::getSettings() + array(
			'note' => 'analytics note for the db entry'
		);
	}
	
	public function getStreamRequest(){
		return new \Snap\Lib\Streams\Request('user_login', $this);
	}
	
	public function hasConsumed(){
		return $this->consumed;
	}
	
	public function needsData(){
		return !$this->consumed;
	}
	
	public function isWaiting(){
		return $this->waitingQueue != null;
	}
	
	public function setWaitingQueue( \Snap\Lib\Streams\WaitingQueue $queue ){
		$this->waitingQueue = $queue;
	}
	
	public function consumeRequest( \Snap\Lib\Streams\Request $request ){
		$this->consumed = true;
		
		$db = new \Snap\Adapter\Db\Mysql( ANALYTICS_DB );
		$data = $request->getStreamData('user_login');
		
		if ( !is_null($data) ){
			$db->update(
				ANALYTICS_TABLE,
				array( ANALYTICS_ID => \Snap\Prototype\Analytics\Lib\Session::getId() ),
				array( ANALYTICS_USER => \Snap\Prototype\User\Lib\Current::loggedIn() ?
					\Snap\Prototype\User\Lib\Current::getUser()->id() : null
				)
			);
		}

		$res = $db->insert(ANALYTICS_LOG_TABLE, array(
			ANALYTICS_ID => \Snap\Prototype\Analytics\Lib\Session::getId(),
			ANALYTICS_REFERER => isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'',
			ANALYTICS_URL => isset($_SERVER['REDIRECT_URL'])?
				$_SERVER['REDIRECT_URL'] : (isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:''),
			ANALYTICS_NOTE => $this->note
		));
	}
}