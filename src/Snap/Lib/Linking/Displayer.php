<?php

namespace Snap\Lib\Linking;

interface Displayer extends \Snap\Node\Core\Snapable{
	public function setData( array $data ); // info about the current set for the page
}