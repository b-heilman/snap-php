<?php

namespace Snap\Node\Translation;

class Markup extends \Snap\Node\Core\Template {
 	protected function getTranslator(){
 		return new \Snap\Lib\Markup\Translator();
 	}
}