<!-- noptimize -->
<?php

echo ecwid_get_scriptjs_code();
echo ecwid_get_product_browser_url_script();
?>

<div class='ec-cart-widget'
	 data-fixed='false'
	 data-fixed-shape='<?php echo $instance[self::FIELD_FIXED_SHAPE]; ?>'
	 data-layout='<?php echo $instance[self::FIELD_LAYOUT]; ?>'
	 data-icon='<?php echo $instance[self::FIELD_ICON]; ?>'
></div>

<script>
    Ecwid.init();
</script>
<!-- /noptimize -->