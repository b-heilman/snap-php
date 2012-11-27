<?php

namespace Snap\Lib\Db;

interface Result{
	public function asArray($val = false);
	public function asHash($key, $val = false);
	public function asIndex($dexs, $val = false);

	public function nextVal( $var );
	public function next($object = false);
	public function hasNext();
	public function count();
	public function getFields($asHash = false);
}