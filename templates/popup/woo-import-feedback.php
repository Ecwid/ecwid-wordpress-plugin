<h3><?php esc_html_e( 'How was your overall experience with switching from WooCommerce to Ecwid?', 'ecwid-shopping-cart' ); ?></h3>

<ul class="reasons-list">
<?php
foreach ( $reasons as $key => $reason ) :
	?>
	<li class="reasons-list-item selected" data-option-key="<?php echo esc_attr( $key ); ?>">
		<?php if ( isset( $reason['has_message'] ) && $reason['has_message'] ) : ?>
		<div class="message">
			<?php
			$message_hint = '';
			if ( $reason['code'] === 'theme' ) {
				$message_hint = $reason['message_hint'];
			}
			?>
			<textarea class="more-details" name="message[<?php echo esc_attr( $key ); ?>]" placeholder="<?php echo esc_attr( $reason['message_hint'] ); ?>"><?php echo esc_textarea( $message_hint ); ?></textarea>

		</div>
		<?php endif; ?>
	</li>
	<?php
endforeach;
?>
</ul>
