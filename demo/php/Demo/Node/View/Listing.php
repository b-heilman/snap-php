<?php

namespace Demo\Node\View;

class Listing extends \Snap\Node\View\Listing {
	protected function getTemplateVariables( $info = null ){
		// I know $info will be a string, so convert it
		return array(
			'content' => $info
		);
	}
}