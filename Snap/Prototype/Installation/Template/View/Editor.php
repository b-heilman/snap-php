<?php
$el = null;

if ( $form instanceof \Snap\Node\Core\Snapable ){
	$this->addClass('active');
	$this->append( $el = $form );
}elseif ( is_string($form) ){
	$this->addClass('active');
	$this->append( $el = new $form() );
}elseif( is_array($form) ){
	$this->addClass('active');
	$this->append( $el = new \Snap\Node\Core\Form() );
	
	if ( isset($form['form']) ){
		if ( is_array($form['form']) ){
			$forms    = $form['form'];
			$settings = $form['settings'];
		}else{
			$forms    = array( $form['form'] );
			$settings = array( $form['settings'] );
		}
	}else{
		$forms = $form;
		$settings = array();
	}
	
	foreach ( $forms as $key => $form ){
		$el->append( new $form(isset($settings[$key])?$settings[$key]:array()) );
	}
}

if ( $el && count($el->getElementsByClass('\Snap\Node\Form\Input\Button')) == 0 ){
	$el->append( new \Snap\Node\Form\Control() );
}