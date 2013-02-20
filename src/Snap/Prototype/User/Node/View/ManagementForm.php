<?php

namespace Snap\Prototype\User\Node\View;

// TODO : this is going to be refactored
class Management extends \Snap\Node\Core\Form 
	implements \Snap\Node\Core\Styleable {
	
	protected function processInput( \Snap\Lib\Form\Result &$formData ){
	    $users = $formData->getChange('user_data');
	     
        if ( $users ){
	        foreach ( $users->getChangeList() as $key ){
	            $in = $users->getValues( $key );
				
	            $u = new \Snap\Prototype\User\Lib\Element($in['id']);

	            $dat = array();

	            $changes = $users->getChangeValues( $key );
	            
	            if ( isset($changes['password']) ){
	            	$u->updatePassword( $changes['password'] );
	            	unset( $changes['password'] );
	            }
	             
	            if ( !empty($changes) ){
	            	$u->update($changes);
	            }
	        }
        }
	}
	
	public function getStyles(){
		return array(
			new \Snap\Lib\Linking\Resource\Local( $this )
		);
	}
	
	protected function makeTemplateContent(){
		$fields = array(
				'login'     => 'Login',
				'display' 	=> 'Display',
				'status'		=> 'Status',
				'password'	=> 'Password',
				'admin'			=> 'Admin?'
		);
		
		return array(
			'hidden' => array( 'id', 'status_date', 'creation_date' ),
			'fields' => $fields,
			'types'  => array(
			'admin' => array('type' => 'checkbox'),
			'status'	=> array(
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
