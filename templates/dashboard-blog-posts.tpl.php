<div class="template-container">
	<div class="ecwid-blog-post">
        <div class="ecwid-blog-post-image-container">
            <a class="ecwid-blog-post-link" target="_blank"><div class="ecwid-blog-post-image"></div></a>
        </div>
        <div class="ecwid-blog-post-text-container">
            <a class="ecwid-blog-post-title ecwid-blog-post-link" target="_blank"></a>
            <p class="ecwid-blog-post-excerpt"></p>
        </div>
	</div>
</div>
<div class="ecwid-blog-posts"></div>
<div class="ecwid-blog-footer">
    <a href="<?php _e( 'https://www.ecwid.com/blog', 'ecwid-shopping-cart' ); ?>">
        <?php echo sprintf( __( '%s Blog', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?>
        <span aria-hidden="true" class="dashicons dashicons-external"></span>
    </a>
    |
    <a href="<?php _e( 'https://support.ecwid.com/hc/en-us', 'ecwid-shopping-cart' ); ?>">
        <?php echo _e( 'Knowledge Base', 'ecwid-shopping-cart' ); ?>
        <span aria-hidden="true" class="dashicons dashicons-external"></span>
    </a>
</div>
