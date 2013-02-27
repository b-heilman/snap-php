<?php

namespace Snap\Prototype\Installation\Model\Form;

class Row extends \Snap\Model\Form {
	
	public
		$prototype;
	
	public function __construct( $prototype = null ){
		if ( $prototype != null && $prototype instanceof \Snap\Prototype\Installation\Lib\Prototype ){
			$this->prototype = $prototype;
		}else{
			throw new \Exception( get_class($this).' needs a prototype, instance of \Snap\Prototype\Installation\Lib\Prototype' );
		}
		
		$this->setUniqueTag( $prototype->name );
		
		parent::__construct();

		$inputs = array();
		foreach( $prototype->installs as $table => $installed ){
			$inputs[] = new \Snap\Lib\Form\Input\Checkbox( $table, 1, $installed );
		}
		$this->setInputs( $inputs );
	}
}	