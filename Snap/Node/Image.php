<?php

namespace Snap\Node;

class Image extends \Snap\Node\Simple{
	protected 
		$src,
		$alt;
	
	public function __construct( $settings = array() ) {
		$settings['tag'] = 'img';
		
		parent::__construct( $settings );
		
		$this->src = isset($settings['src']) ? $settings['src'] : '';
		$this->alt = isset($settings['alt']) ? $settings['alt'] : '';
	}
	
	public static function getSettings(){
		parent::getSettings() + array(
			'src' => 'the url to the image',
			'alt' => 'the alternate text'
		);
	}
	
	protected function getAttributes(){
		return parent::getAttributes()
			.($this->src?" src=\"{$this->src}\"":'')
			.($this->alt?" alt=\"{$this->alt}\"":'');
	}
}