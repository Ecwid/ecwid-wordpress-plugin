<div id="sync-container" class="state-initial">
<?php $prods = new Ecwid_Products(); $api = new Ecwid_Api_V3(get_ecwid_store_id()); ?>

<script>
jQuery(document).ready(function() {

var sse_available = false;
var set_time_limit_available = <?php echo EcwidPlatform::is_set_time_limit_available() ? 'true' : 'false'; ?>;
if (set_time_limit_available && typeof(EventSource) != 'undefined') {
	sse_available = true;
}

if (sse_available) {
	jQuery('#sse_on').html('YES');
} else {
	jQuery('#sse_on').html('NO');
	jQuery('#sync-container').addClass('no-sse');
}

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

		jQuery('#sync-container').removeClass('state-in-progress').addClass('state-complete');
		source.close();
	});

	source.addEventListener('start', function(e) {

	});

	source.addEventListener('updated_product', function(e) {
		var data = jQuery.parseJSON(e.data);

		jQuery('#current_item').text(data.product.name + ' id:' + data.product.id + ' sku:' + data.product.sku);
		increment_progress_counter('updated');
	});

	source.addEventListener('deleted_product', function(e) {
		var data = jQuery.parseJSON(e.data);

		jQuery('#current_item').text('Deleted product # ' + data.product.id);

		increment_progress_counter('deleted');
	});

	source.addEventListener('skipped_deleted', function(e) {
		increment_progress_counter('skipped_deleted');
	});


	source.addEventListener('created_product', function(e) {
		var data = jQuery.parseJSON(e.data);

		jQuery('#current_item').text(data.product.name + ' id:' + data.product.id + ' sku:' + data.product.sku);
		increment_progress_counter('created');
	});

	source.addEventListener('deleted_disabled', function(e) {
		increment_progress_counter('deleted_disabled');
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

var updatedFrom = '<?php echo $estimation['updated_from']; ?>';

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
		increment_progress_counter(counters[i], data[counters[i]]);
	}
}

function increment_progress_counter(name, increment = 1) {
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

<?php $api = new Ecwid_Api_V3(); if ( !$api->get_token() ): ?>
<div>
	No token. <a href="<?php echo get_reconnect_link(); ?>">Reconnect</a>
</div>
<?php endif; ?>

<div class="sync-block" id="sync-button">
	<a id="sync-button"><?php _e('Update local db', 'ecwid-shopping-cart'); ?></a>
	<a id="sync-button-slow"><?php _e('Update local db', 'ecwid-shopping-cart'); ?></a>
</div>
<div class="sync-block" id="updating"><?php ecwid_embed_svg('update'); ?><?php _e('The products are being updated. Please, wait a few minutes.', 'ecwid-shopping-cart'); ?></div>

<div class="sync-block" id="update-progress">
	<?php _e(
		sprintf(
			'Products updated %s out of %s',
			'<span id="count_updated">0</span>',
			'<span id="total_updated">' . ($estimation['total_updated'] + $estimation['total_deleted']) . '</span>'
		)
	);
	?>
</div>
<div class="sync-block" id="complete">
	<?php _e( 'The products have been updated successfully', 'ecwid-shopping-cart' ); ?>
</div>

<div class="sync-block" id="sync-error">
	<span class="message"><?php _e( 'We could not update some products', 'ecwid-shopping-cart' ); ?></span>
	<a href=""><?php _e( 'Report error', 'ecwid-shopping-cart' ); ?></a>
</div>

<div class="sync-block" id="last-sync-date">
	<?php _e( 'Last sync date', 'ecwid-shopping-cart' ); ?>:
	<span id="sync_date"><?php echo strftime( '%F', $estimation['last_update_time'] ); ?> </span>
</div>


<div style="display:none">
<button id="sync-button_reset">RESET</button>
<div>set_time_limit + SSE available: <span id="sse_on"></span></div>

<div>
	last update: <?php echo $estimation['last_update']; ?>
</div>

<div>
	last updated product time: <?php echo $estimation['updated_from']; ?>
</div>

<div>
	last deleted product time: <?php echo $estimation['deleted_from']; ?>
</div>

<div>
	total to add/update: <?php echo $estimation['total_updated']; ?>
</div>
<div>
	total to delete: <?php echo $estimation['total_deleted']; ?>
</div>

<div>
	Currently processing: <span id="current_item"></span>
</div>

<div>
	total updated: <span id="updated">0</span>
</div>

<div>
	total deleted: <span id="deleted">0</span>
</div>

<div>
	total created: <span id="created">0</span>
</div>

<div>
	total deleted disabled: <span id="deleted_disabled">0</span>
</div>

<div>
	total skipped deleted: <span id="skipped_deleted">0</span>
</div>
</div>

</div>