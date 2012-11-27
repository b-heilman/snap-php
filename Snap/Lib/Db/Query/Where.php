<?php

namespace Snap\Lib\Db\Query;

use \Snap\Lib\Db\Query\Where\Element;
use \Snap\Lib\Db\Query\Where\Join;
use \Snap\Lib\Db\Query\Where\Expression;
use \Snap\Lib\Db\Query\Where\Action;
use \Snap\Lib\Db\Query\Where\Nulled;

class Where implements Element {

	protected 
		$result,
		$expressions = array(),
		$leading = null;

	public function __construct( $logic = null ){
		$this->extend( $logic );
	}

	protected function add( Element $logic, $join = 0 ){
		$this->result = null;
		
		if( $logic instanceof \Snap\Lib\Db\Query\Where ){
			if ( $logic->leading != null ){
				if ( $this->leading == null ){
					$this->leading = $logic;
				}else{
					$this->expressions[] = new Join( $logic, $join );
				}
			}
		}elseif ( $this->leading == null ){
			$this->leading = $logic;
		}elseif( $logic instanceof Expression && Join::$AND == $join ){
			$this->expressions[ $logic->getVariable() ] = new Join( $logic, $join );
		}else{
			$this->expressions[] = new Join( $logic, $join );
		}
	}

	public function extend( $logic ){
		if ( is_array($logic) ){
			foreach( $logic as $key => $val ){
				if ( $val instanceof Expression ){
					$this->add( $val, Join::$AND );
				}elseif( $val instanceof Action ){
					$this->add( $val, Join::$AND );
				}elseif( is_null($val) ){
					$this->add( new Nulled( $key ), Join::$AND );
				}else{
					$this->add( new Expression( $key, '=', $val), Join::$AND );
				}
			}
		}elseif( $logic instanceof \Snap\Lib\Db\Query\Where  ){
			$this->add( $logic, Join::$AND );
		}elseif( is_string($logic) ){
			$this->result = $logic;
		}
	}

	public function _and( Element $logic ){
		$this->add( $logic, Join::$AND );
	}

	public function _or(  Element $logic ){
		$this->add( $logic, Join::$OR );
	}

	public function toString( \Snap\Adapter\Db $db ){
		if ( $this->result != null ){
			return $this->result;
		}elseif ( $this->leading == null ){
			return '';
		}else{
			$tmp = '';
			foreach( $this->expressions as $exp ){
				$tmp .= $exp->toString( $db );
			}

			if ( $tmp )
				return $this->result = '('.$this->leading->toString( $db ).')'.$tmp;
			else
				return $this->result = $this->leading->toString( $db );
		}
	}
}