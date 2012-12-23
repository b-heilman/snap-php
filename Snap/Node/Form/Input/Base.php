<?php

// was form_data_node
// TODO I should break this logic out so all <input> are not block type tags...
// you can technically append to them, which just isn't right

namespace Snap\Node\Form\Input;

abstract class Base extends \Snap\Node\Block 
	implements \Snap\Node\Form\WrappableInput {
		
	protected 
		$wrapper = null,
		$disabled,
		$name,
		$readonly,
		$trueValue,       // This will be the actual value passed in
		$value;           // This will be a form_data_basic
	
	protected function parseSettings( $settings = array() ){
		if ( isset($settings['name']) ){
			$this->name = $settings['name'];
		}else{
			throw new \Exception( get_class($this)." requires a name" );
		}
		
		$this->trueValue = isset($settings['value']) ? $settings['value'] : '';
		$this->value = new \Snap\Lib\Form\Data\Basic( $this->getName(), $this->getFunctionalValue() ); // need to wait until name is parsed out and trueValue
		$this->disabled = isset($settings['disabled']) ? $settings['disabled'] : false;
		$this->readonly = isset($settings['readonly']) ? $settings['readonly'] : false;
		
		parent::parseSettings( $settings );
	}

	public static function getSettings(){
		parent::getSettings() + array(
			'name'     => 'the name of the input in the form',
			'value'    => 'default value of the input',
			'disabled' => 'disable the input',
			'readonly' => 'make the input read only'
		);
	}
	
	public function setWrapper( \Snap\Node\Snapable $node ){
		$this->wrapper = null;
	}
	
	public function getWrapper(){
		return $this->wrapper == null ? $this : $this->wrapper;
	}
	
	protected function baseClass(){
		return 'form-input';
	}
	
	// TODO : this is probably a hack
	protected function getFunctionalValue(){
		return $this->trueValue;
	}

	public function getInput( \Snap\Node\Form $form ){
    	$data = $form->getValue( $this->value->getName() );
    	
    	if ( $data !== null ){
    		$this->value->setValue( $data );
    	}
    	
    	return $this->value;
    }
    
    protected function getAttributes() {
    	return parent::getAttributes()
    		.( " name = \"{$this->name}\"" )
    		.( $this->disabled ? ' disabled="true"' : '' )
    		.( $this->readonly ? ' readonly="true"' : '' );
    }
    
    public function getName(){
    	return $this->name;
    }
    
	public function getValue(){
    	return $this->value->getValue();
    }
    
	public function setValue( $value ){
		$this->value->setValue( $value );
    }
    
    public function hasChanged(){
    	return $this->value->hasChanged();
    }
    
    public function changeName( $name ){
    	$this->value->changeName( $name );
    }
    
    // TODO : I shouldn't need this... has to be a hack around
    public function setDefaultValue( $value ){
    	$this->value->setDefaultValue($value);
    }
    
    public function changeValue( $value ){ // TODO : this needs to go
    	$this->setValue( $value );
    }
    
	public function reset(){
		$this->value->setValue( $this->value->getDefault() );
    }
}