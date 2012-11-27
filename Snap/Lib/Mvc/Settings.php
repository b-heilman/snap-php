<?php

namespace Snap\Lib\Mvc;

class Settings {

	public 
		$view, 
		$control, 
		$data;
	
	public function __construct( Control\Factory $control, View\Factory $view, Data $data = null ){	
		$this->control = $control;
		$this->view = $view;
		$this->data = ( $data == null ? new Data() : $data );
	}
}