<?php

namespace Snap\Prototype\WebFS\Node\Page;

class Access extends \Snap\Node\Page\Basic {

	public function html(){
		$file = \Snap\Prototype\WebFS\Lib\File::fromUrlId( $_GET['file'] );
		
		if ( $file->exists() ) {
    		header('Content-Description: File Transfer');
    		header('Content-Type: application/octet-stream');
   			header('Content-Disposition: attachment; filename='.$file->getOrigName());
    		header('Content-Transfer-Encoding: binary');
    		header('Expires: 0');
    		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    		header('Pragma: public');
    		header('Content-Length: ' . $file->getSize());
    		
    		return $file->getContents();
		}else{
			if ( $file->isValid() ){
				$this->write('File does not exist');
				
			}else{
				$this->write("File info isn't valid");
			}
			
			return parent::html();
		}
	}
	
	protected function getTranslator(){
		return null;
	}
	
	protected function defaultTitle(){
		return 'File Access Failed';
	}
	
	protected function getMeta(){
		return '';
	}
	
	static public function getFileLink( \Snap\Prototype\WebFS\Lib\File $file ){
		return WEBFS_ACCESS.'?file='.$file->getUrlId();
	}
}