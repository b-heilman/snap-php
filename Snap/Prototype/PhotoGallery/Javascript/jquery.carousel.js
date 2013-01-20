;(function( $, global ){
	"use strict";
	
	var
		installed = false,
		carousel = null;
	
	function Carousel(){
		this.$ = $( this.template );
		$(document.body).append( this.$ );
		
		this.$content = this.$.find('.carousel-content');
		this.$overlay = this.$.find('.carousel-overlay');
		
		this.$.data( 'this', this );
		
		this.$.find('.slide-left, .slide-right').each(function() { 
			this.onselectstart = function() { return false; }; 
			this.unselectable = "on";
			
			jQuery(this).css({
				'user-select'         : 'none',
				'-o-user-select'      : 'none', 
				'-moz-user-select'    : 'none',
				'-khtml-user-select'  : 'none',
				'-webkit-user-select' : 'none'
			}); 
		});
	
		if ( !installed ){
			installed = true;
			
			$(document.body).on('click', '.slide-left', function( e ){
				var
					dis = $(this).closest('.carousel').data('this'),
					$el = dis.$active;
					
				if ( $el.prev().length != 0 ){
					var
						left = parseInt(dis.$list.css('left'));
					
					dis.$active.removeClass('active');
					dis.$active = $el.prev();
					dis.$active.addClass('active');
					
					dis.$list.css( 'left', left + dis.width );
				
					console.log( 'prev : '+dis.$active.prev().length );
					if ( dis.$active.prev().length == 0 ){
						dis.$.addClass('no-left');
					}
					
					if ( dis.$active.next().length != 0 ){
						dis.$.removeClass('no-right');
					}
				}
			});
			
			$(document.body).on('click', '.slide-right', function( e ){
				var
					dis = $(this).closest('.carousel').data('this'),
					$el = dis.$active;
					
				if ( $el.next().length != 0 ){
					var
						left = parseInt( dis.$list.css('left') );
					
					dis.$active.removeClass('active');
					dis.$active = $el.next();
					dis.$active.addClass('active');
					
					dis.$list.css( 'left', left - dis.width );
					
					console.log( 'next : '+dis.$active.next().length );
					if ( dis.$active.next().length == 0 ){
						dis.$.addClass('no-right');
					}
					
					if ( dis.$active.prev().length != 0 ){
						dis.$.removeClass('no-left');
					}
				}
			});
			
			$(document.body).on('mousedown', '.carousel-mat', function( event ){
				if ( event.target == this ){
					$(this).closest('.carousel').data('this').hide();
				}
			});
			
			$(document.body).on('click', '.carousel-close', function( event ){
				$(this).closest('.carousel').data('this').hide();
			});
		}
	}
	
	Carousel.prototype.template = '<div class="carousel" style="position: absolute; left: 0px; top: 0px; margin: 0px; padding: 0px; border: none; width: 100%; height: 100%;">'
		+ '<div class="carousel-overlay" style="position: absolute; left: 0px; top: 0px; margin: 0px; padding: 0px; border: none; width: 100%; height: 100%;"/>'
		+ '<div class="carousel-mat" style="position: absolute; left: 0px; top: 0px; margin: 0px; padding: 0px; border: none; width: 100%; height: 100%;">'
			+ '<div class="carousel-viewport">'
				+ '<span class="carousel-close">close</span>'
				+ '<div class="carousel-content-wrapper"><div class="carousel-content"/></div>'
				+ '<span class="slide-left">left</span><span class="slide-right">right</span>'
			+ '</div>'
	+'</div>';
	
	Carousel.prototype.changeSettings = function( settings ){
		
	};
	
	Carousel.prototype.hide = function(){
		this.$.css({
			left : '-9999px',
			top  : '-9999px'
		});
	};

	Carousel.prototype.show = function(){
		this.$.css({
			left : '0px',
			top  : '0px'
		});
	};
	
	Carousel.prototype.add = function( $el ){
		console.log( 'add' );
		if ( typeof($el) == 'string' ){
			$el = $($el);
		}
		
		if ( !$el.is('ul') && !$el.is('ol') ){
			console.log( 'find list' );
			$el = $( $el.find('ul , ol').get(0) );
		}
		
		if ( $el.is('ul') || $el.is('ol') ){
			var
				contentWidth = this.$content.innerWidth(),
				fullWidth = 0;
				
			$el.css({
				position : 'relative',
				left     : '-999999px',
				top      : '0px',
				padding  : '0px',
				margin   : '0px',
				border   : 'none'
			});
			
			this.width = contentWidth;
			
			this.$content.empty();
			this.$content.append( $el );
			this.$list = $el;
			
			// parse the li elements
			this.$active = $el.find('> li').each( function(){
				fullWidth += contentWidth;
				$(this).css({
					display : 'inline-block',
					width   : contentWidth+'px',
					height  : '100%',
					padding : '0px',
					margin  : '0px',
					border  : 'none'
				}).addClass( 'carousel-cell' );
			}).first().addClass('active');
			
			$el.css({
				width : fullWidth+'px',
				left  : '0px'
			}).addClass( 'carousel-menu' );
			
			this.$.addClass('no-left');
			this.show();
		}
	};
	
	$.fn.carousel = function( settings ){
		console.log( 'carousel' );
		if ( carousel == null ){
			carousel = new Carousel();
		}
		
		if ( settings ){
			carousel.changeSettings( settings );
		}
		
		carousel.add( this );
	};
}(jQuery, this));