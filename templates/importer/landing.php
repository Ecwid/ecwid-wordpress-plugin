<div class="ecwid-importer state-landing">
	<div class="importer-state importer-state-landing">
		<div>Landing</div>
		<button class="button button-primary" id="ecwid-importer-woo">Woo</button>
	</div>

	<div class="importer-state importer-state-no-token">
		<?php require __DIR__ . '/import-no-token.php'; ?>
	</div>


	<div class="importer-state importer-state-woo">
		<?php require __DIR__ . '/import-woo.php'; ?>
	</div>


	<div class="importer-state importer-state-woo-in-progress">
		<?php require __DIR__ . '/import-woo-in-progress.php'; ?>
	</div>
	
	<div class="importer-state importer-state-complete">
		<?php require __DIR__ . '/complete.php'; ?>
	</div>
</div>