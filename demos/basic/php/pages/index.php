<?php
class index_page extends page_node
	implements styleable_local_node {

	public function getLocalCSS(){
		return array(
			'/base.css',
			'/pages/index.css'
		);
	}

	protected function defaultTitle(){
		return 'The example';
	}

	protected function getMeta(){
		return '';
	}
}