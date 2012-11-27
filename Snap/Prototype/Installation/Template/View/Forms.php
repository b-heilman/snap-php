<?php 

if ( !empty($forms) ){ ?>
	<ul>
	<?php foreach( $forms as $form ){ ?>
	<li><?php 
		$this->append( $link = $factory->createLink($form,$form) );
		if( $form == $active ){
			$link->addClass('active');
		}
	?></li>
	<?php } ?>
	</ul><?php 
} 
