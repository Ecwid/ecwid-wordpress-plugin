<div class="ecwid-message <?php echo esc_attr($type); ?>">
	<?php if ($title): ?>
	<div class="ecwid-message-title">
		<?php echo esc_html($title); ?>
	</div>
	<?php endif; ?>

	<div class="ecwid-message-content">
		<?php echo $message; ?>
	</div>

	<?php if ($primary_button || $secondary_button || $do_not_show_again): ?>
	<div class="ecwid-message-buttons">
		<?php if ($primary_button): ?>
		<div>
			<a
				class="button button-primary"
				href="<?php echo esc_attr($primary_url); ?>"
				<?php if ($primary_blank): ?>
				target="_blank"
				<?php endif; ?>
			>
				<?php echo esc_html($primary_title); ?>
			</a>
		</div>
		<?php endif; ?>

		<?php if ($secondary_button): ?>
		<div>
			<a
				class="button"
				href="<?php echo esc_attr($secondary_url); ?>"
				<?php if ($secondary_blank): ?>
				target="_blank"
				<?php endif; ?>
			>
				<?php echo esc_html($secondary_title); ?>
			</a>
		</div>
		<?php endif; ?>

		<?php if ($do_not_show_again): ?>
		<div class="hide-wrapper">
			<a class="ecwid-message-hide" name="<?php echo $name; ?>" href="javascript: void(0);">
				<?php _e('Never show this message again', 'ecwid-shopping-cart'); ?>
			</a>
		</div>
		<?php endif; ?>
	</div>
	<?php endif; ?>
</div>