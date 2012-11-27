<?php

namespace Snap\Prototype\Indexing\Node\View;

class Link extends \Snap\Node\View\Listing {
	protected function setVariables( $info = array() ){
		return array(
			'text'  => $info->getDisplay(),
			'link'  => $info->getFullPath(),
			'class' => $info->isCurrent()?'active':''
		);
	}
}