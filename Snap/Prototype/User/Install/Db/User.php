<?php

namespace Snap\Prototype\User\Install\Db;

use \Snap\Lib\Db\Definition;

class User {
	public function __construct(){
		$status = AUTH_STATUS_FIELD;
		$init = AUTH_INIT_STATUS;

		$options = array( 
			'engine' => 'InnoDB', 
			'PRIMARY KEY ( '.USER_ID.' )',
			'UNIQUE( '.USER_LOGIN.' )'
		);
		
		if ( USER_LOGIN != USER_DISPLAY ){
			$options[] = 'UNIQUE( '.USER_DISPLAY.' )';
		}
		
		Definition::addTable(USER_TABLE, $options);
		
		Definition::addTableField(USER_TABLE, USER_ID, 'int unsigned', false,
							array('AUTO_INCREMENT'));
		Definition::addTableField(USER_TABLE, USER_LOGIN, 'varchar(32)', false);
		Definition::addTableField(USER_TABLE, USER_PASSWORD, 'varchar(64)', false);
		
		if ( USER_LOGIN != USER_DISPLAY ) {
			Definition::addTableField(USER_TABLE, USER_DISPLAY, 'varchar(32)', false);
		}
		
		Definition::addTableField(USER_TABLE, AUTH_STATUS_FIELD, "ENUM('CREATED', 'ACTIVE', 'INACTIVE')",
							false, array("DEFAULT '$init'"));
		
		if ( USER_FB_ID ){
			Definition::addTableField(USER_TABLE, 'fb_id', 'int unsigned', false);
		}
							
		Definition::addTableField(USER_TABLE, 'status_date', 'datetime', false);
		Definition::addTableField(USER_TABLE, 'creation_date', 'timestamp', false,
							array("DEFAULT CURRENT_TIMESTAMP"));
							
		Definition::addTableField(USER_TABLE, 'external_login',  'bool', false, array("default '0'"));
		Definition::addTableField(USER_TABLE, USER_ADMIN, 'bool', false, array("default '0'"));

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
	}
}