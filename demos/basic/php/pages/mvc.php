<?php
class mvc_page extends page_node
	implements styleable_local_node {

	public function getLocalCSS(){
		return array(
			'/base.css'
		);
	}

	protected function defaultTitle(){
		return 'The mvc page';
	}

	protected function getMeta(){
		return '';
	}
}