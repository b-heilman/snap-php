<?php

namespace Snap\Node;

class Form extends \Snap\Node\Template {
	
    private 
    	$proc = null, 
    	$input, 
    	$navVars = array(),
    	$validator;
    	
    protected 
    	$formName,
    	$method, 
    	$action, 
    	$encoding, 
    	$target,
    	$messaging,
    	$messagingOwner,
    	$messagingRollup,
    	$processResult = false;

    public function __construct( $settings = array() ) {
    	$this->input = new \Snap\Lib\Form\Input();
    	
    	parent::__construct( $settings );
    }
	
    protected function parseSettings( $settings = array() ){
    	$settings['tag'] = 'form';
    	
    	if ( isset($settings['formName']) && !empty($settings['formName']) ){
    		$this->formName = $settings['formName'];
		}else{
			if ( isset($settings['id']) && !empty($settings['id']) ){
    			$this->formName = $settings['id'];
    		}elseif ( isset($settings['name']) && !empty($settings['name']) ){
    			$this->formName = $settings['name'];
    		}else{
				$this->formName = get_called_class();
    		}
		}

		if ( defined('FORCE_ALL_FORMS_TO') ){
			$settings['action'] = FORCE_ALL_FORMS_TO;
		}
		
		// turn of messaging for this form.  Messaging can actually be explicitly turned off
		if ( !isset($settings['messaging']) || $settings['messaging'] ){
			$this->activateMessaging();
		}
		
		// messaging rollup means the messages are rolled up from the children to the parent form
		$this->messagingOwner = true;
		$this->messagingRollup = isset($settings['messagingRollup']) ? $settings['messagingRollup'] : true;
		
		$this->target = isset($settings['target']) ? $settings['target'] : '_self';
		$this->action = isset($settings['action']) ? $settings['action'] : '';
		$this->setEncoding( isset($settings['encoding']) ? $settings['encoding']: '' );
		$this->setMethod( isset($settings['method']) ? strtoupper($settings['method']) : 'POST' );
		$this->setValidator( isset($settings['validator']) ? $settings['validator'] : $this->defaultValidator() );
		
		parent::parseSettings($settings);
    }
    
    protected function getContent(){
    	return ( $this->path == '' ) ? '' : parent::getContent();
    }
    
    protected function setValidator( $validator ){
    	$this->validator = $validator;
    }
    
    protected function defaultValidator(){
    	return null;
    }
    
    protected function setNavVar( $var, $value ){
    	$this->navVars[$var] = $value;
    }
    
    protected function readNavVar( $var ){
    	return $this->input->readGet($var);
    }
    
	public function setEncoding( $encoding ){
		$encoding = strtolower($encoding);
		
		if ( $this->parent && $this->tag == 'div' ){
			if ( $encoding != 'application/x-www-form-urlencoded' ){
				$this->parent->setEncoding( $encoding );
			}
		}else{
			$this->encoding  = ( $encoding ) ? $encoding : 'application/x-www-form-urlencoded';
		}
	}
	
