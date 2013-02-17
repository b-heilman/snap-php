<?php
// Product.php

namespace Demo\Prototype\Bugger\Model\Doctrine\Junk;

/**
 * @Entity @Table(name="coded_products")
 **/
class CodedProduct extends \Demo\Prototype\Bugger\Model\Doctrine\Product {
	
    /** @Column(type="integer") **/
    protected $code;

    public function getCode() {
        return $this->code;
    }

    public function setCode($code) {
        $this->code = $code;
    }
}