<?php

namespace Snap\Lib\Db\Query;

class From {

	static public 
		$INNER_JOIN  = 0,
		$LEFT_JOIN = 1;

	protected 
		$primaryTable,
		$tables = array(),
		$joins = array();

	public function __construct( $options ){
		if ( is_string($options) ){
			$tbls = explode(',', $options);
			$this->primaryTable = array_shift( $tbls );
			$this->tables = $tbls;
		}elseif ( is_array($options) ){
			$first = array_shift( $options );
			if ( $first instanceof From ){
				$this->primaryTable = $first->primaryTable;
				$this->tables = $first->tables;
				$this->joins = $first->joins;
			}else{
				$this->primaryTable = $first;
			}

			$this->tables = array_merge( $this->tables, $options );
		}elseif ( $options instanceof From ){
			$this->primaryTable = $options->primaryTable;
			$this->tables = $options->tables;
			$this->joins = $options->joins;
		}
	}

	public function extend( $options ){
		if ( is_string($options) ){
			$tbls = explode(',', $options);
			$this->tables = array_merge($tbls, $this->tables);
		}elseif ( is_array($options) ){
			$this->tables = array_merge($options, $this->tables);
		}elseif ( $options instanceof From ){
			// TODO this doesn't seem completely right...
			$this->tables[] = $options;
		}
	}

	public function mergeJoin( From $join, $baseVariable, $joinVariable, $joinType = 0 ){
		$this->joins[] = new Fromer($this->table, $baseVariable, $join->table, $joinVariable, $joinType);
		$this->joins[] = $join;
	}

	public function join( $baseVariable, $joinTable, $joinVariable, $joinType = 0 ){
		$this->joins[] = new Fromer($this->primaryTable, $baseVariable, $joinTable, $joinVariable, $joinType);
	}

	protected function getJoins( \Snap\Adapter\Db $db ){
		$rtn = '';

		foreach( $this->joins as $join ){
			$rtn .= "\n\t ";

			if ( $join instanceof From ){
				$rtn .= $join->getJoins( $db );
			}else{
				$rtn .= $join->toString( $db );
			}
		}

		return $rtn;
	}

	public function toString( \Snap\Adapter\Db $db ){
		$tables = '';
		if ( !empty($this->tables) ){
			foreach( $this->tables as $table ){
				if ( table instanceof From ){
					$tables .= ', '.$table->toString();
				}else{
					$tables .= ', '.$table;
				}
			}
		}

		return $this->primaryTable.' '.$this->getJoins( $db ).$tables;
	}
}