<?php
$info = $this->getStreamData();
		
if ( $info != null ){
	if( $info->hasVar('active') ){
		$info = $info->get( $info->getVar('active') );
	}else{
		$info = $info->get(0);
	}
?>
<pre><?php print_r($info)?></pre>
<?php
}