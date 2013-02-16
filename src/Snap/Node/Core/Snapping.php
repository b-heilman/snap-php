<?php

namespace Snap\Node\Core;

interface Snapping extends Stacking {

	static public function setFactory( \Snap\Lib\Node\StackFactory $factory );
	
	public function get( $place );
	
	public function verifyControl( Snapable $in ); // Notify about an append, but do not append it, to be used for going up parent chain
	
	public function append( Snapable $in, $ref = null );
	public function appendAt( Snapable $in, $where = 0, $ref = null );
	public function appendAfter( Snapable $ele, Snapable $in, $ref = null );
	public function appendBefore( Snapable $ele, Snapable $in, $ref = null );
	public function prepend( Snapable $in, $ref = null );
	
	public function remove( Snapable $ele );
	public function removeAt( $where );
	
	public function childCount();
	
	public function getElementsByClass( $class );
	public function getElementByReference( $ref );
}