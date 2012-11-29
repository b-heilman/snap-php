<?php

namespace Snap\Prototype\Indexing\Node\View;

class Link extends \Snap\Node\View\Listing {
	protected function getTemplateVariables( $info = array() ){
		return array(
			'text'  => $info->getDisplay(),
			'link'  => $info->getFullPath(),
			'class' => $info->isCurrent()?'active':''
		);
	}
}