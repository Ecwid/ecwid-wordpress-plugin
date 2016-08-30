<?php $prods = new Ecwid_Products(); $api = new Ecwid_Api_V3(get_ecwid_store_id()); ?>
<div>
	last update date: <?php echo strftime("%c", $prods->get_last_update_time()); ?>
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
	<?php $prods->sync(); ?>
</div>