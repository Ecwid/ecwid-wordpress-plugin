<?php
$content = "<script>xProductBrowser('defaultProductId=$ecwid_id');</script>";
echo ecwid_wrap_shortcode_content( $content, 'product', array() ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
