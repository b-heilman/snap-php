<?php

namespace Snap\Node\Form;

class Table extends \Snap\Node\Block 
	implements \Snap\Node\Form\Input {
	
	private 
		$tableInfo = null, 
		$table;
		
    protected 
		$headers, 
		$hidden, 
		$types, 
		$data, 
		$inputs = array(), 
		$selects = array(),
		$name;

    protected function parseSettings( $settings = array() ) {
    	if ( isset($settings['name']) ){
        	$this->name = $settings['name'];
        }else{
        	throw new \Exception('A '.get_class( $this ).' required a name');
        }

        if ( isset($settings['headers']) ){
        	$this->headers = $settings['headers'];
        }
        
     	if ( isset($settings['types']) ){
        	$this->types = $settings['types'];
        }
        
     	if ( isset($settings['hidden']) ){
        	$this->hidden = $settings['hidden'];
        }
        
		if ( isset($settings['data']) && is_array($settings['data']) ){
			foreach( $settings['data'] as $row ){
				$this->addRow( $row );
			}
		}
		
		parent::parseSettings( $settings );
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'data'    => 'the initial data',
			'name'    => 'the name to put all the input data behind',
			'headers' => 'the labels for the columns',
			'hidden'  => 'the fields that get converted to hidden',
			'types'   => 'the type for each column (select, textarea, checkbox, text)'
		);
	}

	public function getType(){
		return 'table';
	}
	
	public function addRow( $row ){
		if ( $this->tableInfo == null ){
			$this->parseInfo( $row );

			$headers = $this->tableInfo['headers'];
			$cols  = count($headers);

			$this->append( $this->table = new \Snap\Node\Table(array(
				'id'      => $this->id.'_tbl', 
				'columns' => $cols, 
				'class'   => 'input_table'
			)) );

			for ( $j = 0; $j < $cols; ++$j ) {
	        	$this->table->write($headers[$j]);
	        }
		}
		
		$table = 	$this->table;
		$hidden = 	$this->tableInfo['hidden'];
	    $indexs = 	$this->tableInfo['indexs'];
		$headers = 	$this->tableInfo['headers'];
		$types = 	$this->tableInfo['types'];

		$cols  = count($headers);
        $hide = count($hidden);

        $i = count($this->inputs); // this is essentially the row count

        $t = array();

		for ( $j = 0; $j < $hide; ++$j ) {
        	$de = $hidden[$j];
        	
            $this->append( $t[$de] = new \Snap\Node\Form\Input\Hidden(array(
            	'name'  => $de.'_'.$i, 
            	'value' => $row[$de]
            )) );
        }

        $tr = null;

        for ( $j = 0; $j < $cols; ++$j ) {
        	$de = $indexs[$j];

            if ( isset($types[$de]) ){
            	switch( $types[$de]['type'] ){
                	case 'select':
                		if ( !isset($this->selects[$de]) ){
                			$this->selects[$de] = array();
                		}

                    	$tr = $table->append( $this->selects[$de][] =
                    		$t[$de] = new \Snap\Node\Form\Input\Select(array(
                    			'name'    => $de.'_'.$i, 
                    			'options' => $types[$de]['selections'], 
                    			'value'   => $row[$de]
                    		))
                    	);
                    	break;
                    case 'checkbox':
                    	$tr = $table->append(
                    		$t[$de] = new \Snap\Node\Form\Input\Checkbox(array(
                    			'name'    => $de.'_'.$i, 
                    			'value'   => '1',
                    			'checked' => (isset($row[$de]) ? $row[$de] : false)
                    		))
                    	);
                    	break;
                    case 'textarea':
                    	$tr = $table->append(
                    		$t[$de] = new \Snap\Node\Form\Input\Textarea(array(
                    			'name'  => $de.'_'.$i, 
                    			'value' => $row[$de]
                    		))
                    	);
                    	break;
                    default :
                    	$tr = $table->append(
                    		$t[$de] = new \Snap\Node\Form\Input\Text(array(
                    			'name'  => $de.'_'.$i, 
                    			'value' => $row[$de]
                    		))
                    	);
                }
            }else{
            	$tr = $table->append( 
            		$t[$de] = new \Snap\Node\Form\Input\Text(array(
	                    'name'  => $de.'_'.$i, 
	                    'value' => $row[$de]
					)) 
				);
            }

            $this->inputs[$i] = $t;
        }

        if ( $tr != null ){
        	$row['_row'] = $tr;
        	$this->data[] = $row;
        }
	}

	public function setSelections( $column, $selections ){
		if ( isset($this->selects[$column]) ){
			foreach( $this->selects as $select ){
				$select->setSelections($selections);
			}
		}

		$this->tableInfo['types'][$column]['selections'] = $selections;
	}

	public function removeRow($field, $value){
		$c = count( $this->data );

		for( $i = 0; $i < $c; ++$i ){
			if ( $this->data[$i][$field] == $value ){
				$row = $this->data[$i]['_row'];
				array_splice($this->data, $i, 1);
				--$c;

				$row->removeFromParent();
			}
		}
	}

	private function parseInfo( $row ){
		$this->tableInfo = array();
		
		if ( empty($this->headers) ) {
	    	if ( empty($row) )
	        	$indexs = $headers = array();
	        else
	            $indexs = $headers = array_keys( $row );
	    }else{
	    	$indexs = array_keys($this->headers);
	        $headers = array_values($this->headers);
	    }

	    if ( empty($this->hidden) ) {
	        $hidden = array( reset($indexs) );
	        array_splice($indexs, 0, 1);
	        array_splice($headers, 0, 1);
	    }else{
	      	$hidden = $this->hidden;
	    }

	    if ( empty($this->types) ){
	       	$this->tableInfo['types'] = array();
	    }else{
	    	$this->tableInfo['types'] = $this->types;
	    }

	    $this->tableInfo['hidden'] = $hidden;
	    $this->tableInfo['indexs'] = $indexs;
	    $this->tableInfo['headers'] = $headers;
	}

	public function getName(){
		return $this->name;
	}
	
	public function changeName($name){
		return $this->name = $name;
	}
	
	public function hasChanged(){
		return true; // TODO no idea
	}
	
	public function getValue(){
		// TODO : I need to do this
	}
	
	public function setValue( $value ){
		// TODO : I need to do this
	}
	
	public function setDefaultValue( $value ){
		// TODO : yeah, gotta
	}
	
	public function getInput( \Snap\Node\Form $form ) {
	    $inputs = &$this->inputs;

	    $c = count($inputs);

	    $r = new \Snap\Lib\Form\Data\Complex( $this->name );

	    for ( $i = 0; $i < $c; ++$i ) {
	        $il = $inputs[$i];
	       
	        foreach ( $il as $input ){
	        	$r->add( $input->getInput($form) );
	        }
	    }

	    return $r;
	}

	public function reset(){}
}