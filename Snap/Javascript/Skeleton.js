if ( jQuery ){
	jQuery(".skeleton_node .replacement-target").each( function(){
		alert('TODO : need to make a general class all systems can count on to be available as loader is not set');
		jQuery(this.parentNode).load( '$loader', 
			{'frameworkClass' : jQuery(this).data('frameworkClass')}, 
			function(response, status, xhr) {
				if (status == "error") {
					$("#error").html(msg + xhr.status + " " + xhr.statusText);
				}
			}
		);
	});
}