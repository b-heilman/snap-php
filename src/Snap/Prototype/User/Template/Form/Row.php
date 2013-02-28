<?php

use \Snap\Node\Form\Element;

$this->includeParentTemplate();

if ( !$prototype->installs['User'] ){
	$this->loadTemplate( $this->getTemplate('Form/Create.php') );
}