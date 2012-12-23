<?php

namespace Snap\Adapter\Db;

use 
	Snap\Lib\Db;

class Mysql implements \Snap\Adapter\Db { // 1Drinkth33ar

	public static 
		$links,		// an array of all connections that are shared
		$connections; // an array of all connections

	protected static 
		$autocommit = true;

	private static 
		$stmt, 
		$result, 
		$lastError, 
		$lastQuery;

    protected 
		$schema, 
		$link;

	private 
		$mem,
		$error;

    public function __construct( $schema, $independent = false, $host = '', $user = '', $pass = '' ){
		global 
			$mysql_db_connections;

		$this->schema = $schema;

		if ( isset($mysql_db_connections[$schema]) ){

			$t = $mysql_db_connections[$schema];

			if ( $host == '' )
				$host = $t['host'];

			if ( $user == '' )
				$user = $t['user'];

			if ( $pass == '' )
				$pass = $t['pwd'];

			if ( isset($t['schema']) )
				$this->schema = $t['schema'];
		}else{
			$t = $mysql_db_connections[SITE_DB];

			if ( $host == '' )
				$host = $t['host'];

			if ( $user == '' )
				$user = $t['user'];

			if ( $pass == '' )
				$pass = $t['pwd'];

			if ( isset($t['schema']) )
				$this->schema = $t['schema'];
		}

	    if ( $independent ){
	    	$this->link = self::$connections[] = 
	    		new Db\Mysql\Link( $host, $user, $pass );
	    }elseif ( !isset(self::$links[$host.'-'.$user]) ){
	    	$this->link = self::$connections[] = self::$links[$host.'-'.$user] =
	    		new Db\Mysql\Link( $host, $user, $pass );
	    }else{
	    	$this->link = self::$links[$host.'-'.$user];
	    }

	    $this->link->connect();

		if ( mysqli_connect_errno() ) {
    		throw new \Exception( sprintf("Connect failed: %s\n", mysqli_connect_error()) );
	    }
	}

	public function accessable(){
		return $this->link->selectDB( $this->schema );
	}

	public function generate(){
		return $this->link->process("CREATE DATABASE {$this->schema}");
	}

	public function __wakeup(){
	    $this->link->connect();
	}

	public function ping(){
		return $this->link->connection->ping();
	}

	public function autocommit($auto){
		$this->link->autocommit($auto);
	}

	static public function autocommitAll($auto){
		foreach( self::$connections as $link ){
			$link->connection->autocommit($auto);
		}
	}

	public function commit(){
		$this->link->connection->commit();
	}

	static public function commitAll(){
		foreach( self::$connections as $link ){
			$link->connection->commit();
		}
	}

	public function rollback(){
		$this->link->connection->rollback();
	}

	static public function rollbackAll(){
		foreach( self::$connections as $link ){
			$link->connection->rollback();
		}
	}

	public function tableExists( $table ){
		$table = $this->escStr( $table );

		$search = explode('.', $table);

		if ( isset($search[1]) ){
			$res = $this->query(  "SHOW TABLES FROM {$search[0]} LIKE '$search[1]'" );
		}else{
			$res = $this->query(  "SHOW TABLES LIKE '$table'" );
		}

		return $res && $res->hasNext();
	}

	public function tableDrop($table){
		$table = $this->escStr( $table );

		return $this->query("DROP TABLE IF EXISTS `$table`;");
	}

	public function select($table, $data = array(), $columns = array('*')){
		if ( $data instanceof Db\Query ) {
			$q = new Db\Mysql\Query($data);

			$q->setPrimaryTable($table);

			$query = $q->getSql( $this );
		}else{
			$query = "SELECT ".implode(',', $columns);
			$query .= " FROM `$table`";
			$switch = false;
			if ( !empty($data) ){
				$query .= " WHERE ";

				foreach ($data as $key => $val)
					{
					if ($switch){
						$query .= "AND";
					}else
						$switch = true;

					if ( $val === null ){
						$query .= " `$key` IS NULL ";
					}else{
						$val = $this->escStr($val);
						$query .= " `$key` = '$val' ";
					}
				}
			}
		}

		return $this->query($query);
	}

	public function delete($table, $data){
		$query = "DELETE ";
		$query .= " FROM `$table`";
		$switch = false;
		
		if ($data !== ''){
			$query .= " WHERE ";

			foreach ($data as $key => $val)
				{
				if ($switch){
					$query .= "AND";
				}else
					$switch = true;

				$val = $this->escStr($val);
				$query .= " `$key` = '$val' ";
			}
		}

		return $this->query($query);
	}

	public function insert($table, $data){
		$fields = array_keys($data);
		$values = array_values($data);

		foreach ( $values as $key => $val ){
			if ( is_null($val) ){
				unset($fields[$key]);
				unset($values[$key]);
			}else{
				$values[$key] = $this->escStr($val);
			}
		}

		$fields = implode($fields, '`,`');
		$values = implode($values, "','");

		$query = "INSERT INTO `$table` (`$fields`) VALUES ('$values');";

		return $this->query( $query );
	}

	public function insertedID(){
		//todo do I gotta check statement?
		return $this->link->connection->insert_id;
	}

