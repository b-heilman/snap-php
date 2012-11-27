<h1>The VC example</h1>
<?php 
	$this->append( new array_controller(array(
		'outputStream' => 'simple' 
	)) );
	
	$this->append( new list_view(array(
		'inputStream' => 'simple' 
	)) );
?>