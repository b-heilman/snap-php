<?php

namespace Snap\Lib\Db\Mysql;

class Link {
	
	public 	
		$host,
		$connection = null;
		
    private 
		$user,
		$pass,
		$schema = '',
		$result;

    public function __construct($host, $user, $pass = null) {
    	$this->host = $host;
    	$this->user = $user;
    	$this->pass = $pass;
    }

    public function info(){
    	return "{$this->schema} => {$this->host},{$this->user},{$this->pass}";
    }

    public function connect(){
    	if ( $this->connection == null ) {
    		$this->connection = new \mysqli($this->host, $this->user, $this->pass);
    	}
    }

    public function disconnect(){
    	$this->connection->close();
    	$this->schema = '';
    	$this->connection = null;
    }

    public function configuration( $autocommit, $settings = array() ){
    	for( $i = 0, $c = count($settings); $i < $c; ++$i ){
    		if ( !$this->connection->query($settings[$i]) ){
    			return false;
    		}
    	}

    	return $this->connection->autocommit($autocommit);
    }

    public function selectDB($db){
    	$res = true;

    	if( $db != $this->schema ){
    		$res = $this->connection->select_db($db);
    		if ( $res )
    			$this->schema = $db;
    	}

    	return $res;
    }

    public function processQuery($sql){
    	return $this->connection->query($sql);
    }

    public function processMulti($sql, $useResults = false){
    	if ( $this->connection->multi_query($sql) ){
    		if ( $useResults ){
    			$res = array();

    			do {
			        $res[] = $this->connection->store_result();
			    }while( $this->connection->more_results() && $this->connection->next_result() );

			    return $res;
    		}else{
    			do {
			        $res = $this->connection->use_result();
			        if ( $res )
						$res->free(); // just throw it out...
			    }while( $this->connection->more_results() && $this->connection->next_result() );

    			return true;
    		}
    	}else{
    		return false;
    	}
    }

    public function autocommit($which){
    	return $this->connection->autocommit($which);
    }

    public function commit(){
    	return $this->connection->commit();
    }

    public function rollback(){
    	return $this->connection->rollback();
    }

    public function __destruct() {
	//	if ( $this->connection )
	//		$this->connection->close();
	}
}