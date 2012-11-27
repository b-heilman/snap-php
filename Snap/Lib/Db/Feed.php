<?php

namespace Snap\Lib\Db;

interface Feed extends \Snap\Lib\Db\Query\Info {
	public static function getAdapter();
	public function getContentQuery();        // ruturns db_query
	public function query( Query $query ); // pull out data after changes are made
}