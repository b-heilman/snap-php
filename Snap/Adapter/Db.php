<?php

namespace Snap\Adapter;

interface Db {
	public function __construct( $schema, $independent = false, $host = '', $user = '', $pass = '' );

	public function info();

	public function accessable();
	public function generate();

	public function autocommit( $auto );
	public static function autocommitAll( $auto );

	public function commit();
	public static function commitAll();

	public function rollback();
	public static function rollbackAll();

	public function tableExists( $table );
	public function tableDrop( $table );

	public function query( $query );
	public function multi( $sql, $useResults = false );

	public function insert($table, $data);
	/**
	 * @return \Snap\Lib\Db\Result
	 * ---------------
	 * @param $table - the table to be queried
	 * @param $where - the where clause
	 * @param $columns - the columns to return
	 */
	public function select( $table, $where = '', $columns = array() );
	public function update( $table, $where, $data );
	public function delete( $table, $where );

	public function affectedRows();
	public function error();
	public function escStr( $string );
	public function insertedID();

	public function disableValidation();
	public function enableValidation();

	public function myQuery();
	public function myError();
	public static function lastQuery();
	public static function lastError();
}