<?php

namespace Snap\Prototype\Installation\Node\Page;

use 
	\Snap\Node;

class Admin extends Node\Page\Basic
	implements Node\Core\Styleable {
	
	protected function getMeta(){
		return '';
	}
	
	protected function defaultTitle(){
		return 'Admin Console';
	}
	
	protected function getTemplateVariables(){
		$valid = true;
		try {
			$proto = new \Snap\Prototype\Installation\Lib\Prototype('\Snap\Prototype\User');
		}catch( \Exception $e ){
			$proto = null;
			$valid = false;
		}
		
		return array(
			'accessible' => $valid,
			'security'   => function() use ( $proto ) {
				static $tableExists = null;
				
				if ( $proto && is_null($tableExists) ){
					$tableExists = $proto->installed;
				}
				
				return \Snap\Prototype\User\Lib\Current::isAdmin() || !$tableExists;
			}
		);
	}
}