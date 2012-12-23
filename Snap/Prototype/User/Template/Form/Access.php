<?php

$this->append( $this->messaging );

$this->append( new \Snap\Prototype\User\Node\Form\Login(), 'login' );

$this->append( new \Snap\Prototype\User\Node\Form\Logout(), 'logout' );