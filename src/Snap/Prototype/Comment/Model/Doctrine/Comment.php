<?php

namespace Snap\Prototype\Comment\Model\Doctrine;

/**
 * @Entity @Table(name="comments")
 **/
class Comment extends \Snap\Model\Doctrine {
	
	protected 
	/**
	 * @Column(type="string")
	 **/
		$content,
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
     * @ManyToOne(targetEntity="\Snap\Prototype\Comment\Model\Doctrine\Thread")
     **/
    	$thread,
    /**
     * @ManyToOne(targetEntity="\Snap\Prototype\Comment\Model\Doctrine\Comment")
     **/
    	$parent;
	
	public function setContent( $content ){
		$this->content = $this->sanitizeComment( $content );
	}
	
	protected function sanitizeComment( $comment ){
		return str_replace("\n", '<br>', htmlentities($comment) );
	}
	
	public function getContent(){
		return $this->content;
	}
	
	public function deactivate(){
		$this->active = false;
	}
	
	public function activate(){
		$this->active = true;
	}
	
	public function setUser( \Snap\Prototype\User\Model\Doctrine\User $user ){
		$this->user = $user;
	}
	
	public function getUser(){
		return $this->user;
	}
	
	public function setThread( \Snap\Prototype\Comment\Model\Doctrine\Thread $thread ){
		$this->thread = $thread;
	}
	
	public function getThread(){
		return $this->thread;
	}
	
	public function setParent( \Snap\Prototype\Comment\Model\Doctrine\Comment $comment ){
		$this->parent = $comment;
	}
	
	public function getParent(){
		return $this->parent;
	}
	
	/**
	 * @return \DateTime
	 */
	public function getCreationDate(){
		return $this->creationDate;
	}
	
	public function persist(){
		if ( $this->id == null ){
			$this->activate();
			$this->creationDate = new \DateTime();
		}
		
		parent::persist();
	}
}