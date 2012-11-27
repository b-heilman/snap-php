<?php

namespace Snap\Prototype\WebFS\Node;

class Image extends \Snap\Node\Image {
	public function __construct( $settings = array() ) {
		if ( isset($settings['file']) ){
			$file = new \Snap\Prototype\WebFS\Lib\File( $settings['file'] );
		}else{
			throw new \Exception('You are creating a '.get_class($this).' without a file');
		}
		
		$settings['src'] = \Snap\Prototype\WebFS\Node\Page\Access::getFileLink($file);
		$settings['alt'] = $file->info( WEBFS_NAME );
		
		parent::__construct( $settings );
	}
}