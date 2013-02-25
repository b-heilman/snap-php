<?php

namespace Snap\Model;

use Doctrine\Common\Annotations\DocLexer;

use
	\Doctrine\ORM\Tools\Setup,
	\Doctrine\ORM\EntityManager;

abstract class Doctrine extends \Snap\Lib\Core\StdObject {
	
	static protected
	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
		$entityManager = null;
		
	protected
	/**
	 * @Id @GeneratedValue @Column(type="integer")
	 **/
		$id = null;
	
	public function __construct(){
		static::init();
	}
	
	public function duplicate( Doctrine $in ){
		$class = get_class($in);
		$ex = new \Exception();
		error_log( $ex->getTraceAsString() );
		if ( $this instanceof $class ){
			foreach( get_object_vars($in) as $var => $val ){
				$this->{$var} = $val;
			}
			
			error_log( 'merging...' );
			self::$entityManager->merge( $this );
		}else throw new \Exception('Can not duplicate a class you are not an instance of');
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function setId( $id ){
		$this->id = $id;
	}
	
	public function initialized(){
		return !is_null($this->id);
	}
	
	public function flush(){
		static::$entityManager->flush();
	}
	
	public function persist(){
		static::$entityManager->persist( $this );
	}
	
	static public function all(){
		static::init();
		
		$class = get_called_class();
		
		return static::$entityManager->getRepository( $class )->findAll();
	}
	/**
	 * 
	 * @return \Snap\Model\Doctrine
	 */
	static public function find( $doctrineInfo ){
		static::init();
		
		$class = get_called_class();
		
		if ( is_array($doctrineInfo) ){
			return static::$entityManager->getRepository( $class )->findOneBy( $doctrineInfo );
		}else{
			return static::$entityManager->find( $class,	$doctrineInfo );
		}
	}
	
	/**
	 *
	 * @return \Snap\Model\Doctrine[]
	 */
	static public function findMany( $doctrineInfo ){
		static::init();
		
		$class = get_called_class();
	
		if ( is_array($doctrineInfo) ){
			return static::$entityManager->getRepository( $class )->findBy( $doctrineInfo );
		}else{
			$res = static::$entityManager->find( $class, $doctrineInfo );
			if ( is_array($res) ){
				return $res;
			}else{
				return array( $res );
			}
		}
	}
	
	/**
	 * @return \Doctrine\ORM\EntityManager
	 */
	static public function getEntityManager(){
		static::init();
		
		return static::$entityManager;
	}
	
	static protected function init(){
		parent::init();
		
		if ( is_null(static::$entityManager) ){
			$isDevMode = true;
			
			$settings = array(
				'driver' => DB_DRIVER,
				'dbname' => DB_NAME
			);
			
			if ( defined('DB_USER') ){
				$settings['user'] = DB_USER;
			}
			
			if ( defined('DB_PASSWORD') ){
				$settings['password'] = DB_PASSWORD;
			}
			
			if ( defined('DB_HOST') ){
				$settings['host'] = DB_HOST;
			}
			
			if ( defined('DB_PORT') ){
				$settings['port'] = DB_PORT;
			}
			
			if ( defined('DB_CHARSET') ){
				$settings['charset'] = DB_CHARSET;
			}
			
			static::$entityManager = EntityManager::create( 
				$settings, 
				Setup::createAnnotationMetadataConfiguration(array(), $isDevMode)
			);
		}
	}
}
