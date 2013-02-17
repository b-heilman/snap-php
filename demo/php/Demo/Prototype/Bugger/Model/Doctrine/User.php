<?php

namespace Demo\Prototype\Bugger\Model\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="d_users")
 **/
class User
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var int
     **/
    protected $id;

    /**
     * @Column(type="string")
     * @var string
     **/
    protected $name;

    /**
     * @OneToMany(targetEntity="Bug", mappedBy="reporter")
     * @var Bug[]
     **/
    protected $reportedBugs = null;

    /**
     * @OneToMany(targetEntity="Bug", mappedBy="engineer")
     * @var Bug[]
     **/
    protected $assignedBugs = null;
    
    public function __construct()
    {
    	$this->reportedBugs = new ArrayCollection();
    	$this->assignedBugs = new ArrayCollection();
    }
    
    public function addReportedBug($bug)
    {
    	$this->reportedBugs[] = $bug;
    }
    
    public function assignedToBug($bug)
    {
    	$this->assignedBugs[] = $bug;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}