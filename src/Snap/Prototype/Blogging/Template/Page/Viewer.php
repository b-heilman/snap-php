<?php

$this->append( new \Snap\Prototype\Topic\Control\Feed\Limiting(array(
	'class'        => 'main-view',
	'type'         => $this->blogType,
	'outputStream' => $blogContent,
	'navVar'       => $blogNavVar, //'topic'
)) );

$this->append( new \KPflueger\Node\View\Content(array(
		'inputStream' => $blogContent,
		'class'       => 'content-view'
)) );