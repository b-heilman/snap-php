<?php

class array_controller extends controller_node {
	protected function makeData(){
		$data = new \Snap\Lib\Mvc\Data();
		
		$data->add( array( 1, 2, 3, 4, 5, 6, 7, 8 ) );
		
		return $data;
	}
}