<h2><?php printf( __( '%s Shopping Cart — Advanced settings', 'ecwid-shopping-cart' ), Ecwid_WL::get_brand() ); ?></h2>

<div class="wrap">
<form class="pure-form pure-form-aligned ecwid-settings advanced-settings" method="POST" action="options.php">


	<?php settings_fields('ecwid_options_page'); ?>
	<input type="hidden" name="settings_section" value="advanced" />

	<fieldset>

		<?php if (get_option('ecwid_hide_appearance_menu') != 'Y'): ?>
		<div class="pure-control-group bottom-border">

			<label for="ecwid_default_category_id">
				<?php _e('Category shown by default', 'ecwid-shopping-cart'); ?>
			</label>

			<select name="ecwid_default_category_id" id="ecwid_default_category_id">
				<option value=""><?php _e('Store root category', 'ecwid-shopping-cart'); ?></option>
				<?php foreach ($categories as $category): ?>
					<option
						value="<?php echo esc_attr($category->id); ?>"
						<?php if ($category->id == get_option('ecwid_default_category_id')): ?>
							selected="selected"
						<?php endif; ?>
					>
						<?php echo esc_html($category->path); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<div class="note">
				<?php _e('By default, the storefront shows a list of root categories. You can override this behavior and show a different category when customers open your store for the first time. This is useful if you only have one category or want to display a specific set of items (e.g. "Featured Products") to new visitors.', 'ecwid-shopping-cart'); ?>
			</div>
		</div>

		<hr />

		<?php endif; ?>

		<div class="pure-control-group checkbox">
			<div class="label">
				<label for="ecwid_is_sso_enabled" class="premium-feature">

					<input
						id="ecwid_is_sso_enabled"
						name="ecwid_is_sso_enabled"
						type="checkbox"
						<?php if ( $is_sso_enabled ) : ?>
							checked="checked"
						<?php endif; ?>
						<?php if ( $is_sso_checkbox_disabled ) : ?>
						disabled="disabled"
					    <?php endif; ?>
					/>
					<?php _e('Customer Single Sign-On', 'ecwid-shopping-cart'); ?>
					<?php ecwid_embed_svg('star'); ?>
				</label>

				<div class="note">
					<?php printf( __( 'Single Sign-On allows your customers to have a single login for your WordPress site and your %s store. When someone logs in to your site, they will automatically be logged in to their customer account in your store as well with no need to enter their email/password again.', 'ecwid-shopping-cart'), Ecwid_WL::get_brand() ); ?>
				</div>
				<?php if (!ecwid_is_paid_account()): ?>
				<div class="upgrade-note">
					<a
						class="button ecwid-button button-green" target="_blank"
						href="admin.php?page=ecwid&ecwid_page=<?php echo urlencode(ecwid_get_admin_iframe_upgrade_page()); ?>">
						<?php _e( 'Upgrade to get this feature', 'ecwid-shopping-cart' ); ?>
					</a>
					<div class="note grayed-links">
						<?php printf( __( 'This feature is available on the %s\'s Venture plan and above', 'ecwid-shopping-cart'), Ecwid_WL::get_brand() ); ?>
					</div>
				</div>
				<?php endif; ?>
				<?php if ( !$is_sso_enabled && ecwid_is_paid_account() && !get_option('ecwid_sso_secret_key') && !$has_create_customers_scope): ?>
					<div class="note">
						<?php printf( __( 'To allow %s automatically log in customers to your store, please provide it with a permission to use the customer data in the store. <a %s>Please use this link to do that</a>', 'ecwid-shopping-cart'), Ecwid_WL::get_brand(), 'href="' . $reconnect_link . '"'); ?>
					</div>
				<?php endif; ?>

				<?php if ( !get_option('users_can_register' ) ): ?>
				<div class="note">
					<?php echo sprintf(__('To make sure your customer can actually log in to your site and store, enable registration in the <a %s>site settings</a>', 'ecwid-shopping-cart'), 'href="options-general.php"'); ?>
				</div>
				<?php endif; ?>
			</div>
		</div>

		<hr />

		<div class="pure-control-group checkbox">
			<div class="label">
				<label for="ecwid_use_chameleon">

					<input
						id="ecwid_use_chameleon"
						name="ecwid_use_chameleon"
						type="checkbox"
						<?php if (get_option('ecwid_use_chameleon')): ?>
							checked="checked"
						<?php endif; ?>
						/>
					<?php _e('Chameleon skin', 'ecwid-shopping-cart'); ?>
				</label>

				<div class="note">
					<?php printf( __( 'Automatic adjustment of your store design to your WordPress theme. Whatever WordPress theme you use, %s will detect predominant colors and font and use them in your product catalog.', 'ecwid-shopping-cart'), Ecwid_WL::get_brand() ); ?>
				</div>
				<div class="note grayed-links">
