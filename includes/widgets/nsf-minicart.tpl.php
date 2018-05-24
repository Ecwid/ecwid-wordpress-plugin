<!-- noptimize -->
<?php

echo ecwid_get_scriptjs_code();
echo ecwid_get_product_browser_url_script();
?>

<div class='ec-cart-widget'
	 data-fixed='<?php echo $instance[self::FIELD_FIXED]; ?>'
	 data-fixed-position='<?php echo $instance[self::FIELD_FIXED_POSITION]; ?>'
	 data-fixed-shape='<?php echo $instance[self::FIELD_FIXED_SHAPE]; ?>'
	 data-layout='<?php echo $instance[self::FIELD_LAYOUT]; ?>'
	 data-show-empty-cart='<?php echo $instance[self::FIELD_SHOW_EMPTY_CART]; ?>'
	 data-show-buy-animation='<?php echo $instance[self::FIELD_SHOW_BUY_ANIMATION]; ?>'
	 data-icon='<?php echo $instance[self::FIELD_ICON]; ?>'
></div>

<script>
    Ecwid.init();
</script>
<!-- /noptimize -->