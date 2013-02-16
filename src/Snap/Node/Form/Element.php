<?php

namespace Snap\Node\Form;

use Snap\Node\Core\Text;
// Wrapper for input fields.  Adds labels and comments to the input
/*
 * TODO __construct($type, $name, $label = false, $value = '', $options = '')
 * - match the constructor
 * - make sure options match
 */

// TODO start using options to toggle between attributes for inputs
// TODO $name needs to be switched to $id to make more sense

class Element extends \Snap\Node\Core\Template {
		
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
    	$name = $input->getName();
    	
    	$settings['class'] = ( isset($settings['class']) ? $settings['class'].' ' : '' )
    		. 'form-element '.$input->getType().'-wrapper';
    	$settings['tag'] = 'div';
    	
    	if ( $input instanceof Input ){
			$this->input = $input;
			/*
			TODO
	    	if ( $input instanceof WrappableInput ){
				$this->input->setWrapper( $this );
			}else{
				throw new \Exception( 'the "input" setting needs to be type of input_node, '.get_class($input).' passed instead' );
			}
			*/
		}else{
			throw new \Exception( 'the "input" setting needs to be type of input_node, '.get_class($input)
				.' passed instead. Settings: '.print_r($settings, true)
			);
		}
		
		
    	
		if ( isset($settings['label']) ){
			$this->label = $settings['label'];
		}

		if ( isset($settings['note']) ){
			$this->note = $settings['note'];
		}
		
		parent::parseSettings( $settings );
	}
}
