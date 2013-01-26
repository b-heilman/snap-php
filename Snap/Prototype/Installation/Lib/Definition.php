<?php

namespace Snap\Prototype\Installation\Lib;

interface Definition {
	public function getTable();
	public function getTableEngine();
	public function getTableOptions();
	
	public function getFields();
	public function getPrepop();
}