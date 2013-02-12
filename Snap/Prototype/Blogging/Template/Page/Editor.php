<div class="blog-content">
<?php 
	$this->append( $loginControl );
	
	$this->append( $logoutControl );
	$this->append( $logoutView );

	
	$this->append( $editorControl );
	$this->append( $editorView );
?>
</div>
<div class='right-nav'>
<?php 
	/*
	$this->append( new \Snap\Prototype\WebFS\Node\Controller\Extensions(array(
		'extensions'   => array('jpg', 'gif', 'png'),
		'prevMax'      => -1,
		'nextMax'      => -1,
		'outputStream' => 'files',
		'navVar'       => 'topic'
	)) );
	
	$this->append( new \Snap\Node\View\Stacked(array(
		'inputStream' => array('files','new_file'),
		'class'       => 'file-thread',
		'primaryView' => '\Snap\Prototype\WebFS\Node\View\File'
	)) );
	
	$this->append( new \Snap\Prototype\WebFS\Node\Form\AjaxCreate(array(
		'messaging'=>true
	)) );
	*/
?>
</div>
