<script type='text/javascript'>//<![CDATA[
	jQuery(document).ready(function() {
		document.body.className += ' ecwid-no-padding';
		$ = jQuery;
		// Create IE + others compatible event handler
		var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
		var eventer = window[eventMethod];
		var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

		// Listen to message from child window
		eventer(messageEvent,function(e) {
			$('#ecwid-frame').css('height', e.data.height + 'px');
		},false);

		$('#ecwid-frame').attr('src', '<?php echo $iframe_src; ?>');
		ecwidSetPopupCentering('#ecwid-frame');
	});
	//]]>

</script>


		<iframe seamless id="ecwid-frame" frameborder="0" width="100%" height="700" scrolling="yes"></iframe>

<?php require_once ECWID_PLUGIN_DIR . 'templates/admin-footer.php'; ?>