	public function baseClass(){
		return 'snap-form';
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'target'     => 'the target of the form',
			'encoding'   => 'what type of form encoding to use',
			'action'     => 'where the data is getting submitted',
			'method'     => 'what method to submit the data with',
			'validator'  => 'the validator to handle the form inputs'
		);
	}

	protected function getAttributes(){
		$atts = '';
		
		if ( $this->tag == 'form' ){
			if ( !empty($this->navVars) ){
				$first = false;
				
				if ( strpos($this->action, '?') === false ){
					$this->action .= '?';
					$first = true;
				}elseif( strpos($this->action, '=') === false ){
					$first = true;
				}
				
				foreach( $this->navVars as $nav => $var ){
					if ( $first ){
						$first = false;
					}else{
						$this->action .= '&';
					}
					
					$this->action .= urlencode($nav).'='.urldecode($var);
				}
			}
			
			$atts = " target=\"{$this->target}\""
				. " enctype=\"{$this->encoding}\""
				. " action=\"{$this->action}\""
				. " method=\"{$this->method}\"";
		}
		
		return parent::getAttributes().$atts;
	}

	protected function build(){
		$this->append( new \Snap\Node\Form\Input\Hidden(array(
			'name'  => 'form_'.$this->formName, 
			'value' => 1
		)) );
			
		parent::build();
	}
	
	public function getValue( $name ){
		return ( $this->wasSubmitted($name) ? $this->read($name) : null );
	}

	public function wasSubmitted( $name ){
		return ( $this->method == 'GET' ) 
			? $this->input->issetGet( $name ) 
			: $this->input->issetPost( $name ) ;
	}

	public function wasFormSubmitted(){
		return $this->wasSubmitted( 'form_'.$this->formName );
	}
	
	protected function read( $name ){
		return ( $this->method == 'GET' ) 
			? $this->input->readGet( $name ) 
			: $this->input->readPost( $name );
	}

	public function removeControls(){
		// I am just going to remove all submits from $in and then be done with it.  If I need to make
		// this none destructive later I can
		$eles = $this->getElementsByClass('\Snap\Node\Form\Input\Button');
	    $c = count( $eles );
		for( $i = 0; $i < $c; $i++ ){
			$e = $eles[$i];
			$e->removeFromParent();
		}
	}

	public function clear(){
		parent::clear();

		$this->append( new \Snap\Node\Form\Input\Hidden(array(
			'name'  => 'form_'.$this->formName, 
			'value' => 1
		)) );
	}

	public function reset(){
		$eles = $this->getElementsByClass('\Snap\Node\Form\Input');
    	$c = count( $eles );
    	
		for( $i = 0; $i < $c; $i++ ){
			$eles[$i]->reset();
		}
	}

	protected function setMethod( $method ){
		$method = strtoupper($method);
		
		$this->method = ( $method == 'GET' ? 'GET' : 'POST' );
	}
	
	/**
	 * pull in all of the data from the form.  errors and data are returned
	 *------
	 * @return form_data_result
	 **/
	public function getInput(){
	    if ( !$this->proc ) {
    		$this->proc = new \Snap\Lib\Form\Data\Result( $this );
	   	}
	 
	   	if ( $this->validator ){
	   		$this->validator->validate( $this->proc );
	   	}
	   	
	   	return $this->proc;
	}
	
	public function takeControl( \Snap\Node\Snapable $in ){
		if ( ($in instanceof \Snap\Node\Snapping && !($in instanceof Form)
			|| ($in instanceof \Snap\Node\Snapping && $in instanceof Form && $this->tag != 'div')) ){
			if ( $in instanceof Form ){
				$this->alterSubForm( $in );
			}else{
				$forms = $in->getElementsByClass('\Snap\Node\Form'); // TODO : well, bruteforce to the rescue
			    foreach( $forms as $form ){ 
					$this->alterSubForm( $form ); 
			   	}
			}
		}
		
		parent::takeControl($in);
	}
	
	protected function wrapSubForms(){
		$forms = $this->getElementsByClass('\Snap\Node\Form'); // TODO : well, bruteforce to the rescue
	    foreach( $forms as $form ){ $this->alterSubForm($form); }
	}
	
	protected function alterSubForm( Form $form ){
		$form->tag = 'div';
		$form->addClass('form-section');
		
		// The parent form will take over control of the message from the children
		if ( $this->messagingRollup ){
			$form->activateMessaging( $this->messaging );
			$form->messagingOwner = false;
		}
		
		$form->wrapSubForms();
		
		// anything here will be pulled up
		if ( $form->encoding !== 'application/x-www-form-urlencoded' ){
			$this->setEncoding( $form->encoding );
		}
		
		if ( !empty($form->navVars) ){
			$this->navVars = $this->navVars + $form->navVars;
		}
		
		$form->navVars = &$this->navVars;
	}
	
	protected function canProcess(){
		return $this->wasFormSubmitted() && $this->processResult === false;
	}
	
	protected function isInputReady( \Snap\Lib\Form\Data\Result $proc ){
		return $proc->hasChanged() && !$proc->hasErrors();
	}
	// wrap on the standard append, but this time it checks if it's
	// an form_row_node being passed in to make sure everything stays in line
	
    protected function _process(){
    	if ( $this->canProcess() ){
    		$this->processResult = null;
    		
    		$proc = $this->getInput();
    		
    		if ( !$this->wasFormSubmitted() ){
    			$proc->clearErrors();
    		}
    		
    		if ( $this->isInputReady($proc) ){
    			$this->processResult = $this->processInput( $proc );
    		}
    		
    		if ( $proc->hasErrors() ){
    			$errors = $proc->getErrors();
    			
    			//TODO : error processing
    			$c = count($errors);
    			for( $i = 0; $i < $c; ++$i ){
    				$var = $errors[$i];
    				
    				// TODO : this is going to need to get a little more complex...
    				$this->addError( is_object($var) ? $var->getError() : $var );
    			}
    		}
    	}elseif( !$this->wasFormSubmitted() ){
    		$this->processResult = null;
    	}
    }
    
    /**
     * @return \Snap\Lib\Form\Data\Result
     **/
	public function getProcessResult(){
    	return $this->processResult;
    }
    
    protected function processInput( \Snap\Lib\Form\Data\Result &$formData ){
    	return null;
    }
    
    protected function _finalize(){
    	if ( $this->messaging && (!$this->messagingOwner || $this->messaging->childCount() == 0) ){
    		$this->remove( $this->messaging );
    	}
    }
    
    public function getMessageBlock(){
    	return $this->messaging;
    }
    
    // TODO : search the treee and see if a message block has been set via the template
    protected function activateMessaging( \Snap\Node\Snapping $block = null ){
    	if ( $block == null ){
    		$this->messaging = new \Snap\Node\Block(array(
				'tag'   => 'div',
				'class' => 'form-messages '
			));
    	}else{
    		if ( $this->messaging != null && $this->messaging->hasParent() ){
				// TODO : maybe copy it over?
				$this->messaging->removeFromParent();
    		}
    		
    		$this->messaging = $block;
    	}
    }
    
	protected function addNote( $note, $class = '' ){
		if ( $this->messaging ){
    		$this->messaging->write( $note, 'note-message '.$class );
    	}
    }
    
	protected function addError( $error, $class = '' ){
    	if ( $this->messaging ){
    		$this->messaging->write( $error, 'error-message '.$class );
    	}
    }
}