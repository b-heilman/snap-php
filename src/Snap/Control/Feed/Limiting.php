<?php

namespace Snap\Control\Feed;

use \Snap\Lib\Db\Executable;

class Limiting extends NavigationQuery {
	
	protected 
		$prevMax, 
		$nextMax, 
		$active;
	
	protected static 
		$gt = true,
		$lt = false;
	
	protected function parseSettings( $settings = array() ){
		$this->prevMax = isset($settings['prevMax']) ? $settings['prevMax'] : 10;
		$this->nextMax = isset($settings['nextMax']) ? $settings['nextMax'] : 10;
	
		parent::parseSettings($settings);
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'prevMax'      => 'number of previous elements returned',
			'nextMax'      => 'number of next elements returned',
			'active'       => 'the default active to display'
		);
	}
	
	protected function getPrimaryRow( \Snap\Lib\Db\Executable $query ){
		$res = false;
		$choice = $this->getUrlValue();
		$primaryField = $query->getPrimaryField();
			
		$query->reset();
		
		if ( $choice != null ){
			$query->modify( array('where' => array($primaryField => $choice)) );
			$res = $query->exec();
		}
		
		if ( !$res || !$res->hasNext() ){
			$query->reset();
			
			$query->override(
				array(
				'order by' => array($primaryField => false),
				'limit'    => 1
				) 
			);
			
			$res = $query->exec();
		}
		
		if ( !$res ){
			throw new \Exception("query error: ".$query->getMsg());
		}
		
		return ( $res->hasNext() ) ? $res->next() : null;
	}
	
	protected function getPrevRows( Executable $query, $primaryRow ){
		return $this->getRows( $query, $primaryRow, self::$lt, $this->prevMax );
	}
	
	protected function getNextRows( Executable $query, $primaryRow ){
		return $this->getRows( $query, $primaryRow, self::$gt, $this->nextMax );
	}
	
	protected function getRows( Executable $query, $primaryRow, $op, $limit ){
		$primaryField = $query->getPrimaryField();
		
		$query->reset();
		$query->setOrder(false);
		
		if ( $this->prevMax != -1 ){
			$query->override( array('limit' => $limit) );
		}
		
		$query->modify( array(
			'where' => new \Snap\Lib\Db\Query\Where(
				array( new \Snap\Lib\Db\Query\Where\Expression($primaryField, $op ? '>' : '<', $primaryRow[$primaryField]) )
			)
		) );
		
		if ( !($res = $query->exec()) ){
			throw new \Exception("query error: ".$query->getMsg());
		}
		
		return $res->hasNext() ? $res->asArray() : array();
	}
	
	protected function getAllRows( Executable $query ){
		$query->reset();
		$query->setOrder(false);
		
		if ( $this->prevMax != -1 ){
			$query->override( array('limit' => $this->prevMax) );
		}
		
		if ( !($res = $query->exec()) ){
			throw new \Exception("query error: ".$query->getMsg());
		}
		
		return $res->hasNext() ? $res->asArray() : array();
	}
	
	protected function makeData( $input = array() ){
		$data = new \Snap\Lib\Mvc\Data\Collection();
		
		$query = $this->getExecutable();
		
		if ( is_null($this->getUrlValue()) ){
			$tmp = $this->getAllRows( $query );
			if ( count($tmp) > 0 ){
				$data->add( $tmp );
				$data->setVar( 'active', 0 );
			}
		}else{
			$primaryRow = $this->getPrimaryRow( $query );
			
			if ( $primaryRow != null ) {
				$tmp = $this->getNextRows( $query,$primaryRow );
				if ( count($tmp) > 0 ){
					$data->add( $tmp );
				}
				
				$data->setVar( 'active', $data->count() );
				$data->push( $primaryRow );
				
				$tmp = $this->getPrevRows( $query,$primaryRow );
				if ( count($tmp) > 0 ){
					$data->add( $tmp );
				}
			}
		}
		
		return $data;
	}
}