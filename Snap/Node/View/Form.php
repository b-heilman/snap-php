<?php

namespace Snap\Node\View;

abstract class Form extends \Snap\Node\Core\Template {
	    	
    protected 
    	$action, 
    	$target,
    	$content,         
    	$encoding, 
    	$messaging,
    	$messagingOwner,
    	$messagingRollup;

    protected function parseSettings( $settings = array() ){
    	$settings['tag'] = 'form';

    	if ( isset($settings['content']) ){
	    	$this->content = $settings['content'];
	    	/** @var $this->content \Snap\Model\Form **/
    	}else{ 
    		$this->content = null;
    	}
    	
    	if ( $this->content == null ){
    		$this->setEncoding( null );
    	}elseif( $this->content instanceof \Snap\Model\Form ){
    		$this->setEncoding( $this->content->getEncoding() );
    	}else{
    		throw new Exception("A form's content needs to be instance of \Snap\Model\Form");
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
		
		parent::parseSettings($settings);
    }
    
    // allows for a form to be created that is a wrapper around other forms
    protected function getTemplateContent(){
    	return ( $this->path == '' ) ? '' : parent::getTemplateContent();
    }
    
    public function setEncoding( $encoding ){
		$this->encoding  = ( $encoding ) 
			? strtolower($encoding) 
			: 'application/x-www-form-urlencoded';
	}
	
	public function baseClass(){
		return 'snap-form';
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'target'     => 'the target of the form',
			'encoding'   => 'what type of form encoding to use',
			'action'     => 'where the data is getting submitted',
			'content'    => 'instance of \Snap\Model\Form to populate data with'
		);
	}

	protected function getAttributes(){
		$atts = '';
		$method = $this->content ? $this->content->getMethod() : 'POST';
		
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
		if ( $this->content ){
			$this->append( new \Snap\Node\Form\Input\Hidden(array(
				'input'  => $this->content->getControlInput()
			)) );
		}
		
		parent::processTemplate();
	}
	
	protected function _finalize(){
		parent::_finalize();
		
		if ( $this->messaging && $this->content && $this->content instanceof \Snap\Model\Form ){
			$proc = $this->content->getResults(); // just make sure the inputs have their values updated from the stream
			$output = $this->content->getInputs();
			
			error_log( 'Form : messaging : '.get_class($this) );
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
						$this->messaging->write( $error->getError(), 'form-error-message' );
						$error->markReported();
					}
				}
			}
		}
	}
	
	protected function getTemplateVariables(){
		if ( $this->content && $this->content instanceof \Snap\Model\Form ){
			/** @var \Snap\Lib\Form\Result **/
			$output = $this->content->getInputs();
		}else{
			$output = array();
		}
		
		
		if ( $this->messaging && $this->messagingOwner ){
			$output['__messages'] = $this->messaging;
		}else{
			$output['__messages'] = null;
		}
		
		return $output;
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
    
    public function takeControl( \Snap\Node\Core\Snapable $in ){
    	if ( $in instanceof Form && $this->tag != 'div' ){
    		$this->alterSubForm( $in );
    	}
    
    	parent::takeControl($in);
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