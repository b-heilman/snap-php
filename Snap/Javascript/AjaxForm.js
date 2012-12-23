var form_ajax_done = true;

if ( !window.frames['oframe'] ){
	jQuery(document.body).append('<iframe style="display: none;" id="oFrame" name="oFrame"></iframe>');
}
	
jQuery(document.body).delegate("form.ajax-form", 'submit', function(){
	var 
		dis = this,
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
	
	$this
		.attr('target', 'oFrame')
		.attr( 'action', "<?php print AJAX_FORM_SERVICE; ?>" )
		.append( "<input type='hidden' name='ajaxClass' value='"+$this.attr("data-ajax-class")+"'/>" )
		.append( "<input type='hidden' name='ajaxInit' value='"+$this.attr("data-ajax-init")+"'/>" );
		
	jQuery( '#oFrame' )
		.attr( 'src', 'o.O' )
		.load( func ); // clear the frame
});