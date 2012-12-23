<?php

namespace Snap\Prototype\Analytics\Install\Db;

use \Snap\Lib\Db\Definition;

class Stats {
	public function __construct(){
		Definition::addTable( ANALYTICS_LOG_TABLE, array('PRIMARY KEY ('.ANALYTICS_LOG_ID.')'), 'InnoDB' );
		
		Definition::addTableField( ANALYTICS_LOG_TABLE, ANALYTICS_LOG_ID, 'int unsigned', false, array('AUTO_INCREMENT') );
		Definition::addTableField( ANALYTICS_LOG_TABLE, ANALYTICS_ID, 'int unsigned', false );
		Definition::addTableField( ANALYTICS_LOG_TABLE, ANALYTICS_REFERER, 'text', false );
		Definition::addTableField( ANALYTICS_LOG_TABLE, ANALYTICS_URL, 'text', false );
		Definition::addTableField( ANALYTICS_LOG_TABLE, ANALYTICS_NOTE, 'text', true );
		Definition::addTableField( ANALYTICS_LOG_TABLE, 'creation_date', 'timestamp', false, array("DEFAULT CURRENT_TIMESTAMP") );
		
		Definition::addTableRelation( ANALYTICS_LOG_TABLE, ANALYTICS_ID, ANALYTICS_TABLE, ANALYTICS_ID, 'CASCADE', 'RESTRICT');
	}
}