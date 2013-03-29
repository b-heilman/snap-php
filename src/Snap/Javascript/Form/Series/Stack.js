;(function( $ ){ $(document).ready(function(){	
	$(document.body).on('click', '.form-series-stack-target .form-series-stack-control button.add', function(){
		// TODO : this needs to become a global
		var
			$el = $(this).closest('.form-series-stack'),
			$target = $el.find('.form-series-stack-target'),
			$count = $el.find('.form-series-stack-count'),
			t = $('#'+$el.data('snap-template')).text().replace(/\s*<!\[CDATA\[\s*|\s*\]\]>\s*|[\r\n\t]/g, ''),
			content = $('<div/>').html( t ).text(),
			count = $count.val();
		
		$target.append( $.jqote($.jqotec(content),{'set' : count++}) );
		
		$count.val( count );
	});
}); }( jQuery ));