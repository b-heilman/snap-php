<?php

namespace Snap\Prototype\Topic\Model\Doctrine;

use
	\Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="topic_types")
 **/
class Type extends \Snap\Model\Doctrine {

	protected
	/**
	 * @Column(type="string")
	**/
	$name,
	/**
	 * @Column(type="boolean")
	**/
	$active = false,
	/**
     * @OneToMany(targetEntity="\Snap\Prototype\Topic\Model\Doctrine\Topic", mappedBy="type")
     */
	$topics;

	public function __construct(){
		parent::__construct();
		
		$this->topics = new ArrayCollection();
	}
	
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

	public function persist(){
		if ( $this->id == null ){
			$this->activate();
			$this->creationDate = new \DateTime();
		}

		parent::persist();
	}
}
