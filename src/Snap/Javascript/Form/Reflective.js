;(function( $, global ){
	$(document).ready(function(){
		var 
			iframes = 0;
		
		$(document.body).on('submit', '.form-reflective-wrapper form', function( e ){
			var
				$form = jQuery( this ),
				form = this,
				$wrapper = $form.closest('.form-reflective-wrapper');
	
			// TODO : I do not like this...
			if ( !global.formAjaxCallbacks ){
				global.formAjaxCallbacks = {};
			}
			
			if ( !form.iframed ){
				var 
					name = 'reflection_'+(iframes++);
				
				form.iframed = true;
				
				global.formAjaxCallbacks[name] = function(){
					var
						iframe = document.getElementById( name );
					
					if ( this.document.body.innerHTML != '' ){
						var
							json = $.parseJSON( $(this.document.body).text() );
						
						global.Snap.decodeJson( json, function(){ $wrapper.html( json.content ); });
					}
					
					iframe.parentNode.removeChild( iframe );
				};
				//console.log(window.parent.formAjaxCallbacks); 
				$(document.body).append('<iframe onload="var win = window.parent; win.formAjaxCallbacks.'+name+'.call( win.frames.'+name+' );" class="reflective" id="'
					+name+'" name="'+name+'" style="position: absolute; top: -99999px; left: -99999px;"/>');
				
				form.target = name;
				form.action = $wrapper.data('reflection')+'&__asJson=1';
			}
		});
	});
}(jQuery, this) );