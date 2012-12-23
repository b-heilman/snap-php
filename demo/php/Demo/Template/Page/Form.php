<h1>The form test</h1>
<?php 
	$this->append( $form = new \Demo\Node\Form\Test() );
	$form->setValidator(new \Snap\Lib\Form\Validator(array(
		'blank' => new \Snap\Lib\Form\Test\Required('You need to fill the text field')
	)) );
?>