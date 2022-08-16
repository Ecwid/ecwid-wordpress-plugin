<h3><?php esc_html_e( 'If you have a moment, please let us know why you are deactivating:', 'ecwid-shopping-cart' ); ?></h3>

<ul class="reasons-list">
<?php foreach ( $reasons as $key => $reason ) : ?>
	<li class="reasons-list-item" data-option-key="<?php echo esc_attr( $key ); ?>">
		<label>
			<span>
				<input type="radio" name="reason" value="<?php echo esc_attr( $key ); ?>" data-text="<?php esc_attr_e( $reason['text'] ); ?>"/>
			</span>
			<span>
				<?php echo esc_html( $reason['text'] ); ?>
			</span>
		</label>	
		<?php if ( isset( $reason['has_message'] ) && $reason['has_message'] ) : ?>
		<div class="message">
			<?php
			$message_hint = '';
			if ( $reason['code'] === 'theme' ) {
				$message_hint = $reason['message_hint'];
			}
			?>
			<textarea name="message[<?php echo esc_attr( $key ); ?>]" placeholder="<?php echo esc_attr( $reason['message_hint'] ); ?>"><?php echo esc_textarea( $message_hint ); ?></textarea>

			<div class="ec-deactivate-notice">
				<?php
				echo sprintf(
					__( 'You can <a %1$s>contact %2$s support</a> and let us help you with the problem you are facing, instead of removing the plugin.', 'ecwid-shopping-cart' ), //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'href="' . esc_url( $support_link ) . '" target="_blank"',
					esc_html( Ecwid_Config::get_brand() )
				);
				?>
			</div>

		</div>
		<?php endif; ?>
	</li>
<?php endforeach; ?>
</ul>
