<?php

namespace Snap\Prototype\WebFS\Node\Controller;

use
	\Snap\Lib\Db,
	\Snap\Lib\Db\Query;

class Extensions extends \Snap\Node\Controller\Limiting {

	protected 
		$type;
	
	public function __construct( $settings = array() ){
		$where = new Query\Where();
		
		if ( isset($settings['extensions']) ){
			foreach( $settings['extensions'] as $ext ){
				$where->_or( new Query\Where\Expression('extension', '=', $ext) );
			}
		}
		
		$settings['query'] = new Db\Executable(
			new Db\Query(array(
				'from'  => WEBFS_TABLE,
				'where' => $where
			)),
			\Snap\Prototype\WebFS\Lib\File::getAdapter(),
			WEBFS_ID
		);
			
		parent::__construct( $settings );
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'extensions' => 'the extensions to use'
		);
	}
}