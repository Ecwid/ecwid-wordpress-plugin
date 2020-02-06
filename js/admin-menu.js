
function ecwidRefreshEcwidMenuItemSelection(slug)
{
    if (!slug) {
        slug = ecwidGetCurrentMenuSlug();
    }
    
    if (!slug || slug.indexOf(ecwid_admin_menu.baseSlug) == -1) return;
    
    var parent = jQuery('li#toplevel_page_' + ecwid_admin_menu.baseSlug);

    parent.addClass('wp-has-current-submenu').addClass('wp-menu-open');
    parent.find('a.toplevel_page_ec-store').addClass('wp-has-current-submenu').addClass('wp-menu-open');
    
    var selector = 'a[data-ecwid-menu-slug="' + slug + '"]';
    if (jQuery(selector).length == 0) {
        selector = 'a[data-ecwid-menu-slug="' + decodeURI(slug) + '"]';
    }
    

    jQuery('.current', parent).removeClass('current');
    jQuery('.wp-has-current-submenu3', parent).removeClass('wp-has-current-submenu3');
    
    jQuery(selector, parent)
        .addClass('current')
        .closest('li')
        .addClass('current')
        .closest('.wp-has-submenu3').addClass('wp-has-current-submenu3');

    ecwidSetCurrentPageTitle( selector );
}

function ecwidSetCurrentPageTitle(selector) {
    var delimiter = String.fromCharCode(8249),
        title_splited = document.title.split( delimiter ),
        title = jQuery(selector).last().text();

    if( title_splited.length ) {
        title += ' ' + delimiter + ' ' + title_splited[title_splited.length-1];
    }
    document.title = title;
}

function ecwidGetCurrentMenuSlug()
{
    var query_parts = location.search.split('&');
    var slug = null;
    for (var i = 0; i < query_parts.length; i++) {
        var param = query_parts[i];
        if (i == 0 && param.startsWith('?')) {
            param = param.substr(1);
        }

        ecwidPagePrefix = 'page=';

        if (!param.startsWith(ecwidPagePrefix)) continue;

        slug = param.substr(ecwidPagePrefix.length);
    }
    
    return slug;
}


function ecwidApplyIframeAdminMenu($link, menu) {

    $link
    .data('ecwid-menu', menu)
    .attr('data-ecwid-menu-slug', menu.slug)
    .click(function () {
        if ( jQuery(this).hasClass('current') ) {
            return false;
        }

        if( ecwid_params.is_demo_store ) {
            location.href = jQuery(this).attr('href');
            return false;
        }

        var ecwidMenu = jQuery(this).data('ecwid-menu');

        var link = jQuery(this).closest('li');
        var is3dlevelMenuRoot = link.hasClass('wp-has-submenu3');
        
        var isOpen = jQuery('li.current').closest('.toplevel_page_ec-store').length > 0;
        
        var slug = jQuery(this).data('ecwid-menu-slug');

        if( slug == 'ec-storefront-settings' ) {
            jQuery('#ec-storefront-settings').show();
            jQuery('#ecwid-frame').hide();

            jQuery(document).scrollTop(0);
        } else {
            jQuery('#ecwid-frame').show();
            jQuery('#ec-storefront-settings').hide();

            ecwidOpenAdminPage(ecwidMenu.hash);
        }

        history.pushState({}, null, ecwidMenu.url);

        ecwidRefreshEcwidMenuItemSelection();

        jQuery('#wpwrap.wp-responsive-open').removeClass('wp-responsive-open');
        jQuery(this).parents('.opensub').removeClass('opensub');

        if ( !isOpen ) return true;
        
        return false;
    });
}

function ecwidAddSubmenu(items, parent) {
    var $parent = jQuery(parent);
    var $parentListItem = $parent.closest('li');

    var $parentList = jQuery('<ul class="wp-submenu3 wp-submenu3-wrap">');

    $parentListItem.addClass('wp-has-submenu3');
    $parentListItem.append($parentList);

    if ($parentListItem.find('a').hasClass('current')) {
        $parentListItem.addClass('wp-has-current-submenu3');
    }

    for (var i = 0; i < items.length; i++) {

        var item = items[i];
        var $link = jQuery('<a>').text(item.title).attr('href', item.url);

        jQuery('<li>').append($link).appendTo($parentList);
        ecwidApplyIframeAdminMenu($link, item);
    }

    $parent.closest('li').on('touchstart', function(e) {
        var link = jQuery(this);

        if (!link.hasClass('opensub') && link.hasClass('wp-has-submenu3')) {
            link.addClass('opensub');
            e.preventDefault();
            return false;
        }
    }).mouseover(function () {
        jQuery(this).addClass('opensub');
    }).mouseout(function () {
        jQuery(this).removeClass('opensub');
    });
}

