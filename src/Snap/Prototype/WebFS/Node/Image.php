<?php

namespace Snap\Prototype\WebFS\Node;

class Image extends \Snap\Node\Core\Simple {
	protected
		$src,
		$alt;
	
	public function __construct( $settings = array() ) {
		$settings['tag'] = 'img';
		
		if ( isset($settings['file']) ){
			$file = new \Snap\Prototype\WebFS\Lib\File( $settings['file'] );
		}else{
			throw new \Exception('You are creating a '.get_class($this).' without a file');
		}
		
		$this->src = \Snap\Prototype\WebFS\Node\Page\Access::getFileLink($file);
		$this->alt = $file->info( WEBFS_NAME );
		
		parent::__construct( $settings );
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