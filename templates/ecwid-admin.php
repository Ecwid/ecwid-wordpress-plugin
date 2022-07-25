<script type='text/javascript'>//<![CDATA[
	jQuery(document.body).addClass('ecwid-admin-iframe ecwid-no-padding');

	jQuery(document).ready(function() {
		jQuery('#ecwid-frame').attr('src', '<?php echo $iframe_src; ?>');
		ecwidSetPopupCentering('#ecwid-frame');

		jQuery.ajax({
			url: ajaxurl + '?action=<?php echo Ecwid_Store_Page::WARMUP_ACTION; ?>'
		});
	});
	//]]>
</script>

<style type="text/css">
	<?php
	if ( isset( $_GET['page'] ) && in_array( $_GET['page'], array( 'ec-storefront-settings', 'ec-store-developers' ) ) ) {
		echo '#ecwid-frame { display: none; } ';
		echo sprintf( '#%s { display: block; }', sanitize_text_field( wp_unslash( $_GET['page'] ) ) );
	}
	?>
	.ec-ui-framework-page { display: none; }
</style>

<?php

Ecwid_Admin_Storefront_Page::do_page();
Ecwid_Admin_Developers_Page::do_page();

?>

<iframe seamless id="ecwid-frame" frameborder="0" width="100%" height="700" scrolling="no"></iframe>

<?php require_once ECWID_PLUGIN_DIR . 'templates/admin-footer.php'; ?>
