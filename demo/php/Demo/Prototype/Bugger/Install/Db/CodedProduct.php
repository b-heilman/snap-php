<?php

namespace Demo\Prototype\Bugger\Install\Db;

class CodedProduct extends Product {
	
	public function getTable(){
		return 'coded_products';
	}
	
	public function getFields(){
		return parent::getFields() + array(
			'code'    => array( 'type' => 'varchar(32)' )
		);
	}
}