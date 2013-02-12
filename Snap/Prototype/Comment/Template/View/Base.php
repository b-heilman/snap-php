<div class='comment-view-header'>
	<span class='comment-user'><?php echo $user->name(); ?></span>
	<span class='time'>written <?php echo $time->maxSince(); ?> ago</span>
</div>
<div class='comment-view-content'>
	<pre><?php echo $comment->info('content'); ?></pre>
</div>
<div class='comment-view-footer'>
<?php
if ( \Snap\Prototype\User\Lib\Current::isAdmin() ){
	$model = new \Snap\Prototype\Comment\Model\Form\Delete( $comment );
	$this->append( new \Snap\Prototype\Comment\Node\View\DeleteForm(array('model' => $model)) );
	$this->append( new \Snap\Prototype\Comment\Node\Controller\DeleteForm(array('model' => $model)) );
}
?>
</div>