<div class='comment-view-header'>
	<span class='comment-user'><?php echo $user; ?></span>
	<span class='time'><?php echo $time; ?></span>
</div>
<div class='comment-view-content'>
	<pre><?php echo $comment->getContent(); ?></pre>
</div>
<div class='comment-view-footer'>
<?php
if ( isset($delete) ){
	$this->append( $delete );
}
?>
</div>