<h1>The form test</h1>
<?php
	$content = new \Demo\Lib\Form\TestForm();
	
	$this->append( $form = new \Demo\Node\Controller\TestForm(array(
		'outputStream' => 'form_data',
		'content'      => $content
	)) );
	
	$this->append( $form = new \Demo\Node\View\TestForm(array(
		'content' => $content
	)) );
	
	$this->append( new \Snap\Node\View\Dump(array(
		'inputStream'  => 'form_data'
	)) );
?>