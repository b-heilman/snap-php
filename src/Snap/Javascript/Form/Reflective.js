;(function( $, global ){
	$(document).ready(function(){
		var 
			iframes = 0;
		
		$(document.body).on('submit', '.form-reflective-wrapper form', function( e ){
			var
				$form = jQuery( this ),
				form = this,
				$wrapper = $form.closest('.form-reflective-wrapper');
	
			if ( !form.iframe ){
				var 
					name = 'reflection_'+(iframes++);
				
				$(document.body).append('<iframe class="reflective" name="'+name+'" style="position: absolute; top: -99999px; left: -99999px;"/>');
				
				form.iframe = global.frames[name];
				form.target = name;
				form.action = $wrapper.data('reflection')+'&__asJson=1';
				
				form.iframe.onload = function(){
					if ( this.document.body.innerHTML != '' ){
						var
							json = $.parseJSON( $(this.document.body).text() );
						
						global.Snap.decodeJson( json, function(){
							console.log( json );
							$wrapper.html( json.content );
						});
					}
				};
			}
		});
	});
}(jQuery, this) );