<?php

namespace Snap\Prototype\Installation\Lib;

class Management {
	
	protected 
		$installs,
		$uninstalls,
		$prototype;
	
	public function __construct( array $installs, array $uninstalls, Prototype $prototype ){
		error_log( print_r($installs,true) );
		error_log( print_r($uninstalls,true) );
		$this->installs = $installs;
		$this->uninstalls = $uninstalls;
		$this->prototype = $prototype;
	}
	
	public function hasInstalls(){
		return !empty($this->installs);
	}
	
	public function hasUninstalls(){
		return !empty($this->uninstalls);
	}
	
	public function getInstaller(){
		return new \Snap\Prototype\Installation\Lib\Installer( $this->prototype, $this->installs );
	}
	
	public function getUninstaller(){
		return new \Snap\Prototype\Installation\Lib\Uninstaller( $this->prototype, $this->uninstalls );
	}
}