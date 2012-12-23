<?php

$this->append( new \Snap\Node\Form\Input\Basic(array(
	'type' => 'text',
	'name' => 'text_test'
)) );

$this->append( new \Snap\Node\Form\Input\Textarea(array(
	'name' => 'textarea_test'
)) );

?><div class='form-elements'><?php
$this->append( new \Snap\Node\Form\Element(array(
	'label' => 'Test',
	'input' => new \Snap\Node\Form\Input\Basic(array(
		'name'  => 'element_1',
		'value' => 'original',
		'type'  => 'text'
	))
)) );

$this->append( new \Snap\Node\Form\Element(array(
	'label' => 'Test 2',
	'input' => new \Snap\Node\Form\Input\Basic(array(
		'name'  => 'element_2',
		'value' => 'original',
		'type'  => 'text'
	)),
	'note'  => 'this is a test'
)) );
?></div>

<?php
$this->append( new \Snap\Node\Form\Input\Basic(array(
	'name' => 'password_test',
	'type' => 'password'
)) );

$this->append( new \Snap\Node\Form\Element(array(
	'label' => 'Select',
	'input' => new \Snap\Node\Form\Input\Select(array(
		'name'  => 'select',
		'value' => '',
		'options'  => array(
			'' => 'This is default',
			1  => 'this is another value',
			2  => 'Yet another'
		)
	))
)) );
?>
<div class='checkboxes'>
<?php
$this->append(
	new \Snap\Node\Form\Input\Checkbox(array(
		'name'  => 'cb[]',
		'value' => 'first'
	))
);
$this->append(
	new \Snap\Node\Form\Input\Checkbox(array(
		'name'  => 'cb[]',
		'value' => 'second'
	))
);
?>
</div>
<div class='checkboxes'>
<?php
$this->append(
	new \Snap\Node\Form\Input\Checkbox(array(
		'name'  => 'cb_1',
		'value' => 'one'
	))
);
$this->append(
	new \Snap\Node\Form\Input\Checkbox(array(
		'name'  => 'cb_2',
		'value' => 'two'
	))
);
$this->append(
	new \Snap\Node\Form\Input\Checkbox(array(
		'name'  => 'cb_3',
		'value' => 'three'
	))
);
$this->append(
	new \Snap\Node\Form\Input\Checkbox(array(
		'name'  => 'cb_4',
		'value' => 'four'
	))
);
?>
</div>
<hr/>
<?php
$this->append( $this->messaging );
?>
<hr/>
<?php 
$this->append(
	new \Snap\Node\Form\Input\Basic(array(
		'name' => 'blank',
		'type' => 'text'
	))
);
$this->append( new \Snap\Node\Form\Control() );