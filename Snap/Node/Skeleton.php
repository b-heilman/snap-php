<?php

namespace Snap\Node;

abstract class Skeleton extends \Snap\Node\Block 
	implements \Snap\Node\Skeletal, \Snap\Node\Actionable {
	
	protected 
		$skeletonText;
	
	public function __construct( $settings = array() ){
		parent::__construct( $settings );
		$this->skeletonText = 'skeleton';
	}
	
	public function baseClass(){
		return 'skeleton_node';
	}
	
	public function skeletalHtml(){
		static $saved = false;
		if ( !$saved ){
			$saved = true;
			
			// TODO : I might want to limit this?  Right now I'm just brute forcing it, but if memory
			// counts I need to revisit this
			
			form_input::save();
			\Snap\Lib\Mvc\Control::save();
		}
		
		$loader = SKELETAL_LOADER;
		$class = get_class($this);
		
		return $this->_html("<span class=\"replacement-target\" data-frameworkClass=\"$class\">"
			. "<noscript>To view this content, you need to enable javascript and reload the page, "
			. "or go <a href=\"$loader?class=$class\">here</a></noscript>"
			. "<span class='content'>{$this->skeletalContent()}</span>"
		. '</span>');
	}
	
	protected function skeletalContent(){
		return '<!-- To have this content load, you need JS running -->';
	}
	
	public static function getActions(){
		return array(
			new \Snap\Lib\Linking\Resource\Relative($this)
		);
	}
}