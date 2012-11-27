<?php
// table row element used for building tables
namespace Snap\Node\Table;

use Snap\Node;

class Row extends \Snap\Node\Linear {

	public 
		$cell,
		$header,
		$curCell;
		
	protected 
		$lastWrite;

	public function __construct( $settings = array() ) {
		$settings['tag'] = 'tr';
		
		parent::__construct( $settings );

		$this->cell 		= 	isset($settings['cellClass']) ? $settings['cellClass'] : '';
		$this->header		= 	isset($settings['header']) ? $settings['header'] : false;
		$this->curCell 		=	null;
		$this->lastWrite 	= 	-1;

		$this->growTo( isset($settings['size'])?$settings['size']:0 );
	}

	public static function getSettings(){
		return parent::getSettings() + array(
			'cellClass' => 'the class for the cells',
			'header'    => 'is this a row of table headers?',
			'size'      => 'default size of the row'
		);
	}
	
	public function growTo( $size ){
		while( $this->childCount() < $size ){
			if ($this->header){
				parent::append( new Node\Block(array('tag'=>'th','class'=>$this->cell)) );
			}else{
				parent::append( new Node\Block(array('tag'=>'td','class'=>$this->cell)) );
			}
		}
	}

	public function write( $txt, $class = '', $place = -1 ){
		$settings = array( 'text' => $txt, 'class' => $class, 'tag' => 'span' );
		return $this->append( new Node\Text($settings), $place);
	}

	// childwrap is used here to wrap all elements passed in into a table cell or header
	// TODO what about prepend?
	public function append( Node\Snapable $in, $place = -1, $ref = null ){
		if ( $place == -1 ){
			$i = ++$this->lastWrite;
			if ( $i == $this->childCount() ) {
				if ($this->header){
					parent::append( $e = new Node\Block(array('tag'=>'th','class'=>$this->cell)) );
				}else{
					parent::append( $e = new Node\Block(array('tag'=>'td','class'=>$this->cell)) );
				}
			}else{
				$e = $this->inside->get($i);
			}
		}else{
			++$this->lastWrite;

			$this->growTo( $place + 1 );

			$e = $this->inside->get($place);
		}

		if ( $in != null )
			$e->append( $in );

		return ($this->curCell = $e);
	}
	
	public function inner(){
		$i = 0;
		$this->inside->walk(function($el) use (&$i){
			$el->addClass("col-$i");
			$i++;
		});
		
		$this->inside->first(function($el){
			$el->addClass('first');
		});
		
		$this->inside->last(function($el){
			$el->addClass('last');
		});
		
		return parent::inner();
	}
}
