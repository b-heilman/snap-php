<?php
$this->append( $this->paging_paginator );
			
	$this->paging_paginator->append( new paging_control_basic(array(
		'navVar' => 'logPage',
		'class'  => 'analytics-pages'
	)) );
		
	$this->paging_paginator->append( new paging_displayer_table(array(
		'headers' => array('User', 'IP', 'Browser', 'Count')
	)) );