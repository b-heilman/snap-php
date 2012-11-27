<?php

namespace Snap\Node\Controller;

abstract class Query extends \Snap\Node\Controller {
	
	private 
		$executable;
	
	protected function parseSettings( $settings = array() ){
		if ( isset($settings['query']) ){
			$this->setExecutable( $settings['query'] );
		}else{
			throw new \Exception('Controller\Query needs a query');
		}
		
		parent::parseSettings($settings);
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'query'   => 'either an instance of db_feed or db_executable'
		);
	}
	
	private function setExecutable( \Snap\Lib\Db\Query\Info $executable ){
		$this->executable = \Snap\Lib\Db\Executable::create( $executable );
	}
	
	protected function getExecutable(){
		return $this->executable;
	}
	
	protected function makeData( $input = array() ){
		$query = $this->getExecutable();
		
		$query->reset();
		
		if ( !($res = $query->exec()) ){
			throw new \Exception("query error: ".$query->getMsg());
		}else{
			return new \Snap\Lib\Mvc\Data( $res->hasNext() ? $res->asArray() : array() );
		}
	}
}