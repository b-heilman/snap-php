<?php

namespace Snap\Node\Controller;

class Paging extends \Snap\Node\Controller\Query {
	
	protected 
		$url,
		$perPage, 
		$active;
	 
	public function __construct( $settings = array() ){
		parent::__construct($settings);
		
		$this->perPage = isset($settings['perPage']) ? $settings['perPage'] : 10;
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'perPage'  => 'number of items per page'
		);
	}
	
	protected function makeData( $input = array() ){
		$choice = $this->getUrlValue();
		$ctrl = new \Snap\Lib\Mvc\Control( $this->factory );
		
		$active = ( (is_null($choice) ? $this->active : $choice) - 1 );
		$query = $this->getExecutable();
		
		$query->override(array(
			'limit'    => ($this->perPage * $active).','.$this->perPage
		));
		
		$ctrl->variables->set( 'active', $active );
		
		$curr = $query->exec();
		$ctrl->data->add( $curr->asArray() );
		
		$query->reset();
			
		$fields = $query->getGroupingFields();
		
		if ( $fields ){
			$query->override( array(
				'select'   => array( 'count' => 
					new \Snap\Lib\Db\Query\Select\Action('count', 'distinct '.implode(',',$fields)) ),
				'group by' => false
			) );
		}else{
			$query->override( array(
				'select'   => array( 'count' => new \Snap\Lib\Db\Query\Select\Action('count', '*') )
			) );
		}
		
		$curr = $query->exec();
		$ctrl->variables->set( 'pages', ceil($curr->nextVal('count')/$this->perPage) );
		
		return $ctrl;
	}
}