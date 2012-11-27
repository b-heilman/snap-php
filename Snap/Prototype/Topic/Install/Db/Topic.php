<?php

namespace Snap\Prototype\Topics\Install\Db;

use \Snap\Lib\Db\Defintion;

class Topic {

	public function __construct(){
		Defintion::addTable( TOPIC_TABLE, array('PRIMARY KEY ('.TOPIC_ID.')','UNIQUE ('.TOPIC_TITLE.', '.TOPIC_TYPE_ID.')'), 'InnoDB' );
		
		Defintion::addTableField(TOPIC_TABLE, TOPIC_ID, 'int unsigned', false, array('AUTO_INCREMENT'));
		Defintion::addTableField(TOPIC_TABLE, TOPIC_TITLE, 'varchar(64)', false);
		Defintion::addTableField(TOPIC_TABLE, 'content', 'text', false);
		Defintion::addTableField(TOPIC_TABLE, TOPIC_TYPE_ID, 'int unsigned', false);
		Defintion::addTableField(TOPIC_TABLE, TOPIC_COMMENT_THREAD, 'int unsigned', false);
		Defintion::addTableField(TOPIC_TABLE, 'creation_date', 'timestamp', false, array("DEFAULT CURRENT_TIMESTAMP"));
		Defintion::addTableField(TOPIC_TABLE, 'active', 'bool', false, array("default '1'"));
		
		Defintion::addTableRelation(TOPIC_TABLE, TOPIC_TYPE_ID, 
			TOPIC_TYPE_TABLE, TOPIC_TYPE_ID, 'CASCADE', 'RESTRICT');
			
		Defintion::addTableRelation(TOPIC_TABLE, TOPIC_COMMENT_THREAD, 
			COMMENT_DB.'.'.COMMENT_THREAD_TABLE, COMMENT_THREAD_ID, 'CASCADE', 'RESTRICT');
	}
}