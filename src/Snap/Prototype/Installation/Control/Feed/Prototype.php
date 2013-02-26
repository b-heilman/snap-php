<?php

namespace Snap\Prototype\Installation\Control\Feed;

class Prototype extends \Snap\Control\Feed {
	public function __construct( $settings = array() ){
		parent::__construct($settings);
	}
	
	protected function makeData(){
		$rtn = new \Snap\Lib\Mvc\Data\Collection();
		
		$prototypes = \Snap\Lib\Core\Bootstrap::getAvailablePrototypes();
		
		foreach( $prototypes as $prototype ){
			$rtn->add( new \Snap\Prototype\Installation\Lib\Prototype($prototype) );
		}
		
		return $rtn;
	}
}