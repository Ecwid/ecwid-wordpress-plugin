<div class="wrap ecwid-admin">
    
    <div class="ec-store-box">
		<?php require ECWID_PLUGIN_DIR . 'templates/admin-head.php'; ?>

        <form method="POST" action="options.php" class="pure-form ecwid-settings general-settings">
            <?php settings_fields('ecwid_options_page'); ?>
            <fieldset>
    
                <input type="hidden" name="settings_section" value="general" />
    
                <div class="greeting-box">
    
                    <div class="image-container">
                        <img class="greeting-image" src="<?php echo( esc_attr ( ECWID_PLUGIN_URL ) ); ?>/images/store_inprogress.png" width="142" />
                    </div>
    
                    <div class="messages-container">
                        <div class="main-message">
    
                            <?php printf( __('Thank you for choosing %s to build your online store', 'ecwid-shopping-cart'), Ecwid_Config::get_brand() ); ?>
                        </div>
                        <div class="secondary-message">
                            <?php _e('The first step towards opening your online business: <br />Let’s get started and add a store to your WordPress website in <strong>3</strong> simple steps.', 'ecwid-shopping-cart'); ?>
                        </div>
                    </div>
    
                </div>
                <hr />
    
                <ol>
                    <li>
                        <h4><?php printf( __('Register at %s', 'ecwid-shopping-cart'), Ecwid_Config::get_brand() ); ?></h4>
                        <div>
                            <?php printf( __('Create a new %s account which you will use to manage your store and inventory. The registration is free.', 'ecwid-shopping-cart'), Ecwid_Config::get_brand() ); ?>
                        </div>
                        <div class="ecwid-account-buttons">
                            <a class="pure-button pure-button-secondary" target="_blank" href="<?php echo ecwid_get_register_link(); ?>">
                                <?php _e('Create new account', 'ecwid-shopping-cart'); ?>
                            </a>
                            <a class="pure-button pure-button-secondary" target="_blank" href="https://<?php echo Ecwid_Config::get_cpanel_domain(); ?>">
                                <?php _e('I already have an account, sign in', 'ecwid-shopping-cart'); ?>
                            </a>
                        </div>
                        <div class="note">
                            <?php _e('You will be able to sign up through your existing Google, Facebook or PayPal profiles as well.', 'ecwid-shopping-cart'); ?>
                        </div>
                    </li>
                    <li>
                        <h4><?php _e('Find your Store ID', 'ecwid-shopping-cart'); ?></h4>
                        <div>
                            <?php printf( __('Store ID is a unique identifier of any %1$s store, it consists of several digits. You can find it on the "Dashboard" page of %1$s control panel. Also the Store ID will be sent in the Welcome email after the registration.', 'ecwid-shopping-cart'), Ecwid_Config::get_brand() ); ?>
                        </div>
                    </li>
                    <li>
                        <h4>
                            <?php _e('Enter your Store ID', 'ecwid-shopping-cart'); ?>
                        </h4>
                        <div><label for="ecwid_store_id"><?php _e('Enter your Store ID here:', 'ecwid-shopping-cart'); ?></label></div>
                        <div class="pure-control-group store-id">
                            <input
                                id="ecwid_store_id"
                                name="ecwid_store_id"
                                type="text"
                                placeholder="<?php _e('Store ID', 'ecwid-shopping-cart'); ?>"
                                value="<?php if ( !ecwid_is_demo_store() ) echo esc_attr( get_ecwid_store_id() ); ?>"
                                />
                            <button type="submit" class="<?php echo ECWID_MAIN_BUTTON_CLASS; ?>"><?php printf( __('Save and connect your %s store to the site', 'ecwid-shopping-cart'), Ecwid_Config::get_brand() ); ?></button>
                        </div>
    
                    </li>
                </ol>
            </fieldset>
        </form>
    </div>
	<?php require ECWID_PLUGIN_DIR . 'templates/admin-footer.php'; ?>
</div>
