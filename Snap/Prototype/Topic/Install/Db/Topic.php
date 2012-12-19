<?php

namespace Snap\Prototype\Topic\Install\Db;

use \Snap\Lib\Db;

\Snap\Lib\Core\Bootstrap::includeConfig( 'Snap/Prototype/Comment/Install/config.php' );

class Topic {

	public function __construct(){
		Db\Definition::addTable( TOPIC_TABLE, array('PRIMARY KEY ('.TOPIC_ID.')','UNIQUE ('.TOPIC_TITLE.', '.TOPIC_TYPE_ID.')'), 'InnoDB' );
		
		Db\Definition::addTableField(TOPIC_TABLE, TOPIC_ID, 'int unsigned', false, array('AUTO_INCREMENT'));
		Db\Definition::addTableField(TOPIC_TABLE, TOPIC_TITLE, 'varchar(64)', false);
		Db\Definition::addTableField(TOPIC_TABLE, 'content', 'text', false);
		Db\Definition::addTableField(TOPIC_TABLE, TOPIC_TYPE_ID, 'int unsigned', false);
		Db\Definition::addTableField(TOPIC_TABLE, TOPIC_COMMENT_THREAD, 'int unsigned', false);
		Db\Definition::addTableField(TOPIC_TABLE, 'creation_date', 'timestamp', false, array("DEFAULT CURRENT_TIMESTAMP"));
		Db\Definition::addTableField(TOPIC_TABLE, 'active', 'bool', false, array("default '1'"));
		
		Db\Definition::addTableRelation(TOPIC_TABLE, TOPIC_TYPE_ID, 
			TOPIC_TYPE_TABLE, TOPIC_TYPE_ID, 'CASCADE', 'RESTRICT');
			
		Db\Definition::addTableRelation(TOPIC_TABLE, TOPIC_COMMENT_THREAD, 
			COMMENT_DB.'.'.COMMENT_THREAD_TABLE, COMMENT_THREAD_ID, 'CASCADE', 'RESTRICT');
	}
}