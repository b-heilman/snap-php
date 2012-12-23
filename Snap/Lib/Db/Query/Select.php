<?php

namespace Snap\Lib\Db\Query;

use \Snap\Lib\Db\Query\Select\Variable;

class Select {

	protected 
		$vars;

	public function __construct( $variables = null ){
		$this->vars = array();

		$this->extend( $variables );
	}

	// alias => value 
	public function extend( $variables ){
		if ( is_array($variables) ){
			foreach( $variables as $key => $val ){
				if ( $val instanceof Variable ){
					$this->vars[] = $val;
				}elseif( is_numeric($key) ){
					$this->vars[] = new Variable( $val );
				}else{
					$this->vars[] = new Variable( $val, $key );
				}
			}
		}elseif( is_string($variables) ){
			$vars = explode( ', ', $variables );
			foreach( $vars as $var ){
				$pos = stripos($var, ' as ');
				if ( $pos !== false ){
					$this->vars[] = new Variable(
						substr($var, 0, $pos - 1),
						trim( substr($var, $pos + 4) )
					);
				}else{
					if ( $var == '*' ){
						array_unshift( $this->vars, new Variable($var) );
					}else{
						$this->vars[] = new Variable( $var );
					}
				}
			}
		}elseif( $variables instanceof Select ){
			$this->vars = $variables->vars;
		}
	}

	public function addVariable( Variable $var ){
		$this->vars[] = $var;
	}

	public function hasVariable( $name ){
		foreach( $this->vars as $val ){
			if ( $val->getName() == $name )
				return true;
		}

		return false;
	}

	public function toString( \Snap\Adapter\Db $db ){
		if ( is_string($this->vars) ){
			return $this->vars;
		}else{
			$rtn = '';

			foreach( $this->vars as $val ){
				if ( !empty($rtn) ){
					$rtn .= ', ';
				}

				$rtn .= $val->toString( $db );
			}

			return $rtn;
		}
	}
}