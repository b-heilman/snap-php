<div class="admin-head"><?php
	$this->append( $logoutView );
?></div>
<div class="admin-content"><?php
if ( $accessible ){
	$this->append( new \Snap\Control\Feed\Navigation(array(
		'navVar'       => 'form',
		'outputStream' => 'form_nav'
	)));
	
	$this->append( new \Snap\Prototype\Installation\Node\View\Forms(array(
		'deferTemplate' => $security,
		'inputStream'   => 'prototype_nav',
		'navStream'     => 'form_nav'
	)) );
	
	$this->append( new \Snap\Prototype\Installation\Node\View\Editor(array(
		'deferTemplate'   => $security,
		'prototypeStream' => 'prototype_nav',
		'formStream'      => 'form_nav'
	)) );
	
	$this->append( new \Snap\Prototype\Installation\Node\View\ManagementForm(array(
		'deferTemplate' => $security
	)) );
	
	$this->append( new \Snap\Prototype\Installation\Control\Feed\Management(array(
		'deferTemplate' => $security,
		'inputStream'   => 'prototype_action',
		'outputStream'  => 'install_messages'
	)) );
	
	$this->append( new \Snap\Node\View\Stacked(array(
		'primaryView' => '\Snap\Node\View\Dump',
		'inputStream' => 'install_messages'
	)) );
}else{?> <h5>You need to set up the database</h5> <?php }
?></div>
