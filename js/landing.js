hide_on_loading = '.create-store-button, .create-store-have-account-question';
invisible_on_loading = '.create-store-have-account-link';
show_on_loading = '.create-store-loading, .create-store-loading-note';

hide_on_success = '.create-store-loading, .create-store-loading-note';
show_on_success = '.create-store-success, .create-store-success-note';


jQuery(document).ready(function(){

	jQuery('.create-store-button').click(function() {

	    if (ecwidParams.isWL) {
	        location.href = ecwidParams.registerLink;
	        return;
        }


		var $context = jQuery(this).closest('.ecwid-button');
		jQuery(hide_on_loading + ', ' + invisible_on_loading, $context).fadeTo(150, .01).promise().done(function() {
			jQuery(hide_on_loading, $context).hide();
			jQuery(invisible_on_loading, $context).css('visibility', 'hidden');

			jQuery(show_on_loading, $context).fadeIn(300);
		})

		jQuery.ajax(ajaxurl + '?action=ecwid_create_store',
			{
				success: function(result) {
					var html = result;
					jQuery(hide_on_success, $context).fadeTo(150, .01).promise().done(function() {
						jQuery(hide_on_success, $context).hide();

						jQuery(show_on_success, $context).fadeIn(300);
						setTimeout(function() {
							location.href="admin.php?page=ec-store";
						}, 1000);
					})
				},
				error: function() {
					window.location.href = ecwidParams.registerLink;
				}
			}
		);
	});

});
switch_to_success = function() {

}

var initScrollAnimation = function(selectors){

    var windowHeight = jQuery(window).height();
    var elements = selectors.map(function (name) {
        return jQuery(name);
    });

    jQuery(window).on('scroll', onScroll);

    function onScroll() {

        var windowScrollTop = jQuery(window).scrollTop();
        var windowBottom = (windowScrollTop + windowHeight);

        jQuery.each(elements, function () {
            var element = jQuery(this);
            var elementTop = element.offset().top;
            var elementBottom = (elementTop + element.outerHeight());
            var elementMiddle = elementTop + element.outerHeight()/2;

            if ( (elementBottom >= windowScrollTop) && (elementTop <= windowBottom) ) {
                element.addClass('in-view');
            }
        });
    }
}
//to start: initScrollAnimation(['.selector1', '#selector2']);

jQuery(document).ready(function() {
var userAction = "click";
!function() {
    function t() {
        !function() {
            var t = 0;
            h.each(function() {
                t = Math.max(t, jQuery(this).children().last().css("height", "auto").outerHeight())
            }),
                h.each(function() {
                    var e = jQuery(this).children().last()
                        , o = e.outerHeight();
                    i < 750 ? e.css({
                        "margin-top": 0,
                        "margin-bottom": 0,
                        height: t
                    }) : e.css({
                        "margin-top": (t - o) / 2,
                        "margin-bottom": (t - o) / 2
                    })
                })
        }()
    }
    function e(t) {
        a || n == t || (a = !0,
            c[n].removeClass("selected"),
            jQuery(h[n]).removeClass("selected"),
            n > t ? (t = (t + c.length) % c.length,
                jQuery(h[t]).css({
                    "margin-left": "-10%"
                }).prependTo(s).animate({
                    "margin-left": 0
                }, 300, function() {
                    a = !1
                })) : (t = (t + c.length) % c.length,
                jQuery(h[t]).insertAfter(h[n]),
                jQuery(h[n]).animate({
                    "margin-left": "-10%"
                }, 300, function() {
                    jQuery(this).css("margin-left", 0).appendTo(s),
                        a = !1
                })),
            setTimeout(function() {
                o.css({
                    background: jQuery(h[t]).attr("data-bgcolor"),
                    background: "linear-gradient(to bottom, " + jQuery(h[t]).attr("data-bgcolor") + " 0%, " + jQuery(h[t]).attr("data-bgcolor2") + " 100%)",
                    filter: "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='" + jQuery(h[t]).attr("data-bgcolor") + "', endColorstr='" + jQuery(h[t]).attr("data-bgcolor") + "',GradientType=0 )"
                }),
                    jQuery(h[t]).addClass("selected")
            }, 10),
            c[n = t].addClass("selected"))
    }
    var o = jQuery(".block-banner-gallery");
    if (o.length) {
        var n = 0
            , a = !1
            , i = jQuery(window).width()
            , s = jQuery(".roll", o).on("touchstart", function(t) {
            this.touchStartPosX = t.originalEvent.touches[0].clientX,
                this.touchStartPosY = t.originalEvent.touches[0].clientY
        }).on("touchmove", function(t) {
            this.touchMovePosX = t.originalEvent.changedTouches[0].clientX,
                this.touchMovePosY = t.originalEvent.changedTouches[0].clientY;
            var o = this.touchMovePosX - this.touchStartPosX
                , a = this.touchMovePosY - this.touchStartPosY;
            Math.abs(a) < Math.abs(o) && t.preventDefault(),
            this.touchStartPosX > 0 && this.touchStartPosY > 0 && Math.abs(o) > 100 && (this.touchStartPosX = -1,
                this.touchStartPosY = -1,
                e(o > 0 ? n - 1 : n + 1))
        })
            , r = jQuery(".markers", o)
            , c = []
            , h = s.children().each(function(t) {
            var e = jQuery("<div></div>").appendTo(r);
            c.push(e),
            t || (jQuery(this).addClass("selected"),
                e.addClass("selected"))
        });
        if (c.length > 1) {
            for (var l = 0; l < c.length; l++)
                c[l].on(userAction, function(t) {
                    return function(o) {
                        o.preventDefault(),
                            e(t)
                    }
                }(l));
            r.removeClass("hidden");
            jQuery(".left", o).on(userAction, function(t) {
                t.preventDefault(),
                    e(n - 1)
            }).removeClass("hidden"),
                jQuery(".right", o).on(userAction, function(t) {
                    t.preventDefault(),
                        e(n + 1)
                }).removeClass("hidden")
        }
        jQuery(window).on("resize", t)
    }
}();
})