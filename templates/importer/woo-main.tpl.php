<div class="wrap ecwid-importer state-<?php echo $this->_is_token_ok() ? 'woo' : 'no-token'; ?>">
	<div class="importer-state importer-state-no-token">
		<?php require __DIR__ . '/import-no-token.php'; ?>
	</div>


	<div class="importer-state importer-state-woo">
		<?php require __DIR__ . '/import-woo.tpl.php'; ?>
	</div>


	<div class="importer-state importer-state-woo-in-progress">
		<?php require __DIR__ . '/import-woo-in-progress.tpl.php'; ?>
	</div>

	<div class="importer-state importer-state-complete">
		<?php require __DIR__ . '/complete.tpl.php'; ?>
	</div>
</div>