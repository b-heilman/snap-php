<?php 
	$this->append( $this->login = new \Snap\Prototype\User\Node\Form\Access() );

	$this->append( new \Snap\Prototype\Blogging\Node\Form\Editor(array(
		'class'    => 'blog-content',
		'type'     => 'Blog',
		'controls' => true
	)) );
?>
<div class='right-nav'>
<?php 
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
?>
</div>
