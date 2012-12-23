<?php

namespace Snap\Lib\Template;

abstract class Token implements \Snap\Lib\Core\Token {

	protected 
		$content = '',
		$requiredVars = array(),
		$vars = array();
		
	abstract public function evaluate();
	
	public function __construct( $content ){
		$this->content = $content = ltrim($content);
		
		preg_match_all('/\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/', $content, $matches);
		$matches = $matches[0];
		
		foreach( $matches as $match ){
			$match = ltrim($match,'$');
			$this->requiredVars[$match] = 1;
		}
	}
	
	public function getContent(){
		return $this->content;
	}
	
	public function __toString(){
		if ( $this->canEvaluate() ){
			return $this->evaluate();
		}else{
			return '';
		}
	}
	
	public function addData( $data, $value='' ){
		if ( is_array($data) ){
			foreach( $data as $var => $value ){
				$this->setVar($var, $value);
			}
		}else{
			$this->setVar($data, $value);
		}
	}
	
	protected function setVar( $var, $value ){
		$this->{'__'.$var} = $value;
		
		if ( isset($this->requiredVars[$var]) )
			unset($this->requiredVars[$var]);
	}
	
	protected function getVars(){
		$vars = array();
		foreach ($this as $key => $value) {
			if ( is_string($key) && $key{0} == '_' && $key{1} == '_' ){
				$vars[ substr($key, 2) ] = $value;
			}
		}
			
		return $vars;
	}
	
	public function getRequirements(){
		return $this->requiredVars;
	}
	
	public function canEvaluate(){
		return empty($this->requiredVars); // The logic is when the requiredVars array has been emptied, all data needed has been set
	}
	
	protected function replaceVariables( $str ){
		return preg_replace('/\$([A-Za-z_][a-zA-Z0-9_\x7f-\xff]*)/', '$this->__${1}', $str);
	}
}