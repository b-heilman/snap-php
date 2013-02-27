<?php

use \Snap\Node\Form\Element;

$this->includeParentTemplate();

if ( empty($prototype->installs) ){
	$this->loadTemplate( $this->getTemplate('Form/Create.php') );
}