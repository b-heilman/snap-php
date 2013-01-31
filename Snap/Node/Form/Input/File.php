<?php

namespace Snap\Node\Form\Input;

class File extends \Snap\Node\Core\Simple 
	implements \Snap\Node\Form\WrappableInput {
	
	protected 
		$name,
		$file,
		$wrapper;
	
	public function __construct( $settings = array() ){
		$settings['tag'] = 'input';
		
		parent::__construct( $settings );
		
		if ( isset($settings['name']) ){
			$this->file = new \Snap\Lib\Form\Input( $settings['name'], '' );
		}else{
			$this->file = new \Snap\Lib\Form\Input( 'a_file', '' );
			throw new \Exception('Snap\Node\Form\Input\File requires a name');
		}
	}
	
	public function getType(){
		return 'file';
	}
	
	public function setWrapper( \Snap\Node\Core\Snapable $node ){
		$this->wrapper = null;
	}
	
	public function getWrapper(){
		return $this->wrapper == null ? $this : $this->wrapper;
	}
	
	public function getName(){
		return $this->file->getName();
	}
	
	public function changeName($name){
		$this->file->changeName($name);
	}
	
	public function hasChanged(){
		$this->file->hasChanged();
	}
	
	public function getInput( \Snap\Node\Core\Form $form ){
		$name = $this->file->getName();
		
		if ( isset($_FILES[$name]) ){
			$this->file->setValue( $_FILES[$name] );
			
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
			
				$this->file->setError( // TODO : do I wanna be nice and translate?
					new \Snap\Lib\Form\Error\Coded( $uploadErrors[$errorCode], $errorCode, 'Snap\Node\Form\Input\File' )
				); 
			}
		}else{
			$this->file->setError( 
				new \Snap\Lib\Form\Error\Coded( 'No file for stream', 100, 'Snap\Node\Form\Input\File' )
			);
		}

		return $this->file;
	}

	public function getValue(){
		return $_FILES[$name];
	}
	
	public function setValue( $value ){}
	
	public function setDefaultValue( $value ){}
	
	protected function baseClass(){
		return get_class($this);
	}
	
    public function reset(){
    }
    
    protected function getAttributes() {
    	//throw new \Exception();
    	
    	$form = $this->closest('\Snap\Node\Core\Form');
		$form->setEncoding('multipart/form-data'); // TODO : gotta do a build on this
		
    	return parent::getAttributes().' type="file" '."name=\"{$this->file->getName()}\"";
    }
}