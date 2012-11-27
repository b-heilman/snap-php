<?php
/****************
this object is meant to handle creating links.
$link : the path to link to
$tooltip : what to show when moused over.  the title of the element
$javascript: what to run on the click event
$text: default text for the object.
$hidden: hide the default text with css
$class: add a class to the object
*/

namespace Snap\Node;

class Href extends \Snap\Node\Block {

	protected 
		$href,
		$title;
	
	public function __construct( $settings = array() ) {
		$settings['tag'] = 'a';
		
		parent::__construct( $settings );
		
		$this->href = isset($settings['href']) ? $settings['href'] : '';
		$this->title = isset($settings['title']) ? $settings['title'] : '';
	}
	
	public function setTitle( $title ){
		$this->title = $title;
	}
	
	public function setHref( $href ){
		$this->href = $href;
	}
	
	public static function getSettings(){
		parent::getSettings() + array(
			'href' => 'url to link to',
			'title' => 'the tooltip', 
		);
	}
	
	protected function getAttributes(){
		return parent::getAttributes()
			.( $this->href ? " href=\"{$this->href}\"" : '' )
			.( $this->title ? " title=\"{$this->title}\"" : '' );
	}
}