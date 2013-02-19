<?php

namespace Snap\Lib\Node;

class StackFactory {
	public function makeStack( \Snap\Node\Core\Snapable $node ){
		return new Stack( $node, $this->makeExtender() );
	}
	
	protected function makeExtender(){
		static $extender = null;
		
		if ( is_null($extender) ){
			$extender = new Extender();
		
			$extender->addExtension( \Snap\Lib\Node\Extension\Streams::getInstance() );
			
			$extender->addExtension( \Snap\Lib\Node\Extension\Builder::getInstance() );
			$extender->addExtension( \Snap\Lib\Node\Extension\Processor::getInstance() );
			$extender->addExtension( \Snap\Lib\Node\Extension\Javascript::getInstance() );
			$extender->addExtension( \Snap\Lib\Node\Extension\Css::getInstance() );
			$extender->addExtension( \Snap\Lib\Node\Extension\Finalizer::getInstance() );
		}	
		
		return $extender;
	}
}