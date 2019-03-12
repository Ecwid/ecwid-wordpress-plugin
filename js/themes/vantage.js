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

Ecwid.OnAPILoaded.add( function() {
	if( jQuery('#scroll-to-top:visible').length > 0 ) {
		var s = jQuery('#scroll-to-top'),
			sb = parseInt(s.css('bottom')),
			sr = parseInt(s.css('right')),
			sh = s.outerHeight(true),
			sw = s.outerWidth(true);

		var c = jQuery('.ec-minicart'),
			cb = c.parent().data('verticalIndent'),
			cr = c.parent().data('horizontalIndent'),
			cw = c.outerWidth(true);

		if( cr <= (sr + sw) && cb <= (sb + sh) ){

			c.css('right', sr);

			s.on('change',function(){
				if( jQuery(this).hasClass('displayed') ){
					c.css('bottom', sb + sh + 10 );
				} else {
					c.css('bottom', cb );
				}
			});

			s.trigger('change');
		}
	}
});