<ul class="image-list">
<?php
foreach( $images as $src => $title ){ ?>
	<li class='image-wrapper'><b class='image-name'><?php echo $title; ?></b><img src="<?php echo $src; ?>" alt="Image Missing"/></li>
<?php } ?>
</ul>