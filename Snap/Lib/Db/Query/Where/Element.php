<?php

namespace Snap\Lib\Db\Query\Where;

interface Element {
	public function toString( \Snap\Adapter\Db $db );
}