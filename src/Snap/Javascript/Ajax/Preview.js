;(function( $, global ){
	$(document.body).on('click', '.preview-list a', function( e ){
		var
			root = $(this).closest('.preview-wrapper');
		
		e.stopPropagation();
		e.preventDefault();
		
		$.ajax({
			url      : $(this).attr('href')+'?__contentOnly=1&__asJson=1',
			type     : 'get',
			dataType : 'json',
			success  : function( json ){
				global.Snap.decodeJson( json, function(){
					var
						list = root.find('.preview-list'),
						pane = root.find('.preview-pane'),
						content = root.find('.preview-content');
					
					list.hide();
					pane.show();
					content.html( json.content );
				});
			}
		});
		
		return false;
	});
	
	$(document.body).on('click', '.preview-wrapper .preview-back', function( e ){
		var
			root = $(this).closest('.preview-wrapper');
		
		e.stopPropagation();
		e.preventDefault();
		
		root.find('.preview-list').show();
		root.find('.preview-pane').hide();
	
		return false;
	});
}( jQuery, this ));