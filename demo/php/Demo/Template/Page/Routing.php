<div class="demo-wrapper">
	<h5>The routing Demo</h5>
	<div>
		<a href="<?php echo $this->getSiteUrl('/demo'); ?>">Demo Home</a> - 
		<a href="<?php echo $this->getSiteUrl('/demo/check1'); ?>">Check1</a> - 
		<a href="<?php echo $this->getSiteUrl('/demo/check2'); ?>">Check2</a>
	</div>
	<?php $this->append( $content ); ?>
</div>