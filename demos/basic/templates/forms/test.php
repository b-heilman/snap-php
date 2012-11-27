<?php

$this->append( new form_input_node(array(
	'type' => 'text',
	'name' => 'text_test'
)) );

$this->append( new form_textarea_node(array(
	'name' => 'textarea_test'
)) );

?><div class='form-elements'><?php
$this->append( new form_element_node(array(
	'label' => 'Test',
	'input' => new form_input_node(array(
		'name'  => 'element_1',
		'value' => 'original',
		'type'  => 'text'
	))
)) );

$this->append( new form_element_node(array(
	'label' => 'Test 2',
	'input' => new form_input_node(array(
		'name'  => 'element_2',
		'value' => 'original',
		'type'  => 'text'
	)),
	'note'  => 'this is a test'
)) );
?></div>

<?php
$this->append( new form_input_node(array(
	'name' => 'password_test',
	'type' => 'password'
)) );

$this->append( new form_element_node(array(
	'label' => 'Select',
	'input' => new form_select_node(array(
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
	new form_checkbox_node(array(
		'name'  => 'cb[]',
		'value' => 'first'
	))
);
$this->append(
	new form_checkbox_node(array(
		'name'  => 'cb[]',
		'value' => 'second'
	))
);
?>
</div>
<div class='checkboxes'>
<?php
$this->append(
	new form_checkbox_node(array(
		'name'  => 'cb_1',
		'value' => 'one'
	))
);
$this->append(
	new form_checkbox_node(array(
		'name'  => 'cb_2',
		'value' => 'two'
	))
);
$this->append(
	new form_checkbox_node(array(
		'name'  => 'cb_3',
		'value' => 'three'
	))
);
$this->append(
	new form_checkbox_node(array(
		'name'  => 'cb_4',
		'value' => 'four'
	))
);
?>
</div>
<hr/>
<?php
$this->append( $this->messaging );

$this->setValidator(new form_validator(array(
	'blank' => new form_test_required('You need to fill the text field')
)) );

$this->append(
	new form_text_node(array(
		'name' => 'blank'
	))
);
$this->append( new form_control_node() );