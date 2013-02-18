<?php
// Product.php

namespace Demo\Prototype\Bugger\Model\Doctrine;

/**
 * @Entity @Table(name="products")
 **/
class Product extends \Snap\Model\Doctrine {
	
    /** @Column(type="string") **/
    protected $name;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
    
    protected function copy( \Snap\Model\Doctrine $in ){
    	$this->id = $in->id;
    	$this->name = $in->name;
    }
}