<?php echo sprintf(__( 'Please note this functionality is in beta. So if you run into difficulties or find problems with Chameleon, please <a %s>let us know</a>.', 'ecwid-shopping-cart'), ' target="_blank" href="' . esc_html__( Ecwid_WL::get_contact_us_url(), 'ecwid-shopping-cart' ) . '"' ); ?>
				</div>
		</div>


		<?php $show_categories = ecwid_migrations_is_original_plugin_version_older_than('3.3') || get_option('ecwid_use_new_horizontal_categories') != 'Y'; ?>

		<hr <?php echo $show_categories ? '' : ' hidden'; ?> />

	    <div class="pure-control-group checkbox<?php echo $show_categories ? '' : ' hidden'; ?>">
			<div class="label">
				<label for="ecwid_use_new_horizontal_categories">

					<input
						id="ecwid_use_new_horizontal_categories"
						name="ecwid_use_new_horizontal_categories"
						type="checkbox"
						<?php if (get_option('ecwid_use_new_horizontal_categories') == 'Y'): ?>
							checked="checked"
						<?php endif; ?>
						value="Y"
						/>
					<?php _e('Enable the new category menu', 'ecwid-shopping-cart'); ?>
				</label>

				<div class="note">
					<?php echo sprintf(
						__('The new category menu looks better and is more mobile-friendly. If you haven\'t yet added category menu to your store page, you can do that in the <a %s>store page editor</a> (enable the "Show categories" option)', 'ecwid-shopping-cart'),
						'href="post.php?post=' . ecwid_get_current_store_page_id() . '&action=edit&show-ecwid=true"'
					); ?>
				</div>
			</div>
		</div>

			<hr />

			<div class="pure-control-group checkbox">
				<div class="label">
					<label for="<?php echo Ecwid_Products::OPTION_ENABLED; ?>">

						<input
							id="<?php echo Ecwid_Products::OPTION_ENABLED; ?>"
							name="<?php echo Ecwid_Products::OPTION_ENABLED; ?>"
							type="checkbox"
							<?php if ( Ecwid_Products::is_enabled() ): ?>
								checked="checked"
							<?php endif; ?>
							value="Y"
                            <?php if ( !Ecwid_Products::is_feature_available() ): ?>
                                disabled="disabled"
                            <?php endif; ?>
						/>
						<?php _e('Integration with search on your site <sup>beta</sup>', 'ecwid-shopping-cart'); ?>
					</label>

					<div class="note">
						<?php printf( __( '%s stores your products data in a secure cloud storage. The product pages are displayed on the fly when a customer browses your store. So, basically, the products are not stored on the site, that\'s why the site search doesn\'t find product pages while looking through site pages and posts. This option enables a local storage mode: the products will be stored both in the cloud and on your site. The site search results will list product pages as well as regular pages/posts of your site.', 'ecwid-shopping-cart' ), Ecwid_WL::get_brand() ); ?>
					</div>
				</div>
			</div>

            <?php ecwid_sync_do_page(); ?>

			<div class="note grayed-links">
				<?php echo sprintf(__('Please note this functionality is in beta. So if you run into difficulties or find problems with it, please <a %s>let us know</a>.', 'ecwid-shopping-cart'), ' target="_blank" href="' . esc_html__( Ecwid_WL::get_contact_us_url(), 'ecwid-shopping-cart' ) . '"'); ?>
			</div>

	</fieldset>

	<fieldset>

		<div class="pure-control-group" style="margin-top: 30px">
			<button type="submit" class="<?php echo ECWID_MAIN_BUTTON_CLASS; ?>">
				<?php _e('Save changes', 'ecwid-shopping-cart'); ?>
			</button>
		</div>
	</fieldset>
</form>
</div>