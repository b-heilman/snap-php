<?php

namespace Snap\Lib\Token;

interface Factory {
	public function make( \Snap\Lib\Token\Prototype $content, $translatorClass );
}