<?php

namespace Snap\Lib\Core;

class Session {

	private static 
		$inited = false,
		$sess = null,
		$defaultTimeout = SESS_TIMEOUT,
		$timeouts = array();

	protected static 
		$db,
		$id = null;

	private static function getSessionDir(){
		return WEB_FILE_PATH.PATH_SEPARATOR.'sessions';
	}

	private static function getSessionPath(){
		return WEB_FILE_PATH.PATH_SEPARATOR.'sessions'.PATH_SEPARATOR.self::$id.'.sess';
	}

	private static function init(){
		if ( !self::$inited ){
			self::$inited = true;

			if ( self::$sess == null ){
				if ( SESS_MODE == 'db' ){
					$class = SITE_DB_ADAPTER;
					self::$db = $db = $this->db = new $class(SITE_DB);

					if ( !$db->tableExists(SESS_TABLE) ){
						\Snap\Lib\Db\Definition::addTable(SESS_TABLE, array( 'engine' => 'InnoDB',
																	'PRIMARY KEY ( sess_id )'));
						\Snap\Lib\Db\Definition::addTableField(SESS_TABLE, 'sess_id', 'int unsigned', false,
												array('AUTO_INCREMENT'));
						\Snap\Lib\Db\Definition::addTableField(SESS_TABLE, 'data', 'text', false);

						\Snap\Lib\Db\Definition::install( $db, true );
					}
				}

				$data = null;

				switch( SESS_MODE ){
					case 'cookie' :
							$data = self::$sess = array();
							foreach( $_COOKIE as $key => $value ){
								$data[$key] = unserialize( snap_krypter::cookieDecrypt($value) );
							}
						break;
					case 'db' :
					case 'file' :
							if( isset($_REQUEST['_sess']) ){
								self::$id = $id = snap_krypter::cookieDecrypt($_REQUEST['_sess']);

								if( SESS_MODE == 'db' ){
									$res = self::$db->select( SESS_TABLE, array('sess_id' => $id) );

									if ( $res ){
										$data = unserialize( snap_krypter::decrypt($res->nextVal('data')) );
									}
								}else{
									$path = self::getSessionPath();
									if ( file_exists ( $path ) ){
										$data = unserialize( snap_krypter::decrypt(file_get_contents($path)) );
									}
								}
							}else{

							}
						break;

					default : // php
							session_start();
							self::$id = session_id();
							$data = $_SESSION;
						break;
				}
				
				// now we run the security checks
				if ( isset($data['secure']) ){
					$failed = false;

					if ( SESS_SECURITY & 1 && $data['secure']['ip'] != $_SERVER['REMOTE_ADDR'] ){
						$failed = 1;
					}

					if ( SESS_SECURITY & 2 ){
						$lock = $data['secure']['lock'];
						foreach( $data as $key => $dat ){
							if ( $dat['lock'] != $lock ) {
								unset( $data[$key] );
							}
						}
					}
					// TODO : add some more

					if ( !$failed ){
						self::$sess = $data;
					}else{
						self::$sess = null;
					}
				}
			}

			// initialize your snap_session
			if ( self::$sess == null ){
				self::$sess = array();

				switch( SESS_MODE ){
					case 'db' :
						$res = self::$db->insert(SESS_TABLE, array('data' => ''));
						self::$id = self::$db->insertedID();

						break;
					case 'file' :
						$files = scandir( self::getSessionDir() );
						self::$id = time().'_'.count( $files );

						break;
					case 'cookie' :
					default : // php
						break;
				}
			}
		}
	}

	public static function save(){
		if ( self::$inited ){
			self::$sess['secure'] = array(
				'ip' 	=> $_SERVER['REMOTE_ADDR'],
				'last'	=> time()
			);

			if ( SESS_SECURITY & 2 ){
				// TODO : let someone set up their own lock algorithm
				$lock = time().'_'.self::$id;

				foreach( self::$sess as $key => $d ){
					self::$sess[$key]['lock'] = $lock;
				}
			}

			switch( SESS_MODE ){
				case 'db' :
					setCookie('_sess', snap_krypter::cookieEncrypt(serialize(self::$id)),
								self::$defaultTimeout, '/');

					return self::$db->update( SESS_TABLE, array('sess_id' => self::$id),
											array('data' => snap_krypter::encrypt(self::$sess)) );

				case 'file' :
					setCookie('_sess', snap_krypter::cookieEncrypt(serialize(self::$id)),
								self::$defaultTimeout, '/');

					$dir = self::getSessionDir();
					$file = self::getSessionPath();

					if ( !file_exists($dir) )
						mkdir( $dir );

					return file_put_contents( $file, snap_krypter::encrypt(self::$sess) );

				case 'cookie' :
					foreach( self::$sess as $key => $val ){
						$timeout = isset(self::$timeouts[$key])
							? self::$timeouts[$key] : self::$defaultTimeout;
						setCookie( $key, snap_krypter::cookieEncrypt(serialize($val)),$timeout, '/' );
					}

				default : // php
					foreach( self::$sess as $key => $val ){
						$_SESSION[$key] = $val;
					}
					
				break;
			}
		}
	}

	public static function linkWithSession( $link ){
		$pos = strpos($link, '_sess=');

		if ( $pos !== false ){
			$amp = strpos($link, '&', $pos + 7);

			$front = substr($link, 0, $pos);

			if ( $amp !== false ){
				$link = $front . substr($link, $amp + 1);
			}else{
				$link = $front;
			}
		}

		$last = $link{strlen($link) - 1};

		if ( $last != '&' && $last != '?' && $link != '' ){
			$link .= '&';
		}

		return $link .= '_sess='.snap_krypter::cookieEncrypt(self::$id);
	}

	public static function setDefaultTimeout( $timeout ){
		self::$defaultTimeout = $timeout;
	}

	protected $group;

	public function __construct( $group ){
		self::init();

		$this->group = $group;
	}

	public function setTimeout( $timeout ){
		self::$timeouts[$this->group] = $timeout;
	}

	public function clear(){
		$this->unsetVar();
	}
	
	/*------
	 * dex can be an array, otherwise it's the index of where the value get inserted
	 */
	public function setVar( $dex , $val ){
		if ( isset(self::$sess[$this->group]) ){
			self::$sess[$this->group][$dex] = $val;
		}else{
			self::$sess[$this->group] = array( $dex => $val );
		}
	}

	public function unsetVar( $dex = null ){
		if ( $dex == null ){
			self::$sess[$this->group] = array();
		}else{
			unset( self::$sess[$this->group][$dex] );
		}
	}

	public function getVar( $dex = null ){
		if ( isset(self::$sess[$this->group]) ){
			if ( is_null($dex) ){
				return self::$sess[$this->group];
			}elseif( isset(self::$sess[$this->group][$dex]) ){
				return self::$sess[$this->group][$dex];
			}else return null;
		}else return null;
	}
}