<?php

namespace Snap\Prototype\Topic\Node\Form;

class Management extends \Snap\Node\Core\Form 
	implements \Snap\Node\Core\Styleable, \Snap\Node\Core\Consumer {
		
	protected 
		$consumed = false,
		$waitingQueue;
		
	public function __construct( $settings = array() ){
		$this->hidden = array( TOPIC_ID );

		$this->fields = array(  
			TOPIC_TITLE    => 'Title',
			TOPIC_TYPE_ID  => 'Type',
			'content'      => 'Content',
			'remove'	   => 'Remove?',
		);

		$this->types = array(	
			'remove'        => array('type' => 'checkbox'),
			TOPIC_TYPE_ID   => array(
				'type' => 'select',
				'selections' => \Snap\Prototype\Topic\Lib\Type::hash()
			),
			'content'       => array('type' => 'textarea')
		);
		
		parent::__construct( $settings );
	}
	
	public function isWaiting(){
		return $this->waitingQueue != null;
	}
	
	public function setWaitingQueue( \Snap\Lib\Streams\WaitingQueue $queue ){
		$this->waitingQueue = $queue;
	}
	
	public function getStreamRequest(){
		return new \Snap\Lib\Streams\Request( 'topics_new_form_proto', $this );
	}
	
	public function consumeRequest( \Snap\Lib\Streams\Request $request ){
		$this->consumed = true;
		
		$data = $request->getStreamData('topics_new_form_proto');
		
		if ( $data != null ){
			for( $i = 0, $c = $data->count(); $i < $c; $i++ ){
				$info = $data->get($i);
				$t = new \Snap\Prototype\Topic\Lib\Element($info);
				
				$this->table->addRow( $t->info() );
			}
		}
	}
	
	public function hasConsumed(){
		$this->consumed;
	}
	
	public function needsData(){
		return true;
	}

	protected function processInput( \Snap\Lib\Form\Result &$formData ){
		$topic = $formData->getChange( 'topic_data' );
	        
        if ( $topic ){
        	$changeList = $topic->getChangeList();
        	
        	foreach ( $changeList as $row ){
	            $in = $topic->getValues( $row );
	            
	            $t = new \Snap\Prototype\Topic\Lib\Element( $in[TOPIC_ID] );

	            $changes = $topic->getChangeValues( $row );
	            
	            if ( isset($changes['remove']) ){
	            	if ( $t->delete() ){
	            		$this->table->removeRow(TOPIC_ID, $in[TOPIC_ID]);
	            	}
	            }else{
	            	$t->update( $changes );
	            }
	        }
        }
        
        return null;
	}
	
	public function getStyles(){
		return array(
			new \Snap\Lib\Linking\Resource\Local( $this->page,$this)
		);
	}
}