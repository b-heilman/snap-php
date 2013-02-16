<?php
use Snap\Node\Core\Actionable;

$this->form = null;

if ( $form instanceof \Snap\Node\Core\Snapable ){
	$this->addClass('active');
	$this->append( $this->form = $form );
}elseif ( is_string($form) ){
	$this->addClass('active');
	$this->append( $this->form = new $form() );
}elseif ( is_callable($form) ){
	$this->addClass('active');
	$this->append( $this->form = $form() );
}elseif( is_array($form) ){
	$this->addClass('active');
	$this->append( $this->form = new \Snap\Node\View\Form() );
	
	$forms = $form;
	
	foreach ( $forms as $key => $form ){
		$f = $form instanceof \Snap\Node\Core\Snapable
			? $form 
			: ( is_callable( $form )
				? $form()
				: new $form()
			);
			
		$this->form->append( $f );
	}
}