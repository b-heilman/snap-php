<?php

namespace Snap\Prototype\Blogging\Model\Doctrine;

/**
 * @Entity @Table(name="blogs")
 **/
class Blog extends \Snap\Prototype\Topic\Model\Doctrine\Topic {
	
	protected
	/**
	 * @Column(type="string")
	**/
		$content;
	
	public function getContent(){
		return $this->content;
	}
	
	public function setContent( $content ){
		$this->content = $content;
	}
}