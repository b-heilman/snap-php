<?php

namespace Snap\Lib\Markup\Token;

// TODO : ancient code
class Factory implements \Snap\Lib\Token\Factory {

	protected 
		$topLevevl;
	
	public function __construct( $topLevel = true ){
		$this->topLevevl = $topLevel;
	}
	
	public function make( \Snap\Lib\Token\Prototype $proto, $translatorClass ){
		$type = $proto->getType();
		
		if ( !isset($translatorClass) )
			$translatorClass = '\Snap\Lib\Markup\Translator';
			
		if ( $type == '*' || $type == '#' ){
			throw new Exception('this was all old, need to redo');
			
			return $list;
		}elseif( $type == '=' ){
			return $proto->getContent();
		}elseif( $type == '' ){
			$content = $proto->getContent();
			
			if ( is_string($content) ){
				return new \Snap\Node\Core\Text(array(
					'text'  => trim($content), 
					'tag'   => 'p',
					'class' => 'markup-paragraph'
				));
			}elseif( $content instanceof \Snap\Node\Core\Snapable ){
				return $content;
			}elseif( $content instanceof \Snap\Lib\Core\Stack ){
				$rtn = new block_node(array(
					'tag'   => 'p',
					'class' => 'markup-paragraph'
				));
				
				for( $i = 0; $i < $content->count(); $i++ ){
					$el = $content->get($i);
					
					if ( $el instanceof \Snap\Node\Core\Snapable ){
						$rtn->append( $el );
					}elseif( is_string($el) ){
						$rtn->write( $el );
					}
				}
				
				return $rtn;
			}else throw new exception('can not use content of type '.get_class($content));
		}else throw new exception('can not use type "'.get_class($type).'"');
	}
}