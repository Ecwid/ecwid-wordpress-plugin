<h2><?php _e( 'Import complete.', 'ecwid-shopping-cart' ); ?></h2>

<p class="plan-limit-message">
    <?php echo sprintf( __ ( 'Not all products have been copied to %1$s because you reached the product count limit on your pricing plan in %1$s. If you want to import more products, please consider <a %2$s>upgrading your %1$s plan</a>.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand(), 'href="' . $this->_get_billing_page_url() .'"' ); ?>
</p>

<ul>
    <li>
        <?php echo sprintf( __( 'Imported products: %s', 'ecwid-shopping-cart' ), '<span id="import-results-products"></span>' ); ?>
    </li>
<?php if ( ecwid_is_paid_account() ): ?>     
    <li>
		<?php echo sprintf( __( 'Imported categories: %s', 'ecwid-shopping-cart' ), '<span id="import-results-categories"></span>' ); ?>
    </li>
</ul>
<?php endif; ?>

<p>
    <a class="button button-primary" href="admin.php?page=<?php echo Ecwid_Admin::ADMIN_SLUG; ?>-admin-products">
        <?php echo sprintf( __('Go to your %s Products', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?>
    </a>
</p>

<div class="errors">
    <?php _e( 'Some of the items could not be imported.', 'ecwid-shopping-cart' ); ?> <a class="btn-details"><?php _e( 'Details...', 'ecwid-shopping-cart' ); ?></a>
    <pre class="details"></pre>
</div>