	public function update($table, $where, $data){
		$query = "UPDATE `$table` SET";
		$switch = false;
		
		foreach ($data as $key => $val){
			if ($switch){
				$query .= ",";
			}else
				$switch = true;

			if ( $val === null )
			    $query .= " `$key` = NULL ";
			else
			    $query .= " `$key` = '{$this->escStr($val)}' ";
		}

		$query .= 'WHERE ';
		$switch = false;
		
		foreach ($where as $key => $val){
			if ($switch){
				$query .= "AND";
			}else
				$switch = true;

			$query .= " `$key` = '$val' ";
		}

		return $this->query( $query );
	}

	// standard way of creating a query
	public function query( $query ){
		if ( $query instanceof Db\Query ){
			$query = $query->getSql( $this );
		}elseif ( !is_string($query) ){
			throw new \Exception('db_mysql_adapter requires a string.');
		}
		
		$query = ltrim($query);

		if ( is_object(self::$stmt) ){
			self::$stmt->close();
		}
		
		self::$stmt = null;

		if ( is_object(self::$result) ){
			@self::$result->close();
		}
		
		$this->mem = self::$lastQuery = $query;

		if ( !$this->link->selectDB($this->schema) ){
			throw new \Exception('Failed selecting '.$this->schema);
		}

		self::$result = $this->link->processQuery($query);

		if( $this->link->connection->error != '' ){
			$this->error = self::$lastError = $this->link->connection->error;
		}
		
		return ( self::$result !== false )
			? (is_object(self::$result) ? new Db\Mysql\Result(self::$result):true)
			: ( $this->link->connection->errno === 0 );
	}

	public function multi( $sql, $useResults = false ){
		if ( is_object(self::$stmt) ){
			self::$stmt->close();
		}
		
		self::$stmt = null;

		if ( is_object(self::$result) ){
			@self::$result->close();
		}
		
		$this->mem = self::$lastQuery = $sql;

		if ( !$this->link->selectDB($this->schema) ){
			throw new \Exception('Failed selecting '.$this->schema);
		}

		$res = $this->link->processMulti($sql, $useResults);

		if ( $this->link->connection->error != '' ){
			$this->error = self::$lastError = $this->link->connection->error;
		}
		
		return $res;
	}

	public function prepare($query){
		if ( is_object($this->result) ){
			self::$result->close();
		}
		
		self::$result = null;

		if ( is_object($this->stmt) ){
			$this->stmt->close();
		}
		
		self::$lastQuery = $query;
		$this->stmt = $this->connect->prepare($query);
	}

	public function execute($vars){
		$vals = array(0 => '');

		foreach( $vars as $inst ){
			if ( is_array($inst) ){
				$vals[0] .= $inst['type'];
				$vals[] = $inst['value'];
			}else{
				if ( is_numeric($inst) ){
					if ( strpos($inst) === false ){
						$vals[0] .= 'd';
						$vals[] = $inst;
					}else{
						$vals[0] .= 'i';
						$vals[] = $inst;
					}
				}else{
					$vals[0] .= 's';
					$vals[] = $inst;
				}
			}
		}

        if ( call_user_func_array(array(self::$stmt, 'bind_param'), $vals) ){
        	if ( !self::$stmt->execute() ){
				self::$lastError = $stmt->error;

				return false;
			}else return self::$stmt;
        }else return false;
	}

	public function procedure($query, $returns = false){
		$query = ltrim($query);
		$rtn = true;

		if ( is_object(self::$result) )
			self::$result->close();

		if ( is_object(self::$stmt) )
			self::$stmt->close();

		$this->link->selectDB($this->schema);

		self::$stmt = $this->connect->prepare($query);

		if ( self::$stmt !== FALSE ){
            if ( !self::$stmt->execute() ){
				$error = self::$stmt->error;

				return false;
			}else{
				$error = '';
			}

			self::$stmt->close();

			if ($returns) {
				$c = count($returns);
      			for( $i = 0; $i < $c; ++$i ){
      				$returns[$i] = "@{$returns[$i]} as '$returns[$i]'";
      			}

      			if( ($res = $this->query("SELECT ".implode(',', $returns))) === false ){
      				$r = new \Snap\Lib\Db\Mysql\Result(self::$result = $res);
      				$rtn = $r->next();
      			}
			}else{
				$rtn = true;
			}

			$this->error = self::$lastError = $error;
			$this->mem = self::$lastQuery = $query;
		}

		return $rtn;
	}

	public function affectedRows(){
		return $this->link->connection->affectedRows;
	}

	public function error(){
		return $this->link->connection->errno.' : '.$this->link->connection->error;
	}

	public function info(){
		return $this->schema.' : link('.$this->link->info().')';
	}

	public function escStr($string){
		if ( !is_string($string) ){
			return $string;
		}else
			return $this->link->connection->escape_string($string);
	}

	public function disableValidation(){
		return $this->query('SET FOREIGN_KEY_CHECKS = 0');
	}

	public function enableValidation(){
		return $this->query('SET FOREIGN_KEY_CHECKS = 1');
	}

	public function myQuery(){
		return $this->mem;
	}

	public function myError(){
		return $this->error;
	}

	public static function lastQuery(){
		return self::$lastQuery;
	}

	public static function lastError(){
		return self::$lastError;
	}
}
