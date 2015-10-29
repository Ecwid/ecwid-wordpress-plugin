<div class="ecwid-debug">
<?php


	$all_plugins = get_plugins();

	$active_plugins = get_option('active_plugins');

	$theme =  wp_get_theme();

	$all_options = wp_load_alloptions();
?>

<h2>Active plugins</h2>

<?php foreach($active_plugins as $path): ?>
	<div class="section">
		<div>
			<?php echo $all_plugins[$path]['Name']; ?>
		</div>
		<div>
			<?php echo $all_plugins[$path]['PluginURI']; ?>
		</div>
	</div>
<?php endforeach; ?>

<h2>All plugins</h2>

<?php foreach($all_plugins as $key => $item): ?>
	<div class="section">
		<div>
			<?php echo $item['Name']; ?>
		</div>
		<div>
			<?php echo $item['PluginURI']; ?>
		</div>
	</div>
<?php endforeach; ?>

<h2>Theme</h2>

<div class="section">
	<div><?php echo $theme->get('Name'); ?></div>
	<div><?php echo $theme->get('ThemeURI'); ?></div>
</div>

<h2>Options</h2>

<?php foreach($all_options as $key => $option): ?>
<?php if (strpos($key, 'ecwid') !== false): ?>
	<div class="section">
		<div>
			<?php echo $key; ?>
		</div>
		<div>
			<?php echo $option; ?>
		</div>
	</div>
<?php endif; ?>
<?php endforeach; ?>




</div>
