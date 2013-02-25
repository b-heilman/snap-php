<?php

namespace Snap\Prototype\Topic\Model\Form;

class Create extends \Snap\Model\Form {
	
	public 
		$type;
	
	public function __construct( $type = null ){
		parent::__construct();
	
		if ( !is_null($type) ){
			if ( is_numeric($type) ){
				$type = \Snap\Prototype\Topic\Model\Doctrine\Type::find( (int)$type );
			}elseif ( is_string($type) ){
				$type = \Snap\Prototype\Topic\Model\Doctrine\Type::find(array( 'name' => $type ));
			}else{
				$type = null;
			}
		}
		
		$this->type = $type;
		
		$this->setInputs(array(
			new \Snap\Lib\Form\Input\Basic( 'name', '' )
		));
		
		$this->setValidations(array(
			new \Snap\Lib\Form\Validation\Required( 'name', 'You need to supply a title' )
		));
		
		if ( !$type ){
			// TODO : hash : has to be a better way
			$em = \Snap\Prototype\Topic\Model\Doctrine\Type::getEntityManager();
			$qb = $em->createQueryBuilder();
			
			$hash = array('' => 'Pick A Type');
			
			$qb->select( 't' )
				->from('\Snap\Prototype\Topic\Model\Doctrine\Type', 't')
				->orderBy('t.name',  'ASC');
			$result = $qb->getQuery()->getResult();
			
			for( $i = 0, $c = count($result); $i < $c; $i++ ){
				$obj = $result[$i];
				$hash[ $obj->getId() ] = $obj->getName();
			}
			
			$this->setInputs(array(
				new \Snap\Lib\Form\Input\Optionable( 'type', '', $hash )
			));
				
			$this->setValidations(array(
				new \Snap\Lib\Form\Validation\Generic( 'type', function( $val ){
					return $val !== '';
				}, 'Please pick a type')
			));
		}
	}
}