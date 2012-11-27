<?php
//TODO : what the hell was I on?
namespace Snap\Lib\Core;

class Time {
	protected $time;
	
	public function __construct($time){
		if ( is_numeric($time) ){
			$this->time = $time;
		}else{
			$this->time = strtotime($time);
		}
	}
	
	protected function calcLength($time){
		$split = array();
		
		$split['d'] = (int)($time / 86400);
			
		$time = $time % 86400;
		$split['h'] =  (int)($time / 3600);
		
		$time = $time % 3600;
		$split['m'] =  (int)($time / 60);
		
		$split['s'] = $time % 60;
		
		return $split;
	}
	
	protected function calcSince(){
		return time() - $this->time;
	}
	
	protected function stringTime($split){
		$t = self::stripTime($split['d'], 'day');
		$t .= self::stripTime($split['h'], 'hour');
		$t .= self::stripTime($split['m'], 'minute');
		$t .= self::stripTime($split['s'], 'second');
		
		return $t;
	}
	
	protected function maxTime($split){
		if ( $split['d'] > 0 ){
			return $this->stripTime($split['d'], 'day');
		}
			
		if ( $split['h'] > 0 ){
			return $this->stripTime($split['h'], 'hour');
		}
			
		if ( $split['m'] > 0 ){
			return $this->stripTime($split['m'], 'minute');
		}
			
		return $this->stripTime($split['s'], 'second');
	}
	
	protected function stripTime($time, $type){
		$t = '';
		if ( $time > 0 ){
			$t .= $time." $type";
			if ( $time > 1 ){
				$t .= 's ';
			}else{
				$t .= ' ';
			}
		}
		
		return $t;
	}
	
	public function maxSince(){
		return $this->maxTime( $this->calcLength( $this->calcSince() ) );
	}
}
