(function( $ ){
	"use strict";

	$.fn.selectAutocomplete = function( settings ){
		settings = jQuery.extend({}, {
			autocomplete : {},
			textName : 'text',
			textValue : null,
			makeLabel : function( label, value ){
				return '<span class="autocomplete-label" data-value="'+value+'">'+label+'</span>'
			},
			ignoreValue : null
		}, settings);
		
		return this.each(function(){
			var 
				ops,
				options,
				select = this,
				$select = $(this),
				$text = $('<input type="text" name="'+settings.textName+'" value="'+(settings.textValue?settings.textValue:'')+'"/>'),
				$labels = $('<span class="autocomplete-labels"/>'),
				index = {},
				options = [],
				multiMode = $select.attr('multiple') ? [] : null,
				ignoreValue = settings.ignoreValue;
			
			// Create the new structure and wrap the element
			$select.wrap( '<div class="select-autocomplete ui-widget"/>' )
				.before( $labels )
				.before( $text );
			
			// unwrap the options and build an index
			$select.find('option').each(function(){
				var 
					value = this.value,
					text  = this.textContent ? this.textContent : this.innerText;
					
				if ( ignoreValue === null || value !== ignoreValue ){
					index[text] = value;
					options.push({
						value : value,
						label : text
					});
				}
			});
			
			$labels.on('click', '.autocomplete-label', function(){
				var
					$this = $(this);
					
				multiMode.splice( $this.index(), 1 );
				$select.val( multiMode );
				
				$this.remove();
				$text.css( 'text-indent', $labels.width() ).focus();			
			});

			function addLabel( label, value ){
				$labels.append( settings.makeLabel(label,value) );
				multiMode.push( value );
				
				$text.val( '' );
				$select.val( multiMode );
				
				$text.css( 'text-indent', $labels.width() );
			}
			
			$text.blur(function(){
				var
					t = $text.val(),
					v = index[ t ];
				
				if ( multiMode == null ){
					if ( v == undefined ){
						$select[0].selectedIndex = 0; // assume 0 is throw away
					}else{
						$select.val(v);
					}
				}else{
					if ( v == undefined ){
						$text.val('');
					}else{
						addLabel( t, v );
					}
				}
				
				$select.change();
			});
			
			$text.autocomplete( jQuery.extend({}, settings.autocomplete, {
				source    : options,        // the select's options
				appendTo  : $select.parent(), // append to the wrapper
				focus: function() { return false; },
				select : function( event, ui ){
					if ( multiMode == null ){
						$text.val( ui.item.label );
						$select.val( ui.item.value );
					}else{
						addLabel( ui.item.label,ui.item.value );
					}
					
					$select.change();
					
					if ( settings.autocomplete.select != undefined ){
						settings.autocomplete.select( event, ui );
					}
						
					return false;
				}
			}) );
			
			if ( select.selectedIndex != -1 ){
				if ( multiMode ){
					var
						ops = select.options;
					
					for( var i = 0, l = ops.length; i < l; i++ ){
						var
							op = ops[ i ];
						
						if ( op.selected ){
							addLabel( op.textContent ? op.textContent : op.innerText, op.value );
						}
					}
				}else{
					var
						op = select.options[ select.selectedIndex ];
		
					if ( op && op.value !== ignoreValue ){
						$text.val( op.textContent ? op.textContent : op.innerText, op.value );
					}
					$select.change();
				}
			}
		});
	}
}( jQuery ));