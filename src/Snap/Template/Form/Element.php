<label>
<?php 
if ( $this->label != null ){
	if ( is_string($this->label) ){
		$this->write( $this->label );
	}else{
		$this->append( $this->label );
	}
}

$this->append( $this->input );

if ( $this->note != null ){
	if ( is_string($this->note) ){
		$this->write( $this->note );
	}else{
		$this->append( $this->note );
	}
}
?>
</label>