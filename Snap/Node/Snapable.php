<?php

namespace Snap\Node;

interface Snapable extends \Snap\Lib\Core\Token {
	public function __construct( $settings = array() );
	public static function getSettings();
	
	public function kill();
	public function clear();
	
	public function html();
	public function inner();
	public function toString();
	
	public function hasId();
	public function getId();
	
	public function setPage( \Snap\Node\Page $page );
	public function setParent( \Snap\Node\Snapping $parent );
	public function getParent();
	public function hasParent();
	public function setExtender( \Snap\Lib\Node\Extender $extender );
	public function hasExtender();
	
	public function removeFromParent();
	public function closest( $class );
	
	public function makeClone();
}