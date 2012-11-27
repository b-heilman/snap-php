<?php

namespace Snap\Lib\Db;

class Executable implements \Snap\Lib\DB\Query\Info {

	protected 
		$origQuery, 
		$query, 
		$adapter, 
		$primaryField;
	
	public function __construct( Query $query, \Snap\Adapter\Db $adapter, $primaryField ){
		$this->setQuery( $query );
		$this->setadapter( $adapter );
		$this->primaryField = $primaryField;
	}
	
	public function getPrimaryField(){
		return $this->primaryField;
	}
	
	public function setOrder( $fields = null ){
		if ( is_array($fields) ){
			$order = array('order by' => $fields);
		}else{
			$primaryField = $this->getPrimaryField();
				
			$order = array(
					'order by' => array($primaryField => (is_bool($fields) ? $fields : false))
			);
		}
		
		$this->override( $order );
	}
	
	public function setQuery( Query $query ){
		$this->origQuery = clone $query;
		$this->query = $query;
	}
	
	public function setAdapter( \Snap\Adapter\Db $adapter ){
		$this->adapter = $adapter;
	}
	
	public function getGroupingFields(){
		return $this->query->getGroupingFields();
	}
	
	public function override( $options ){
		$this->query->override($options);
	}
	
	public function modify( $options ){
		$this->query->modify($options);
	}
	
	public function reset(){
		$this->query = clone $this->origQuery;
	}
	
	public function exec(){
		return $this->adapter->query( $this->query );
	}
	
	public function getMsg(){
		return $this->adapter->lastQuery()."\n:>".$this->adapter->lastError();
	}
	
	public function __toString(){
		return $this->query->getSql( $this->adapter );
	}
	
	static public function create( Query\Info $query ){
		if ( $query instanceof db_feed ) {
			return new db_executable(
				$query->getContentQuery(), 
				$query->getAdapter(),
				$query->getPrimaryField()
			);
		}elseif( $query instanceof Executable ){
			return $query;
		}else{
			throw new \Exception(
				'query of class ' . get_class($query) . ' is not accepted to build a db_executable'
			);
		}
	}
}