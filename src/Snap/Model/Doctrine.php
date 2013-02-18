<?php

namespace Snap\Model;

use
	\Doctrine\ORM\Tools\Setup,
	\Doctrine\ORM\EntityManager;

abstract class Doctrine extends \Snap\Lib\Core\StdObject {
	
	static protected
		$entityManager = null;
		
	protected
		/**
		 * @Id @GeneratedValue @Column(type="integer")
		 * @var int
		 **/
		$id;
	
	public function __construct( $doctrineInfo = null ){
		static::init();
		
		if ( !is_null($doctrineInfo) ){
			$this->copy( static::$entityManager->find(
				get_class($this),
				$this->decodeFind( $doctrineInfo )
			) );
		}
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function setId( $id ){
		$this->id = $id;
	}
	
	public function valid(){
		return $this->getId() == null;
	}
	
	public function flush(){
		static::$entityManager->flush();
	}
	
	public function persist(){
		static::$entityManager->persist( $this );
	}
	
	protected function decodeFind( $doctrineInfo ){
		return $doctrineInfo;
	}
	
	abstract protected function copy( Doctrine $in );
	
	static public function getEntityManager(){
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