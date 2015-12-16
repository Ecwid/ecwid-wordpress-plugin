<script type='text/javascript'>//<![CDATA[
	window.onload=function(){
		$ = jQuery;
		// Create IE + others compatible event handler
		var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
		var eventer = window[eventMethod];
		var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

		// Listen to message from child window
		eventer(messageEvent,function(e) {
			$('#ecwid-frame').css('height', e.data.height + 'px');
			$('#superwrap').css('height', (e.data.height) + 'px');
		},false);

		$(document).ready(function(){
			$('#ecwid-frame').attr('src', '<?php echo $iframe_src; ?>');
		});

	}//]]>

</script>

<div id="superwrap" class="ecwid-admin-superwrap">
	<div id="wrap">
		<iframe seamless id="ecwid-frame" frameborder="0" width="100%" height="700" scrolling="no"></iframe>
	</div>
</div>

<?php require_once ECWID_PLUGIN_DIR . 'templates/admin-footer.php'; ?>
