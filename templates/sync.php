<div id="sync-container" class="state-initial" <?php if ( ! get_option( 'ecwid_local_base_enabled', false ) ) {
	echo ' style="display:none"';} ?>>
<?php
$prods = new Ecwid_Products();
$api   = new Ecwid_Api_V3( get_ecwid_store_id() );
?>

<script>
jQuery(document).ready(function() {

jQuery('#sync-container').addClass('no-sse');

jQuery('#ecwid_local_base_enabled').click(function() {
	jQuery('#sync-container').css('display', (jQuery(this).is(':checked')) ? '' : 'none');
});

jQuery('#sync-button').click(function() {
	sync_by_chunks();

	return false;
});

var updatedFrom = '<?php echo esc_js( $estimation['last_update'] ); ?>';

function do_no_sse_sync(mode, offset, limit, time) {
	jQuery.getJSON('admin-post.php?action=ecwid_sync_no_sse&mode=' + mode + '&offset=' + offset + '&limit=' + limit + '&time=' + updatedFrom, {}, process_no_sse_sync);
}

function process_no_sse_sync(data) {
	var mode = '<?php echo $estimation['last_update'] == 0 ? 'updated' : 'deleted'; ?>', offset = 0, limit = 20;

	var processed_updates = data.updated + data.created + data.deleted_disabled;
	var processed_deletes = data.deleted + data.skipped_deleted;

	if ( processed_updates + processed_deletes == 0 ) {
		jQuery('#sync-date').text( data.last_update );
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
		offset = parseInt(data.offset) + parseInt(data.limit);
	}

	do_no_sse_sync(mode, offset, limit, updatedFrom);
}

function update_no_sse_stuff(data) {
	var counters = ['deleted', 'skipped_deleted', 'deleted_disabled'];
	for (var i = 0; i < counters.length; i++) {
		increment_progress_counter(data[counters[i]], 'deleted');
	}
	var counters = ['created', 'updated'];
	for (var i = 0; i < counters.length; i++) {
		increment_progress_counter(data[counters[i]], 'updated');
	}
}

function increment_progress_counter(increment = 1, counter_type) {
	debugger;

	if (increment == 0) {
		return;
	}

	var name = 'count_' + counter_type;

	var css = '#' + name;
	var current = jQuery(css).data('count');
	if (!current) {
		current = increment;
	} else {
		current += increment;
	}
	jQuery(css).data('count', current).text(current);
	jQuery('#' + counter_type + '-progress').show();
}

function do_no_sse_over() {
	jQuery('#sync-container').removeClass('state-in-progress').addClass('state-complete');
	jQuery('#deleted-progress,#updated-progress').hide();
}

jQuery('#sync-button-slow').click(function() {

	jQuery('#sync-container').removeClass('state-initial').addClass('state-in-progress');
	var mode = '<?php echo $estimation['last_update'] == 0 ? 'updated' : 'deleted'; ?>', offset = 0, limit = 100;

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

<?php if ( ! Ecwid_Api_V3::get_token() ) : ?>
<div>
	<?php esc_html_e( 'To enable this feature, the plugin needs a permission to read your store product information.', 'ecwid-shopping-cart' ); ?>
	<a href="<?php echo esc_url( get_reconnect_link() ); ?>"><?php esc_html_e( 'Provide access.', 'ecwid-shopping-cart' ); ?></a>
</div>
<?php else : ?>

<div class="sync-block" id="sync-buttons">
	<a id="sync-button-slow"><?php esc_html_e( 'Synchronize products', 'ecwid-shopping-cart' ); ?></a>
</div>
<div class="sync-block progress-indicator" id="updating">
	<div class="sync-icon">
		<?php ecwid_embed_svg( 'update' ); ?>
	</div>
	<div>
		<?php esc_html_e( 'We\'re synchronizing your products. This may take a few minutes. Please do not reload the page.', 'ecwid-shopping-cart' ); ?>
	</div>
</div>
<div class="sync-block">
	<?php
	echo wp_kses_post(
		sprintf(
			__( 'Deleted products synchronized: %1$s out of %2$s', 'ecwid-shopping-cart' ),
			'<span id="count_deleted">0</span>',
			'<span id="total_deleted">' . ( $estimation['total_deleted'] ) . '</span>'
		)
	);
	?>
</div>

<div class="sync-block" id="updated-progress">
	<?php
	echo wp_kses_post(
		sprintf(
			__( 'Products synchronized: %1$s out of %2$s', 'ecwid-shopping-cart' ),
			'<span id="count_updated">0</span>',
			'<span id="total_updated">' . ( $estimation['total_updated'] ) . '</span>'
		)
	);
	?>
</div>
<div class="sync-block" id="complete">
	<?php esc_html_e( 'Products are successfully synchronized. The product pages are up to date.', 'ecwid-shopping-cart' ); ?>
</div>

<div class="sync-block" id="last-sync-date">
	<?php esc_html_e( 'Last update', 'ecwid-shopping-cart' ); ?>:
	<span id="sync-date">
		<?php if ( $estimation['last_update'] == 0 ) : ?>
			<?php esc_html_e( 'Not synchronized yet', 'ecwid-shopping-cart' ); ?>
		<?php else : ?>
			<?php echo esc_html( ecwid_format_date( $estimation['last_update'] ) ); ?>
		<?php endif; ?>
	</span>
</div>
<?php endif; ?>

</div>
