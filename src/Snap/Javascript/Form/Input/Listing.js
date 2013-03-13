alert('hey');
;(function( $ ){ $(document).ready(function(){	
	$(document.body).on('addNode', '.input-listing', function( e, data ){
		var
			$el = $(this);
		
		$el.append( '<li>' + $( '#'+$el.data('snap-template') ).jqote(data) +'</li>' );
	});
}); }( jQuery ));