<script type='text/javascript'>//<![CDATA[
    document.body.className += ' ecwid-no-padding';
    //]]>
</script>
<div class="ecwid-landing <?php echo $register ? 'register' : 'connect'; echo $connection_error ? ' conn-error': ''; ?>">
    <div class="ecwid-thank">
        <h1>
            <?php printf( __( 'Welcome to %s!', 'ecwid-shopping-cart'), Ecwid_WL::get_brand() ); ?>
            <span><?php printf( __( 'Thank you for choosing %s to build your online store. The first step to sell successfully online is to setup your store! Let’s get started and add a store to your website in a few simple steps.', 'ecwid-shopping-cart'), Ecwid_WL::get_brand() ); ?></span>
        </h1>

        <div class="ecwid-button">

            <button class="create-store-button btn btn-primary btn-large">
                <?php printf( __( 'Create Free %s Store', 'ecwid-shopping-cart'), Ecwid_WL::get_brand() ); ?>
            </button>
            <button class="create-store-loading btn btn-primary btn-large btn-loading">
                <div class="loader">
                    <div class="ecwid-spinner spin-right">
                        <svg width="60px" height="60px" viewBox="0 0 60 60" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <path class="loader-outer" d="M30,60 C46.5685425,60 60,46.5685425 60,30 C60,13.4314575 46.5685425,0 30,0 C13.4314575,0 0,13.4314575 0,30 C0,46.5685425 13.4314575,60 30,60 L30,60 Z"></path>
                            <path class="loader-background" d="M30,56 C44.3594035,56 56,44.3594035 56,30 C56,15.6405965 44.3594035,4 30,4 C15.6405965,4 4,15.6405965 4,30 C4,44.3594035 15.6405965,56 30,56 L30,56 Z" fill="#FFFFFF"></path>
                            <path class="loader-inner" d="M12.0224719,32.0224719 C10.9078652,32.0224719 10,31.1146067 10,30 C10,18.9707865 18.9707865,10 30,10 C31.1146067,10 32.0224719,10.9078652 32.0224719,12.0224719 C32.0224719,13.1370787 31.1146067,14.0449438 30,14.0449438 C21.2,14.0449438 14.0449438,21.2 14.0449438,30 C14.0449438,31.1146067 13.1370787,32.0224719 12.0224719,32.0224719 L12.0224719,32.0224719 Z M30,50 C28.8853933,50 27.9775281,49.0921348 27.9775281,47.9775281 C27.9775281,46.8629213 28.8853933,45.9550562 30,45.9550562 C38.8,45.9550562 45.9550562,38.8 45.9550562,30 C45.9550562,28.8853933 46.8629213,27.9775281 47.9775281,27.9775281 C49.0921348,27.9775281 50,28.8853933 50,30 C50,41.0292135 41.0292135,50 30,50 L30,50 Z" fill="#231F20"></path>
                        </svg>
                    </div>
                </div>
            </button>

            <button class="create-store-success btn btn-large btn-success btn-icon">
                <i class="icon-check"></i>
                <?php _e('Store is created', 'ecwid-shopping-cart'); ?>
            </button>

            <div class="create-store-loading-note ecwid-button-description">
                <?php _e('Creating store', 'ecwid-shopping-cart'); ?>
            </div>

            <div class="create-store-success-note ecwid-button-description">
                <?php _e('Preparing your store dashboard', 'ecwid-shopping-cart'); ?>
            </div>

            <div class="button-description-mobile">
                <?php _e('Free registration, No credit card required', 'ecwid-shopping-cart'); ?>
            </div>
            <div class="button-description-mobile on-error ecwid-connection-error">
                <?php printf( __( 'Connection error: please click the button again and give permissions for this plugin<br /> to show your %s store on this site.', 'ecwid-shopping-cart' ), Ecwid_WL::get_brand() ); ?>
            </div>
            <div class="create-store-have-account ecwid-button-description">
                <span class="create-store-have-account-question"><?php printf( __( 'Already have %s account?', 'ecwid-shopping-cart'), Ecwid_WL::get_brand() ); ?></span>
                <a class="create-store-have-account-link" href="admin-post.php?action=ecwid_connect"><?php _e('Connect your store to Wordpress site', 'ecwid-shopping-cart'); ?></a>
            </div>
            <div class="button-description-mobile">
                <?php printf( __( 'You will be asked to log in to your %s Control Panel<br />and give permissions to show your store on this site', 'ecwid-shopping-cart'), Ecwid_WL::get_brand() ); ?>
            </div>
            <div class="button-description-mobile">
                <h3><?php _e('Get ready to sell online', 'ecwid-shopping-cart'); ?></h3>
            </div>
        </div>
        <div class="ecwid-thank-background">
            <div class="ecwid-thank-background-tablet"><img src="<?php echo ECWID_PLUGIN_URL; ?>images/landing/tablet-iphone.png"></div>
        </div>
    </div>
    <div class="ecwid-description">
        <div class="ecwid-description-inner">
            <div class="ecwid-description-image"><img src="<?php echo ECWID_PLUGIN_URL; ?>images/landing/ecwid-description-image.jpg"></div>
            <div class="ecwid-description-text">
                <h2><?php printf( __( 'Sell Everywhere<br>with your %s store', 'ecwid-shopping-cart'), Ecwid_WL::get_brand() ); ?></h2>
                <p><?php _e('Start selling on your WordPress site. Then mirror your shop on your Facebook page, blog and marketplaces like Google Shopping, Yahoo and Shopping.com.', 'ecwid-shopping-cart'); ?></p>
                <p><?php printf( __( 'Use %s\'s mobile-POS to swipe credit cards and sell on the go. Your orders and inventory are always synchronized with your online store.', 'ecwid-shopping-cart'), Ecwid_WL::get_brand() ); ?></p>
            </div>
        </div>
    </div>
    <div class="ecwid-features">
        <div class="ecwid-features-inner">
            <h2><?php _e('Features', 'ecwid-shopping-cart'); ?></h2>
            <div class="ecwid-features-top">
                <div class="ecwid-features-top-item">
                    <div class="ecwid-features-top-item-image">
                        <img src="<?php echo ECWID_PLUGIN_URL; ?>images/landing/wordpress.svg">
                    </div>
                    <div class="ecwid-features-top-item-text">
                        <h3><?php _e('Compatible with your theme', 'ecwid-shopping-cart'); ?></h3>
                        <p><?php printf( __( '%s is compatible with your<br>“%s” WordPress theme<br>out of the box.', 'ecwid-shopping-cart' ), Ecwid_WL::get_brand(), ecwid_get_theme_name() ); ?></p>
                    </div>
                </div>
                <div class="ecwid-features-top-item">
                    <div class="ecwid-features-top-item-image">
                        <img src="<?php echo ECWID_PLUGIN_URL; ?>images/landing/gift.svg" class="gift">
                    </div>
                    <div class="ecwid-features-top-item-text">
                        <h3><?php _e('Free and always up to date', 'ecwid-shopping-cart'); ?></h3>
                        <p><?php _e('Free plan always available with tons of features<br>at no additional cost. Updates are seamless, automatic<br>and free of charge.', 'ecwid-shopping-cart'); ?></p>
                    </div>
                </div>
            </div>
            <div class="ecwid-features-bottom">
                <div class="ecwid-features-bottom-item">
                    <div class="ecwid-features-bottom-item-image">
                        <img src="<?php echo ECWID_PLUGIN_URL; ?>images/landing/responsive-grow.svg">
                    </div>
                    <div class="ecwid-features-bottom-item-text">
                        <h3><?php _e('Responsive design', 'ecwid-shopping-cart'); ?></h3>
                        <p><?php _e('Your store looks perfect<br />on all devices', 'ecwid-shopping-cart'); ?></p>
                    </div>
                </div>
                <div class="ecwid-features-bottom-item">
                    <div class="ecwid-features-bottom-item-image">
                        <img src="<?php echo ECWID_PLUGIN_URL; ?>images/landing/secure-pci.svg" class="secure">
                    </div>
                    <div class="ecwid-features-bottom-item-text">
                        <h3><?php _e('PCI DSS Certified', 'ecwid-shopping-cart'); ?></h3>
                        <p><?php _e('Secure checkout with over 40<br />payment options', 'ecwid-shopping-cart'); ?></p>
                    </div>
                </div>
                <div class="ecwid-features-bottom-item">
                    <div class="ecwid-features-bottom-item-image">
                        <img src="<?php echo ECWID_PLUGIN_URL; ?>images/landing/global.svg">
                    </div>
                    <div class="ecwid-features-bottom-item-text">
                        <h3><?php _e('Global Reach', 'ecwid-shopping-cart'); ?></h3>
                        <p><?php _e('Over 1 million merchants in 175 countries', 'ecwid-shopping-cart'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ecwid-start">
        <h2><?php _e('Start selling <br>on your WordPress <nobr>site for free</nobr>', 'ecwid-shopping-cart'); ?>
        </h2>
        <div class="ecwid-button">
            <button class="create-store-button btn btn-primary btn-large">
                <?php printf( __( 'Create Free %s Store', 'ecwid-shopping-cart'), Ecwid_WL::get_brand() ); ?>
            </button>
            <button class="create-store-loading btn btn-primary btn-large btn-loading">
                <div class="loader">
                    <div class="ecwid-spinner spin-right">
                        <svg width="60px" height="60px" viewBox="0 0 60 60" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <path class="loader-outer" d="M30,60 C46.5685425,60 60,46.5685425 60,30 C60,13.4314575 46.5685425,0 30,0 C13.4314575,0 0,13.4314575 0,30 C0,46.5685425 13.4314575,60 30,60 L30,60 Z"></path>
                            <path class="loader-background" d="M30,56 C44.3594035,56 56,44.3594035 56,30 C56,15.6405965 44.3594035,4 30,4 C15.6405965,4 4,15.6405965 4,30 C4,44.3594035 15.6405965,56 30,56 L30,56 Z" fill="#FFFFFF"></path>
                            <path class="loader-inner" d="M12.0224719,32.0224719 C10.9078652,32.0224719 10,31.1146067 10,30 C10,18.9707865 18.9707865,10 30,10 C31.1146067,10 32.0224719,10.9078652 32.0224719,12.0224719 C32.0224719,13.1370787 31.1146067,14.0449438 30,14.0449438 C21.2,14.0449438 14.0449438,21.2 14.0449438,30 C14.0449438,31.1146067 13.1370787,32.0224719 12.0224719,32.0224719 L12.0224719,32.0224719 Z M30,50 C28.8853933,50 27.9775281,49.0921348 27.9775281,47.9775281 C27.9775281,46.8629213 28.8853933,45.9550562 30,45.9550562 C38.8,45.9550562 45.9550562,38.8 45.9550562,30 C45.9550562,28.8853933 46.8629213,27.9775281 47.9775281,27.9775281 C49.0921348,27.9775281 50,28.8853933 50,30 C50,41.0292135 41.0292135,50 30,50 L30,50 Z" fill="#231F20"></path>
                        </svg>
                    </div>
                </div>
            </button>

            <button class="create-store-success btn btn-large btn-success btn-icon">
                <i class="icon-check"></i>
                <?php _e('Store is created', 'ecwid-shopping-cart'); ?>
            </button>

            <div class="create-store-loading-note ecwid-button-description">
                <?php _e('Creating store', 'ecwid-shopping-cart'); ?>
            </div>

            <div class="create-store-success-note ecwid-button-description">
                <?php _e('Preparing your store dashboard', 'ecwid-shopping-cart'); ?>
            </div>

            <div class="ecwid-button-description">
                <?php printf( __( 'Already have %s account?', 'ecwid-shopping-cart'), Ecwid_WL::get_brand() ); ?>
                <a href="admin-post.php?action=ecwid_connect"><?php _e('Connect your store to this site', 'ecwid-shopping-cart'); ?></a>
            </div>
        </div>
    </div>
</div>