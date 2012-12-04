<?php
require_once('Snap/Lib/Core/Bootstrap.php');

$legal = false;

if ( isset($_GET['r']) ){
	$resource = $_GET['r'];
}else{
	if ( isset($_SERVER['REDIRECT_URL']) ){
		// REDIRECT_URL - PHP_SELF
		// http://localhost/test/ym/something/or/other?woot=1
		// '/test/ym/something/or/other' - '/redirect.php' 
		// /ym/something/or/other
		
		$find = explode( '/', $_SERVER['PHP_SELF']  );
		$path = explode( '/', $_SERVER['REDIRECT_URL'], count($find) + 1 );
		
		$resource = array_pop( $path );
	}elseif( isset($_SERVER['PATH_INFO']) ){
		// PATH_INFO
		// http://localhost/test/index.php/ym/something/or/other?woot=1
		// /ym/something/or/other
		$resource = $_SERVER['PATH_INFO'];
	}else{
		$resource = '';
	}
}

if ( substr($resource, -2) === 'js' ){
	$legal = true;
	header('Content-type: application/javascript');
}else{
	$ctype = substr($resource, -3);
	switch ( $ctype ){
		case 'css' :
			$legal = true;
			header('Content-type: text/css');
		break;
		
		case "jpeg":
			$ctype = 'jpg';
		case "jpg" : 
		case "gif" : 
		case "png" : 
			$legal = true;
			header('Content-type: image/'.$ctype);
		break;
	}
}

if ( $legal ){
	$file = \Snap\Lib\Core\Bootstrap::testFile( $resource );

	if ( $file ) {
		echo file_get_contents( $file );
	}
}