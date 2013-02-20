<?php

namespace Snap\Prototype\Comment\Model\Doctrine;

/**
 * @Entity @Table(name="comment_threads")
 **/
class Thread extends \Snap\Model\Doctrine {
	
	protected 
	/**
	 * @Column(type="string", nullable=true)
	 **/
		$name,
	/**
	 * @Column(type="datetime")
	 **/
		$creationDate,
	/**
	 * @Column(type="boolean")
	 **/
		$active = false,
	/**
     * @ManyToOne(targetEntity="\Snap\Prototype\User\Model\Doctrine\User")
     **/
    	$user,
	/**
     * @OneToMany(targetEntity="\Snap\Prototype\Comment\Model\Doctrine\Comment", mappedBy="thread")
     * @var \Snap\Prototype\Comment\Model\Doctrine\Comment[]
     **/
		$comments;
	
	public function setName( $name ){
		$this->name = $name;
	}
	
	public function hasName(){
		return !is_null($this->name);
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function deactivate(){
		$this->active = false;
	}
	
	public function activate(){
		$this->active = true;
	}
	
	public function addComment( \Snap\Prototype\Comment\Model\Doctrine\Comment $comment ){
		$comment->addThread( $this );
		$this->comments[] = $comment;
	}
	
	public function setUser( \Snap\Prototype\User\Model\Doctrine\User $user ){
		$this->user = $user;
	}
	
	public function getUser(){
		return $this->user;
	}
	
	public function persist(){
		if ( $this->id == null ){
			$this->activate();
			$this->creationDate = new \DateTime();
		}
		
		parent::persist();
	}
}
