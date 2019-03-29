<div class="head">
	<?php
	if( !Ecwid_Config::is_wl() ) {
		ecwid_embed_svg( 'ecwid-logo-blue' );
	}
	?>
	<h3>
		<?php printf( __( '%s', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?>
	</h3>
</div>