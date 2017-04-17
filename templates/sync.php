<div id="sync-container" class="state-initial"<?php if (!get_option('ecwid_local_base_enabled', false)) echo ' style="display:none"'; ?>>
<?php
$prods = new Ecwid_Products();
$api = new Ecwid_Api_V3(get_ecwid_store_id());
?>

<script>
jQuery(document).ready(function() {

var sse_available = false;
var set_time_limit_available = <?php echo EcwidPlatform::is_set_time_limit_available() ? 'true' : 'false'; ?>;
if (set_time_limit_available && typeof(EventSource) != 'undefined') {
	sse_available = true;
}

if (sse_available && <?php echo ( get_option( Ecwid_Products::OPTION_NO_SSE ) ? '0' : '1') ?>) {
	jQuery('#sse_on').html('YES');
} else {
	jQuery('#sse_on').html('NO');
	jQuery('#sync-container').addClass('no-sse');
}

jQuery('#ecwid_local_base_enabled').click(function() {
   jQuery('#sync-container').css('display', (jQuery(this).is(':checked')) ? '' : 'none');
});

jQuery('#sync-button').click(function() {
	if (sse_available) {
		sync_sse();
	} else {
		sync_by_chunks();
	}
	return false;
});

function sync_sse() {
	jQuery('#sync-container').removeClass('state-initial').addClass('state-in-progress');

	var source = new EventSource('admin-post.php?action=ecwid_sync_sse');
	source.addEventListener('completed', function(e) {

	    var data = jQuery.parseJSON(e.data);
		jQuery('#sync-container').removeClass('state-in-progress').addClass('state-complete');
		jQuery('#sync-date').text(data.last_update);
		source.close();
	});

    source.addEventListener('created_product', function(e) {
        increment_progress_counter(1);
    });

    source.addEventListener('updated_product', function(e) {
        increment_progress_counter(1);
    });

    source.addEventListener('fetching_products', function(e) {
		var data = jQuery.parseJSON(e.data);

		jQuery('#current_item').text(
			'Fetching products... '
			+ data.offset + ' - '
			+ Math.min(data.offset + data.limit, <?php echo intval($estimation['total_updated']); ?>)
			+ ' of <?php echo intval($estimation['total_updated']); ?>');
	});

	source.addEventListener('fetching_deleted_product_ids', function(e) {
		var data = jQuery.parseJSON(e.data);

		jQuery('#current_item').text('Fetching deleted products... ' + data.offset + ' - ' + Math.min(data.offset + data.limit, <?php echo intval($estimation['total_deleted']); ?>) + ' of <?php echo intval($estimation['total_deleted']); ?>');
	});
}

var updatedFrom = '<?php echo $estimation['last_update']; ?>';

function do_no_sse_sync(mode, offset, limit, time) {
	jQuery.getJSON('admin-post.php?action=ecwid_sync_no_sse&mode=' + mode + '&offset=' + offset + '&limit=' + limit + '&time=' + updatedFrom, {}, process_no_sse_sync);
}

function process_no_sse_sync(data) {
	var mode = 'deleted', offset = 0, limit = 100;

	var processed_updates = data.updated + data.created + data.deleted_disabled;
	var processed_deletes = data.deleted + data.skipped_deleted;

	if (processed_updates + processed_deletes == 0) {
		return do_no_sse_over();
	}

	update_no_sse_stuff(data);

	if (data.total == data.count + data.offset) {
		if (processed_updates > 0) {
			return do_no_sse_sync('updated', data.offset + limit, limit);
		} else {
			mode = 'updated';
		}
	} else {
		if (processed_updates > 0) {
			mode = 'updated';
		}
		offset = data.offset + data.limit;
	}
	if (mode == 'updated') {
		jQuery('#current_item').text('Updating products...');
	} else {
		jQuery('#current_item').text('Deleting products...');
	}
        do_no_sse_sync(mode, offset, limit, updatedFrom);
}

function update_no_sse_stuff(data) {
	var counters = ['created', 'updated', 'deleted', 'skipped_deleted', 'deleted_disabled'];
	for (var i = 0; i < counters.length; i++) {
		increment_progress_counter(data[counters[i]]);
	}
}

function increment_progress_counter(increment = 1) {
    debugger;

    if (increment == 0) {
		return;
	}

	name = 'count_updated';

	var css = '#' + name;
	var current = jQuery(css).data('count');
	if (!current) {
		current = increment;
	} else {
		current += increment;
	}
	jQuery(css).data('count', current).text(current);
}

function do_no_sse_over() {
	jQuery('#sync-container').removeClass('state-in-progress').addClass('state-complete');
}

jQuery('#sync-button-slow').click(function() {

	jQuery('#sync-container').removeClass('state-initial').addClass('state-in-progress');
	var mode = 'deleted', offset = 0, limit = 100;

	jQuery('#current_item').text('Started importing...');

	do_no_sse_sync(mode, offset, limit);

	return false;
});
	jQuery('#sync-button_reset').click(function() {
		location.href='admin-post.php?action=ecwid_sync_reset';
		return false;
	});
});
</script>

<?php if ( !Ecwid_Api_V3::get_token() ): ?>
<div>
    <?php _e( 'To enable this feature, the plugin needs a permission to read your store product information.', 'ecwid-shopping-cart' ); ?>
	<a href="<?php echo get_reconnect_link(); ?>"><?php _e( 'Provide access.', 'ecwid-shopping-cart' ); ?></a>
</div>
<?php else: ?>

<div class="sync-block" id="sync-buttons">
	<a id="sync-button"><?php _e('Synchronize products', 'ecwid-shopping-cart'); ?></a>
	<a id="sync-button-slow"><?php _e('Synchronize products', 'ecwid-shopping-cart'); ?></a>
</div>
<div class="sync-block" id="updating">
	<div class="sync-icon">
		<?php ecwid_embed_svg('update'); ?>
	</div>
	<div>
		<?php _e('We\'re synchronizing your products. This may take a few minutes. Please do not reload the page.', 'ecwid-shopping-cart'); ?>
	</div>
</div>
<div class="sync-block" id="update-progress">
	<?php echo sprintf(__( 'Products synchronized: %s out of %s', 'ecwid-shopping-cart' ),
			'<span id="count_updated">0</span>',
			'<span id="total_updated">' . ($estimation['total_updated']) . '</span>'
		);
	?>
</div>
<div class="sync-block" id="complete">
	<?php _e( 'Products are successfully synchronized. The product pages are up to date.', 'ecwid-shopping-cart' ); ?>
</div>

<div class="sync-block" id="last-sync-date">
	<?php _e( 'Last update', 'ecwid-shopping-cart' ); ?>:
	<span id="sync-date">
        <?php if ( $estimation['last_update'] == 0 ): ?>
            <?php _e( 'Not synchronized yet', 'ecwid-shopping-cart' ); ?>
        <?php else: ?>
            <?php echo ecwid_format_date( $estimation['last_update'] ); ?>
        <?php endif; ?>
    </span>
</div>
<?php endif; ?>

</div>