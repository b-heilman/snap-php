<?php

namespace Snap\Node\Core;

class Table extends \Snap\Node\Block {

	protected 
		$body,
		$columns = array(),
		$even,
		$odd,
		$cell,
		$colCount,
		$colLimit,
		$curRow,
		$orderSet = null;

	/*--------------
	* if $data is -1, then no enforcing for the size of the table
	* if colLimit >= 0, then a limit is used for append
	*/
	public function __construct( $settings = array() ) {
		$settings['tag'] = 'table';
		
		parent::__construct( $settings );

		parent::append( $this->body = new \Snap\Node\Core\Block(array('tag' => 'tbody')) );

		$this->cell = isset($settings['cellClass']) ? $settings['cellClass'] : 'dl-table-cell';
		$this->colCount = 0;
		
		if( isset($settings['columns']) ){
			$this->colLimit = $settings['columns'];
		}else{
			$this->colLimit = -1;
		}

		$this->curRow 	= null;
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'columns'   => 'the number of columns',
			'data'      => 'the default data',
			'cellClass' => 'the class for the individual cells'
		);
	}

	protected function getAttributes(){
		return parent::getAttributes().' cellspacing="0px" cellpadding="0px"';
	}
	
	public function childCount(){
		return $this->body->childCount();
	}

	public function widthTo($size){
		$this->body->inside->walk(function($row) use ($size){
			$row->growTo( $size );
		});

		if ( $size > $this->colCount )
			$this->colCount = $size;
	}

	public function heightTo($size){
		while( $this->childCount() < $size ){
			$this->addRow();
		}
	}

	public function addRow( $size = 0, $header = false ){
		if ( $this->colLimit == 0 ){
			if ( $size > $this->colCount ){
				$this->widthTo( $size );
			}else{
				$size = $this->colCount;
			}
		}

		$this->body->append( $this->curRow = new \Snap\Node\Table\Row( array(
			'cellClass' => $this->cell, 
			'header'    => $header,
			'size'      => $size
		)) );
		return $this->curRow;
	}

	public function setColumn($col, array $options ){
		$this->columns[$col] = $options;
		// array( 'width' => $width, 'class' => $class, 'id' => $id, 'style' => $style );
	}
	/*--------
	 *
	 * This wrapped the append function and shoves all appended data into a table row.
	 * As rows are filled up, new ones are created.
	 */
	public function write( $txt, $class = '', $row = -1, $col = -1 ){
		return $this->append( new \Snap\Node\Core\Text(array(
			'tag'   => 'span', 
			'text'  => $txt, 
			'class' => $class
		)), $row, $col);
	}
	
	public function join( array $data, $row = -1, $col = -1){
		if ( empty($data) ){
			throw new \Exception('table_node passed empty array');
		}
		
		$i = ($row == -1) ? $this->body->inside->count() : $row ;
		$startJ = ($col == -1) ? 0 : $col ;
		
		foreach( $data as $r ){ // I'm not assuming these tables are indexed by numbers
			$j = $startJ;
			
			foreach( $r as $c ){
				if ( $c instanceof \Snap\Node\Core\Snapable ){
					$this->append( $c, $i, $j );
				}elseif ( is_string($c) ) {
					$this->append( new \Snap\Node\Core\Text($c), $i, $j );
				}else{
					$this->append( new \Snap\Node\Core\Comment('nothing to see here') , $i, $j );
				}
				
				$j++;
			}
			
			$i++;
		}
	}
	
	//TODO I need to do the prepend here, move most of this to pend
	public function append( \Snap\Node\Core\Snapable $in, $row = -1, $col = -1, $ref = null ){
		$this->rendered = '';

		if ( $in instanceof \Snap\Node\Table\Row ){
			// TODO : I'm forgetting about $row and $col...
			$t = $in->childCount();

			if ( $t > $this->colCount )
				$this->colCount = $t;

			if ( $this->colLimit > 0 && $t > $this->colLimit )
				throw new \Exception('Column Limit Exceeded By Appended Row');

			$this->curRow = $in;
			$this->body->append( $in );

			return $in;
		}else{
			if ( $row != -1 ){
				if ( $row >= $this->childCount() )
					$this->heightTo( $row + 1 );

				$this->curRow = $this->body->inside->get($row);
			}elseif ( $this->curRow == null ) {
				$this->addRow();
			}

			if ( $col == -1 ){
				$col = $this->curRow->childCount();
			}

			if ( $this->colLimit > 0 && $this->colLimit <= $col ){
				$this->addRow();

				$col -= $this->colLimit;
			}elseif ( $this->colLimit == 0 && $this->colCount <= $col ) {
				// Size the column, and then make sure the rest are the same size
				$this->widthTo( $col + 1 );
			} // else there is no governing of the columns, so just add it to the current row

			if ( is_string($in) ){
				$this->curRow->write($in, $col);
			}else
				$this->curRow->append($in, $col);

			return $this->curRow;
		}
	}

	public function inner(){
		if ( !empty($this->columns) ){
			$co = count( $this->columns );
			for( $i = 0; $i < $co; ++$i ){
				if ( isset($this->columns[$i]) ){
					$c = $this->columns[$i];
					$c['tag'] = 'col';
					
					parent::append( $c );
				}else{
					parent::append( new \Snap\Node\Core\Simple(array('tag'=>'col')) );
				}
			}
		}

		$this->body->inside->first(function($el){
			$el->addClass('first');
		});

		$this->body->inside->last(function($el){
			$el->addClass('last');
		});

	    return parent::inner();
    }
}