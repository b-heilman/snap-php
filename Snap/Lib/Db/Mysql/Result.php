<?php

namespace Snap\Lib\Db\Mysql;

use \Snap\Lib\Db;

class Result implements Db\Result {
	//TODO i need to finish setting this up for statements, but I never use them
	// i prefer to esc... so it's not getting done yet
	private 
		$stmt = false,
		$data,
		$place;

	public function __construct($result){
		$this->stmt = $result instanceof mysqli_stmt;
		$this->data = $result;
		$this->place = 0;
	}

	//TODO allow an object to be passed back
	public function asArray($val = false){
		$r = array();

		if ( $val === true ){
			while ($n = $this->data->fetch_object()){
				$r[] = $n;
			}
		}else{
			while ($n = $this->data->fetch_assoc()){
				if($val != '')
					$r[] = $n[$val];
				else
					$r[] = $n;
			}
		}

		return $r;
	}

	public function asHash($key, $val = false){
		$r = array();

		if ( $val === true ){
			while ($n = $this->data->fetch_object()){
				$r[$n->$key] = $n;
			}
		}else{
			while ($n = $this->data->fetch_assoc()){
				if ( $val )
					$r[$n[$key]] = $n[$val];
				else
					$r[$n[$key]] = $n;
			}
		}

		return $r;
	}

	public function asIndex($dexs, $val = false, $obj = false){
		$t = array();

		if ( $obj ){
			while ($n = $this->data->fetch_object()){
				if ( is_array($dexs) ){
					$t2 = $t;
					foreach($dexs as $dex){
						if ( !isset($t2[$n->$dex]) )
							$t2[$n->$dex] = array();
						$t2 = $t2[$n->$dex];
					}

					$t2[] = $n;
				}else{
					$t[$n->$dexs] = $n;
				}
			}
		}else{
			while ($n = $this->data->fetch_assoc()){
				if ( is_array($dexs) ){
					$t2 = $t;

					foreach($dexs as $dex){
						if ( !isset($t2[$n[$dex]]) )
							$t2[$n[$dex]] = array();
						$t3 = $t2;
						$t2 = $t3[$n[$dex]];
					}

					if($val != '')
						$t2[] = $n[$val];
					elseif ( $val )
						$t2[] = $n;
					else
						$t3[$n[$dex]] = $n;
				}else{
					if($val != '' )
						$t[$n[$dexs]] = $n[$val];
					elseif( $val )
						$t[$n[$dexs]][] = $n;
					else
						$t[$n[$dexs]] = $n;
				}
			}
		}

		return $t;
	}

	public function nextVal($val){
		$this->place++;
		$t = $this->data->fetch_assoc();
		return $t[$val];
	}

	public function next($object = false){
		$this->place++;
		return ($object === true)?$this->data->fetch_object():$this->data->fetch_assoc();
	}

	public function hasNext(){
		return $this->place < $this->data->num_rows;
	}

	public function count(){
		return $this->data->num_rows;
	}

	public function getFields($asHash = false){
		if ( $asHash ){
			$fields = $this->search->fetch_fields();
			$f = array();
			$c = count( $fields );
			for( $i = 0; $i < $c; ++$i ){
				$t = $fields[$i];
				$f[$t->name] = $t->name;
			}

			return $f;
		}else{
			return $this->data->fetch_fields();
		}
	}
}