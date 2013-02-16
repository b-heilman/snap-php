<?php

namespace Snap\Node\Translation;

class Blog extends \Snap\Node\Translation\Markup {
 	protected function getTranslator(){
 		return new \Snap\Lib\Blog\Translator();
 	}
}