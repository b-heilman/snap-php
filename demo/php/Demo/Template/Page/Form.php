<h1>The form test</h1>
<?php
	$content = new \Demo\Model\Form\TestForm();
	
	$this->append( $form = new \Demo\Node\Controller\TestForm(array(
		'outputStream' => 'form_data',
		'model'        => $content
	)) );
	
	$this->append( $form = new \Demo\Node\View\TestForm(array(
		'model' => $content
	)) );
	
	$this->append( new \Snap\Node\View\Dump(array(
		'inputStream'  => 'form_data'
	)) );
?>