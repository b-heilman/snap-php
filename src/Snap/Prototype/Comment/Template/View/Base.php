<div class='comment-view-header'>
	<span class='comment-user'><?php echo $user; ?></span>
</div>
<div class='comment-view-content'>
	<pre><?php echo $comment->getContent(); ?></pre>
</div>
<div class='comment-view-footer'>
	<span class='timestamp'><?php echo $timestamp; ?></span>
<?php
if ( isset($delete) ){
	$this->append( $delete );
}
?>
</div>