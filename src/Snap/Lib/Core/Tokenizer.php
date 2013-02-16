<?php

namespace Snap\Lib\Core;

interface Tokenizer {
	public function __construct( $content );
	public function getNext();
	public function hasNext();
}