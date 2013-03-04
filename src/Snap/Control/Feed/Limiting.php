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
	
	protected function getPrimaryRow( \Doctrine\ORM\QueryBuilder $qb, $value ){
		$res = false;
			
		$qb = clone($qb);
		
		$qb->add( 'where', 'target.id = :id' )
			->setParameter( 'id', $value )
			->setMaxResults( 1 );

		$q = $qb->getQuery();
		return $q->getSingleResult();
	}
	
	protected function getPrevRows( \Doctrine\ORM\Query $qb, $rowId ){
		return $this->getRows( $query, $primaryRow, self::$lt, $this->prevMax );
	}
	
	protected function getNextRows( \Doctrine\ORM\Query $qb, $rowId ){
		return $this->getRows( $query, $primaryRow, self::$gt, $this->nextMax );
	}
	
	protected function getRows( \Doctrine\ORM\Query $qb, $rowId, $op, $limit ){
		$qb = clone($qb);
		
		if ( $limit != -1 ){
			$qb->setMaxResults( $limit );
		}
		
		$qb->add('orderBy', 'target.id DESC')
			->add( 'where', 'target.id '.($op ? '>' : '<').' :id')
			->setParameter( 'id', $rowId );
		
		$q = $qb->getQuery();
		return $q->getResult();
	}
	
	protected function getAllRows( \Doctrine\ORM\QueryBuilder $qb ){
		//$qb = clone($qb);
		
		if ( $this->prevMax != -1 ){
			$qb->setMaxResults( $this->prevMax );
		}
		
		$q = $qb->getQuery();
		return $q->getResult();
	}
	
	protected function makeData( $input = array() ){
		$data = new \Snap\Lib\Mvc\Data\Collection();
		
		try{
			$qb = $this->getQueryBuilder();
			$value = $this->getUrlValue();
			
			if ( is_null($value) ){
				$tmp = $this->getAllRows( $qb );
				if ( count($tmp) > 0 ){
					$data->add( $tmp );
					$data->setVar( 'active', 0 );
				}
			}else{
				$primaryRow = $this->getPrimaryRow( $qb, $value );
				
				if ( $primaryRow != null ) {
					$tmp = $this->getNextRows( $qb, $primaryRow->getId() );
					if ( count($tmp) > 0 ){
						$data->add( $tmp );
					}
					
					$data->setVar( 'active', $data->count() );
					$data->push( $primaryRow );
					
					$tmp = $this->getPrevRows( $qb, $primaryRow->getId() );
					if ( count($tmp) > 0 ){
						$data->add( $tmp );
					}
				}
			}
		}catch( \Exception $ex ){
			error_log( $ex->getMessage().' - '.$ex->getFile().' : '.$ex->getLine() );
			error_log( $ex->getTraceAsString() );
		}
		
		return $data;
	}
}