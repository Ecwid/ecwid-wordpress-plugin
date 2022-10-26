<?php

echo ecwid_get_scriptjs_code(); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo ecwid_get_product_browser_url_script(); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>

<div class='ec-cart-widget'
	data-fixed='false'
	data-fixed-shape='<?php echo esc_attr( $instance[ self::FIELD_FIXED_SHAPE ] ); ?>'
	data-layout='<?php echo esc_attr( $instance[ self::FIELD_LAYOUT ] ); ?>'
	data-icon='<?php echo esc_attr( $instance[ self::FIELD_ICON ] ); ?>'
></div>

<!--noptimize-->
<script>
if (typeof Ecwid != 'undefined') {
	Ecwid.init();
}
</script>
<!--/noptimize-->
