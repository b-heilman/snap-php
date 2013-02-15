;(function( $, global ){
	$(document).ready(function(){
		var
			dialog = $('<div />').dialog({
				autoOpen : false,
				height   : 700,
				modal    : true
			});
		
		$(document.body).on('click', 'a', function( e ){
			e.stopPropagation();
			e.preventDefault();
			
			$.ajax({
				url      : $(this).attr('href')+'?__contentOnly=1&__asJson=1',
				type     : 'post',
				dataType : 'json',
				success  : function( rtn ){
					dialog.empty();
					
					dialog.html('<div class="overlay-content-wrapper">'+rtn.html+'</div>');
					
					dialog.dialog('open');
				}
			});
			
			return false;
		});
	});
}( jQuery, this ));