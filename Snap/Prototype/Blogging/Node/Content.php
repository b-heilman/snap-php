<?php

namespace Snap\Prototype\Blogging\Node;

class Content extends \Snap\Prototype\Topic\Node\View\Simple {
 	
	protected function getTopic(){
		$mvc = $this->getStreamData();
		return $mvc->get( $mvc->getVar('active') );
	}
	
	protected function getTemplateVariables(){
		$res = parent::getTemplateVariables();
 		
 		$content = $res['content'];
 		$translator = null;
 		
 		// hook for allowing mom to clean up code
 		if ( preg_match('/^<!-- translator : (.*) -->/', $content, $matches) ){
 			if ( $matches[1] == 'template' ){
 				$translator = new \Snap\Lib\Template\Translator();
 			}
 		}
 		
 		if ( !$translator ){
 			$translator = new \Snap\Prototype\Blogging\Translator();
 		}
		
 		$res['content'] = new \Snap\Node\Content(array(
			'content'    => $content,
			'translator' => $translator
		));
		
		return $res;
 	}
}