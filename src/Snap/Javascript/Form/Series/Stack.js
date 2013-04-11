;(function( $, global ){ $(document).ready(function(){
	// hack
	// TODO : use off
	if ( !global.__stackListen ){
		global.__stackListen = true;
		
		$(document.body).on('click', '.form-series-stack .form-series-stack-control button.add', function(){
			// TODO : this needs to become a global
			var
				$el = $(this).closest('.form-series-stack'),
				$target = $el.find('.form-series-stack-target'),
				$count = $el.find('.form-series-stack-count'),
				t = $('#'+$el.data('snap-template')).text().replace(/\s*<!\[CDATA\[\s*|\s*\]\]>\s*|[\r\n\t]/g, ''),
				content = $('<div/>').html( t ).text(),
				count = $count.val();
			
			$el.removeClass('empty');
			$target.append( $.jqote($.jqotec(content),{'set' : count++}) );
			
			$count.val( count );
		});
	}
}); }( jQuery, this ));