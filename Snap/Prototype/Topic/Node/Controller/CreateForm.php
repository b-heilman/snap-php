<?php

namespace Snap\Prototype\Topic\Node\Controller;

class CreateForm extends \Snap\Node\Controller\Form {
	
	protected function formatTitle( $title ){
		return $title;
	}
	
	protected function formatContent( $content ){
		return $content;
	}
	
	protected function processInput( \Snap\Lib\Form\Result $formRes ){
		$res = null;
		
		$info = array(
			TOPIC_TYPE_ID => $formData->getValue('new_topic_type'),
			TOPIC_TITLE   => $this->formatTitle( $formData->getValue('new_topic_title') ),
			'content'     => $this->formatContent( $formData->getValue('new_topic_content') )
		);
		
		try {
			if ( $id = \Snap\Prototype\Topic\Lib\Element::create($info) ){
				$res = new \Snap\Prototype\Topic\Lib\Element($id);
				
				$formRes->addNote( 'Topic created' );
				
				$this->content->reset();
			}else{
				$formRes->addFormError( 'Error creating topic' );
				
				error_log( \Snap\Adapter\Db\Mysql::lastQuery() );
				error_log( \Snap\Adapter\Db\Mysql::lastError() );
			}
		}catch( \Exception $ex ){
			$formRes->addError( 'Exception creating topic' );
			
			error_log( $ex->getMessage() );
		}
		
		return $res;
	}
}