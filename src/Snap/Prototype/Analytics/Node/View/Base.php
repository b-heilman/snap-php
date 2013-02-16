<?php

namespace Snap\Analytics\Node\View;

class Base extends \Snap\Node\Core\Block 
	implements \Snap\Node\Core\Styleable {
	
	protected 
		$paging_paginator;
	
	public function __construct( $settings = array() ){
		$settings['tag'] = 'div';
		
		parent::__construct( $settings );
		
		$query = array(
			'select' => array(
				ANALYTICS_ID,
				ANALYTICS_IP,
				'creation_date',
				ANALYTICS_USER
			)
		);
		
		if ( isset($settings['logic']) && ($settings['logic'] instanceof \Snap\Lib\Db\Query\Where) ){
			$query['where'] = $settings['logic']; 
		}
		
		$from = new \Snap\Lib\Db\Query\From( 'analytics' );
		$from->join( 'a_id', 'analytic_logs', 'a_id' );
		$from->join( 'u_id', 'users', 'user_id', \Snap\Lib\Db\Query\From::$LEFT_JOIN );
		
		$query = new \Snap\Lib\Db\Query(array(
			'select'   => array( 
				'User'    => 'users.display', 
				'IP'      => 'analytics.ip', 
				'Browser' => 'analytics.browser', 
				'Count'   => new \Snap\Lib\Db\Query\Select\Action('count', '*') 
			),
			'from'     => $from,
			'where'    => "analytics.browser LIKE 'Mozilla%'",
			'group by' => 'analytics.a_id'
		));
		
		$this->paging_paginator = new \Snap\Lib\Paging\Paginator(array(
			'target'        => new \Snap\Lib\Db\Executable( $query, $t->getDefaultHandler() ),
			'groupCount'    => 40
		));
	}
	
	public function baseClass(){
		return 'analytics_base_view';
	}
	
	public function getStyles(){
		return array(
			new \Snap\Lib\Linking\Resource\Relative( $this )
		);
	}
}