;(function( $, global ){
	$(document).ready(function(){
		$(document.body).on('click', 'a', function( e ){
			e.stopPropagation();
			e.preventDefault();
			
			$.ajax({
				url      : $(this).attr('href')+'?contentOnly=1&asJon=1',
				type     : 'post',
				dataType : 'html',
				success  : function( content ){
					
					$('<div class="overlay-content-wrapper">'+content+'</div>').overlay();
				}
			});
			
			return false;
		});
	});
}( jQuery, this ));