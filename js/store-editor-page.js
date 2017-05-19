jQuery(document).ready(function() {
	$popup = jQuery('#ecwid-store-popup-content');

	/*
	 * Media buttons handlers
	 */
	jQuery('#update-ecwid-button,#insert-ecwid-button').click(ecwid_open_store_popup);

	/*
	 * Close button handler
	 */
	jQuery('.media-modal-close', $popup).click(function() {
		$popup.removeClass('open');
		return false;
	});

	jQuery(document).keydown(function(e) {
		if (e.keyCode == 27 && $popup.hasClass('open')) {
			$popup.removeClass('open');
			return false;
		}
	});


	/**
	 * Builds params object from the wp.shortcode
	 *
	 * @param shortcode
	 * @returns {*}
	 */
	buildParams = function(shortcode) {
		if (!shortcode) return {};

		var attributes = jQuery.extend({}, shortcode.shortcode.attrs.named);

		if (jQuery.inArray(attributes.category_view, ['grid', 'list', 'table']) == -1) {
			attributes.category_view = undefined;
		}

		if (!jQuery.inArray(attributes.search_view, ['grid', 'list', 'table']) == -1) {
			attributes.search_view = undefined;
		}

		var defaults = getDefaultParams();

		if (!attributes.grid || attributes.grid.match(/^\d+,\d+$/) === null) {
			attributes.grid = defaults.grid_columns + ',' + defaults.grid_rows;
		}

		var grid = attributes.grid.match(/^(\d+),(\d+)/);
		attributes.grid_rows = grid[1];
		attributes.grid_columns = grid[2];

		for (var i in {'categories_per_row': defaults.categories_per_row, 'list': defaults.list_rows, 'table': defaults.table_rows, 'grid_rows': defaults.grid_rows, 'grid_columns': defaults.grid_columns, 'default_category_id': 0}) {
			parsed = parseInt(attributes[i]);
			if (isNaN(parsed) || parsed < 0) {
				attributes[i] = undefined;
			}
		}

		var widgets = attributes.widgets;
		if (typeof widgets == 'undefined') {
			widgets = "productbrowser search categories minicart";
		}

		widgets = widgets.split(/[^a-z^A-Z^0-9^-^_]/);

		return {
			'show_search': jQuery.inArray('search', widgets) != -1,
			'show_categories': jQuery.inArray('categories', widgets) != -1,
			'show_minicart': jQuery.inArray('minicart', widgets) != -1,
			'categories_per_row': attributes.categories_per_row,
			'category_view': attributes.category_view,
			'search_view': attributes.search_view,
			'list_rows': attributes.list,
			'table_rows': attributes.table,
			'grid_rows': grid[1],
			'grid_columns': grid[2],
			'default_category_id': attributes.default_category_id,
			'minicart_layout': 'attachToCategories'
		};

	}


	/*
	 * Returns default parameters object
	 */
	getDefaultParams = function() {
		return {
			'show_search': true,
			'show_minicart': true,
			'show_categories': true,
			'categories_per_row': 3,
			'grid_rows': ecwid_pb_defaults.grid_rows,
			'grid_columns': ecwid_pb_defaults.grid_columns,
			'table_rows': ecwid_pb_defaults.table_rows,
			'list_rows': ecwid_pb_defaults.list_rows,
			'default_category_id': 0,
			'category_view': 'grid',
			'search_view': 'list',
			'minicart_layout': 'MiniAttachToProductBrowser'
		}
	}

	/*
	 * Tests whether there is a valid store shortcode
	 */
	checkEcwid = function() {

		var hasEcwid = false;
		if (typeof tinyMCE != 'undefined' && tinyMCE.activeEditor && !tinyMCE.activeEditor.isHidden()) {
			content = tinyMCE.activeEditor.getBody();

			hasEcwid = jQuery(content).find('.ecwid-store-editor').length > 0;
		} else {
			hasEcwid = ecwid_get_store_shortcode(jQuery('#content').val());
		}

		if (hasEcwid) {
			jQuery('.wp-media-buttons').addClass('has-ecwid');
		} else {
			jQuery('.wp-media-buttons').removeClass('has-ecwid');
		}
		if (typeof tinymce != 'undefined' && tinymce.activeEditor && !tinymce.activeEditor.isHidden()) {
			var body = tinymce.activeEditor.dom.doc.body;
			var button = tinymce.activeEditor.dom.select('#ecwid-edit-store-button');

			if (hasEcwid && button.length == 0) {
				var button = jQuery('<input type="button" id="ecwid-edit-store-button" contenteditable="false" data-mce-bogus="true" value="' + ecwid_i18n.edit_store_appearance + '" />')
						.appendTo(body);

				button.click(ecwid_open_store_popup);
			} else if (!hasEcwid && button.length > 0) {
				tinymce.activeEditor.dom.remove(button);
			}

			if (hasEcwid) {
				var store = jQuery(body).find('.ecwid-store-editor');
				var button = jQuery('#ecwid-edit-store-button', body);

				var width = this.buttonWidth;
				if (!width) {
					width = button.outerWidth();
					this.buttonWidth = width;
				}
				button.css({
					'position': 'absolute',
					'top': '' + (store.offset().top + 168) + 'px',
					'left': '' + (store.offset().left + store.outerWidth() / 2 - width / 2 - 2) + 'px'
				});
			}

			jQuery('#wp_editbtns').css('display', 'none !important');
		}

		if (window.location.search.indexOf('show-ecwid=true') != -1 && typeof this.show_ecwid_processed == 'undefined') {
			ecwid_open_store_popup();
			this.show_ecwid_processed = true;

			if (tinymce.activeEditor) {
				tinymce.activeEditor.plugins.ecwid.addToolbar();
			}
		}
	}

	setInterval(checkEcwid, 1000);

	jQuery('#content-tmce').click(function() {
		checkEcwid()
	});
	/*
	 * Handles media modal menus
	 */
	jQuery('.media-menu-item', $popup).click(function() {
		jQuery('.media-menu .media-menu-item', $popup).removeClass('active');
		jQuery(this).addClass('active');

		jQuery('.media-modal-content', $popup).attr('data-active-dialog', jQuery(this).attr('data-content'));
		jQuery('.media-menu').removeClass('visible');
		return false;
	});

	jQuery('h1', $popup).click(function() {
		jQuery('.media-menu').toggleClass('visible');
	})

	/*
	 * Main button click
	 */
	jQuery('.button-primary', $popup).click(function() {

		var result = {}, defaults = getDefaultParams();

		result.widgets = 'productbrowser';
		for (var i in {search:1, categories:1, minicart:1}) {
			if (jQuery('input[name=show_' + i + ']').prop('checked')) {
				result.widgets += ' ' + i;
			}
		}

		getNumber = function(name, fallback) {
			var value = parseInt(jQuery('[name=' + name + ']', $popup).val());

			if (isNaN(value) || value < 0) {
				value = fallback;
			}

			return value;
		}

		getString = function(name, values, fallback) {
			var value = jQuery('[name=' + name + ']', $popup).val();

			if (jQuery.inArray(value, values) == -1) {
				value = fallback;
			}

			return value;
		}

		result.categories_per_row = getNumber('categories_per_row', defaults.categories_per_row);
		result.grid = getNumber('grid_rows', defaults.grid_rows) + ',' + getNumber('grid_columns', defaults.grid_columns);
		result.list = getNumber('list_rows', defaults.list_rows);
		result.table = getNumber('table_rows', defaults.table_rows);
		result.default_category_id = getNumber('default_category_id', defaults.default_category_id);
		result.category_view = getString('category_view', ['list', 'grid', 'table'], defaults.category_view);
		result.search_view = getString('search_view', ['list', 'grid', 'table'], defaults.search_view);
		result.minicart_layout = defaults.minicart_layout;


		var existingShortcode = ecwid_get_store_shortcode(jQuery('#content').val());
		var shortcode = {};
		if (!existingShortcode) {
			shortcode.shortcode = new wp.shortcode();
			shortcode.shortcode.tag = ecwid_params.store_shortcode;
			shortcode.shortcode.type = 'single';
		} else {
			shortcode = existingShortcode;
		}

		for (var i in result) {
			shortcode.shortcode.attrs.named[i] = result[i];
		}

		if (existingShortcode) {
			jQuery('#content').val(
				jQuery('#content').val().replace(existingShortcode.content, shortcode.shortcode.string())
			);
			if (tinyMCE.activeEditor) {
				jQuery(tinymce.activeEditor.getBody()).find('.ecwid-store-editor').attr('data-ecwid-shortcode', shortcode.shortcode.string());
			}
		} else {

			if (tinymce.activeEditor && !tinymce.activeEditor.isHidden()) {
				tinymce.activeEditor.execCommand('mceInsertContent', false, shortcode.shortcode.string());
				tinymce.activeEditor.execCommand('mceSetContent', false, tinymce.activeEditor.getBody().innerHTML);
			} else {

				getCursorPosition = function(el) {
					var pos = 0;
					if('selectionStart' in el) {
						pos = el.selectionStart;
					} else if('selection' in document) {
						el.focus();
						var Sel = document.selection.createRange();
						var SelLength = document.selection.createRange().text.length;
						Sel.moveStart('character', -el.value.length);
						pos = Sel.text.length - SelLength;
					}
					return pos;
				}

				var el = jQuery('#content');
				var cursorPosition = getCursorPosition(el.get(0));

				el.val(el.val().substr(0, cursorPosition) + shortcode.shortcode.string() + el.val().substr(cursorPosition));

			}
		}


		jQuery('#ecwid-store-popup-content').removeClass('open');
	});

	updatePreview = function() {
		jQuery('.store-settings input[type=checkbox]', $popup).each(function(idx, el) {
			var widget = jQuery(el).parent().attr('data-ecwid-widget');
			var preview = jQuery('.store-settings-preview svg path.' + widget, $popup);
			if (jQuery(el).prop('checked')) {
				jQuery('.store-settings-wrapper').addClass('ecwid-' + widget);
			} else {
				jQuery('.store-settings-wrapper').removeClass('ecwid-' + widget);
			}
		});
	}

	jQuery('.store-settings-wrapper label', $popup).hover(
		function() {
			jQuery('.store-settings-wrapper').attr('data-ecwid-widget-hover', jQuery(this).attr('data-ecwid-widget'));
		},
		function() {
			jQuery('.store-settings-wrapper').attr('data-ecwid-widget-hover', '');
		}
	);

	jQuery('.store-settings input[type=checkbox]', $popup).change(updatePreview);
});

