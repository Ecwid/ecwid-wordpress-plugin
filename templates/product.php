<?php
$content = <<<HTML
<script>xProductBrowser('defaultProductId=$ecwid_id');</script>
HTML;
echo ecwid_wrap_shortcode_content($content, 'product', array());
?>