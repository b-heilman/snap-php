<?php

namespace Snap\Prototype\WebFS\Lib;

class File extends \Snap\Lib\Db\Element 
	implements \Snap\Lib\Db\Feed {

	static protected 
		$id_field = WEBFS_ID,
		$name_field = WEBFS_NAME,
		$table = WEBFS_TABLE,
		$db = null;
	
	static public function fromUrlId( $id ){
		return new \Snap\Prototype\WebFS\Lib\File( strpos("/:@&%=?.#", $id) === False
			? \Snap\Lib\Core\Krypter::cookieDecrypt($id) 
			: \Snap\Lib\Core\Krypter::urlDecrypt($id)
		);
	}

	public static function getAdapter(){
		self::loadDB();
		
		return self::$db;
	}
	
	public function getContentQuery(){
		$this->load();
		
		return new \Snap\Lib\Db\Query( array(
			'from' => new \Snap\Lib\Db\Query\From( array(WEBFS_TABLE) )
		) );
	}
	
	public function getPrimaryField(){
		return WEBFS_ID;
	}
	
	// TODO : this is hard coded for Mysql?
	static protected function loadDB(){
		if ( self::$db == null ){
			self::$db = new \Snap\Adapter\Db\Mysql( WEBFS_DB );
		}
	}
	
	static public function registerFile($fileInfo, $name, $options = array() ){
		$access = 0;
		$controlled = false;
		
		$file = self::moveFile( $fileInfo );
	
		if ( $file ){
			/*
			// 1 2 4
			if( isset($options['user']) ){
				$access += $options['user'] & 7;
				unset( $options['user'] );
				
				$controlled = true;
			}
			
			// 8 16 32
			if( isset($options['group']) ){
				$access += ( $options['group'] << 3 ) & 56;
				unset( $options['group'] );
				
				$controlled = true;
			}
			
			// 64 128 256 = 
			if( isset($options['other']) ){
				$access += ( $options['other'] << 6 ) & 448;
				unset( $options['other'] );
				
				$controlled = true;
			}
			
			if ( !$controlled ){
				$access = 511;
			}
			*/
			$fileName = $fileInfo["name"];
			$pos = strrpos( $fileName, '.' );
			$fileExt = substr( $fileName, $pos + 1 );
			$fileName = substr( $fileName, 0, $pos );
			
			$options += array(
				'path'              => $file,
				'original_name'     => $fileName,
				'extension'         => $fileExt,
				// WEB_FILE_PERMISSION => $access,
				WEBFS_NAME       => $name
			);
			
			$id = self::create( $options );
			if ( $id ){
				$options[WEBFS_ID] = $id;
				
				return $options;
			}else{
				return false;
			}
		}else return false;
	}
	
	static protected function moveFile( $fileInfo ){
		$dir = date('Ymd');
		
		if ( !file_exists(WEB_FILE_ROOT.'/'.$dir) ){
			mkdir( WEB_FILE_ROOT.'/'.$dir, 0777, true );
		}
		
		$file = $dir.'/'.rand(9999999, 9999999999).'.dat';
		$fileName =  WEB_FILE_ROOT.'/'.$file;
		
		if ( move_uploaded_file($fileInfo["tmp_name"], $fileName) ){
			if ( !file_exists($fileName) ){
				return null;
			}else{
				return $file;
			}
			
		} else return null;
	}
	
	public function getUrlId(){
		return \Snap\Lib\Core\Krypter::urlEncrypt( $this->id() );
	}
	
	public function exists(){
		return $this->isValid() && file_exists( $this->getRealPath() );
	}
	
	public function getSize(){
		return filesize( $this->getRealPath() );
	}
	
	public function getContents(){
		return file_get_contents( $this->getRealPath() );
	}
	
	public function getOrigName(){
		$this->load();
		
		return $this->info['original_name'].'.'.$this->info['extension'];
	}
	
	protected function getRealPath(){
		$this->load();
		
		return WEB_FILE_ROOT.'/'.$this->info['path'];
	}
	/*
	public function getPermissions(){
		$this->load();
		
		$perm = $this->info[WEB_FILE_PERMISSION];
		
		return array(
			'user' => ( $perm & 7 ),
			'group' => ( ($perm & 56) >> 3 ),
			'other' => ( ($perm & 448) >> 6 )
		);
	}
	*/
}