<?php

namespace Snap\Prototype\Comment\Install\Db;

use \Snap\Lib\Db\Definition;

class Comment {
	public function __construct(){
		Definition::addTable( COMMENT_TABLE, array('PRIMARY KEY ('.COMMENT_ID.')'), 'InnoDB' );
		
		Definition::addTableField( COMMENT_TABLE, COMMENT_ID, 'int unsigned', false,array('AUTO_INCREMENT') );
		Definition::addTableField( COMMENT_TABLE, COMMENT_THREAD_ID, 'int unsigned', false );
		Definition::addTableField( COMMENT_TABLE, COMMENT_USER, 'int unsigned', false );
		Definition::addTableField( COMMENT_TABLE, 'content', 'text', false );
		Definition::addTableField( COMMENT_TABLE, 'creation_date', 'timestamp', false, array("DEFAULT CURRENT_TIMESTAMP") );
		Definition::addTableField( COMMENT_TABLE, 'active', 'bool', false, array("default '1'") );
		Definition::addTableField( COMMENT_TABLE, COMMENT_PARENT, 'int unsigned', true );
		/* TODO : drop support, this info is maintained in the software level
		Definition::addTableRelation(COMMENT_TABLE, COMMENT_USER, 
			USER_DB.'.'.USER_TABLE, USER_ID, 'CASCADE', 'RESTRICT');
			
		Definition::addTableRelation(COMMENT_TABLE, COMMENT_THREAD_ID, 
			COMMENT_THREAD_TABLE, COMMENT_THREAD_ID, 'CASCADE', 'RESTRICT');
			
		Definition::addTableRelation(COMMENT_TABLE, COMMENT_PARENT, 
			COMMENT_TABLE, COMMENT_ID, 'CASCADE', 'CASCADE');
		*/
	}
}