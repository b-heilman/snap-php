;(function( $ ){ $(document).ready(function(){	
	$(document.body).on('addNode', '.form-series', function( e, data ){
		// TODO : this needs to become a global
		var
			$el = $(this),
			// need to convert from htmlentities
			t = $('#'+$el.data('snap-template')).text().replace(/\s*<!\[CDATA\[\s*|\s*\]\]>\s*|[\r\n\t]/g, ''),
			content = $('<div/>').html( t ).text();
		
		$el.append( $.jqote($.jqotec(content),data) );
	});
}); }( jQuery ));