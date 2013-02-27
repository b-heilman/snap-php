<?php

namespace Snap\Control\Feed;

abstract class Query extends \Snap\Control\Feed {
	
	private 
		$query;
	
	protected function parseSettings( $settings = array() ){
		if ( isset($settings['query']) ){
			$this->setQuery( $settings['query'] );
		}else{
			throw new \Exception( get_class($this).' needs a query' );
		}
		
		parent::parseSettings($settings);
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'query'   => 'instance of \Doctrine\ORM\QueryBuilder'
		);
	}
	
	private function setQuery( \Doctrine\ORM\QueryBuilder $query ){
		$this->query = $query;
	}
	
	/**
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	protected function getQueryBuilder(){
		return $this->query;
	}
	
	protected function makeData( $input = array() ){
		try{
			$res = $this->query->getResult();
		}catch( \Exception $ex ){
			$res = array();
			error_log( $ex->getMessage().' - '.$ex->getFile().' : '.$ex->getLine() );
			error_log( $ex->getTraceAsString() );
		}
		
		return new \Snap\Lib\Mvc\Data\Collection( $res );
	}
}