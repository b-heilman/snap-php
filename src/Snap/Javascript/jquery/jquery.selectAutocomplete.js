(function( $ ){
	"use strict";

	$.fn.selectAutocomplete = function( settings ){
		var settings = jQuery.extend({}, {
			autocomplete : {},
			textName : 'text',
			makeLabel : function( value, label ){
				return '<span class="autocomplete-label" data-value="'+value+'">'+label+'</span>'
			}
		}, settings);
		
		return this.each(function(){
			var 
				ops,
				options,
				select = this,
				$select = $(this),
				$text = $('<input type="text" name="'+settings.textName+'"/>'),
				$labels = $('<span class="autocomplete-labels"/>'),
				options = [],
				availableTags = [],
				multiMode = $select.attr('multiple') ? [] : null;
			
			// Create the new structure and wrap the element
			$select.wrap( '<div class="select-autocomplete ui-widget"/>' )
				.before( $labels )
				.before( $text );
			
			// unwrap the options and build an index
			$select.find('option').each(function(){
				var 
					value = this.value,
					text  = this.textContent ? this.textContent : this.innerText;
					
				availableTags.push( text );
				options.push({
					value : value,
					label : text
				});
			});
			
			$labels.on('click', '.autocomplete-label', function(){
				var
					$this = $(this);
					
				multiMode.splice( $this.index(), 1 );
				$select.val( multiMode );
				
				$this.remove();
				$text.css( 'text-indent', $labels.width() ).focus();			
			});
			
			$text.autocomplete( jQuery.extend({}, settings.autocomplete, {
				source    : options,        // the select's options
				appendTo  : $select.parent(), // append to the wrapper
				focus: function() {
					// prevent value inserted on focus
					return false;
				},
				select : function( event, ui ){
					if ( multiMode == null ){
						$text.val( ui.item.label );
						$select.val( ui.item.value );
					}else{
						$labels.append( settings.makeLabel(ui.item.value,ui.item.label) );
						multiMode.push( ui.item.value );
						
						$text.val( '' );
						$select.val( multiMode );
						
						$text.css( 'text-indent', $labels.width() );
					}
					
					if ( settings.autocomplete.select != undefined ){
						settings.autocomplete.select( event, ui );
					}
						
					return false;
				}
			}) );
		});
	}
}( jQuery ));