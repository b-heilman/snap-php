<?php

namespace Snap\Node\Core;

class Form extends \Snap\Node\Core\Template {

	private 
		$hasSubmit = false;
	
    protected 
    	$active = true,
    	$action, 
    	$target,
    	$model,         
    	$encoding,
    	$messaging,
    	$messagingOwner,
    	$messagingRollup;

    public function __construct( $settings = array() ){
    	if ( !is_array($settings) ){
    		$settings = array( 'model' => $settings );
    	}
    
    	parent::__construct( $settings );
    }
    
    protected function parseSettings( $settings = array() ){
    	$settings['tag'] = 'form';

    	if ( isset($settings['model']) ){
    		/** @var $this->model \Snap\Model\Form **/
	    	$this->model = $settings['model'];
    	}else{ 
    		$this->model = null;
    	}
    	
    	if ( $this->model == null ){
    		$this->setEncoding( null );
    	}elseif( $this->model instanceof \Snap\Model\Form ){
    		$this->setEncoding( $this->model->getEncoding() );
    	}else{
    		throw new \Exception("A form's model needs to be instance of \Snap\Model\Form");
    	}
    	
			// turn of messaging for this form.  Messaging can actually be explicitly turned off
			if ( !isset($settings['messaging']) || $settings['messaging'] ){
				$this->activateMessaging();
			}
			
			// messaging rollup means the messages are rolled up from the children to the parent form
			$this->messagingOwner = true;
			$this->messagingRollup = isset($settings['messagingRollup']) ? $settings['messagingRollup'] : true;
			
			$this->target = isset($settings['target']) ? $settings['target'] : '_self';
			$this->action = isset($settings['action']) ? $settings['action'] : static::$pageURI;
			
			parent::parseSettings($settings);
    }
    
    protected function setInactive(){
    	$this->active = false;
    }
    
    // allows for a form to be created that is a wrapper around other forms
    protected function getTemplateHTML(){
    	return ( $this->path == '' ) ? '' : parent::getTemplateHTML();
    }
    
    public function setEncoding( $encoding ){
		$this->encoding  = ( $encoding ) 
			? strtolower($encoding) 
			: 'application/x-www-form-urlencoded';
	}
	
	protected function baseClass(){
		return 'snap-form '.get_class($this);
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'target'   => 'the target of the form',
			'encoding' => 'what type of form encoding to use',
			'action'   => 'where the data is getting submitted',
			'model'    => 'instance of \Snap\Model\Form to populate data with'
		);
	}

	protected function getAttributes(){
		$atts = '';
		$method = $this->model ? $this->model->getMethod() : 'POST';
		
		if ( $this->tag == 'form' ){
			$atts = " target=\"{$this->target}\""
				. " enctype=\"{$this->encoding}\""
				. " action=\"{$this->action}\""
				. " method=\"{$method}\"";
		}
		
		return parent::getAttributes().$atts;
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

	protected function processTemplate(){
		if ( $this->model ){
			$this->append( new \Snap\Node\Form\Input\Hidden(array(
				'input'  => $this->model->getControlInput()
			)) );
		}
		
		parent::processTemplate();
	}
	
	public function wasSubmitted(){
		return $this->model->wasFormSubmitted();
	}
	
	public function hasErrors(){
		return $this->model->hasFormErrors();
	}
	
	public function takeControl( \Snap\Node\Core\Snapable $in ){
		if ( $in instanceof Form && $this->tag != 'div' ){
			$this->alterSubForm( $in );
		}elseif( $in instanceof \Snap\Node\Form\Input\Button  && $in->getType() == 'submit' ){
			$this->hasSubmit = true;
		}
	
		parent::takeControl($in);
	}
	
	protected function _finalize(){
		parent::_finalize();
		
		if ( !$this->hasSubmit && $this->tag == 'form' && $this->active ){
			$this->append( new \Snap\Node\Form\Control() );
		}
		
		if ( $this->messaging && $this->model && $this->model instanceof \Snap\Model\Form ){
			$proc = $this->model->getResults(); // just make sure the inputs have their values updated from the stream
			$output = $this->model->getInputs();
			
			$notes = $proc->getNotes();
			foreach( $notes as $note ){
				$this->messaging->write( $note, 'form-note-message' );
			}
		
			// TODO : right now I am not rolling up input errors, might want to switch
			$errors = $proc->getFormErrors();
			foreach( $errors as $error ){
				/** @var $error \Snap\Lib\Form\Error **/
				$this->messaging->write( $error->getError(), 'form-error-message' );
			}
		
			$errors = $proc->getInputErrors();
			foreach( $errors as $field ){
				$input = $output[ $field ];
				/** @var $input \Snap\Lib\Form\Input **/
				$errs = $input->getErrors();
				foreach( $errs as $error ){
					/** @var $error \Snap\Lib\Form\Error **/
					if ( !$error->isReported() ){
						$this->messaging->write( $error->getError(), 'form-input-error' );
						$error->markReported();
					}
				}
			}
		}
		
		if ( $this->messagingOwner && $this->messaging && !$this->messaging->hasParent() ){
			$this->prepend( $this->messaging );
		}
	}
	
	protected function makeProcessContent(){
		if ( $this->model && $this->model instanceof \Snap\Model\Form ){
			/** @var \Snap\Lib\Form\Result **/
			$output = $this->model->getSeries() + $this->model->getInputs();
		}else{
			$output = array();
		}
		
		if ( $this->messaging && $this->messagingOwner && !$this->messaging->hasParent() ){
			$output['__messages'] = $this->messaging;
		}else{
			$output['__messages'] = null;
		}
		
		return $output;
	}
    
	public function getMessagingBlock(){
		return $this->messaging;
	}
	
    // TODO : search the treee and see if a message block has been set via the template
    protected function activateMessaging( \Snap\Node\Core\Snapping $block = null ){
    	if ( $block == null ){
    		$this->messaging = new \Snap\Node\Core\Block(array(
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
    
    protected function alterSubForm( Form $form ){
    	$form->tag = 'div';
    	$form->addClass('form-section');
    
    	// The parent form will take over control of the message from the children
    	if ( $this->messagingRollup ){
    		$form->activateMessaging( $this->messaging );
    		$form->messagingOwner = false;
    	}
    
 	  	// anything here will be pulled up
    	if ( $form->encoding !== 'application/x-www-form-urlencoded' ){
    		$this->setEncoding( $form->encoding );
    	}
    }
}