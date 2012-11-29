<?php

namespace Demo\Node\Controller;

class Junker extends \Snap\Node\Controller {
	protected function makeData(){
		$data = new \Snap\Lib\Mvc\Data();
		
		$data->add( array(4, 5, 6, 7) );
		
		return $data;
	}
}