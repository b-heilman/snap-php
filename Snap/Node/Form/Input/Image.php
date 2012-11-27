<?php

namespace Snap\Node\Form\Input;

class Image extends \Snap\Node\Form\Input\Basic {

	protected 
		$src, 
		$alt, 
		$title;
	
	public function __construct( $settings = array() ){
		$this->src = isset( $settings['src'] ) ? $settings['src'] : '' ;
		$this->alt = isset( $settings['alt'] ) ? $settings['alt'] : '' ;
		$this->title = isset( $settings['title'] ) ? $settings['title'] : '' ;
		
		$settings['type'] = 'image';
			
		parent::__construct( $settings );
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'src'   => 'the image path',
			'alt'   => 'the alternate text',
			'title' => 'the tooltip for the image'
		);
	}
	
	protected function getAttributes(){
		return parent::getAttributes() 
			. " src=\"{$this->src}\" alt=\"{$this->alt}\" title=\"{$this->title}\"";
	}
}