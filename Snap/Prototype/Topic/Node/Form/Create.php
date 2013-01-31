<?php

namespace Snap\Prototype\Topic\Node\Form;

class Create extends \Snap\Node\Core\ProducerForm 
	implements \Snap\Node\Core\Styleable {
		
	protected 
		$select, 
		$type = null;
	
	protected function parseSettings( $settings = array() ){
		if ( isset($settings['type']) ){
			$this->setType( $settings['type'] );
		}
		
		parent::parseSettings( $settings );
	}
	
	public function getOuputStream(){
		return 'topics_new_form_proto';
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'type' => 'preset the topic to have a preset type'
		);
	}
	
	public function setType( $type ){
		$tt = new \Snap\Prototype\Topic\Lib\Type($type);
		
		$this->type = $tt->id();
	}
	
	protected function formatTitle( $title ){
		return $title;
	}
	
	protected function formatContent( $content ){
		return $content;
	}
	
	protected function processInput( \Snap\Lib\Form\Result &$formData ){
		$res = null;
		
		$info = array(
			TOPIC_TYPE_ID => ($this->type) ? $this->type : $formData->getValue('new_topic_type'),
			TOPIC_TITLE => $this->formatTitle( $formData->getValue('new_topic_title') ),
			'content' => $this->formatContent( $formData->getValue('new_topic_content') )
		);
		
		try {
			if ( $id = \Snap\Prototype\Topic\Lib\Element::create($info) ){
				$res = new \Snap\Prototype\Topic\Lib\Element($id);
				
				$this->prepend( $notes = new \Snap\Node\Core\Block(array(
					'tag'   => 'span', 
					'class' => 'infos'
				)) );
				
				$notes->write( 'Topic created' );
				
				$this->reset();
			}else{
				$formData->addError( 'Error creating topic' );
				
				$this->addNote( \Snap\Adapter\Db\Mysql::lastQuery() );
				$this->addNote( \Snap\Adapter\Db\Mysql::lastError() );
			}
		}catch( \Exception $ex ){
			$formData->addError( 'Error creating topic' );
			
			$this->addNote( $ex->getMessage() );
		}
		
		return $res;
	}
	
	protected function _finalize(){
		parent::_finalize();
		
		if ( !$this->type ){
			$this->select->input->setOptions( array('' => 'Pick A Type') + \Snap\Prototype\Topic\Lib\Type::hash() );
		}
	}
	
	public function getStyles(){
		return array(
			new \Snap\Lib\Linking\Resource\Local( $this->page,$this)
		);
	}
}
