<?php

namespace Snap\Control\Feed;

class Listing extends Query {

	protected function getAllRows( \Snap\Lib\Db\Executable $query ){
		$query->reset();
		$query->setOrder();
		
		if ( !($res = $query->exec()) ){
			throw new \Exception("query error: ".$query->getMsg());
		}
		
		return $this->processResult( $res );
	}
	
	protected function processResult( \Snap\Lib\Db\Result $res ){
		if ( $res->hasNext() ){
			return $res->asArray();
		}else return array();
	}
	
	protected function makeData( $input = array() ){
		$ctrl = new \Snap\Lib\Mvc\Control( $this->factory );
		
		$query = $this->getExecutable();
		
		$ctrl->data->stack->merge( $this->getAllRows( $query ) );
		
		return $ctrl;
	}
}