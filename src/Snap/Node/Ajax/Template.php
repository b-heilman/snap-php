<?php

namespace Snap\Node\Ajax;

class Template extends \Snap\Node\Core\Template {
	
	protected
		$templateData;
	
	public function __construct( $settings = array(), $id = null, $data = null ){
		if ( is_string($settings) ){
			$settings = array( 'template' => $settings, 'templateData' => $data, 'id' => $id );
		}
		
		parent::__construct( $settings );
	}
	
	protected function makeTemplateContent(){
		return $this->templateData;
	}
	protected function parseSettings( $settings = array() ){
		if ( !isset($settings['template']) ){
			throw new \Exception('Need template for '.get_class($this) );
		}
		
		if ( isset($settings['templateData']) ){
			$this->templateData = $settings['templateData'];
		}else{
			throw new \Exception('Need content for '.get_class($this) );
		}
		
		parent::parseSettings( $settings ); 
	}
	
	public function html(){
		$inner = htmlentities( $this->inner() );
		return "<script type='text/xml' id='{$this->id}'>\n<![CDATA[\n{$inner}\n]]>\n</script>";
	}
}