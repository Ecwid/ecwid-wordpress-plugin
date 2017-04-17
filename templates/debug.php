<div class="ecwid-debug">
<?php


	$all_plugins = get_plugins();

	$active_plugins = get_option('active_plugins');

	$theme =  wp_get_theme();

	$all_options = wp_load_alloptions();
?>

<a class="button button-primary" href="admin-post.php?action=ecwid_get_debug" style="margin-top:10px"><?php _e('Download log file', 'ecwid-shopping-cart'); ?></a>

<h2>Active plugins</h2>

<div>
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
</div>

<h2>All plugins</h2>

<div>
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
</div>

<h2>Theme</h2>

<div class="section">
	<div><?php echo $theme->get('Name'); ?></div>
	<div><?php echo $theme->get('ThemeURI'); ?></div>
</div>

<h2>Remote get test</h2>
<div><?php var_export($remote_get_results); ?></div>

<h2>Api V3 profile test</h2>
<div><?php var_export($api_v3_profile_results); ?></div>

<h2>Error log</h2>
<div>
	<?php foreach (json_decode($all_options['ecwid_error_log'], true) as $key => $item): ?>
	<div class="section"><?php echo $item['message']; ?></div>
	<?php endforeach; ?>
</div>

<h2>Misc</h2>
<div>
    <div class="section">
        <div>Theme identification</div>
        <div><?php echo ecwid_get_theme_identification(); ?></div>
    </div>
    <div class="section">
        <div>Affiliate Ref ID</div>
        <div><?php echo apply_filters( 'ecwid_get_new_store_ref_id', '' ); ?></div>
    </div>
</div>
<h2>Options</h2>

<div>
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

<h2>Store pages</h2>
    <?php foreach (Ecwid_Store_Page::get_store_pages_array() as $page_id): ?>
    <div>
        <a target="_blank" href="post.php?post=<?php echo $page_id; ?>&action=edit"><?php echo get_post($page_id)->post_name; ?></a>
        <?php if ( $page_id == get_option( Ecwid_Store_Page::OPTION_MAIN_STORE_PAGE_ID ) ): ?>
        <b> - main</b>
        <?php endif ;?>

    </div>
<?php endforeach; ?>
<h2>PhpInfo</h2>

<div>
	<iframe width="80%" height="500px" srcdoc="<?php ob_start(); phpinfo(); $contents = ob_get_contents(); ob_end_clean(); echo esc_attr($contents); ?>"></iframe>
</div>

</div>

<script>
	jQuery('h2').click(function() {
		jQuery(this).toggleClass('hide');
	})
</script>
