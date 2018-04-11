<script type='text/javascript'>//<![CDATA[
    jQuery(document.body).addClass('ecwid-admin-iframe');

	jQuery(document).ready(function() {
		document.body.className += ' ecwid-no-padding';
		$ = jQuery;
		// Create IE + others compatible event handler
		var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
		var eventer = window[eventMethod];
		var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

		// Listen to message from child window
		eventer(messageEvent,function(e) {
		    if (typeof e.data.height != 'undefined') {
                $('#ecwid-frame').css('height', e.data.height + 'px');
            } else if (
                e.data.action 
                && e.data.action == 'navigationMenuUpdated' 
                && e.data.data && e.data.data.navigationMenuItems 
                && e.data.data.navigationMenuItems.length > 0
                && ecwid_admin_menu.enableAutoMenus
            ) {
		        jQuery.ajax({
                    'url': ajaxurl + '?action=<?php echo Ecwid_Admin::AJAX_ACTION_UPDATE_MENU; ?>',
                    'method': 'POST',
                    'data': {
                        menu: e.data.data.navigationMenuItems
                    },
                    'success': function(result) {
                        jQuery('li[data-ecwid-dynamic-menu]').remove();
                        ecwidAddMenuItems(jQuery.parseJSON(result));
                        ecwidRefreshEcwidMenuItemSelection();
                        if (typeof wpResponsive == 'object' && wpResponsive.activate) {
                            wpResponsive.trigger(); // prevent 'sticky-menu' from being applied incorrectly
                        }
                    }
                });
		        
		        
		        //ecwidUpdateAdminMenus(e.data.data.navigationMenuItems);
            }

                        if (typeof wpResponsive == 'object' && wpResponsive.trigger) {
                            wpResponsive.trigger(); // prevent 'sticky-menu' from being applied incorrectly
                        }
		},false);

		$('#ecwid-frame').attr('src', '<?php echo $iframe_src; ?>');
		ecwidSetPopupCentering('#ecwid-frame');
	});
	//]]>
    
    function ecwidUpdateAdminMenus(menus) {
        
        var result = [];
        for (var i = 0; i < menus.length; i++) {
            var menu = menus[i];
            if (menu.type == 'separator') {
                continue;
            }

            var newItem = {};
            newItem.title = menu.title;
            newItem.slug = menu.path;
            
            if (menu.items) {
                newItem.children = [];
                for (var j = 0; j < menu.items.length; j++) {
                    child = menu.items[j];
                    newItem.children[newItem.children.length] = {
                        'title': child.title,
                        'slug': child.path
                    };
                }
            }
            
            result[result.length] = newItem;
        }
        
        jQuery('li[data-ecwid-dynamic-menu=1]').remove();

        ecwidAddMenuItems(result);
    }

</script>


		<iframe seamless id="ecwid-frame" frameborder="0" width="100%" height="700" scrolling="yes"></iframe>

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery.ajax(
           {
               url: ajaxurl + '?action=<?php echo Ecwid_Store_Page::WARMUP_ACTION; ?>'
           }
       );
    });
</script>
<?php require_once ECWID_PLUGIN_DIR . 'templates/admin-footer.php'; ?>
