<div class='install-name'>
<?php
if ( $prototype->installable ){
	$row = $prototype->installRow;
	$this->append( $row() );
}else{
?><span><?php echo $prototype->name; ?></span><?php
}
?>
</div>
<?php
if ( $prototype->forms ){?>
	<span class='install-forms'><?php 
		// TODO : this should go to the view
		$link = $factory->createLink( $prototype->name );
		
		?><a href='<?php echo $link['href']; ?>' class='<?php echo $link['class']
			.( $prototype->name == $active ? 'active' : '' )?>'>View Forms</a></span><?php 
}

if ( $prototype->installable ) {
	?><span class='install-location'>Found at <?php echo $prototype->installDir?></span><?php
}