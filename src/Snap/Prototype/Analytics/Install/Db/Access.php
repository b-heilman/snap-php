<?php

namespace Snap\Prototype\Analytics\Install\Db;

use \Snap\Lib\Db\Definition;

class Access {
	public function __construct(){
		Definition::addTable( ANALYTICS_TABLE, array('PRIMARY KEY ('.ANALYTICS_ID.')'), 'InnoDB' );
		
		Definition::addTableField( ANALYTICS_TABLE, ANALYTICS_ID, 'int unsigned', false, array('AUTO_INCREMENT') );
		Definition::addTableField( ANALYTICS_TABLE, ANALYTICS_USER, 'int unsigned', true );
		Definition::addTableField( ANALYTICS_TABLE, ANALYTICS_IP, 'varchar(25)', false );
		Definition::addTableField( ANALYTICS_TABLE, ANALYTICS_BROWSER, 'text', false );
		Definition::addTableField( ANALYTICS_TABLE, 'creation_date', 'timestamp', false, array("DEFAULT CURRENT_TIMESTAMP") );
		/*					
		Definition::addTableRelation(ANALYTICS_TABLE, ANALYTICS_USER, 
			USER_DB.'.'.USER_TABLE, USER_ID, 'CASCADE', 'RESTRICT');
		*/
	}
}