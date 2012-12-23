<?php

namespace Snap\Prototype\Installation\Node\Controller;

class Prototype extends \Snap\Node\Controller {
	protected function makeData(){
		$rtn = new \Snap\Lib\Mvc\Data();
		
		$prototypes = \Snap\Lib\Core\Bootstrap::getAvailablePrototypes();
		
		foreach( $prototypes as $prototype ){
			$rtn->add( new \Snap\Prototype\Installation\Lib\Prototype($prototype) );
		}

		return $rtn;
	}
}