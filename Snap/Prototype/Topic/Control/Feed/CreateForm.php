<?php

namespace Snap\Prototype\Topic\Control\Feed;

class CreateForm extends \Snap\Control\Feed\Form {
	
	protected function formatTitle( $title ){
		return $title;
	}
	
	protected function formatContent( $content ){
		return $content;
	}
	
	protected function processInput( \Snap\Lib\Form\Result $formRes ){
		$res = null;
		
		$inputs = $formRes->getInputs();
		
		$info = array(
			TOPIC_TYPE_ID => isset($inputs['type']) ? $inputs['type']->getValue() : $this->model->type,
			TOPIC_TITLE   => $this->formatTitle( $inputs['title']->getValue() ),
			'content'     => $this->formatContent( $inputs['content']->getValue() )
		);
		
		try {
			if ( $id = \Snap\Prototype\Topic\Lib\Element::create($info) ){
				$res = new \Snap\Prototype\Topic\Lib\Element($id);
				
				$formRes->addNote( 'Topic created' );
				
				$this->model->reset();
			}else{
				$formRes->addFormError( 'Error creating topic' );
				
				$formRes->addDebug( \Snap\Adapter\Db\Mysql::lastQuery() );
				$formRes->addDebug( \Snap\Adapter\Db\Mysql::lastError() );
			}
		}catch( \Exception $ex ){
			$formRes->addError( 'Exception creating topic' );
			
			$formRes->addDebug( $ex->getMessage() );
		}
		
		return $res;
	}
}