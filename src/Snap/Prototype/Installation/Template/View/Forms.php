<?php 

if ( !empty($forms) ){ ?>
	<ul>
	<?php 
	foreach( $forms as $form ){ 
	?>
	<li><?php 
		// TODO : this should go to the view
		$link = $factory->createLink( $form );
		
		?><a href='<?php echo $link['href']; ?>' class='<?php echo $link['class']
			.( $form == $active ? ' active' : '' )?>'><?php echo $form; ?></a></li>
	<?php 
	} 
	?></ul><?php 
}
