<?php

namespace Snap\Prototype\WebFs\Node\View;

class File extends \Snap\Node\Core\View {
	
	protected function baseClass(){
		return 'file-view';
	}
	
	protected function getTemplateVariables(){
		$file = new \Snap\Prototype\WebFs\Lib\File( $this->getStreamData()->get(0) );
		
		return array(
			'file' => $file,
			'path' => \Snap\Prototype\WebFs\Node\Page\Access::getFileLink( $file ),
			'name' => $file->info( WEBFS_NAME )
		);
	}
}