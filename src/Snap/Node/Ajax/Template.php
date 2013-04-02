<?php

namespace Snap\Node\Ajax;

class Template extends \Snap\Node\Core\Template {
	
	protected
		$base = null,
		$templateData,
		$templateWrapper;
	
	public function __construct( $settings = array(), $id = null, $data = null ){
		if ( is_object($settings) ){
			if ( $settings instanceof \Snap\Node\Actionable\Templatable ){
				$settings = array(
					'template' => $settings->getPath(),
					'templateWrapper' => $settings->getWrapper(),
					'templateData' => $data
				);
			}else{
				$settings = array(
					'template' => '',
					'templateWrapper' => '',
					'templateData' => array(),
					'base' => $settings
				);
			}
		}
		
		$settings['id'] = $id;
		
		parent::__construct( $settings );
	}
	
	protected function makeTemplateContent(){
		return $this->templateData;
	}
	
	protected function processTemplate(){
		if ( !$this->base ){
			parent::processTemplate();
		}
	}
	
	protected function parseSettings( $settings = array() ){
		if ( isset($settings['base']) ){
			$this->base = $settings['base'];
			$this->templateData = array();
		}else{
			if ( !isset($settings['template']) ){
				throw new \Exception('Need template for '.get_class($this) );
			}
			
			if ( isset($settings['templateData']) ){
				$this->templateData = $settings['templateData'];
			}else{
				throw new \Exception('Need content for '.get_class($this) );
			}
			
			$this->templateWrapper = $settings['templateWrapper'];
		}
		
		parent::parseSettings( $settings ); 
	}
	
	public function html(){
		if ( $this->base ){
			$inner = htmlentities( $this->base->html() );
		}else{
			$inner = htmlentities( '<'.$this->templateWrapper.'>'.$this->inner().'</'.$this->templateWrapper.'>' );
		}
		
		return "<script type='text/xml' id='{$this->id}'>\n<![CDATA[\n{$inner}\n]]>\n</script>";
	}
}