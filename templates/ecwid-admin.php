<div class="wrap ecwid-admin ecwid-connect ecwid-reconnect-allow-sso">
	<div class="box">
		<div class="head"><?php ecwid_embed_svg('ecwid_logo_symbol_RGB');?>
			<h3>
				<?php _e( 'Ecwid Shopping Cart', 'ecwid-shopping-cart' ); ?>
			</h3>
		</div>

		<div class="main-wrap">
			<div class="column">
				<h4>Your store Control Panel<br /> will be displayed here</h4>
				<p class="note">You will be able to manage products, <br /> track your sales and adjust store settings here.</p>
				<div class="connect-button">
					<a href="admin-post.php?action=ecwid_connect&reconnect"><?php _e( 'Re-connect to Enable Control Panel', 'ecwid-shopping-cart' ); ?></a>
				</div>
			</div>

			<div class="column">
				<img src="<?php echo(esc_attr(ECWID_PLUGIN_URL)); ?>/images/new-feature.png" />
			</div>
		</div>

	</div>
	<p><?php echo sprintf(__('Questions? <a %s>Read FAQ</a> or contact support at <a %s>wordpress@ecwid.com</a>', 'ecwid-shopping-cart'), 'target="_blank" href="https://help.ecwid.com/customer/portal/articles/1085017-wordpress-downloadable#FAQ"', 'href="mailto:wordpress@ecwid.com"'); ?></p>
</div>


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
			$('#ecwid-frame').css('height', '700px');

			$('#ecwid-frame').attr('src', '<?php echo $iframe_src; ?>');

			$.ajax({
				'url': '<?php echo $iframe_src; ?>',
				'complete': function() {debugger}
			});

			$('#ecwid-frame').load(function() {debugger;});
		});

	}//]]>

</script>

<div id="superwrap" style="position:relative">
	<div id="wrap" style="position:absolute; left:-20px;right:0px">
		<iframe seamless id="ecwid-frame" frameborder="0" width="100%" height="700" scrolling="no"></iframe>
	</div>
</div>