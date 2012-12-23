<div class='install-name'>
<?php
if ( $prototype->installable ){
	$this->append( $prototype->installRow );
}else{
?><span><?php echo $prototype->name; ?></span><?php
}
?>
</div>
<?php
if ( $prototype->forms ){?>
	<span class='install-forms'><?php 
		$this->append( $link = $factory->createLink($prototype->name,'View Forms') );
		
		if( $prototype->name == $active ){
			$link->addClass('active');
		}
	?></span><?php 
}

if ( $prototype->installable ) {
	?><span class='install-location'>Found at <?php echo $prototype->installDir?></span><?php
}