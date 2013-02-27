<b><?php echo $prototype->name; ?></b>
<?php

foreach( $prototype->installs as $table => $installed ){
	$this->append( new \Snap\Node\Form\Element(array(
		'input' => new \Snap\Node\Form\Input\Checkbox(${$table}),
		'label' => $table
	)), 'checkbox' );
}