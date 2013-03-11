;(function($, global){
	var 
		cssLinks = {},
		jsLinks  = {};
	
	// TODO : make this use my bmoor library
	if ( !global.Snap ){
		global.Snap = {};
	}
	
	global.Snap.decodeJson = function( json, display ){
		var
			js = json.js,
			css = json.css;
		
		for( var i = 0; i < js.length; i++ ){
			var 
				link = js[i],
				script = document.createElement( 'script' );
				script.type = 'text/javascript';
				script.src = link;
				
			if ( !jsLinks[link] ){
				jsLinks[link] = true;
				document.head.appendChild( script );
			}
		}

		for( var i = 0; i < css.length; i++ ){
			var 
				link = css[i],
				script = document.createElement( 'link' );
				script.type = 'text/css';
				script.href = link;
				script.rel = 'stylesheet';
				
			if ( !cssLinks[link] ){
				cssLinks[link] = true;
				document.head.appendChild( script );
			}
		}
		
		display();
	};
}( jQuery, this ));