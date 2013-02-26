<?php

namespace Demo\Control\Feed;

class Junker extends \Snap\Control\Feed {
	protected function makeData(){
		$data = new \Snap\Lib\Mvc\Data\Collection();
		
		$data->add( array(4, 5, 6, 7) );
		
		return $data;
	}
}