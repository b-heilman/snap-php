;(function( $, global ){ $(document).ready(function(){
	$(document.body).on('click', '.group-thumbnail a', function(e){
		var
			$this = $(this);
		
		$.ajax({
			url : $this.attr('href'),
			success : function( response ){
				console.log( response );
				$( response ).carousel();
				console.log( 'eh?' );
			}
		});
		
		e.preventDefault();
	});
}); }( jQuery, this ) );