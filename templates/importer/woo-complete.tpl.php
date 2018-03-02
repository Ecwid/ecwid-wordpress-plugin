<h2><?php _e( 'Import complete.', 'ecwid-shopping-cart' ); ?></h2>

<p class="plan-limit-message">
    <?php echo sprintf( __ ( 'Not all products have been copied to %1$s because you reached the product count limit on your pricing plan in %1$s. If you want to import more products, please consider <a %2$s>upgrading your %1$s plan</a>.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand(), 'href="' . $this->_get_billing_page_url() .'"' ); ?>
</p>

<ul>
    <li>
        Imported products: <span id="import-results-products"></span>
    </li>
    <li>
        Imported categories: <span id="import-results-categories"></span>
    </li>
</ul>

<p>
    <a class="button button-primary" href="admin.php?page=<?php echo Ecwid_Admin::ADMIN_SLUG; ?>-admin-products">
        <?php echo sprintf( __('Go to your %s Products', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?>
    </a>
</p>