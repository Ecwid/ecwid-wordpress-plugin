jQuery(document).ready(function() {

	if (typeof Ecwid == 'undefined') return;

	refreshEcwidMenuItemsSelection();

	Ecwid.OnPageLoaded.add(function(page) {
		if (page.type == 'CART') {
			window.ecwidCurrentMenuPage = 'cart';
		} else if (page.type == 'ACCOUNT_SETTINGS' || page.type == 'ORDERS' || page.type == 'ADDRESS_BOOK') {
			window.ecwidCurrentMenuPage = 'my-account';
		} else if (page.type == 'SEARCH') {
			window.ecwidCurrentMenuPage = 'product-search';
		} else {
			window.ecwidCurrentMenuPage = 'store';
		}
		
	});

	Ecwid.OnPageLoaded.add(refreshEcwidMenuItemsSelection);

	function refreshEcwidMenuItemsSelection(page) {
		
		$allMenus = jQuery('ul').has('li.menu-item');
		$allMenus.each(function (idx, el) {
			var current = findCurrentEcwidMenuItem(el, page);
			if (current) {
				highlightCurrentMenuItem(el, current);
			}
		});
	}

	function highlightCurrentMenuItem(menu, item) {
		jQuery('.current_page_item', menu).removeClass('current_page_item');
		jQuery('.current-menu-item', menu).removeClass('current-menu-item');

		item.addClass('current-menu-item current_page_item');
	}

	function findCurrentEcwidMenuItem(menuElement, page) {
		
		if (page) {
			var endswith = null;
			if (page.type == 'CATEGORY') {
				if (page.categoryId == 0) {
					endswith = '';
				} else {
					endswith = 'c' + page.categoryId;
                }
			}else if (page.type == 'PRODUCT') {
				endswith = 'p' + page.productId;
			}
			
			if (endswith != null) {
				
				if (endswith == '') {
					endswith = ec.config.baseUrl;
				}
				var selector = '>li a[href*="' + ec.config.baseUrl + '"]';
				var exactCatalogPage = jQuery('>li a[href$="' + endswith + '"][href*="' + ec.config.baseUrl + '"]', menuElement).closest('li');
				if (exactCatalogPage.length > 0) {
					return exactCatalogPage;
				}	
			}
        }
        
		var specificMenuItem = findSpecificMenuItem(menuElement);
		if (specificMenuItem) {
			return specificMenuItem;
		}

		var storeMenuItem = findStoreMenuItem(menuElement);
		if (storeMenuItem) {
			return storeMenuItem;
		}

		return null;
	}

	function findSpecificMenuItem(menuElement) {
		var currentPage = getCurrentEcwidPage();
		var currentMenuItem = null;

		if (['my-account', 'product-search', 'cart'].indexOf(currentPage) != -1) {
			currentMenuItem = jQuery('>li.menu-item-object-' + ecwid_menu_data.items['ecwid-' + currentPage]['classes'], menuElement);
			if (currentMenuItem.length > 0) {
				return currentMenuItem;
			}
		}

		return null;
	}

	function findStoreMenuItem(menuElement) {
		var currentMenuItem = null;

		var storeItems = ['ecwid-store', 'ecwid-store-with-categories'];
		for (var i = 0; i < storeItems.length; i++) {
			currentMenuItem = jQuery('>li.menu-item-object-' + ecwid_menu_data.items[storeItems[i]]['classes'], menuElement);
			if (currentMenuItem.length > 0) {
				return currentMenuItem;
			}
		}

		return null;
	}

	function getCurrentEcwidPage() {
		if (!window.ecwidCurrentMenuPage) {
			window.ecwidCurrentMenuPage = 'store';
		}

		return window.ecwidCurrentMenuPage;
	}
})