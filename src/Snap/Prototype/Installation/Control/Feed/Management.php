<?php

namespace Snap\Prototype\Installation\Control\Feed;

class Management extends \Snap\Control\Feed\Converter {
	
	public function hasConsumed(){
		return false;
	}
	
	protected function makeData(){
		$ctrl = $this->input;
		$installs = array();
		$messages = array();
		$uninstalls = array();
		
		if ( $ctrl ){
			for( $i = 0; $i < $ctrl->count(); $i++ ){
				$el = $ctrl->get( $i );
				
				if ( $el instanceof \Snap\Prototype\Installation\Lib\Installer ){
					$installs[] = $el;
				}elseif( $el instanceof \Snap\Prototype\Installation\Lib\Uninstaller ){
					$uninstalls[] = $el;
				}
			}
			
			// set up the handler
			$class = SITE_DB_ADAPTER;
			$handler = new $class(SITE_DB);
			
			// run the installs
			if ( !empty($installs) ){
				$errors = array();
				$success = array();
				
				foreach( $installs as $inst ){
					$inst->getPrototype()->define();
				}
				
				if ( \Snap\Lib\Db\Definition::install( $handler ) ){
					foreach( $installs as $inst ){
						$proto = $inst->getPrototype();
						
						if ( $proto->install($handler) ){
							$success[] = $proto->name.' was installed.';
							
							$success = array_merge( $success, $inst->runHooks($handler) );
						}else{
							$errors[] = $proto->name.' could not be installed';
						}
					}
				}else{
					error_log( $handler->lastQuery() );
					error_log( $handler->lastError() );
					$errors[] = 'Installation failed';
				}
				
				if ( empty($errors) ){
					$handler->commit();
					$messages += $success;
				}else{
					$handler->rollback();
					$messages += $errors;
				}
			}
			
			// run the installs
			if ( !empty($uninstalls) ){
				$errors = array();
				$success = array();
				
				$handler->autocommit( false );
				
				foreach( $uninstalls as $inst ){
					$inst->getPrototype()->define();
				}
				
				if ( \Snap\Lib\Db\Definition::uninstall( $handler ) ){
					foreach( $uninstalls as $inst ){
						$proto = $inst->getPrototype();
						
						if ( $proto->uninstall($handler) ){
							$success[] = $proto->name.' was uninstalled.';
						}else{
							$errors[] = $proto->name.' could not be uninstalled';
						}
					}
				}else{
					$errors[] = 'Uninstallation failed';
				}
				
				if ( empty($errors) ){
					$handler->commit();
					$messages += $success;
				}else{
					$handler->rollback();
					$messages += $errors;
				}
			}
		}
		
		return new \Snap\Lib\Mvc\Data\Collection( $messages );
	}
}