<?php

namespace Snap\Lib\Linking;
// TODO : clean this up

interface Control extends \Snap\Node\Snapable{
	public function setPrevData( array $data );
	public function setNextData( array $data );
	public function setCurrData( array $data );
}