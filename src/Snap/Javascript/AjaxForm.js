;(function( $, global, undefined){
	if ( !window.frames['oframe'] ){
		jQuery(document.body).append('<iframe style="display: none;" id="oFrame" name="oFrame"></iframe>');
	}
		
	$(document.body).delegate("form.ajax-form", 'submit', function(){
		var 
			frame = window.frames['oFrame'],
			$this = jQuery(this),
			func = null;
		
		func = function(){
			var
				form = $(frame.document.body).find('form.ajax-form');
			
			if ( form.length ){
				jQuery('#oFrame').unbind( 'load', func );
				$this.html( form.html() );
			}
		};
		
		$this.attr('target', 'oFrame')
			.attr( 'action', "<?php print $this->page->getAjaxLink('',''); ?>" )
			// It is ok to do that, I am overwriting the __ajaxClass and __ajaxInit
			.append( "<input type='hidden' name='ajaxClass' value='"+$this.attr("data-ajax-class")+"'/>" )
			.append( "<input type='hidden' name='ajaxInit' value='"+$this.attr("data-ajax-init")+"'/>" );
			
		$( '#oFrame' )
			.attr( 'src', 'o.O' ).load( func ); // clear the frame
	});
}( jQuery, this ));