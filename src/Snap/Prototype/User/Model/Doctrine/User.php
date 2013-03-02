<?php

namespace Snap\Prototype\User\Model\Doctrine;

use
	\Snap\Prototype\User\Lib\Auth;

/**
 * @Entity @Table(name="users")
 * @InheritanceType("TABLE_PER_CLASS")
 **/
class User extends \Snap\Model\Doctrine {
	
	protected
	/**
	 * @Column(type="string")
	 **/
		$login,
	/**
	 * @Column(type="string")
	 **/
		$display,
	/**
	 * @Column(type="string")
	 **/
		$password = '***',
	/**
	 * @Column(type="string")
	 **/
		$status,
	/**
	 * @Column(type="datetime")
	 **/
		$statusDate,
	/**
	 * @Column(type="datetime")
	 */
		$creationDate,
	/**
	 * @Column(type="boolean")
	 */
		$isAdmin = false;
	
	public function setDisplay( $display ){
		$this->display = $display;
	}
	
	public function getDisplay(){
		return $this->display;
	}
	
	public function setLogin( $login ){
		$this->login = $login;
	}
	
	public function getLogin(){
		return $this->login;
	}
	
	public function setPassword( $password, Auth $auth ){
		$this->password = $auth->encodePassword( $password );
	}
	
	public function getPassword(){
		return $this->password;
	}
	
	public function setAdmin( $makeAdmin ){
		$this->isAdmin = $makeAdmin ? true : false;
	}
	
	public function isAdmin(){
		return $this->isAdmin;
	}
	
	public function getStatus(){
		return $this->status;
	}
	
	public function setStatus( $status ){
		$this->status = $status;
		$this->statusDate = new \DateTime();;
	}
	
	/**
	 * Hook to allow elements to register their on persistance, and allows this to run before persistance is called
	 * (non-PHPdoc)
	 * @see Snap\Model.Doctrine::persist()
	 */
	public function persist(){
		if ( $this->id == null ){
			$this->setStatus( 'CREATED' );
			$this->creationDate = new \DateTime();
		}
		
		parent::persist();
	}
}