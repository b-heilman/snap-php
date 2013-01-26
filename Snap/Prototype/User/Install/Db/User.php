<?php

namespace Snap\Prototype\User\Install\Db;

class User 
	implements \Snap\Prototype\Installation\Lib\Definition {
	
	public function getTable(){
		return USER_TABLE;
	}
	
	public function getTableEngine(){
		return 'InnoDB';
	}
	
	public function getTableOptions(){
		$options = array(
				'engine' => 'InnoDB',
				'PRIMARY KEY ( '.USER_ID.' )',
				'UNIQUE( '.USER_LOGIN.' )'
		);
		
		if ( USER_LOGIN != USER_DISPLAY ){
			$options[] = 'UNIQUE( '.USER_DISPLAY.' )';
		}
		
		return $options;
	}
	
	public function getFields(){
		$init = AUTH_INIT_STATUS;
		
		$fields = array(
			USER_ID            => array( 'type' => 'int unsigned', 'options' => array('AUTO_INCREMENT') ),
			USER_LOGIN         => array( 'type' => 'varchar(32)' ),
			USER_PASSWORD      => array( 'type' => 'varchar(64)' ),
			AUTH_STATUS_FIELD  => array( 'type' => "ENUM('CREATED', 'ACTIVE', 'INACTIVE')", 'options' => array("DEFAULT '$init'") ),
			'status_date'      => array( 'type' => 'datetime' ),
			'creation_date'    => array( 'type' => 'timestamp', 'options' => array("DEFAULT CURRENT_TIMESTAMP") ),
			'external_login'   => array( 'type' =>'bool', 'options' => array("default '0'") ),
			USER_ADMIN         => array( 'type' =>'bool', 'options' => array("default '0'") )
		);
		
		if ( USER_LOGIN != USER_DISPLAY ) {
			$fields[USER_DISPLAY] = array( 'type' =>'varchar(32)' );
		}
		
		if ( USER_FB_ID ){
			$fields['fb_id'] = array( 'type' =>'int unsigned' );
		}
		
		return $fields;
	}
	
	public function getPrepop(){
		return array();
	}
	// TODO : this should move to software layer
	/*
	Definition::addTableTrigger(USER_TABLE, 'UPDATE', 'BEFORE', "
		IF NEW.$status <> OLD.$status THEN
			SET NEW.status_date = NOW();
		END IF;
		"
	);

	Definition::addTableTrigger(USER_TABLE, 'INSERT', 'BEFORE', "
		SET NEW.status_date = NOW();
		SET NEW.status = '$init';
		"
	);
	*/
}