ecwid_open_store_popup = function() {

	var shortcode;

	if (tinyMCE.activeEditor && !tinyMCE.activeEditor.isHidden()) {
		tinyMCE.activeEditor.save();

		var content = jQuery(tinyMCE.activeEditor.getBody())
				.find('.ecwid-store-editor')
				.attr('data-ecwid-shortcode');

		var shortcode = ecwid_get_store_shortcode(window.decodeURIComponent(content));
	} else {
		shortcode = ecwid_get_store_shortcode(jQuery('#content').val());
	}

	$popup.addClass('open');

	params = {};
	jQuery.extend(params, getDefaultParams(), buildParams(shortcode));

	for (var i in params) {
		var el = jQuery('[name=' + i + ']', $popup);
		if (el.attr('type') == 'checkbox') {
			el.prop('checked', params[i]);
		} else {
			el.val(params[i]);
		}
	}

	// mode determines whether it is a new store or not, and active dialog is the current tab
	// in other words, mode = [add-store,store-settings] and active dialog is [add-store|store-settings, appearance]
	// buttons and menu items are for mode, current title and content are for dialog
	var current = !shortcode ? 'add-store' : 'store-settings';
	jQuery('.media-modal-content', $popup).attr('data-mode', current);
	jQuery('.media-modal-content', $popup).attr('data-active-dialog', current);
	jQuery('.media-menu-item')
			.removeClass('active')
			.filter('[data-content=' + current + ']').addClass('active');

	updatePreview();

	if (tinymce.activeEditor && !tinymce.activeEditor.isHidden()) {
		tinyMCE.activeEditor.execCommand('SelectAll');
		tinyMCE.activeEditor.selection.collapse(true);
	}

	return false;
};
