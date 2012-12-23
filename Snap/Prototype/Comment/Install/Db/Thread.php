<?php

namespace Snap\Prototype\Comment\Install\Db;

use \Snap\Lib\Db\Definition;

class Thread {
	public function __construct(){
		Definition::addTable( COMMENT_THREAD_TABLE, array('PRIMARY KEY ('.COMMENT_THREAD_ID.')'), 'InnoDB' );
		
		Definition::addTableField( COMMENT_THREAD_TABLE, COMMENT_THREAD_ID, 'int unsigned', false, array('AUTO_INCREMENT') );
		Definition::addTableField( COMMENT_THREAD_TABLE, COMMENT_THREAD_USER, 'int unsigned', false);
		Definition::addTableField( COMMENT_THREAD_TABLE, 'creation_date', 'timestamp', false, array("DEFAULT CURRENT_TIMESTAMP") );
		Definition::addTableField( COMMENT_THREAD_TABLE, 'active', 'bool', false, array("default '1'") );
		
		Definition::addTableRelation( COMMENT_THREAD_TABLE, COMMENT_THREAD_USER, USER_DB.'.'.USER_TABLE, USER_ID, 'CASCADE', 'RESTRICT' );
	}
}