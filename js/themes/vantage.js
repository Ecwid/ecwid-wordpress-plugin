(function(){
    var originalAddClassMethod = jQuery.fn.addClass;
    jQuery.fn.addClass = function(){
        var result = originalAddClassMethod.apply( this, arguments );
        jQuery(this).trigger('change');
        return result;
    }

    var originalRemoveClassMethod = jQuery.fn.removeClass;
    jQuery.fn.removeClass = function(){
        var result = originalRemoveClassMethod.apply( this, arguments );
        jQuery(this).trigger('change');
        return result;
    }

})();

jQuery(document).ready(function() {
    if (typeof Ecwid == 'undefined') return;

	Ecwid.OnAPILoaded.add( function() {
		if( jQuery('#scroll-to-top:visible').length > 0 ) {

			var $scroll = jQuery('#scroll-to-top'),
				scroll_bottom = parseInt($scroll.css('bottom'));
			
			var $cart = jQuery('.ec-minicart'),
				cart_bottom = $cart.parent().data('verticalIndent');

			var overlap = false,
				overlap_init = false;

			$scroll.on('change',function(){
				var rect1 = $scroll.get(0).getBoundingClientRect(),
					rect2 = $cart.get(0).getBoundingClientRect();
				
				if( !overlap_init ) {
					overlap = !(rect1.right < rect2.left || 
		                rect1.left > rect2.right || 
		                rect1.bottom < rect2.top || 
		                rect1.top > rect2.bottom);
				}

				if( overlap ){
					if( jQuery(this).hasClass('displayed') ){
						$cart.css('bottom', rect1.height + scroll_bottom + 10 );
						overlap_init = true;
					} else {
						$cart.css('bottom', cart_bottom );
					}
				}
			});

			$scroll.trigger('change');
		}
	});
});