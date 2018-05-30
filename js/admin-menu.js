
function ecwidRefreshEcwidMenuItemSelection(slug)
{
    if (!slug) {
        slug = ecwidGetCurrentMenuSlug();
    }
    
    if (!slug || slug.indexOf(ecwid_admin_menu.baseSlug) == -1) return;
    
    var parent = jQuery('li#toplevel_page_' + ecwid_admin_menu.baseSlug);
    
    parent.addClass('wp-has-current-submenu').addClass('wp-menu-open');
    
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
            var ecwidMenu = jQuery(this).data('ecwid-menu');

            var link = jQuery(this).closest('li');
            var is3dlevelMenuRoot = link.hasClass('wp-has-submenu3');
            var isOpen = link.hasClass('wp-has-current-submenu3');


            ecwidOpenAdminPage(ecwidMenu.hash);
            history.pushState({}, null, ecwidMenu.url);

            ecwidRefreshEcwidMenuItemSelection();

            jQuery('#wpwrap.wp-responsive-open').removeClass('wp-responsive-open');
            jQuery(this).parents('.opensub').removeClass('opensub');

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

    for (var i in items) {

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
    for (var i in items) {
        
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
    if (jQuery('#ecwid-frame').length > 0) {
        if (jQuery('div.update-nag').length > 0) {
            jQuery('#ecwid-frame').addClass('has-wp-message');
        }
    }

    window.ecwidOpenAdminPage = function (place) {
        jQuery('#ecwid-frame')[0].contentWindow.postMessage(JSON.stringify({
            ecwidAppNs: "ecwid-wp-plugin",
            method: "openPage",
            data: place
        }), "*")
    }


    if ( ecwid_admin_menu.enableAutoMenus ) {
    
        for (var i in ecwid_admin_menu.menu) {
    
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
    ecwidRefreshEcwidMenuItemSelection();
});