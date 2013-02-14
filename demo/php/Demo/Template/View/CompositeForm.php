<fieldset>
	<legend>Woot</legend>
<?php
	if ( isset($__messages) ){
		$this->append( $__messages );
	}

	$this->append( new \Snap\Node\Form\Virtual(array(
		'model'        => new \Demo\Model\Form\TestForm(),
	//	'controller'   => '\Demo\Control\Feed\TestForm', // omitted, controller is implied to match view
		'view'         => '\Demo\Node\View\TestForm',
		'outputStream' => 'form_data'
	)));
	
	$this->append( new \Snap\Node\View\Dump(array(
		'inputStream'  => 'form_data'
	)) );
?>
</fieldset>