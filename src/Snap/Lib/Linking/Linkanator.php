<?php

// TODO : is this still used? 

define('linking_linkanator_INDEX',       '_INDEX_');
define('linking_linkanator_SHORT_TITLE', '_SHORT_TITLE_');
define('linking_linkanator_LONG_TITLE',  '_LONG_TITLE_');
define('linking_linkanator_TIME',        '_TIMESTAMP_');
define('linking_linkanator_CONTENT',     '_CONTENT_');

class linking_linkanator extends block_node {
	protected $target, $controllers, $displays, $options, $value = null;
	
	public function __construct( $settings = array() ){
	//	db_feed $target, $options = array(), $class = '', $id = ''){
		if ( !is_array($settings) || !isset($settings['target']) 
			|| !($settings['target'] instanceof db_feed) ){
			throw new \Exception('target needs to be set and an instance of db_feed for linking_linkanator');
		}
		
		$settings['tag'] = 'div';
		
		parent::__construct( $settings );
		
		$this->target = $settings['target'];
		$this->controllers = array();
		$this->displays = array();
		
		$this->options = $settings + array(
			'followingCount' => 1,
			'leadingCount' => 1,
			'linksMax' => 2,
			'modifyQuery' => array(),
			'overrideQuery' => array()
		);
	}
	
	protected function baseClass(){
		return 'linking_linkanator';
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'target'         => 'instance of db_feed used to populate the links',
			'followingCount' => 'max limit of links that follow the active link',
			'leadingCount'   => 'max limit of links that lead the active link',
			'linksMax'       => 'max number of links, first taking into account leading, and then following',
			'modifyQuery'    => 'used to modify the base query from the target',
			'overrideQuery'  => 'used to override the base query from the target'
		);
	}
	
	protected function pend( snapable_node $in ){
		if ( $in instanceof linking_control ){
			$this->controllers[] = $in;
		}
		
		if ( $in instanceof linking_displayer ){
			$this->displays[] = $in;
		}
		
		return parent::pend($in);
	}
	
	public function setValue( $value ){
		$this->value = $value;
	}
	
	protected function finalize(){
		$choice = null;
			
		foreach( $this->controllers as $controller ){
			$t = $controller->getValue();
			
			// TODO : I should make sure they are all based on the same variable
			
			if ( $choice == null ){
				$choice = $t;
			}elseif( $t != null && $choice != $t ){
				// TODO : so they don't match... what should I do.
			}
		}
		
		$value = ( $choice ) ? $choice : $this->value;
		
		$primary = $this->target->getPrimaryField();
		$classQuery = $this->target->getContentQuery();
		
		// processing current information
		$current = new db_query( array(
			'order by' => array($primary => false),
			'limit'    => 1
		) );
		
		if ( $value != null ){
			$current->modify( array('where' => array($primary => $value)) );
		}
		
		// $current->override( $this->options['overrideQuery'] );
		// $current->modify( $this->options['modifyQuery'] );
		
		$curr = $this->target->query( $current );
		$curr = ($curr)?array_shift($curr->asArray()):array();
		
		// processing previous information
		if ( $value != null ){
			$nextQuery = new db_query( array(
				'order by' => array($primary => true),
				'limit'    => $this->options['leadingCount'],
				'where' => new db_query_where(
					array( new db_query_where_expression($primary, '>' , $value) )
				)
			) );
			
			$nextQuery->modify( $classQuery );
			if ( !empty($this->options['overrideQuery']) ){
				$nextQuery->override( $this->options['overrideQuery'] );
			}
			if ( !empty($this->options['modifyQuery']) ){
				$nextQuery->modify( $this->options['modifyQuery'] );
			}
			
			$next = $this->target->query( $nextQuery );
			$next = ( $next ) ? $next->asArray() : array();
		}else{
			$next = array();
		}
		
		$others = $this->options['linksMax'] - count($next);
		$max = ( $others < $this->options['followingCount'] )?
			$others : $this->options['followingCount'];
		
		// processing previous information
		$prevQuery = new db_query( array(
			'order by' => array($primary => false)
		) );
		
		if ( $value != null ){
			$prevQuery->modify( 
				array(
					'where' => new db_query_where(
						array( new db_query_where_expression($primary, '<' , $value) )
					),
					'limit' => $max
				) 
			);
		}else{
			$prevQuery->modify( 
				array( 'limit' => '1, '.$max ) 
			);
		}
		
		$prevQuery->modify( $classQuery );
		if ( !empty($this->options['overrideQuery']) ){
			$prevQuery->override( $this->options['overrideQuery'] );
		}
		if ( !empty($this->options['modifyQuery']) ){
			$prevQuery->modify( $this->options['modifyQuery'] );
		}
		
		$prev = $this->target->query( $prevQuery );
		$prev = ($prev)?$prev->asArray():array();
		
		// now we submit the data
		if ( $curr != null ){
			foreach( $this->displays as $display ){
				$display->setData( $curr );
			}
			
			foreach( $this->controllers as $controller ){
				$controller->setPrevData( $prev );
				$controller->setCurrData( $curr );
				$controller->setNextData( $next );
			}
		}
		
		parent::finalize();
	}
}
