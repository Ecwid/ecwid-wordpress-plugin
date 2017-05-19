<script type='text/javascript'>//<![CDATA[
	document.body.className += ' ecwid-no-padding';
	//]]>
</script>
<div class="ecwid-landing <?php echo $register ? 'register' : 'connect'; echo $connection_error ? ' conn-error': ''; ?>">
	<div class="ecwid-thank">
		<h1 class="on-register">
			<span><?php _e('Plugin is installed successfully!', 'ecwid-shopping-cart'); ?></span>
			<?php _e('There are just a few steps left to start selling<br /> on your WordPress site', 'ecwid-shopping-cart'); ?>
		</h1>
		<h1 class="on-connect">
			<span><?php _e('Plugin is installed successfully!', 'ecwid-shopping-cart'); ?></span>
			<?php _e('There are few little steps left to start selling<br /> on your WordPress site', 'ecwid-shopping-cart'); ?>
		</h1>
		<div class="ecwid-thank-steps">
			<div class="ecwid-thank-step ecwid-thank-step-one<?php echo $register ?'' : ' active'; ?>">
				<div class="ecwid-thank-step-image"><img src="<?php echo ECWID_PLUGIN_URL; ?>images/landing/ecwid.svg" class="none-active"><img src="<?php echo ECWID_PLUGIN_URL; ?>images/landing/ecwid-active.svg" class="active"></div>
				<div class="ecwid-thank-step-description">
					<h2><?php _e('Register', 'ecwid-shopping-cart'); ?></h2>
					<p><?php _e('Create a free Ecwid account to manage your store and inventory.<br /> No credit card required', 'ecwid-shopping-cart'); ?></p>
				</div>
			</div>
			<div class="ecwid-thank-step ecwid-thank-step-two">
				<div class="ecwid-thank-step-image"><img src="<?php echo ECWID_PLUGIN_URL; ?>images/landing/plug.svg" class="none-active"><img src="<?php echo ECWID_PLUGIN_URL; ?>images/landing/plug-active.svg" class="active"></div>
				<div class="ecwid-thank-step-description">
					<h2>
						<?php _e('Connect', 'ecwid-shopping-cart'); ?>
					</h2>
					<p class="on-register"><?php _e('Add your Ecwid store to your site <nobr>in two clicks</nobr>', 'ecwid-shopping-cart'); ?></p>
					<p class="on-connect"><?php _e('Connect your Ecwid store to this site <nobr>in two clicks</nobr>', 'ecwid-shopping-cart'); ?></p>
				</div>
			</div>
			<div class="ecwid-thank-step ecwid-thank-step-three">
				<div class="ecwid-thank-step-image">
					<img src="<?php echo ECWID_PLUGIN_URL; ?>images/landing/dollar.svg" class="none-active">
					<img src="<?php echo ECWID_PLUGIN_URL; ?>images/landing/dollar-active.svg" class="active">
				</div>
				<div class="ecwid-thank-step-description">
					<h2><?php _e('Start selling', 'ecwid-shopping-cart'); ?></h2>
					<p><?php _e('Your storefront is ready', 'ecwid-shopping-cart'); ?></p>
				</div>
			</div>
		</div>
		<div class="ecwid-button">
			<a target="_blank" href="<?php echo esc_attr(ecwid_get_register_link()); ?>" class="button button--blue on-register"  onclick="javascript:switch_to_connect();">
				<?php _e('Create Ecwid store', 'ecwid-shopping-cart'); ?>
			</a>
			<a class="button button--green on-connect" href="admin-post.php?action=ec_connect">
				<?php _e('Connect your store', 'ecwid-shopping-cart'); ?>
			</a>
			<div class="button-description-mobile on-register">
				<?php _e('Free registration, No credit card required', 'ecwid-shopping-cart'); ?>
			</div>
			<div class="button-description-mobile on-error ecwid-connection-error">
				<?php _e( 'Connection error: please click the button again and give permissions for this plugin<br /> to show your Ecwid store on this site.', 'ecwid-shopping-cart' ); ?>
			</div>
			<div class="button-description-mobile on-connect on-no-error">
				<?php _e('You will be asked to log in to your Ecwid Control Panel<br />and give permissions to show your store on this site', 'ecwid-shopping-cart'); ?>
			</div>
			<div class="ecwid-button-description on-register">
				<?php _e('Already have Ecwid account?', 'ecwid-shopping-cart'); ?>
				<a href="admin-post.php?action=ec_connect"><?php _e('Connect your store to Wordpress site', 'ecwid-shopping-cart'); ?></a>
			</div>
			<div class="ecwid-button-description on-connect">
				<?php _e('Don\'t have an Ecwid account?', 'ecwid-shopping-cart'); ?>
				<a target="_blank" href="<?php echo esc_attr(ecwid_get_register_link()); ?>" onclick="javascript:switch_to_connect();"><?php _e('Register at Ecwid for free', 'ecwid-shopping-cart'); ?></a>
			</div>
			<div class="button-description-mobile on-register">
				<?php _e('You will be asked to log in to your Ecwid Control Panel<br />and give permissions to show your store on this site', 'ecwid-shopping-cart'); ?>
			</div>
			<div class="button-description-mobile on-connect">
				<?php _e('No credit card required', 'ecwid-shopping-cart'); ?>
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
				<h2><?php _e('Sell Everywhere<br>with your Ecwid store', 'ecwid-shopping-cart'); ?></h2>
				<p><?php _e('Start selling on your WordPress site. Then mirror your shop on your Facebook page, blog and marketplaces like Google Shopping, Yahoo and Shopping.com.', 'ecwid-shopping-cart'); ?></p>
				<p><?php _e('Use Ecwid\'s mobile-POS to swipe credit cards and sell on the go. Your orders and inventory are always synchronized with your online store.', 'ecwid-shopping-cart'); ?></p>
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
						<p><?php echo sprintf(__('Ecwid is compatible with your<br>“%s” WordPress theme<br>out of the box.', 'ecwid-shopping-cart'), ecwid_get_theme_name()); ?></p>
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
						<p><?php _e('More than 800,000 merchants in 175 countries', 'ecwid-shopping-cart'); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="ecwid-start">
		<h2><?php _e('Start selling <br>on your WordPress <nobr>site for free</nobr>', 'ecwid-shopping-cart'); ?>
		</h2>
		<div class="ecwid-button">
			<a target="_blank" href="<?php echo esc_attr(ecwid_get_register_link()); ?>" class="button button--blue on-register"  onclick="javascript:switch_to_connect();>
				<?php _e('Get Started, Create Ecwid Account', 'ecwid-shopping-cart'); ?>
			</a>
			<a class="button button--green on-connect" href="admin-post.php?action=ec_connect">
				<?php _e('Connect your store', 'ecwid-shopping-cart'); ?>
			</a>
			<div class="ecwid-button-description on-register">
				<?php _e('Already have Ecwid account?', 'ecwid-shopping-cart'); ?>
				<a href="admin-post.php?action=ec_connect""><?php _e('Connect your store to this site', 'ecwid-shopping-cart'); ?></a>
			</div>
			<div class="ecwid-button-description on-connect">
				<?php _e('Don\'t have an Ecwid account?', 'ecwid-shopping-cart'); ?>
				<a target="_blank" href="<?php echo esc_attr(ecwid_get_register_link()); ?>" onclick="javascript:switch_to_connect();"><?php _e('Register at Ecwid for free', 'ecwid-shopping-cart'); ?></a>
			</div>

		</div>
	</div>
</div>