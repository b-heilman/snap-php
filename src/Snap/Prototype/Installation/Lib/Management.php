<?php

namespace Snap\Prototype\Installation\Lib;

class Management {
	
	protected 
		$installer,
		$uninstaller,
		$prototype;
	
	public function __construct( array $installs, array $uninstalls, Prototype $prototype ){
		$this->installer = empty($installs)
			? null
			: new \Snap\Prototype\Installation\Lib\Installer( $prototype, $installs );
		
		$this->uninstaller = empty($uninstalls)
			? null
			: new \Snap\Prototype\Installation\Lib\Uninstaller( $prototype, $uninstalls );
		
		$this->prototype = $prototype;
	}
	
	public function hasInstaller(){
		return !is_null($this->installer);
	}
	
	public function hasUninstaller(){
		return !is_null($this->uninstaller);
	}
	
	public function getInstaller(){
		return $this->installer;
	}
	
	public function getUninstaller(){
		return $this->uninstaller;
	}
}