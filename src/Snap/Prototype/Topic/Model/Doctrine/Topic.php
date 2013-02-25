<?php

namespace Snap\Prototype\Topic\Model\Doctrine;

/**
 * @Entity @Table(name="topics")
 **/
class Topic extends \Snap\Model\Doctrine {

	protected
	/**
	 * @Column(type="string")
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
	 * @ManyToOne(targetEntity="\Snap\Prototype\Topic\Model\Doctrine\Type")
	**/
		$type,
	/**
     * @OneToOne(targetEntity="\Snap\Prototype\Comment\Model\Doctrine\Thread")
     */
		$thread;

	public function setName( $name ){
		$this->name = $name;
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

	public function setThread( \Snap\Prototype\Comment\Model\Doctrine\Thread $thread ){
		$this->thread = $thread;
	}

	public function getThread(){
		return $this->thread;
	}
	
	public function setType( \Snap\Prototype\Topic\Model\Doctrine\Type $type ){
		$this->type = $type;
	}
	
	public function persist(){
		if ( $this->id == null ){
			$this->activate();
			$this->creationDate = new \DateTime();
		}

		parent::persist();
	}
}
