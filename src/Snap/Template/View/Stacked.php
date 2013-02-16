<?php

if ( $this->uniqueness ){
	$content->makeUnique( $this->uniqueness );
}

if ( $content ){
	$count = $content->count();
	
	if ( $count == 1 ){
		$this->append( $this->createPrimaryView($content->get(0),$content->getVar('factory')) );
	}else{
		$data = $content;
		
		// TODO : this should prolly be moved to the view... but I will let it slide
		if ( $data->hasVar('active') ){
			$active = $data->getVar('active');
			$i = ( $this->listPrev == -1 ? 0 : ($active - $this->listPrev) );
			$c = ( $this->listNext == -1 ? $data->count() : ($active + $this->listNext + 1) );
				
			if ( $this->listTotal != -1 && $this->listTotal < $this->listPrev ) {
				$i = $active - $this->listTotal;
			}
		
			if ( $i < 0 ){
				$i = 0;
			}
				
			if ( $this->listTotal != -1 && $i + $this->listTotal < $c ) {
				$c = $i + $this->listTotal + 1;
			}
				
			if ( $c > $data->count() ){
				$c = $data->count();
			}
		}else{
			$active = -1;
			$i = 0;
			$c = $data->count();
		}
		
		// actually render the list
		?><ol class='stacked-list'><?php
		for( ; $i < $c; ++$i ){
			?><li class='stacked-element'><?php
			$info = $data->get($i);
			$factory = $data->getVar('factory');
			
			if ( $active == $i ){
				if ( $this->listActive ){
					$this->append( $t = $this->createPrimaryView($info, $factory) );
					$t->addClass('active');
				}
			}else{
				$linkInfo = null;
				
				if ( $this->linkVar && isset($info[$this->linkVar]) && $factory != null ){
					$linkInfo = $factory->createLink( $info[$this->linkVar] );
					?><a href="<?php echo $linkInfo['href']; ?>" class="<?php echo $linkInfo['class']; ?>"><?php
				}
				
				$el = $this->append( $this->createSecondaryView($info, $factory) );
		
				if ( $active != -1 ) {
					$pos = $i - $active;
					if ( isset($this->listClasses[$pos]) ){
						$el->addClass( $this->listClasses[$pos] );
					}
				}
				
				if ( $linkInfo != null ){
					 ?></a><?php
				}
			}
			?></li><?php
		}
		?></ol><?php
	}
}else{
	$this->write( 'No content' );
}
