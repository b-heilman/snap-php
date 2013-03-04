<div class='comment-view-header'>
	<span class='comment-user'><?php echo $user; ?></span>
	<span class='time'><?php echo $time; ?></span>
</div>
<div class='comment-view-content'>
	<pre><?php echo $comment->getContent(); ?></pre>
</div>
<div class='comment-view-footer'>
<?php
if ( \Snap\Prototype\User\Lib\Current::isAdmin() ){
	$model = new \Snap\Prototype\Comment\Model\Form\Delete( $comment );
	$this->append( new \Snap\Prototype\Comment\Node\Form\Delete(array('model' => $model)) );
	$this->append( new \Snap\Prototype\Comment\Control\Form\Delete(array('model' => $model)) );
}
?>
</div>