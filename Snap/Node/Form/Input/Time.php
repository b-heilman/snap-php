<?php

namespace Snap\Node\Form\Input;

class Time extends \Snap\Node\Core\Block 
	implements \Snap\Node\Form\WrappableInput {
	
	protected 
		$name, 
		$value,
		$wrapper;
	
	public function __construct( $settings = array() ){
		if ( isset($settings['class']) ){
			$settings['class'] .= ' time_input';
		}else{
			$settings['class'] = 'time_input';
		}
		
		$settings['tag'] = 'span';
		
		parent::__construct( $settings );
		
		if ( isset($settings['name']) ){
			$this->name = $name = $settings['name'];
		}else{
			throw new \Exception( get_class($this).' requires a name' );
		}
		
		if ( isset($settings['value']) ){
			$value = $settings['value'];
			
			preg_match("/(\d{1,2}):(\d{2}) ?(AM|PM)?/i", $value, $match);
			$hour = $match[1];
			$min = $match[2];
			
			if ($hour > 12 || count($match) < 3){
				if ($hour > 12){
					$hour -= 12;
					$toggle = 'PM';
				}else{
					$toggle = 'AM';
				}
			}else{
				$toggle = strtoupper( $match[3] );
			}
		}else{
			$hour = 12;
			$min = 0;
			$toggle = 'AM';
		}
		
		if ( $hour == 0 ){
			$hour = 12;
		}
		
		$this->value = new \Snap\Lib\Form\Input( $this->name, $this->buildTime($hour, $min, $toggle) );
		
		$this->append( $this->hour = new \Snap\Node\Form\Input\Text(array(
			'mode'  => 'numeric', 
			'name'  => $name.'_hour',
			'value' => $hour,
			'size'  => 2
		)) );
			
		$this->write(':');
		
		$this->append( $this->min = new \Snap\Node\Form\Input\Text(array(
			'mode'  => 'numeric', 
			'name'  => $name.'_min',
			'value' => sprintf('%02d', $min),
			'size'  => 2
		)) );
			
		$this->append( $this->tog = new \Snap\Node\Form\Input\Select(array(
			'name'    => $name.'_tog', 
			'options' => array('AM'=>'AM','PM'=>'PM'), 
			'value'   => $toggle
		)) );
	}

	public function setWrapper( \Snap\Node\Core\Snapable $node ){
		$this->wrapper = null;
	}
	
	public function getWrapper(){
		return $this->wrapper == null ? $this : $this->wrapper;
	}
	
	public function getType(){
		return 'time';
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function changeName($name){
		$this->name = $name;
	}
	
	public function hasChanged(){
		$this->value->hasChanged();
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'name' => 'the base name of the inputs used for time',
			'value' => 'the time you want in the input'
		);
	}
	
	public function reset(){
		//I don't need to do anything, but my children do
		$this->hour->reset();
		$this->min->reset();
		$this->tog->reset();
	}

	public function getInput( \Snap\Node\Core\Form $form ){
	    $h = $this->hour->getInput( $form );
		$hour = ( (int)$h->getValue() );
		if ( $h->error ){
			$this->value->setError( 
				new \Snap\Lib\Form\Error\Simple('hour '.$h->error)
			);
		}
		
		$m = $this->min->getInput( $form );
		$min = ( (int)$m->getValue() );
		if ( $m->error ){
			$this->value->setError( 
				new \Snap\Lib\Form\Error\Simple('minutes '.$m->error)
			);
		}
		
	    if (  $hour < 1 || 12 < $hour ){
	        $this->value->setError( 
	        	new \Snap\Lib\Form\Error\Simple('hour not rational')
	        );
	    }
	    
	    if (  $min < 0 || 59 < $min ){
	    	$this->value->setError(
	    		new \Snap\Lib\Form\Error\Simple('minutes not rational')
	    	);
	    }

		$t = $this->tog->getInput( $form );

		if ( $hour == 12 ){
			$hour = 0;
		}

		$this->value->setValue( $this->buildTime($hour + ($t->getValue() == 'PM'?12:0), $min) );

		return $this->value;
	}
	
	public function getValue(){
		return $this->value->getValue();
	}
	
	public function setValue( $value ){
		$this->value->setValue( $value );
	}
	
	public function setDefaultValue( $value ){
		$this->value->setDefaultValue( $value );
	}
	
	public function buildTime( $hour, $min, $toggle = null ){
		if ( $toggle == 'PM' )
			$hour += 12;
			
		return sprintf('%02d', $hour);':'.sprintf('%02d', $min);
	}
}
