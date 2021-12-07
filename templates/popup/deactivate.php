<h3><?php _e( 'If you have a moment, please let us know why you are deactivating:', 'ecwid-shopping-cart' ); ?></h3>

<ul class="reasons-list">
<?php foreach ( $reasons as $key => $reason ): ?>
	<li class="reasons-list-item" data-option-key="<?php echo $key; ?>">
		<label>
			<span>
				<input type="radio" name="reason" value="<?php echo $key; ?>" data-text="<?php esc_attr_e( $reason['text'] ); ?>"/>
			</span>
			<span>
				<?php echo $reason['text']; ?>
			</span>
		</label>	
		<?php if ( @$reason['has_message'] ): ?>
		<div class="message">
			<textarea name="message[<?php echo $key; ?>]" placeholder="<?php echo $reason['message_hint']; ?>"><?php if($reason['code'] == 'theme'){ echo $reason['message_hint']; } ?></textarea>

			<div class="ec-deactivate-notice">
				<?php
				echo sprintf(
					__('You can <a %1$s>contact %2$s support</a> and let us help you with the problem you are facing, instead of removing the plugin.', 'ecwid-shopping-cart'),
					sprintf( 'href="%s" target="_blank"', $support_link ),
					Ecwid_Config::get_brand()
				);
				?>
			</div>

		</div>
		<?php endif; ?>
	</li>
<?php endforeach; ?>
</ul>