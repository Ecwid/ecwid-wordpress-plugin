<h2>Online store hidden parameters</h2>

<div style="max-width: 800px">
<b style="color: red">WARNING: ADVANCED CONFIGURATION AHEAD!</b> Changing these settings may significantly affect the plugin functionality, including admin settings and storefront. You should only continue if you are sure of what you are doing.
<br />
<br />
    Having a problem working with the Online store plugin? Visit our <a target="_blank" href="https://support.ecwid.com">Help center</a> if you haven't yet.
</div>
<br />
<form method="POST" type="multipart/form-data" action="admin-post.php?action=<?php echo ecwid_get_update_params_action(); ?>">
<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( ecwid_get_update_params_action() ); ?>" />
	
<?php foreach ( ecwid_get_update_params_options() as $key => $option ): ?>
<div><?php echo $key; ?>: 
	<span>
	<?php if ( @$option['type'] == 'bool' ): ?>
		<select name="option[<?php echo $key; ?>]">
			<option value=""<?php if (get_option($key) == ''):?> selected="selected"<?php endif; ?>>off</option>
			<option value="Y"<?php if (get_option($key) ):?> selected="selected"<?php endif; ?>>on</option>
		</select>
	<?php elseif ( @$option['type'] == 'string'): ?>
		<input type="text" name="option[<?php echo $key; ?>]" value="<?php echo get_option( $key ); ?>">
	<?php elseif ( @$option['values'] ): ?>
		<select name="option[<?php echo $key; ?>]">
		<?php foreach ( @$option['values'] as $value ): ?>
			<option value="<?php echo $value; ?>"<?php if ( $value == get_option($key)): ?> selected="selected"<?php endif; ?>><?php echo $value; ?></option>
		<?php endforeach; ?>	
		</select>
	<?php endif; ?>
		
	</span>	
	
	<?php echo get_option($key); ?>
</div>
<?php endforeach; ?>
<button class="btn btn-primary">submit</button>
</form>

<br />
<h2>Clear plugin cache</h2>
<a href="?<?php echo ecwid_get_clear_all_cache_action(); ?>&redirect_back">Clear all caches</a>