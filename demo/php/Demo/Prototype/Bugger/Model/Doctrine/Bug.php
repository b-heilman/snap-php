<?php

namespace Demo\Prototype\Bugger\Model\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="bugs")
 **/
class Bug extends \Snap\Model\Doctrine {
    /**
     * @Column(type="string")
     **/
    protected $description;
    /**
     * @Column(type="datetime")
     **/
    protected $created;
    /**
     * @Column(type="string")
     **/
    protected $status;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="assignedBugs")
     **/
    protected $engineer;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="reportedBugs")
     **/
    protected $reporter;

    /**
     * @ManyToMany(targetEntity="Product")
     **/
    protected $products;
	
	public function __construct( $doctrineInfo = null ){
		parent::__construct( $doctrineInfo );
		
		$this->products = new ArrayCollection();
	}
	
	public function setCreated( $time ){
		$this->created = $time;
	}
	
	public function setStatus( $status ){
		$this->status = $status;
	}
	
	public function setDescription( $description ){
		$this->description = $description;
	}
	
	public function getDescription(){
		return $this->description;
	}
	
	public function setEngineer($engineer)
	{
		$engineer->assignedToBug($this);
		$this->engineer = $engineer;
	}
	
	public function setReporter($reporter)
	{
		$reporter->addReportedBug($this);
		$this->reporter = $reporter;
	}
	
	public function getEngineer()
	{
		return $this->engineer;
	}
	
	public function getReporter()
	{
		return $this->reporter;
	}
	
	public function assignToProduct($product)
	{
		$this->products[] = $product;
	}
	
	public function getProducts()
	{
		return $this->products;
	}
	
	protected function copy( \Snap\Model\Doctrine $in ){
		$this->id = $in->id;
		$this->name = $in->name;
		$this->engineer = $in->engineer;
		$this->reporter = $in->reporter;
		$this->products = &$this->products;
	}
}