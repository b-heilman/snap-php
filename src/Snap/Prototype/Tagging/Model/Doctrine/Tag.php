<?php

namespace Snap\Prototype\Tagging\Model\Doctrine;

/**
 * @Entity @Table(name="tags")
 * @InheritanceType("TABLE_PER_CLASS")
 **/
class Tag extends \Snap\Model\Doctrine {

	protected
	/**
	 * @Column(type="string")
	 **/
		$name,
	/**
   * @ManyToMany(targetEntity="Snap\Prototype\Topic\Model\Doctrine\Topic", inversedBy="tags")
   * @JoinTable(name="topic_tags")
	 * @var \Snap\Prototype\Tagging\Lib\Taggable[]
	 */
		$targets;

	public function __construct(){
		$this->targets = new \Doctrine\Common\Collections\ArrayCollection();
		
		parent::__construct();
	}
	
	public function setName( $name ){
		$this->name = $name;
	}

	public function getName(){
		return $this->name;
	}

	// TODO : set taggable interface?
	public function addTarget( \Snap\Prototype\Tagging\Lib\Taggable $target ){
		$this->targets[] = $target;
		$target->addTag( $this );
	}

	public function getTargets(){
		return $this->targets;
	}
}