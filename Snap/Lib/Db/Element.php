<?php

namespace Snap\Lib\Db;

abstract class Element extends Definition 
	implements \Snap\Lib\Core\Arrayable {
	
	protected 
		$info = array(), 
		$init = null,
		$plural = false;

	public function __construct( $data ){
		self::callStatic('loadDB');
		$db = self::pullStatic('db');
		
		if ( !isset($db) ){
			throw new \Exception('You Need To Define A Static DB variable');
		}
		
		if( $data instanceof Element){
			 $this->info = $data->info;
			 $this->init = $data->init;
			 $this->plural = $data->plural;
		}else{
			$name_field = self::pullStatic('name_field');
			$id_field   = self::pullStatic('id_field');
			
			if ( $data instanceof snap_arrayable ){
				$data = $data->toArray();
			}
		
			if ( is_array($data) ){
				if ( isset($data[$name_field]) && isset($data[$id_field]) ){
					$this->info = $data;
					$this->init = $data[$id_field];
				}elseif ( isset($data[$name_field]) ){
					$this->init = $data[$name_field];
				}elseif ( isset($data[$id_field]) ){
					$this->init = $data[$id_field];
				}elseif ( isset($data[0]) ) {
					$this->plural = true;
					$this->init = $data;
				}else{
					throw new \Exception('You gave me: '.print_r($data, true).', what am I supposed to do with that? Looking for '."|$id_field|$name_field|");
				}
			}else{
				$this->init = $data;
			}
		}
	}

	public function info( $var = '' ) {
		$this->load();
		
		if ( $var == '' ){
			return $this->info;
		}elseif( isset($this->info[$var]) ){
			return $this->info[$var];
		}else return null;
	}

	public function toArray(){
		return $this->info();
	}
	
	public function update( $data ){
		self::callStatic('loadDB');
		$db = self::pullStatic('db');
		$table = self::pullStatic('table');
		$id = self::pullStatic('id_field');

		if ( $this->plural ){
			throw new \Exception('Updates are only allowed against singular instances at this time.');
		}else{
			//TODO I need to improve this -- The database may change the data via triggers...
			if ( $db->update($table, array($id => $this->id()), $data) ){
				$this->info = array(); 
				return true;
			}else return false;
		}
	}
	
	public function delete(){
		self::callStatic('loadDB');
		$db = self::pullStatic('db');
		$table = self::pullStatic('table');
		$id = self::pullStatic('id_field');

		// TODO actually do this
		if ( $this->plural ){
			throw new \Exception('Need to write this part.');
		}else{
			return $db->delete( $table, array($id => $this->id() ) );
		}
	}

	public function name(){
		$name = self::pullStatic('name_field');

		if ( $this->plural ){
			if ( !empty($this->info) || $this->load() ){
				$rtn = array();
				foreach( $this->info as $d ){
					$rtn[] = $d[$name];
				}
				return $rtn;
			}else return false;
		}else{
			if ( isset($this->info[$name]) || $this->load() ){
				return $this->info[$name];
			}else return false;
		}
	}

	public function id(){
		$id = self::pullStatic('id_field');

		if ( $this->plural ){
			if ( !empty($this->info) || $this->load() ){
				$rtn = array();
				foreach( $this->info as $d ){
					$rtn[] = $d[$id];
				}
				return $rtn;
			}else return false;
		}else{
			if ( is_numeric($this->init) ) {
				return $this->init;
			}elseif ( isset($this->info[$id]) || $this->load() ){
				return $this->info[$id];
			}else return false;
		}
	}

	public function query( Query $query ){
		$db = self::pullStatic('db');
		
		return $db->query( $query );
	}
	
	public function getDefaultHandler(){
		return self::pullStatic('db');
	}
	
	public function queryMatch( $table = '' ){
		$db = self::pullStatic('db');

		$id = self::pullStatic('id_field');
		$name = self::pullStatic('name_field');

		if ( $table != '' )
			$table = $table.'.';

		if ( $this->plural ){
			$vals = array_values($this->init);
			foreach( $vals as $key => $val ){
				$vals[$key] = $db->escStr($val);
			}

			if ( is_numeric($vals[0]) ){
				return $table.$id.' IN ('.implode(',', $vals).')';
			}else{
				return $table.$name.' IN ("'.implode('","', $vals).'")';
			}
		}else{
			if( is_array($this->init) ){
				$tmp = '';
				
				foreach( $this->init as $key => $val ){
					if ( $tmp != '' ){
						$tmp .= ' AND ';
					}
					
					$tmp .= $table.$key.' = "'.$db->escStr($val).'"';
				}
				
				return $tmp;
			}elseif ( is_numeric($this->init) ){
				return $table.$id.' = '.$db->escStr($this->init);
			}else{
				return $table.$name.' = "'.$db->escStr($this->init).'"';
			}
		}
	}

	public function isLoaded(){
		return !empty($this->info);
	}

	public function isValid(){
		$this->load();
		
		return ( $this->info !== null );
	}
	
	protected function load(){
		if ( !$this->isLoaded() ){
			$db = self::pullStatic('db');
			$table = self::pullStatic('table');

			$this->loaded = true;
			
			$res = $db->query('SELECT * FROM '.$table.' WHERE '.$this->queryMatch());

			if ( $res ){
				$this->info = $res->next();
			}else{
				$this->info = null;
			}
		}

		return $this->info;
	}
	
	static public function getId( $name ){
		self::callStatic('loadDB');
		$db = self::pullStatic('db');
		$table = self::pullStatic('table');
		$nameField = self::pullStatic('name_field');

		if ( ($res = $db->select($table, array($nameField => $name))) && $res->hasNext() ){
			$data = $res->next();
			return $data[ self::pullStatic('id_field') ];
		}else{
			return false;
		}
	}
	
	static public function create( $data ){
		self::callStatic('loadDB');
		$db = self::pullStatic('db');
		$table = self::pullStatic('table');

		if ( $db->insert( $table, $data ) ){
			return $db->insertedID();
		}else return false;
	}

	static public function hasInstance( $clarifiers = array() ){
		// TODO : maybe I can do something cool to translate it to a limit 1
		self::callStatic('loadDB');
		$db = self::pullStatic('db');
		$table = self::pullStatic('table');

		if ( ($res = $db->select($table, $clarifiers)) && $res->hasNext() ){
			return true;
		}else{
			return false;
		}
	}
	
	static public function get( $clarifiers = array() ){
		self::callStatic('loadDB');
		$db = self::pullStatic('db');
		$table = self::pullStatic('table');

		if ( ($res = $db->select($table, $clarifiers)) && $res->hasNext() ){
			return $res->next();
		}else{
			return array();
		}
	}
	
	static public function hash( $clarifiers = array() ){
		self::callStatic('loadDB');
		$db = self::pullStatic('db');
		$table = self::pullStatic('table');
		$id = self::pullStatic('id_field');
		$name = self::pullStatic('name_field');

		if ( $res = $db->select($table, $clarifiers) ){
			return $res->asHash( $id, $name );
		}else{
			return array();
		}
	}

	static public function data( $clarifiers = array() ){
		self::callStatic('loadDB');
		$db = self::pullStatic('db');
		$table = self::pullStatic('table');

		if ( $res = $db->select($table, $clarifiers) ){
			return $res->asArray();
		}else{
			return array();
		}
	}

	static public function index( $fields, $clarifiers = array() ){
		self::callStatic('loadDB');
		$db = self::pullStatic('db');
		$table = self::pullStatic('table');

		if ( $res = $db->select($table, $clarifiers) ){
			return $res->asIndex( $fields );
		}else{
			return array();
		}
	}
	
	static protected function callStatic( $func, $var = '' ){
		$class = get_called_class();

		eval("\$tst = isset($class::\$table)
						&& isset($class::\$id_field)
						&& isset($class::\$name_field);");

		if ($tst) {
			eval("\$rtn = $class::$func(\$var);");

			return $rtn;
		}else{
			throw new \Exception('An instance of $class must have
								$id_field, $name_field, and $table defined as static members.');
		}
	}

	/*********
	 * @return db_adapter
	 * 
	 */
	static protected function pullStatic( $var ){
		$class = get_called_class();
		eval("\$rtn = $class::$$var;");

		return $rtn;
	}

	static protected function loadDB(){
		throw new \Exception('You need to overload loadDB in any class extending db_element');
	}

	static protected function install_control(){
		// TODO : remove this shit
	}
}