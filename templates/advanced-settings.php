<div class="wrap">
<form class="pure-form pure-form-aligned ecwid-settings advanced-settings" method="POST" action="options.php">

	<h2><?php _e('Ecwid Shopping Cart — Advanced settings', 'ecwid-shopping-cart'); ?></h2>

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
					<?php _e('Single Sign-On allows your customers to have a single login for your WordPress site and your Ecwid store. When someone logs in to your site, they will automatically be logged in to their customer account in your store as well with no need to enter their email/password again.', 'ecwid-shopping-cart'); ?>
				</div>
				<?php if (!ecwid_is_paid_account()): ?>
				<div class="upgrade-note">
					<a
						class="button ecwid-button button-green" target="_blank"
						href="admin.php?page=ecwid&ecwid_page=<?php echo urlencode(ecwid_get_admin_iframe_upgrade_page()); ?>">
						<?php _e( 'Upgrade to get this feature', 'ecwid-shopping-cart' ); ?>
					</a>
					<div class="note grayed-links">
						<?php echo sprintf(__('This feature is available on the Ecwid\'s Venture plan and above', 'ecwid-shopping-cart'), ''); ?>
					</div>
				</div>
				<?php endif; ?>
				<?php if ( !$is_sso_enabled && ecwid_is_paid_account() && !get_option('ecwid_sso_secret_key') && !$has_create_customers_scope): ?>
					<div class="note">
						<?php echo sprintf(__('To allow Ecwid automatically log in customers to your store, please provide it with a permission to use the customer data in the store. <a %s>Please use this link to do that</a>', 'ecwid-shopping-cart'), 'href="' . $reconnect_link . '"'); ?>
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
					<?php _e('Chameleon skin <sup>beta</sup>', 'ecwid-shopping-cart'); ?>
				</label>

				<div class="note">
					<?php _e('Automatic adjustment of your store design to your Wordpress theme. Whatever Wordpress theme you use, Ecwid will detect predominant colors and font and use them in your product catalog.', 'ecwid-shopping-cart'); ?>
				</div>
				<div class="note grayed-links">
<?php echo sprintf(__('Please note this functionality is in beta. So if you run into difficulties or find problems with Chameleon, please <a %s>let us know</a>.', 'ecwid-shopping-cart'), ' target="_blank" href="' . __('https://support.ecwid.com/hc/en-us/requests/new', 'ecwid-shopping-cart') . '"'); ?>
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

			<hr <?php echo ecwid_migrations_is_original_plugin_version_older_than('4.1.3.1') ? '' : ' hidden'; ?> />

			<div class="pure-control-group checkbox<?php echo ecwid_migrations_is_original_plugin_version_older_than('4.1.3.1') ? '' : ' hidden'; ?>">
				<div class="label">
					<label for="ecwid_use_new_search">

						<input
							id="ecwid_use_new_search"
							name="ecwid_use_new_search"
							type="checkbox"
							<?php if (get_option('ecwid_use_new_search') == 'Y'): ?>
								checked="checked"
							<?php endif; ?>
							value="Y"
						/>
						<?php _e('Enable the new search widget', 'ecwid-shopping-cart'); ?>
					</label>

					<div class="note">
						<?php echo sprintf(
							__('The new search widget better adapts to your site and looks nicer. You can add the search bar to your site either in the <a %s>store page editor</a> or in the <a %s>Appearance -> Widgets</a> section.', 'ecwid-shopping-cart'),
							'href="post.php?post=' . ecwid_get_current_store_page_id() . '&action=edit&show-ecwid=true"',
							'href=widgets.php?from-ecwid=true'
						); ?>
					</div>
				</div>
			</div>

	</fieldset>

	<fieldset>

		<div class="pure-control-group">
			<button type="submit" class="<?php echo ECWID_MAIN_BUTTON_CLASS; ?>">
				<?php _e('Save changes', 'ecwid-shopping-cart'); ?>
			</button>
		</div>
	</fieldset>
</form>
</div>


<script type="text/javascript">
	ecwid_kissmetrics_record('Advanced Page Viewed');
</script>