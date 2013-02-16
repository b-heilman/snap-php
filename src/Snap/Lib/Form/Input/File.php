<?php

namespace Snap\Lib\Form\Input;

class File extends \Snap\Lib\Form\Input 
	implements Encoded {
	
	public function __construct( $name, $value = null ){
		parent::__construct( $name, '' );
		
		$this->currValue = $this->origValue = null;
	}
	
	public function getEncoding(){
		return 'multipart/form-data';
	}
	
	public function changeValue( $value ){
		$name = $this->name;
		
		if ( isset($_FILES[$name]) ){
			if ( $_FILES[$name]['error'] && $_FILES[$name]['error']  !== UPLOAD_ERR_OK ){
				$errorCode =  $_FILES[$name]['error'];
		
				$uploadErrors = array(
					UPLOAD_ERR_INI_SIZE   => "Larger than upload_max_filesize.",
					UPLOAD_ERR_FORM_SIZE  => "Larger than form MAX_FILE_SIZE.",
					UPLOAD_ERR_PARTIAL    => "Partial upload.",
					UPLOAD_ERR_NO_FILE    => "No file.",
					UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",
					UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",
					UPLOAD_ERR_EXTENSION  => "File upload stopped by extension."
				);
					
				$this->addError( // TODO : do I wanna be nice and translate?
					new \Snap\Lib\Form\Error\Coded( $uploadErrors[$errorCode], $errorCode, $this )
				);
			}else{
				$this->currValue = $_FILES[$name];
			}
		}else{
			$this->addError(
				new \Snap\Lib\Form\Error\Coded( 'No file for stream', 100, $this )
			);
		}
	}
}