<ul>
<?php
// stream is \Snap\Lib\Mvc\Data
$stream = $this->getStreamData();
$c = $stream->count();

for( $i = 0; $i < $c; $i++ ){
	?><li><?php echo $stream->get($i); ?></li><?php
}
?>
</ul>