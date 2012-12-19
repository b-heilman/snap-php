<?php

namespace Snap\Prototype\Indexing\Lib;

class Organizer {

	static public 
		$mapFrom = '',
		$mapTo = 'php',
		$currentNode;

	static protected 
		$inited = false,
		$defaultErrorMessage = 'I have no idea what you want...';

	static private 
		$trigger,
		$mapOrder = null,
		$urlOrder = null,
		$root;

	protected 
		$ran = false;

	static protected function init(){
		if ( !self::$inited ){
			self::$root = new Point('/');
			self::$trigger = new Organizer();
			self::$inited = true;
		}
	}

	/*-----------
	 * Set the order of the mapping
	 * ======
	 * array : ( 'page', 'some', 'var' ) means to search the mappings array in the order of the page/some/var
	 */
	public static function setMapOrder( $array ){
		self::$mapOrder = $array;
	}

	/*-----------
	 * Set the order of the url for mapping pages
	 * ======
	 * array : ( 'page', 'some', 'var' ) implies url will be /page/some/var
	 */
	public static function setURLMapOrder( $array ){
		self::$urlOrder = $array;
	}

	/*-----------
	 * Add a mapping, which allows for URLs to automatically map to pages
	 * ======
	 * mapping options:
	 * 	url   1 : '/x/y/z'
	 *  array 2 : ( 'page' => x, 'some' => y, 'var' => z )
	 *  	  3 : ( x => ( y => ( z => target ) ) )
	 * target options :
	 *   1 + 2  : target should be a string which is the path to the file to include or a pagelet object
	 *   3      : url order mapping
	 * settings options :
	 *   error  : if a URL is requested beyond this point, the error page is targeted first
	 *   title  : the standard title for the link
	 *   stitle : an optional short title for the link
	 *   target :
	 *   settings : the data to pass in to the node when it's created
	 */
	public static function addMapping( $mapping, $target = array(), $settings = array() ){
		self::init();

		if ( is_string($mapping) ){
			$mapping = trim($mapping);
			if ( $mapping != '' ){
				$res = explode('/', ltrim($mapping, '/'));

				if ( is_array($target) ){
					$settings = $target;
					$target = $target['target'];
				}

				self::addArray( $res, $target, $settings );
			}
		}elseif ( is_array($target) ){
			self::$root->add( $mapping );

			if ( empty(self::$urlOrder) )
				self::setURLMapOrder( $target );

			if ( empty(self::$mapOrder) )
				self::setMapOrder( $target );
		}else{
			self::addArray( array_values($mapping), $target, $settings );

			$keys = array_keys($mapping);
			if ( empty(self::$urlOrder) )
				self::setURLMapOrder( $keys );

			if ( empty(self::$mapOrder) )
				self::setMapOrder( $keys );
		}
	}

	protected static function addArray($path, $target, $settings){
		$base = array('what');
		$map = &$base;

		// This is just stupid.  There has to be a better way of doing this...
		// I don't know what the why the hell PHP is making me do it this way...
		while( count($path) > 0 ){
			$key = array_shift( $path );
			$map[$key] = array();
			$t = &$map[$key];
			unset($map);
			$map = &$t;
			unset($t);
		}

		$map['_target'] = $target;

		foreach( $settings as $key => $val ){
			$map['_'.$key] = $val;
		}

		self::$root->add( $base );
	}

	/*--------------
	 * I want to build a list like this :
	 * **Assume all nodes have a title and target, except for e**
	 * mappings (
	 * 	a => ( b => ( c, d), e => (f, g) ), h, i
	 * )
	 *
	 * with order (
	 *  a => ( b => Admin, e => Management ),
	 *  h => Site
	 * )
	 *
	 * becomes (
	 * Admin => ( title=> a/b, title => a/b/c, title => a/b/d ),
	 * Management => ( title => a/e/f , title => a/e/g ),
	 * Site => ( title => h )
	 * )
	 *==============
	 */
	public static function getLinks( Point $node = null, $order = array(), $deep = 1 ){
		if ( $node == null ) {
			$node = self::$root;
		}

		return self::_getLinks($node, $order, $deep);
	}

	private static function _getLinks( Point $node, $order, $deep = 1 ){
		$links = array();

		if ( !empty($order) ){
			foreach( $order as $path => $group ){
				if ( $node->hasNext($path) ){
					$next = $node->getNext($path);

					if ( $deep != 0 ){
						if ( is_array($group) ){
							$children = self::_getLinks($next, $group, --$deep);
						}else{
							$children = array( $group => self::_getLinks($next, array(), --$deep) );
						}

						foreach( $children as &$grouping ){
							foreach( $grouping as $title => $child ){
								$grouping[$title]->addPathDir($path);
							}

							if ( $next->isLinkable() ){
								$grouping[$next->getTitle()] = new Info($path, $next);
							}
						}

						$links = array_merge( $links, $children );
					}else{
						$links[$next->getTitle()] = new Info($path, $next);
					}
				}
			}
		}else{
			$nodes = $node->getNext();

			foreach( $nodes as $path => $next ){
				if ( $deep != 0 ){
					$children = self::_getLinks($next, array(), --$deep);

					foreach( $children as $title => $child ){
						$children[$title]->addPathDir($path);
					}

					$links = array_merge( $links, $children );
				}

				if ( $next->isLinkable() ){
					$links[$next->getTitle()] = new Info($path, $next);
				}
			}
		}

		return $links;
	}

