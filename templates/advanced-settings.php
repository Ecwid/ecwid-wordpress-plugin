<h2><?php printf( __( '%s — Advanced settings', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></h2>

<div class="wrap">
<form class="pure-form pure-form-aligned ecwid-settings advanced-settings" method="POST" action="options.php">


	<?php settings_fields('ecwid_options_page'); ?>
	<input type="hidden" name="settings_section" value="advanced" />

	<fieldset>

		<?php if ( Ecwid_Seo_Links::should_display_option() ): ?>
            <div class="pure-control-group checkbox">
            <div class="label">
                <label for="<?php echo Ecwid_Seo_Links::OPTION_ENABLED; ?>">

                    <input
                            id="<?php echo Ecwid_Seo_Links::OPTION_ENABLED; ?>"
                            name="<?php echo Ecwid_Seo_Links::OPTION_ENABLED; ?>"
                            type="checkbox"
                        <?php if ( Ecwid_Seo_Links::is_enabled() ): ?>
                            checked="checked"
                        <?php endif; ?>
                            value="Y"
                        <?php if ( !Ecwid_Seo_Links::is_feature_available() ): ?>
                            disabled="disabled"
                        <?php endif; ?>
                    />
                    <?php _e('SEO friendly clean URLs', 'ecwid-shopping-cart'); ?>
                </label>

                <?php if ( Ecwid_Seo_Links::is_feature_available() ): ?>
                    <div class="note">
                        <?php _e( 'This enables new clean URLs format in your store. The new urls do not contain hash sign (\'#\'), so they look nicer and are better indexed by Google.', 'ecwid-shopping-cart' ); ?>
                    </div>
                <?php else: ?>
                    <div class="note">
                        <?php printf( __( 'To enable clean URLs in your store, you will need to enable pretty permalinks in your WordPress site settings. Navigate to <a%s>Settings->Permalink</a> and enable the "Post name" permalink type.', 'ecwid-shopping-cart' ), ' href="' . admin_url( 'options-permalink.php' ) . '"'); ?>
                    </div>
                <?php endif; ?>
                
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
					<?php printf( __( 'Single Sign-On allows your customers to have a single login for your WordPress site and your %s. When someone logs in to your site, they will automatically be logged in to their customer account in your store as well with no need to enter their email/password again.', 'ecwid-shopping-cart'), Ecwid_Config::get_brand() ); ?>
				</div>
				<?php if (!ecwid_is_paid_account()): ?>
				<div class="upgrade-note">
					<a
						class="button ecwid-button button-green" target="_blank"
						href="<?php echo Ecwid_Admin::get_dashboard_url(); ?>&ec-page=<?php echo urlencode( Ecwid_Admin_Main_Page::PAGE_HASH_UPGRADE ); ?>">
						<?php _e( 'Upgrade to get this feature', 'ecwid-shopping-cart' ); ?>
					</a>
					<div class="note grayed-links">
						<?php printf( __( 'Please subscribe to a paid plan to get this feature.', 'ecwid-shopping-cart'), Ecwid_Config::get_brand() ); ?>
					</div>
				</div>
				<?php endif; ?>
				<?php if ( !$is_sso_enabled && ecwid_is_paid_account() && !get_option('ecwid_sso_secret_key') && !$has_create_customers_scope): ?>
					<div class="note">
						<?php printf( __( 'To allow %s automatically log in customers to your store, please provide it with a permission to use the customer data in the store. <a %s>Please use this link to do that</a>', 'ecwid-shopping-cart'), Ecwid_Config::get_brand(), 'href="' . $reconnect_link . '"'); ?>
					</div>
				<?php endif; ?>

				<?php if ( !get_option('users_can_register' ) ): ?>
				<div class="note">
					<?php echo sprintf(__('To make sure your customer can actually log in to your site and store, enable registration in the <a %s>site settings</a>', 'ecwid-shopping-cart'), 'href="options-general.php"'); ?>
				</div>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( get_option( 'ecwid_use_chameleon' ) ): ?>

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
					<?php printf( __( 'Automatic adjustment of your store design to your WordPress theme. Whatever WordPress theme you use, %s will detect predominant colors and font and use them in your product catalog.', 'ecwid-shopping-cart'), Ecwid_Config::get_brand() ); ?>
				</div>
				<div class="note grayed-links">
<?php echo sprintf(__( 'Please note this functionality is in beta. So if you run into difficulties or find problems with Chameleon, please <a %s>let us know</a>.', 'ecwid-shopping-cart'), ' target="_blank" href="' . esc_html__( Ecwid_Config::get_contact_us_url(), 'ecwid-shopping-cart' ) . '"' ); ?>
				</div>
		</div>
        <?php endif; ?>
        <hr />

        <?php if ( count ( Ecwid_Store_Page::get_store_pages_array_for_selector() ) > 1 ): ?>
            <div class="pure-control-group">
                <div class="label">
                    <label for="<?php echo Ecwid_Store_Page::OPTION_MAIN_STORE_PAGE_ID; ?>">
                        <?php _e('Main storefront page', 'ecwid-shopping-cart'); ?>
                    </label>
                    <select id="<?php echo Ecwid_Store_Page::OPTION_MAIN_STORE_PAGE_ID; ?>" name="<?php echo Ecwid_Store_Page::OPTION_MAIN_STORE_PAGE_ID; ?>">
                        <?php foreach ( Ecwid_Store_Page::get_store_pages_array_for_selector() as $id ): ?>
                            <option
                                    value="<?php echo $id; ?>"
                                <?php if ( $id == Ecwid_Store_Page::get_current_store_page_id() ): ?>
                                    selected="selected"
                                <?php endif; ?>
                            ><?php esc_html_e( get_post( $id )->post_title ); ?></option>
                        <?php endforeach; ?>
                    </select>
                

                    <div class="note">
                        <?php _e( 'You have your storefront added to several pages on your site. You can choose the main storefront page here — the store navigation menus and sidebar widgets will open it.', 'ecwid-shopping-cart' ); ?>
                    </div>
                </div>
            </div>
            <hr />

        <?php endif; ?>


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
                    <?php echo sprintf( __( '%s stores your products data in a secure cloud storage. The product pages are displayed on the fly when a customer browses your store. So, basically, the products are not stored on the site, that\'s why the site search doesn\'t find product pages while looking through site pages and posts. This option enables a local storage mode: the products will be stored both in the cloud and on your site. The site search results will list product pages as well as regular pages/posts of your site.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?>
                </div>
            </div>
        </div>
            
        <?php ecwid_sync_do_page(); ?>

        <div class="note grayed-links">
            <?php echo sprintf(__('Please note this functionality is in beta. So if you run into difficulties or find problems with it, please <a %s>let us know</a>.', 'ecwid-shopping-cart'), ' target="_blank" href="' . Ecwid_Config::get_contact_us_url() . '"'); ?>
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