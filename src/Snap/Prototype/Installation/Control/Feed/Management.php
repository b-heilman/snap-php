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
				/** 
				 * @var \Snap\Prototype\Installation\Lib\Management 
				 */
				$el = $ctrl->get( $i );
				
				if ( $el->hasInstaller() ){
					$installs[] = $el->getInstaller();
				}
				
				if( $el->hasUninstaller() ){
					$uninstalls[] = $el->getUninstaller();
				}
			}
			
			// set up the handler
			$class = SITE_DB_ADAPTER;
			$handler = new $class(SITE_DB);
			
			// run the installs
			if ( !empty($uninstalls) ){
				$errors = array();
				$success = array();
				
				$handler->autocommit( false );
				
				foreach( $uninstalls as $inst ){
					$inst->getPrototype()->define( $inst->getTables() );
				}
				
				if ( \Snap\Lib\Db\Definition::uninstall( $handler ) ){
					foreach( $uninstalls as $inst ){
						$proto = $inst->getPrototype();
						
						$success[] = $proto->name.' was uninstalled.';
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
			
			// run the installs
			if ( !empty($installs) ){
				$errors = array();
				$success = array();
			
				foreach( $installs as $inst ){
					$inst->getPrototype()->define( $inst->getTables() );
				}
			
				if ( \Snap\Lib\Db\Definition::install( $handler ) ){
					foreach( $installs as $inst ){
						$proto = $inst->getPrototype();
			
						$success[] = $proto->name.' was installed.';
						$success = array_merge( $success, $inst->runHooks($handler) );
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
		}
		
		return new \Snap\Lib\Mvc\Data\Collection( $messages );
	}
}