function ecwidAddMenuItems(items) {

    var prevItem = jQuery('#toplevel_page_ec-store .wp-submenu-head + li');
    for (var i = 0; i < items.length; i++) {
        
        if (!items.hasOwnProperty(i)) continue;
        
        var menuItem = items[i];
        
        var listItem = jQuery('<li>').insertAfter(prevItem);
        var a = jQuery('<a>').data('ecwid-url', menuItem.hash).text(menuItem.title).appendTo(listItem);
        ecwidApplyIframeAdminMenu(a, menuItem);
        listItem.attr('data-ecwid-dynamic-menu', 1);

        if (menuItem.type == 'separator') {
            listItem.addClass('ec-separator');
        }
        if (menuItem.children) {
            ecwidAddSubmenu(menuItem.children, a);
        }
        prevItem = listItem;
    }
}

jQuery(document).ready(function() {

    var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
    var eventer = window[eventMethod];
    var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
    var previous_frame_page;

    // Listen to message from child window
    eventer(messageEvent,function(e) {

        if (typeof e.data.height != 'undefined') {
            jQuery('#ecwid-frame').css('height', e.data.height + 'px');
        } 

        if ( typeof e.data.action != 'undefined') {

            if ( e.data.action == 'pageLoad' ) {
                var adminpage = e.currentTarget.adminpage;
                var page = e.data.data.page.path;

                if( adminpage.indexOf(ecwid_admin_menu.baseSlug) != -1 ) {

                    jQuery('*[data-ecwid-menu-slug="ec-store-admin-' + page + '"]').eq(0).click();

                } 

                if( adminpage == 'plugin-install-php' ) {
                    if( page.indexOf('apps:view=app&name=') != -1 ) {
                        var admin_page_app = 'admin.php?page=ec-store-admin-my_apps&ec-store-page=';
                        window.open( admin_page_app + encodeURIComponent(page), '_blank' );

                        var frame_src = jQuery('#ecwid-frame')
                                    .attr( 'src' )
                                    .replace( /(&place=).*?(&)/i, '$1' + previous_frame_page + '$2' );
                        jQuery('#ecwid-frame').attr( 'src', frame_src );
                    } else {
                        previous_frame_page = page;
                    }
                }
            } else if (
                e.data.action
                && e.data.action == 'navigationMenuUpdated'
                && e.data.data && e.data.data.navigationMenuItems
                && e.data.data.navigationMenuItems.length > 0
                && ecwid_admin_menu.enableAutoMenus
            ) {
                jQuery.ajax({
                    'url': ajaxurl + '?action=' + ecwid_admin_menu.actionUpdateMenu,
                    'method': 'POST',
                    'data': {
                        menu: e.data.data.navigationMenuItems
                    },
                    'success': function(result) {
                        jQuery('li[data-ecwid-dynamic-menu]').remove();
                        ecwidAddMenuItems(jQuery.parseJSON(result));
                        ecwidRefreshEcwidMenuItemSelection();
                        jQuery(window).trigger('resize');
                    }
                });
            }
        }
    },false);


    if (jQuery('#ecwid-frame').length > 0) {
        if (jQuery('div.update-nag').length > 0) {
            jQuery('#ecwid-frame').addClass('has-wp-message');
        }
    }

    window.ecwidOpenAdminPage = function (place) {
        if (jQuery('#ecwid-frame').length < 1) {
            return;
        }
        
        jQuery('#ecwid-frame')[0].contentWindow.postMessage(JSON.stringify({
            ecwidAppNs: "ecwid-wp-plugin",
            method: "openPage",
            data: place
        }), "*")
    }


    if ( ecwid_admin_menu.enableAutoMenus ) {
    
        for (var i = 0; i < ecwid_admin_menu.menu.length; i++) {
    
            var menu = ecwid_admin_menu.menu[i];
            
            var $link = jQuery('li.toplevel_page_ec-store .wp-submenu a[href$="' + menu.url + '"]');
            $link.closest('li').attr('data-ecwid-dynamic-menu', 1);
            ecwidApplyIframeAdminMenu($link, menu);
    
            if (menu.children) {
                ecwidAddSubmenu(menu.children, $link);
            }
        }

        var $link = jQuery('li.toplevel_page_ec-store .wp-submenu a[href="admin.php?page"]');
        $link.closest('li').attr('data-ecwid-dynamic-menu', 1);
        $link.click(function() { return false; });

        var $link = jQuery('li.toplevel_page_ec-store .wp-submenu a[href="admin.php?page=ec-store"]');
        ecwidApplyIframeAdminMenu($link, {slug:'ec-store', url: 'admin.php?page=ec-store', hash:'dashboard'});

    }
    
    if ( jQuery( '#calypsoify_wpadminmods_css-css' ).length > 0 ) {
        jQuery('#toplevel_page_ec-store').addClass('wpcom-menu');
    }
    if ( jQuery( '#toplevel_page_ec-store .wp-submenu3 li.current' ).length > 0 ) {
        jQuery('#toplevel_page_ec-store > a').addClass('wp-has-current-submenu');
    }
    ecwidRefreshEcwidMenuItemSelection();
});