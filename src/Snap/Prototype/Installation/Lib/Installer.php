<?php

namespace Snap\Prototype\Installation\Lib;

class Installer {
	
	public
		$prototype,
		$hooks = array();
	
	public function __construct( \Snap\Prototype\Installation\Lib\Prototype $prototype ){
		$this->prototype = $prototype;
	}
	
	public function getPrototype(){
		return $this->prototype;
	}
	
	public function addPostInstallHook( $hook ){
		$this->hooks[] = $hook;
	}
	
	public function runHooks( \Snap\Adapter\Db $db ){
		$msgs = array();
		$c = count( $this->hooks );
		
		for( $i = 0; $i < $c; $i++ ){
			$msg = $this->hooks[$i]( $db );
			if ( $msg ){
				$msgs[] = $msg;
			}
		}
		
		return $msgs;
	}
}