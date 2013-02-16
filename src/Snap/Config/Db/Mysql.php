<?php

global $mysql_db_connections;

if ( !isset($mysql_db_connections) || !isset($mysql_db_connections[SITE_DB]) ){
	$mysql_db_connections[SITE_DB]['user'] 		= '';
	$mysql_db_connections[SITE_DB]['pwd']		= '';
	$mysql_db_connections[SITE_DB]['host'] 		= '';
	$mysql_db_connections[SITE_DB]['schema'] 	= '';
}