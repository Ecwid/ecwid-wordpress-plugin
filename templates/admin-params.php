<h2>Online store hidden parameters</h2>

<div style="max-width: 800px">
	<b style="color: red">WARNING: ADVANCED CONFIGURATION AHEAD!</b> Changing these settings may significantly affect the plugin functionality, including admin settings and storefront. You should only continue if you are sure of what you are doing.
	<br />
	<br />
	Having a problem working with the Online store plugin? Visit our <a target="_blank" href="https://support.ecwid.com">Help center</a> if you haven't yet.
</div>
<br />

<form method="POST" type="multipart/form-data" action="admin-post.php?action=<?php echo esc_attr( ecwid_get_update_params_action() ); ?>">

	<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( ecwid_get_update_params_action() ) ); ?>" />

	<style type="text/css">
		#ec-params-table { border-collapse: collapse; border-spacing: 0; margin-bottom: 5px; }
		#ec-params-table td { padding: 3px; } 
		#ec-params-table tr:hover { background-color: lightblue; }
		#ec-params-table select { min-width: 70px; }
	</style>

	<table id="ec-params-table">

	<?php foreach ( ecwid_get_update_params_options() as $key => $option ) : ?>
		<tr style="padding: 0 0 5px;">
			<td><?php echo esc_html( $key ); ?>: </td>
			<td>
			<?php if ( isset( $option['type'] ) && $option['type'] === 'bool' ) : ?>
				<select name="option[<?php echo esc_attr( $key ); ?>]">
					<option value=""
					<?php
					if ( get_option( $key ) === '' ) :
						?>
						selected="selected"<?php endif; ?>>off</option>
					<option value="Y"
					<?php
					if ( get_option( $key ) ) :
						?>
						selected="selected"<?php endif; ?>>on</option>
				</select>
			<?php elseif ( isset( $option['type'] ) && $option['type'] === 'string' ) : ?>
				<input type="text" name="option[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( get_option( $key ) ); ?>">
			<?php elseif ( isset( $option['type'] ) && $option['type'] === 'html' ) : ?>
				<textarea name="option[<?php echo esc_attr( $key ); ?>]" style="width:500px"><?php echo esc_textarea( get_option( $key ) ); ?></textarea>
			<?php elseif ( ! empty( $option['values'] ) ) : ?>
				<select name="option[<?php echo esc_attr( $key ); ?>]">
				<?php foreach ( $option['values'] as $value ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>"
						<?php if ( $value === get_option( $key ) ) { ?>
							selected="selected"
						<?php } ?>
					><?php echo esc_html( $value ); ?></option>
				<?php endforeach; ?>	
				</select>
			<?php endif; ?>

			<?php echo esc_attr( get_option( $key ) ); ?>

			</td>
		</tr>
	<?php endforeach; ?>

	</table>

	<button class="btn btn-primary">Save</button>
</form>

<br />
<h2>Clear plugin cache</h2>
<a href="?<?php echo esc_attr( ecwid_get_clear_all_cache_action() ); ?>&redirect_back">Clear all caches</a>
