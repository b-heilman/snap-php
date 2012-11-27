<label>
<?php 
if ( $this->label != null ){
	$this->append( $this->label );
}

$this->append($this->input);

if ( $this->note != null ){
	$this->append( $this->note );
}
?>
</label>