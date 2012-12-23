<?php

namespace Snap\Lib\Node;

class SkeletalStack extends Stack {
	protected function evalNode( $el ){
		if ( $el instanceof \Snap\Node\Skeletal ){
			$code = $el->skeletalHtml();
			
			$this->pullClassContent( $el );
			
			return $code;
		}else return parent::evalNode($el);
	}
	
	protected function _add( \Snap\Lib\Core\Token $in, $where ){
		if ( !($in instanceof \Snap\Node\Skeletal) ){
			parent::_add( $in, $where );
		}
	}
}