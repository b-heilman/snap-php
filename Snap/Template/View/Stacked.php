<?php

if ( $this->uniqueness ){
	$content->makeUnique( $this->uniqueness );
}

if ( $content ){
	$count = $content->count();
	
	if ( $count == 1 ){
		$this->_append( $this->createPrimaryView($content->get(0),$content->getVar('factory')), $this );
	}else{
		$this->append( $this->createList($content) );
	}
}else{
	$this->write( 'No content' );
}