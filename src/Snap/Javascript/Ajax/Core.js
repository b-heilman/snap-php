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
		
		if (document.createStyleSheet){
			style = document.createStyleSheet( link );
		} else {
			var 
				head = document.getElementsByTagName( 'head' )[0];
			
			style = document.createElement( 'link' );
			style.setAttribute( 'href', path );
			style.setAttribute( 'rel', 'stylesheet' );
			style.setAttribute( 'type', 'text/css' );
			
			head.appendChild( style );
		}
		
		if ( style.sheet ){
			sheet = 'sheet';
			css = 'cssRules';
		}else{
			sheet = 'styleSheet';
			css = 'rules';
		}
		
		interval = setInterval( function(){
			try{
				if ( style[sheet] && style[sheet][css] && style[sheet][css].length ){
					clearInterval( interval );
					onload();
				}
			}catch( ex ){ /* I feel dirty */ }
		},10 );
	}
	
	global.Snap.decodeJson = function( json, display ){
		var
			js = json.js,
			css = json.css,
			fileCount = 1;
		
		function fileDone(){
			fileCount--;
			console.log( fileCount );
			if ( fileCount == 0 ){
				display();
			}
		}
		
		for( var i = 0; i < js.length; i++ ){
			var 
				link = js[i];
				
			if ( link && !jsLinks[link] ){
				console.log( link );
				jsLinks[link] = true;
				fileCount++;
				
				$.ajax({ url : link, dataType : 'script', success : fileDone });
			}
		}

		for( var i = 0; i < css.length; i++ ){
			var 
				link = css[i];
				
			if ( link && !cssLinks[link] ){
				console.log( link );
				cssLinks[link] = true;
				fileCount++;
				
				loadStyleSheet( link, fileDone );
			}
		}
		
		fileDone();
	};
}( jQuery, this ));