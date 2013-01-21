<?php

// Form row objects.  Keeps form elements in line and handles the formatting of the form.
namespace Snap\Node\Form;

// TODO : this should be taken away
class Row extends \Snap\Node\Core\Block {
	
	protected 
		$alignment, 
		$header;

	public function __construct( $settings = array() ){
		$this->alignment = isset($settings['alignment']) ? $settings['alignment'] : 0;
		$this->header = isset($settings['header']) ? $settings['header'] : '';

		$settings['tag'] = 'div';

		parent::__construct( $settings );
	}

	protected function baseClass(){
		return 'form-row';
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'alignment' => 'How many blocks the row should break into',
			'header' => 'Text to put in as the header for the row'
		);
	}
	// give the form a header
	public function setHeader($str){
		$this->rendered = '';
		$this->header = $str;
	}
	
	// only form elements are allowed to be in a form_row
	// TODO append / prepend / pend
	public function append( \Snap\Node\Core\Snapable $in, $width = 1, $ref = null ){
		// i think this needs to be fixed...
		parent::append($in);

		if ( $this->alignment ){
	    	$t = (100 / $this->alignment) * $width;
	    	$in->addStyle("width: {$t}%;");
		}
	}
	// append the header to the inner
	protected function _finalize(){
		parent::_finalize();
		
		if ( $this->header ){
			parent::prepend( new \Snap\Node\Core\Text(array('tag'=>'h5','text'=>$this->header)), true );
		}
	}
}