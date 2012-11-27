<?php

namespace Snap\Node\Form;

use Snap\Node\Text;
// Wrapper for input fields.  Adds labels and comments to the input
/*
 * TODO __construct($type, $name, $label = false, $value = '', $options = '')
 * - match the constructor
 * - make sure options match
 */

// TODO start using options to toggle between attributes for inputs
// TODO $name needs to be switched to $id to make more sense

class Element extends \Snap\Node\Template 
	implements \Snap\Node\Form\Input {
		
	public    
		$input;
		
	protected 
		$label = null,
		$note  = null;

	public static function getSettings(){
		return parent::getSettings() + array(
			'label'     => 'The label for the input',
			'note'      => 'The note for the input, follows the input in the label',
			'input'     => 'The type of input to use'
		);
	}

    protected function parseSettings( $settings = array() ){
    	$input = $settings['input'];
    	
    	if ( is_string($input) ){
    		$input = new $input( array('name' => $settings['name']) );
    		unset( $settings['name'] );
    	}
    	
    	if ( $input instanceof Input ){
			$this->input = $input;
			
	    	if ( $input instanceof WrappableInput ){
				$this->input->setWrapper( $this );
			}else{
				throw new \Exception( 'the "input" setting needs to be type of input_node, '.get_class($input).' passed instead' );
			}
		}else{
			throw new \Exception( 'the "input" setting needs to be type of input_node, '.get_class($input)
				.' passed instead. Settings: '.print_r($settings, true)
			);
		}
		
		$name = $input->getName();
		
    	$settings['class'] = ( isset($settings['class']) ? $settings['class'].' ' : '' )
    		. 'form-element '.( $name == '' ? '' : $name.'-wrapper' ).' '.$input->getType().'-wrapper';
    	$settings['tag'] = 'div';
    	
		if ( isset($settings['label']) ){
			$label = $settings['label'];

			if ( $label instanceof Text ){
				$label->addClass( 'form-element-label' );
				$this->labelText = $label->inner();
				$this->label = $label;
			}elseif( is_string($label) ){
				$this->labelText = $label;
				$this->label = new Text(array(
					'tag'   => 'span',
					'text'  => $label,
					'class' => 'form-element-label'
				));
			}
		}

		if ( isset($settings['note']) ){
			$note = $settings['note'];

			if ( $note instanceof \Snap\Node\Simple ){
				$note->addClass( 'form-element-note' );
				$this->note = $note;
			}elseif( is_string($note) ){
				$this->note = new Text(array(
					'tag'   => 'span',
					'text'  => $note,
					'class' => 'form-element-note'
				));
			}
		}
		
		parent::parseSettings($settings);
	}

	public function getType(){
		return $this->input->getType();
	}
	
	public function getInput( \Snap\Node\Form $form ){
		$res = $this->input->getInput( $form );
		
		// TODO : what to do with labels now?
		/*
		if ( $this->labelText != null ){
			$res->setLabel( $this->labelText );
		}
		*/
		
		return $res;
	}
	
	public function hasChanged(){
		return $this->input->hasChanged();
	}
	
	public function changeName($name){
		$this->input->changeName($name);
	}
	
	public function setDefaultValue( $value ){
		$this->input->setDefaultValue($value);
	}
	
	public function setValue( $value ){
		$this->input->setValue($value);
	}
	
	public function getValue(){
		return $this->input->getValue();
	}
	
	public function getName(){
		return $this->input->getName();
	}
	
	public function reset(){
		$this->input->reset();
	}
}
