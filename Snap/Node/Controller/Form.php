<?php

namespace Snap\Node\Controller;

abstract class Form extends \Snap\Node\Core\Controller {
	
	protected
		$content;
	
	protected function parseSettings( $settings = array() ){
		if ( !isset($settings['content']) ){
			throw new Exception('A form needs content');
		}
		$this->content = $settings['content'];
		/* @var $this->content \Snap\Lib\Form\Content */
		if ( !($this->content instanceof \Snap\Lib\Form\Content) ){
			throw new Exception("A form's content needs to be instance of \Snap\Lib\Form\Content");
		}
		
		parent::parseSettings( $settings );
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