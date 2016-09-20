<hr />
<?php $prods = new Ecwid_Products(); $api = new Ecwid_Api_V3(get_ecwid_store_id()); ?>
<div>
	last update date: <?php echo $prods->get_last_update_time(); ?>
</div>
<div>
	api last update date: <?php echo strftime("%c", strtotime($api->get_store_update_stats()->productsUpdated)); ?>
</div>
<div>
	status:
	<?php if ($prods->is_in_sync()): ?>
	sync
	<?php else: ?>
	out of sync
	<?php endif; ?>
</div>
<div>
	<a class="button" id="ecwid-sync-products">Refresh products cache</a>
	<div id="ecwid-sync-products-inprogress" style="display:none">In progress</div>
	<div id="ecwid-sync-products-success" style="display:none">Success</div>
	<div id="ecwid-sync-products-error" style="display:none">Error</div>
</div>