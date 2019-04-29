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

<iframe seamless id="ecwid-frame" frameborder="0" width="100%" height="700" scrolling="no"></iframe>

<?php require_once ECWID_PLUGIN_DIR . 'templates/admin-footer.php'; ?>