	private function __construct(){
		// just here to make this private
	}

	public function __destruct(){
		if ( !$this->ran )
			$this->run();
	}

	static public function run(){
		self::$trigger->_run();
	}

	protected function _run(){
		try{
			$this->ran = true;
			//--- parse apart the url
			if ( isset($_SERVER['REDIRECT_URL']) ){
				// REDIRECT_URL - PHP_SELF
				// http://localhost/test/ym/something/or/other?woot=1
				// '/test/ym/something/or/other' - '/test/index.php'
				$path = $_SERVER['REDIRECT_URL'];
				$find = $_SERVER['PHP_SELF'];
				$pos = strrpos($find,'/');
	
				if ( $pos != '' ){
					$find = substr($find, 0, $pos);
	
					$pos = strpos( $path, $find );
					$path = substr( $path, $pos+strlen($find) );
				}
			}elseif( isset($_SERVER['PATH_INFO']) ){
				// PATH_INFO
				// http://localhost/test/index.php/ym/something/or/other?woot=1
				// '/ym/something/or/other'
				$path = $_SERVER['PATH_INFO'];
			}else{
				$path = '';
			}
	
			$path = explode('/',ltrim($path,'/'));
	
			if ( self::$urlOrder != null ){
				$path_info = array();
	
				foreach( self::$urlOrder as $key ){
					$path_info[$key] = ( count($path) > 0 )?array_shift($path):null;
				}
			}else{
				$path_info = $path;
			}
	
			//--- now lets map the url to a node
			$node = self::$root;
			$error = false;
	
			if ( self::$mapOrder != null ){
				foreach( self::$mapOrder as $key ){
					if ( $key != '' ){
						if ( isset($path_info[$key]) && $node->hasNext($path_info[$key]) ){
							$node = $node->getNext($path_info[$key]);
	
							unset( $path_info[$key] );
							array_shift( $path );
						}else{
							$error = true;
							break;
						}
					}else{
						array_shift( $path );
					}
				}
			}else{
				while( count($path_info) > 0 ){
					$key = array_shift( $path_info );
					if ( $key != '' ){
						if ( $node->hasNext($key) ){
							$node = $node->getNext($key);
	
							array_shift( $path );
						}else{
							array_unshift( $path_info, $key );
							$error = true;
							break;
						}
					}else{
						array_shift( $path );
					}
				}
			}
	
			//--- check the node and figure out the page
			$page = null;
	
			if ( $error ){
				$node->markError();
			}
	
			$this->output(
				$this->runNode( $node, $this->remapping($node, $path) )
			);
		}catch( Exception $ex ){
			$this->output(
				'<pre clas="indexing-organizer">'
				. $ex->getMessage()
				. "\n---------\n"
				. $ex->getFile().': '.$ex->getLine()
				. "\n=========\n"
				. $ex->getTraceAsString()
				.'</pre>'
			);
		}
	}

	/*-----------
	 * Used to remap the reminants of the URL to variables if remapping has been turned on.
	 */
	protected function remapping( Point $node, $path ){
		$target = $node->getTarget();
		
		if( ($setting = $node->getSetting('remapping')) != false ){
			$remapping = $setting;
		}else $remapping = array();

		$vars = array();

		foreach( $remapping as $key ){
			$vars[$key] = ( count($path) > 0 )?array_shift($path):null;
		}

		return $vars;
	}

	/*---------------
	 * This calls the actual node, passing in the data
	 */
	private function runNode( Point $node, $data ){
		self::$currentNode = $node;

		$node->loadInclude();
		$target = $node->getTarget();

		if ( $target === false ){
			$target = self::$defaultErrorMessage;
		}elseif ( $target == null ){
			$target = '';
		}

		if ( $target instanceof \Snap\Node\Page ){
			$target->setPageData( $node->getSetting() + $data );
		}

		return $this->express($target,$node);
	}

	protected function output( $html ){
		\Snap\Lib\Core\Session::save();
		
		echo $html;
	}

	protected function express( $ele, Point $node ){
		if ( is_object($ele) ){
			if ( !($ele instanceof \Snap\Node\Page) ){
				$t = new \Snap\Node\Page\Basic();

				$t->append( $ele );

				$ele = $t;
			}
			
			$ele->setTitle( $node->getSetting('title') );

			$res = $ele->toString();
		}else{
			$res = $ele;
		}

		return $res;
	}
}