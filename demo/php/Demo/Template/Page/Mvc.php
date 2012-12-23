<h1>The VC example</h1>
<?php 
	$this->append( new \Demo\Node\Controller\Junker(array(
		'outputStream' => 'simple' 
	)) );
	
	$this->append( new \Demo\Node\View\Listing(array(
		'inputStream' => 'simple' 
	)) );
?>