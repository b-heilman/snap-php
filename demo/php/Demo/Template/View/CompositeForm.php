<fieldset>
	<legend>Woot</legend>
<?php
	if ( isset($__messages) ){
		$this->append( $__messages );
	}

	$this->append( $form = new \Demo\Node\Controller\TestForm(array(
		'outputStream' => 'form_data'
	)) );
	
	$this->append( $form = new \Demo\Node\View\TestForm(array()) );
	
	$this->append( new \Snap\Node\View\Dump(array(
		'inputStream'  => 'form_data'
	)) );
?>
</fieldset>