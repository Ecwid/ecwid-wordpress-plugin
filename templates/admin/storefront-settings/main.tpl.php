<?php
// TO-DO вынести в js файл
?>
<script type='text/javascript'>//<![CDATA[
    jQuery(document.body).addClass('ecwid-no-padding');
    jQuery(document.body).css({
    	'font-size': '13px'
    });
    jQuery('#wpbody').css({
    	'background-color': 'rgb(240, 242, 244)'
    });

	// TO-DO remove after update js framework to 1.3.7
    jQuery(document).on('click', '.dropdown-menu__link', function(){
    	jQuery(this).closest('.dropdown-menu').find('.list-dropdown').toggle();
    });

    jQuery(document).ready(function(){

    	jQuery(document).on( 'click', '[data-storefront-status]', function(){
    		var el = jQuery(this),
    			new_status = el.data('storefrontStatus');

    		if( el.hasClass('btn') ) {
    			el.addClass('btn-loading');
    		} else {
    			el.closest('.feature-element__status').find('.dropdown-menu').hide();
    			el.closest('.feature-element__status').find('.iconable-link').show();
    		}

    		var data = {
				action: 'ecwid_storefront_set_status',
				status: new_status
			};

    		jQuery.getJSON(
				'admin-ajax.php',
				data,
				function(data) {
					location.reload();
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
					if( typeof data.reload != 'undefined' ) {
						location.reload();
					}
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
					if( typeof data.reload != 'undefined' ) {
						location.reload();
					}
				}
			);
    		return false;
    	});

    	jQuery(document).on( 'click', '[data-storefront-save-slug]', function(){
    		var slug = jQuery('[name=post_name]').val(),
    			button = jQuery(this),
    			card = jQuery(this).closest('.a-card');

    		button.addClass('btn-loading');

    		var data = {
				action: 'ecwid_storefront_set_page_slug',
				slug: slug
			};

    		jQuery.getJSON(
				'admin-ajax.php',
				data,
				function(data) {
					var fieldset = jQuery('[name=post_name]').closest('.fieldset');

					if( data.status == 'success' ) {
						location.reload();
					}

					if( data.status == 'error' ) {
						fieldset.addClass('has-error');
						fieldset.find('.field__error').text(data.message);
						button.removeClass('btn-loading');
					}
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

					if( data.status == 'success' ) {
						var win = window.open(data.url, '_blank');
	  					win.focus();
					}
				}
			);
    		return false;
    	});

    	jQuery(document).on( 'click', '[data-storefront-show-card]', function(){
    		var card = jQuery(this).data('storefrontShowCard');
    		ecwid_show_storefront_card( jQuery(this), card );
    		return false;
    	});

    	jQuery('[data-storefront-disabled-card]').each(function(){
    		var card = jQuery(this); 

    		card.find('.iconable-block').addClass('iconable-block--disabled');
    		card.find('.status-block').addClass('status-block--disabled');
    		card.find('input').attr('disabled', true);
    		card.find('.btn').attr('disabled', true);
    	});

    	// TO-DO remove after update js framework to 1.3.7
	 //    (function initDropdownMenu() {
		// 	var dropdownMenus = document.querySelectorAll(".dropdown-menu");
		// 	var onDropdownMenuClick = function (e) {
		// 		showDropdown(this);
		// 		e.stopPropagation();
		// 	};
		// 	for (var i = 0; i < dropdownMenus.length; i++) {
		// 		if (dropdownMenus[i].addEventListener) {
		// 			dropdownMenus[i].addEventListener("click", onDropdownMenuClick);
		// 		} else {
		// 			dropdownMenus[i].attachEvent("onclick", onDropdownMenuClick);
		// 		}
		// 	}
		// 	function showDropdown(obj) {
		// 		var listDropdown = obj.parentNode.querySelector(".list-dropdown");
		// 		if (listDropdown) {
		// 			listDropdown.style.display = "block";
		// 		}
		// 	}
		// 	function closeDropdowns() {
		// 		for (var i = 0; i < dropdownMenus.length; i++) {
		// 			var listDropdown = dropdownMenus[i].querySelector(".list-dropdown");
		// 			if (listDropdown) {
		// 				listDropdown.style.display = "none";
		// 			}
		// 		}
		// 	}
		// 	var onDocumentClick = function (e) {
		// 		if (!e.target.classList.contains('.dropdown-menu')) {
		// 			closeDropdowns();
		// 		}
		// 	};
		// 	if (document.addEventListener) {
		// 		document.addEventListener("click", onDocumentClick);
		// 	} else {
		// 		document.attachEvent("click", onDocumentClick);
		// 	}
		// })();

    });

    function ecwid_show_storefront_card( el, need_show_card ) {
    	el.closest('.a-card').hide();
    	jQuery('[data-storefront-card="' + need_show_card + '"]').show();
    }
//]]>
</script>
<?php
// TO-DO вынести в css файл
?>
<style type="text/css">
	.settings-page input[type=checkbox]:disabled:before { content: ''; }
	.settings-page input[type=text] {
		min-height: unset;
	}
	.settings-page input[type=text]:focus, .settings-page select:focus, .settings-page a:focus {
	    border-color: unset;
	    box-shadow: unset;
	    outline: unset;
	}
	.settings-page .field__error { display: none; }
	.settings-page .has-error .field__error { display: block; }
</style>


<div class="settings-page">
	<div class="settings-page__header">
		<div class="settings-page__titles settings-page__titles--left">
			<h1 class="settings-page__title"><?php _e('Your storefront', 'ecwid-shopping-cart'); ?></h1>
			<div class="settings-page__subtitle"></div>
		</div>

		<?php if( $page_status == 'draft' ) {?>
		<div class="a-card a-card--compact a-card--warning">
			<div class="a-card__paddings">
				<div class="iconable-block iconable-block--hide-in-mobile iconable-block--warning">
					<div class="iconable-block__infographics">
						<span class="iconable-block__icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70" focusable="false"><path d="M34.5 67C16.58 67 2 52.42 2 34.5S16.58 2 34.5 2 67 16.58 67 34.5 52.42 67 34.5 67zm0-62C18.23 5 5 18.23 5 34.5S18.23 64 34.5 64 64 50.77 64 34.5 50.77 5 34.5 5z"></path><path d="M34.5 49c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5zM35.5 38.57h-2l-1-14c0-1.17.89-2.07 2-2.07s2 .9 2 2l-1 14.07z"></path></svg></span>
					</div>
					<div class="iconable-block__content">
						<div class="cta-block">
							<div class="cta-block__central">
								<div class="cta-block__title">Store is not accessible on your site.</div>
								<div class="cta-block__content">The store page is in draft. Publish it to make your storefront available for your customers.</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php }?>

		<?php require __DIR__ . '/area-store-page.tpl.php'; ?>

		<?php require __DIR__ . '/area-design.tpl.php'; ?>

		<?php require __DIR__ . '/area-navigation.tpl.php'; ?>

		<?php require __DIR__ . '/area-additional.tpl.php'; ?>

	</div>

</div>

