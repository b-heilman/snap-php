<?php

use \Snap\Node\Form\Element;

$this->includeParentTemplate();

if ( !$prototype->installed ){
	$this->loadTemplate( $this->getTemplate('Form/Create.php') );
}