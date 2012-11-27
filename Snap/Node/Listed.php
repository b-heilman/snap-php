<?php
// a class that hanldes the wrapping of elements for lists

namespace Snap\Node;

class Listed extends \Snap\Node\Block {
	public function __construct(  $settings = array() ){
		if ( !isset($settings['tag'])  )
			$settings['tag'] = 'ul';
			
		parent::__construct( $settings );
	}

	public function prepend( \Snap\Node\Snapable $in, $settings = array() ){
		$settings['tag'] = 'li';
		
		$t = new \Snap\Node\Block( $settings );
		$t->append($in);
		
		parent::prepend($t);

		return $t;
	}

	public function append( \Snap\Node\Snapable $in, $settings = array(), $ref = null ){
		$settings['tag'] = 'li';
		
		$t = new \Snap\Node\Block( $settings );
		$t->append($in);
		
		parent::append($t);

		return $t;
	}

	public function inner(){
		$this->inside->first(function($el){
				$el->addClass('first');
		});
		
		$this->inside->last(function($el){
				$el->addClass('last');
		});

		return parent::inner();
	}
}