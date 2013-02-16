<?php

namespace Snap\Prototype\WebFs\Node\Form;

class Create extends \Snap\Node\View\Form {
	
	public function getOuputStream(){
		return 'new_file';
	}
	
	protected function processInput( \Snap\Lib\Form\Result &$formData ){
		$res = null;
		$this->log('process input');
		if ( $formData->hasChanged('file') && $formData->hasChanged('name') ){
			if ( $res = \Snap\Prototype\WebFs\Lib\File::registerFile(
				$formData->getValue('file'), 
				$formData->getValue('name')
			) ){
				$this->reset();
				$this->addNote( 'File was uploaded' );
			}else{
				$formData->addError( 'File upload failed' );
			}
		}
		
		return $res;
	}
}