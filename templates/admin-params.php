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