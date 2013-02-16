<?php

namespace Snap\Lib\Db;

class Query {

	protected 
		$table,
		$select, 
		$from,
		$where,
		$limit,
		$orderBy,
		$groupBy;

	public function __construct($options = ''){
		if ( $options instanceof Query ){
			$this->select = $options->select;
			$this->from = $options->from;
			$this->where = $options->where;
			$this->limit = $options->limit;
			$this->orderBy = $options->orderBy;
			$this->groupBy = $options->groupBy;
			$this->table = $options->table;
		}elseif ( is_array($options) ){
			$this->select = new Query\Select( '*' );
			$this->from = false;
			$this->where = false;
			$this->limit = false;
			$this->orderBy = false;
			$this->groupBy = false;
			
			$this->override( $options );
		}else{
			throw new \Exception('Query objects are not configured to handle that');
		}
	}

	public function __clone(){
		$this->select = clone $this->select;
		$this->from = clone $this->from;
		
		if ( $this->where )
			$this->where = clone $this->where;
		else 
			$this->where = false;
		
		$this->limit = $this->limit;
		$this->orderBy = $this->orderBy;
		$this->groupBy = $this->groupBy;
	}
	
	public function override( $options ){
		// TODO : what if it's an instance of Query
		if ( isset($options['select']) ){
			if ( $options['select'] )
				$this->select = new Query\Select( $options['select'] );
			else
				$this->select = new Query\Select( '*' );
		}

		if ( isset($options['from']) ){
			if ( $options['from'] )
				$this->from = new Query\From( $options['from'] );
			else
				$this->from = false;
		}

		if ( isset($options['where']) ){
			if ( $options['where'] )
				$this->where = new Query\Where( $options['where'] );
			else
				$this->where = false;
		}

		if ( isset($options['limit']) ){
			if ( $options['limit'] )
				$this->limit = $options['limit'];
			else
				$this->limit = false;
		}

		if ( isset($options['order by']) ){
			if ( $options['order by'] )
				$this->orderBy = $options['order by'];
			else
				$this->orderBy = false;
		}
		
		if ( isset($options['group by']) ){
			if ( $options['group by'] )
				$this->groupBy = $options['group by'];
			else
				$this->groupBy = false;
		}
	}
	
	public function modify( $options ){
		if ( is_array($options) ){
			if ( isset($options['select']) && $options['select'] ){
				$this->select->extend( $options['select'] );
			}

			if ( isset($options['from']) && $options['from'] ){
				if ( $this->from ){
					$this->from->extend( $options['from'] );
				}else{
					$this->from = new Query\From( $options['from'] );
				}
			}

			if ( isset($options['where']) && $options['where'] ){
				if ( $this->where ){
					$this->where->extend( $options['where'] );
				}else{
					$this->where = new Query\Where( $options['where'] );
				}
			}

			if ( isset($options['limit']) && $options['limit'] ){
				$this->limit = $options['limit'];
			}

			if ( isset($options['order by']) && $options['order by'] ){
				$this->orderBy = $options['order by'];
			}
			
			if ( isset($options['group by']) && $options['group by'] ){
				$this->groupBy = $options['group by'];
			}
		}elseif( $options instanceof Query ){
			$this->select->extend( $options->select );

			if ( $this->from ){
				$this->from->extend( $options->from );
			}else{
				$this->from = $options->from;
			}

			if ( $this->where ){
				$this->where->extend( $options->where );
			}else{
				$this->where = $options->where;
			}

			if ( $options->limit ){
				$this->limit = $options->limit;
			}

			if ( $options->orderBy ){
				$this->orderBy = $options->orderBy;
			}
			
			if ( $options->groupBy ){
				$this->groupBy = $options->groupBy;
			}
		}
	}

	public function setPrimaryTable( $table ){
		$this->table = $table;
	}

	protected function getSelectSql(\Snap\Adapter\Db $db){
		$query = 'SELECT ';

		if ( is_array($this->select) ){
			$query .= implode(',', $this->select);
		}elseif ( $this->select instanceof Query\Select ){
			$query .= $this->select->toString( $db );
		}else{
			$query .= $this->select;
		}

		return $query;
	}

	protected function getFromSql(\Snap\Adapter\Db $db){
		if ( !$this->from ){
			if ( $this->table ){
				$this->from = $this->table;
			}else{
				throw new \Exception('Query : no FROM can be generated.  '.
									'No from set in constructor and no primary table set');
			}
		}

		$query = "\nFROM ";

		if ( is_array($this->from) ){
			$query .= implode(',', $this->from);
		}elseif ( $this->from instanceof Query\From ){
			$query .= $this->from->toString( $db );
		}else{
			$query .= $this->from;
		}

		return $query;
	}

	protected function getWhereSql(\Snap\Adapter\Db $db){
		$query = '';

		if ( $this->where ){
			$switch = false;

			$query = "\nWHERE ";

			if ( is_array($this->where) && !empty($this->where)){
				$switch = false;

				foreach ($this->where as $key => $val){
					if ($switch){
						$query .= "AND";
					}else{
						$switch = true;
					}
	
					if ( $val instanceof Query\Where ){
						$query .= $val->toString( $db );
					}else{
						$val = $db->escStr($val);
						$query .= " `$key` = '$val' ";
					}
				}
			}elseif( $this->where instanceof Query\Where ){
				$tmp = $this->where->toString( $db );
				if ( $tmp ){
					$query .= $tmp;
				}else{
					$query = '';
				}
			}elseif ( is_string($this->where) ) {
				$query .= $this->where;
			}else{
				$query = '';
			}
		}

		return $query;
	}

	protected function getOrderSql(\Snap\Adapter\Db $db){
		$query = ' ';

		if ( $this->orderBy ){
			$switch = false;

			$query .= "\nORDER BY ";

			if ( is_array($this->orderBy) ){
				foreach ($this->orderBy as $key => $val){
					if ($switch){
						$query .= ',';
					}else{
						$switch = true;
					}

					if ( !is_numeric($key) ){
						$query .= $key;

						if ( $val ){
							$query .= ' ASC';
						}else{
							$query .= ' DESC';
						}
					}else{
						$query .= $val;
					}
				}
			}else{
				$query .= $this->orderBy;
			}
		}

		return $query;
	}

	public function getGroupSqlingFields(){
		if ( $this->groupBy ){
			if ( is_string($this->groupBy) ){
				return explode(',', $this->groupBy);
			}else{
				return $this->groupBy;
			}
		}else{
			return null;
		}
	}
	
	protected function getGroupSql(\Snap\Adapter\Db $db){
		$query = ' ';

		if ( $this->groupBy ){
			$query .= "\nGROUP BY " . (
				is_string($this->groupBy) ? $this->groupBy : implode(', ', $this->groupBy)
			);
		}

		return $query;
	}
	
	protected function getLimitSql(\Snap\Adapter\Db $db){
		$query = ' ';

		if ( $this->limit ){
			$query .= " \nLIMIT ".$db->escStr($this->limit);
		}

		return $query;
	}

	public function getSql( \Snap\Adapter\Db $db ){
		return $this->getSelectSql($db)
			.$this->getFromSql($db)
			.$this->getWhereSql($db)
			.$this->getOrderSql($db)
			.$this->getGroupSql($db)
			.$this->getLimitSql($db);
	}
}