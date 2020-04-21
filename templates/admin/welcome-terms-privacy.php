<?php if( !Ecwid_Config::is_wl() ) { ?>
<div class="ec-note" style="padding-top: 16px;">
	<?php
	echo sprintf( 
		__( 'By continuing, you agree to the <a %s>Terms of Service</a> and <a %s>Privacy Policy</a>.', 'ecwid-shopping-cart' ),
		'href="http://www.ecwid.com/terms-of-service" target="_blank"',
		'href="https://www.ecwid.com/privacy-policy" target="_blank"'
	);
	?>
</div>
<?php } ?>