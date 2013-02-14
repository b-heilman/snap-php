<?php
// TODO : is this used?

namespace Snap\Lib\Linking\Control;

use \Snap\Node;

//TODO : this should be removed?
class Basic extends \Snap\Control\Feed\Nav 
	implements \Snap\Lib\Linking\Control {

	protected 
		$currNode, 
		$nextNode, 
		$prevNode;
	
	public function build(){
		$this->append( $this->prevNode = new Node\Listed(array(
			'tag'   => 'ol',
			'class' => 'prev-links linking_linkanator-nav'
		)) );
		$this->append( $this->currNode = new Node\Core\Block(array(
			'tag'   => 'span', 
			'class' => 'current-link linking_linkanator-nav'
		)) );
		$this->append( $this->nextNode = new Node\Listed(array(
			'tag'   => 'ol', 
			'class' => 'next-links linking_linkanator-nav'
		)) );
			
		parent::build();
	}
	
	public function setPrevData( array $data){
		if ( isset($this->prevNode) ){
			$c = count($data);
			
			for( $i = $c - 1; $i >= 0; $i-- ){
				$this->appendPrevious( $data[$i] );
			}
		}
	}
	
	public function setNextData( array $data ){
		if ( isset($this->nextNode) ){
			$c = count( $data );
		
			for( $i = 0; $i < $c; $i++ ){
				$this->appendNext( $data[$i] );
			}
		}
	}
	
	public function setCurrData( array $data ){
		if ( isset($this->currNode) ){
			$this->appendCurrent( $data );
		}
	}
	
	protected function appendPrevious( $data ){
		$this->prevNode->append( $this->modifyLink($this->buildNavLink($data), $data) );
	}
	
	protected function appendNext( $data ){
		$this->nextNode->append( $this->modifyLink($this->buildNavLink($data), $data) );
	}
	
	protected function appendCurrent( $data ){
		$this->currNode->append( $this->modifyTitle($this->buildNavTitle($data), $data) );
	}
	
	public function buildNavLink( $data ){
		$link = $this->createLink( $data[linking_linkanator_INDEX] );
		$link->append( new Node\Core\Text(array(
			'tag'   => 'span', 
			'text'  => isset($data[linking_linkanator_TIME])?$data[linking_linkanator_TIME]:'', 
			'class' => 'link-time'
		)) );
		$link->append( new Node\Core\Text(array(
			'tag'   => 'span', 
			'text'  => $data[linking_linkanator_SHORT_TITLE], 
			'class' => 'link-title'
		)) );
		
		return $link;
	}
	
	public function buildNavTitle( $data ){
		$title = new block_node(array(
			'tag'   => 'span', 
			'class' => 'linking_linkanator-nav-title'
		));
		
			$title->append( new Node\Core\Text(array(
				'tag'   => 'span', 
				'text'  => isset($data[linking_linkanator_TIME])?$data[linking_linkanator_TIME]:'', 
				'class' => 'link-time'
			)) );
			
			$title->append( new Node\Core\Text(array(
				'tag'   => 'span', 
				'text'  => $data[linking_linkanator_SHORT_TITLE], 
				'class' => 'link-title'
			)) );
		
		return $title;
	}
	
	protected function modifyLink( Node\View\Nav $link, array $data ){
		$link->setTitle( $data[linking_linkanator_LONG_TITLE] );
		
		return $link;
	}
	
	protected function modifyTitle( Node\Core\Snapable $title, array $data ){
		$title->addClass('showing');
		
		return $title;
	}
}