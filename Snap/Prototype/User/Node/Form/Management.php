<?php

namespace Snap\Prototype\User\Node\Form;

class Management extends \Snap\Node\Form 
	implements \Snap\Node\Styleable {
	
	protected function processInput( \Snap\Lib\Form\Data\Result &$formData ){
	    $users = $formData->getChange('user_data');
	     
        if ( $users ){
	        foreach ( $users->getChangeList() as $key ){
	            $in = $users->getValues( $key );
				
	            $u = new \Snap\Prototype\User\Lib\Element($in[USER_ID]);

	            $dat = array();

	            $changes = $users->getChangeValues( $key );
	            
	            if ( isset($changes[USER_PASSWORD]) ){
	            	$u->updatePassword( $changes[USER_PASSWORD] );
	            	unset( $changes[USER_PASSWORD] );
	            }
	             
	            if ( !empty($changes) ){
	            	$u->update($changes);
	            }
	        }
        }
	}
	
	public function getStyles(){
		return array(
			new \Snap\Lib\Linking\Resource\Local($this)
		);
	}
	
	protected function getTemplateVariables(){
		$fields = array(
				USER_LOGIN 				=> USER_LOGIN_LABEL,
				USER_DISPLAY 			=> USER_DISPLAY_LABEL,
				AUTH_STATUS_FIELD		=> 'Status',
				USER_PASSWORD			=> 'Password',
				USER_ADMIN				=> 'Admin?'
		);
		
		if ( USER_LOGIN == USER_DISPLAY ){
			array_splice( $fields, 1, 1 );
		}
		
		return array(
			'hidden' => array( USER_ID, 'status_date', 'creation_date' ),
			'fields' => $fields,
			'types'  => array(
				USER_ADMIN 			=> array('type' => 'checkbox'),
				AUTH_STATUS_FIELD	=> array(
						'type' => 'select',
						'selections' => array(
								'CREATED' 	=> 'CREATED',
								'ACTIVE' 	=> 'ACTIVE',
								'INACTIVE'	=> 'INACTIVE'
						)
				)
			)
		);
	}
}
