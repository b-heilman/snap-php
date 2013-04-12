;(function($, global){
	var 
		cssLinks = {},
		jsLinks  = {};
	
	// TODO : make this use my bmoor library
	if ( !global.Snap ){
		global.Snap = {};
	}
	
	function loadStyleSheet( path, onload ){
		var
			css,
			style,
			sheet,
			interval = null;
		
		style = document.createElement( 'link' );
		style.setAttribute( 'href', path );
		style.setAttribute( 'rel', 'stylesheet' );
		style.setAttribute( 'type', 'text/css' );
		
		if ( style.sheet ){
			sheet = 'sheet';
			css = 'cssRules';
			
			interval = setInterval( function(){
				try{
				//	console.log( style[sheet] );
					if ( style[sheet] && style[sheet][css] && style[sheet][css].length ){
						clearInterval( interval );
						onload();
					}
				}catch( ex ){ /* I feel dirty */ }
			},10 );
		}else{
			// IE specific
			$( style ).bind('load', onload );
		}
		
		$('head').append( style );
	}
	
	global.Snap.decodeJson = function( json, display ){
		var
			js = json.js,
			css = json.css,
			fileCount = 1;
		
		function fileDone(){
			fileCount--;
			if ( fileCount == 0 ){
				display();
			}
		}
		
		if ( json.redirect ){
			window.location = json.request+'/'+json.redirect;
		}else{
		
			if ( js ){
				for( var i = 0; i < js.length; i++ ){
					var 
						link = js[i];
						
					if ( link && !jsLinks[link] ){
						jsLinks[link] = true;
						fileCount++;
						
						$.ajax({ url : link, dataType : 'script', success : fileDone });
					}
				}
			}
			
			if ( css ){
				for( var i = 0; i < css.length; i++ ){
					var 
						link = css[i];
						
					if ( link && !cssLinks[link] ){
						cssLinks[link] = true;
						fileCount++;
						
						loadStyleSheet( link, fileDone );
					}
				}
			}
			fileDone();
		}
	};
}( jQuery, this ));