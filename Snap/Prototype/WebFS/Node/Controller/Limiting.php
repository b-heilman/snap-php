<?php

namespace Snap\Prototype\WebFS\Node\Controller;

class Limiting extends \Snap\Node\Limiting\Controller {

	protected 
		$type;
	
	public function __construct( $settings = array() ){
		$settings['query'] = new \Snap\Lib\Db\Executable(
			new \Snap\Lib\Db\Query(array(
				'from'     => WEBFS_TABLE
			)),
			\Snap\Prototype\WebFS\Lib\File::getAdapter(),
			WEBFS_ID
		);
			
		parent::__construct( $settings );
	}
}