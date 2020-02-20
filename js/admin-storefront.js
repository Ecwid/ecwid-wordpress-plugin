jQuery(document).ready(function(){

	jQuery(document).on( 'click', '[data-storefront-status]', function(){
		var el = jQuery(this),
			new_status = el.data('storefrontStatus');

        ecwid_toggle_loading_status( el );

		var data = {
			action: 'ecwid_storefront_set_status',
			status: new_status
		};

		jQuery.getJSON(
			'admin-ajax.php',
			data,
			function(data) {
				ecwid_update_storepage_link( data.storepage );
				ecwid_set_storefront_state( data.storepage.status );
                ecwid_toggle_loading_status( el, true );
			}
		);

		return false;
	});

	jQuery(document).on( 'change', '[data-storefront-save-main-page]', function(){
		var page = jQuery(this).val();

		var data = {
			action: 'ecwid_storefront_set_mainpage',
			page: page
		};

		jQuery.getJSON(
			'admin-ajax.php',
			data,
			function(data) {
				ecwid_update_storepage_link( data.storepage );
			}
		);
		return false;
	});

	jQuery(document).on( 'change', '[data-storefront-checkbox]', function(){
		var setting = jQuery(this).data('storefrontCheckbox'),
			is_checked = jQuery(this).is(':checked'),
			status = (is_checked) ? 1 : 0;

		var data = {
			action: 'ecwid_storefront_set_' + setting,
			status: status
		};

		jQuery.getJSON(
			'admin-ajax.php',
			data,
			function(data) {
				if( typeof data.storepage != 'undefined' ) {
                	ecwid_update_storepage_link( data.storepage );
				}
			}
		);
		return false;
	});

	jQuery(document).on( 'click', '[data-storefront-save-slug]', function(){
		var slug = jQuery('[name=post_name]').val(),
			button = jQuery(this),
			card = jQuery(this).closest('.a-card'),
            fieldset = jQuery('[name=post_name]').closest('.fieldset');

		button.addClass('btn-loading');

        fieldset.removeClass('has-error');
        fieldset.find('.field__error').text('');

		var data = {
			action: 'ecwid_storefront_set_page_slug',
			slug: slug
		};

		jQuery.getJSON(
			'admin-ajax.php',
			data,
			function(data) {
				if( data.status == 'success' ) {
					ecwid_update_storepage_link( data.storepage );
                    card.find('[data-storefront-show-card]').trigger('click');
				}

				if( data.status == 'error' ) {
					fieldset.addClass('has-error');
					fieldset.find('.field__error').text(data.message);
				}

                button.removeClass('btn-loading');
			}
		);
		return false;
	});

	jQuery(document).on( 'click', '[data-storefront-create-page]', function(){
		var button = jQuery(this),
			type = button.data('storefrontCreatePage'),
			item_id = false;

		if( typeof button.data('storefrontItemId') != 'undefined' ) {
			item_id = button.data('storefrontItemId');
		}

		if( !button.hasClass('btn') ) {
			button = button.closest('.btn-group').find('.btn');
		}

		button.addClass('btn-loading');

		var data = {
			action: 'ecwid_storefront_create_page',
			type: type,
			item_id: item_id
		};

		jQuery.getJSON(
			'admin-ajax.php',
			data,
			function(data) {
				button.removeClass('btn-loading');

				if( data.status == 'success' && data.open_page ) {
					var win = window.open(data.url, '_blank');
  					win.focus();
				}

				ecwid_set_storefront_state( data.storepage.status );
			}
		);
		return false;
	});

	jQuery(document).on( 'click', '[data-storefront-show-card]', function(){
		var card = jQuery(this).data('storefrontShowCard');
		ecwid_show_storefront_card( jQuery(this), card );
		return false;
	});

	ecwid_disable_cards( jQuery('.settings-page').data('ecStorefrontStatus') );
});

function ecwid_set_storefront_state( state ) {
    jQuery('[data-ec-storefront-status]').attr('data-ec-storefront-status', state);
    ecwid_disable_cards( state );
}

function ecwid_show_storefront_card( el, need_show_card ) {
    el.closest('.a-card').hide();
    jQuery('[data-storefront-card="' + need_show_card + '"]').show();
}

function ecwid_toggle_loading_status( el, close_dropdown ){
    if( el.hasClass('btn') ) {
        if( typeof close_dropdown != 'undefined' ) {
            el.removeClass('btn-loading');    
        } else {
            el.addClass('btn-loading');
        }
    } else {
        el.closest('.feature-element__status').find('.dropdown-menu').toggle();
        el.closest('.feature-element__status').find('.iconable-link').toggle();

        if( typeof close_dropdown != 'undefined' ) {
            el.closest('.feature-element__status').find('.list-dropdown').hide();
        }
    }
}

function ecwid_disable_cards( status ) {
    jQuery('[data-ec-storefront-disabled-card]').each(function(){
        var card = jQuery(this);

        if( card.data('ecStorefrontDisabledCard') == status ) {
            card.find('.iconable-block').addClass('iconable-block--disabled');
            card.find('.status-block').addClass('status-block--disabled');
            card.find('input').attr('disabled', true);
            card.find('.btn').attr('disabled', true);
        } else {
            card.find('.iconable-block').removeClass('iconable-block--disabled');
            card.find('.status-block').removeClass('status-block--disabled');
            card.find('input').removeAttr('disabled');
            card.find('.btn').removeAttr('disabled');
        }
    });
}

function ecwid_update_storepage_link( storepage ) {
    var old_link = jQuery('[data-ec-store-link]').eq(0).attr('href');

    jQuery('[data-ec-store-slug]').html(storepage.slug);
    jQuery('[data-ec-store-slug-input]').val(storepage.slug);

    jQuery('a').each(function(){
        if( typeof jQuery(this).attr('href') != 'undefined' ) {
            if( jQuery(this).attr('href') == old_link ) {
                jQuery(this).attr('href', storepage.link);
            }

            if( jQuery(this).html() == decodeURI(old_link) ) {
                jQuery(this).html( decodeURI(storepage.link) );
            }
        }
    });
}