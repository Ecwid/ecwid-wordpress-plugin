<h2><?php _e( 'Import complete.', 'ecwid-shopping-cart' ); ?></h2>

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