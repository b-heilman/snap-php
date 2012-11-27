<?php
class form_page extends page_node
	implements styleable_local_node {

	public function getLocalCSS(){
		return array(
			'base.css'
		);
	}

	protected function defaultTitle(){
		return 'The form page';
	}

	protected function getMeta(){
		return '';
	}
}