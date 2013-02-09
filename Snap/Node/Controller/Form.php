<?php

namespace Snap\Node\Controller;

abstract class Form extends \Snap\Node\Core\Controller {
	
	protected
		$content;
	
	protected function parseSettings( $settings = array() ){
		if ( isset($settings['content']) ){
			$this->content = $settings['content'];
		}else{
			$className = str_replace( 'Node\Controller', 'Model\Form', get_class($this) );
				
			if ( class_exists($className) ){
				$this->content = $this->makeContent( $className );
			}else throw new \Exception( 'A form needs content, tried : '.$className );
		}
		/* @var $this->content \Snap\Model\Form */
		if ( !($this->content instanceof \Snap\Model\Form) ){
			throw new \Exception("A form's content needs to be instance of \Snap\Model\Form");
		}
		
		parent::parseSettings( $settings );
	}
	
	protected function makeContent( $className ){
		return new $className();
	}
	
	protected function makeData(){
		if ( $this->content->wasFormSubmitted() ){
			$proc = $this->content->getResults();
		
			if ( $proc->hasErrors() ){
				return new \Snap\Lib\Mvc\Data( null ); // pre processing errors
			}else{
				$rtn = $this->processInput( $proc );
				
				if ( $proc->hasErrors() ){
					return new \Snap\Lib\Mvc\Data( null ); // post processing errors
				}else{
					return new \Snap\Lib\Mvc\Data( $rtn );
				}
			}
		}else{
			return new \Snap\Lib\Mvc\Data( null ); // content wasn't even submitted
		}
	}
	
	abstract protected function processInput( \Snap\Lib\Form\Result &$formData );
}