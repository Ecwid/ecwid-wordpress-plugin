<?php

class Ec_Store_Admin_Access {

	const CAP_MANAGE_CONTROL_PANEL = 'ec_store_manage_control_panel';
	const CAP_CAN_GRANT_ACCESS     = 'ec_store_can_grant_access';

	protected $capability;

	public function __construct() {
		if ( is_admin() ) {
			add_action( 'edit_user_profile', array( $this, 'print_custom_user_profile_fields' ) );
			add_action( 'show_user_profile', array( $this, 'print_custom_user_profile_fields' ) );
			add_action( 'user_new_form', array( $this, 'print_custom_user_profile_fields' ) );

			add_action( 'personal_options_update', array( $this, 'save_custom_user_profile_fields' ) );
			add_action( 'edit_user_profile_update', array( $this, 'save_custom_user_profile_fields' ) );
			add_action( 'user_register', array( $this, 'save_custom_user_profile_fields' ) );

			add_action( 'ecwid_authorization_success', array( $this, 'hook_add_cap_for_current_user' ) );

			add_filter( 'additional_capabilities_display', '__return_false', 10, 2 );
		}

		add_filter( 'ec_store_admin_get_capability', array( $this, 'hook_admin_get_capability' ) );
	}

	public function save_custom_user_profile_fields( $user_id ) {

		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return;
		}

		$user = new WP_User( $user_id );

		if ( ! empty( $_POST['ec_store_admin_access'] ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing
			$user->add_cap( self::CAP_MANAGE_CONTROL_PANEL, true );
		} else {
			$user->add_cap( self::CAP_MANAGE_CONTROL_PANEL, false );
		}
	}

	public static function get_users_with_manage_access() {
		$args = array(
			'meta_query' => array( //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => 'wp_capabilities',
					'value'   => self::CAP_MANAGE_CONTROL_PANEL . '";b:1',
					'compare' => 'LIKE',
				),
			),
			'fields'     => array( 'ID' ),
		);

		return get_users( $args );
	}

	public static function has_scope( $user_id = null ) {
		$has_scope = false;

		if ( empty( $user_id ) ) {
			$has_scope = current_user_can( self::CAP_MANAGE_CONTROL_PANEL );
		} else {
			$has_scope = user_can( $user_id, self::CAP_MANAGE_CONTROL_PANEL );
		}

		if ( ! $has_scope && self::is_need_grant_access_by_default( $user_id ) ) {
			$has_scope = true;
		}

		return $has_scope;
	}

	public static function is_need_grant_access_by_default( $user_id ) {

		$cap_not_changed_before = empty( self::get_users_with_manage_access() );
		$is_old_installation    = ecwid_migrations_is_original_plugin_version_older_than( '6.12.4' );

		if ( $cap_not_changed_before && $is_old_installation && user_can( $user_id, 'manage_options' ) ) {
			return true;
		}

		return false;
	}

	public function can_grant_access() {
		if ( current_user_can( self::CAP_CAN_GRANT_ACCESS ) ) {
			return true;
		}

		$args = array(
			'capability' => self::CAP_CAN_GRANT_ACCESS,
			'fields'     => array( 'ID' ),
		);
		if ( empty( get_users( $args ) ) && is_super_admin() ) {
			return true;
		}

		return false;
	}

	public function hook_add_cap_for_current_user() {
		$user_id = get_current_user_id();
		$user    = new WP_User( $user_id );

		$user->add_cap( self::CAP_MANAGE_CONTROL_PANEL, true );
		$user->add_cap( self::CAP_CAN_GRANT_ACCESS, true );
	}

	public function hook_admin_get_capability( $cap ) {

		if ( ! empty( $this->capability ) ) {
			return $this->capability;
		}

		$args = array(
			'meta_query' => array( //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => 'wp_capabilities',
					'value'   => self::CAP_MANAGE_CONTROL_PANEL . '";b:1',
					'compare' => 'LIKE',
				),
			),
			'fields'     => array( 'ID' ),
		);

		if ( ! empty( self::get_users_with_manage_access() ) ) {
			$cap = self::CAP_MANAGE_CONTROL_PANEL;
		}

		$this->capability = $cap;

		return $cap;
	}

	public function print_custom_user_profile_fields( $user ) {

		if ( ! $this->can_grant_access() ) {
			return false;
		}

		if ( $user === 'add-new-user' ) {
			$checked = false;
		} else {
			$checked = self::has_scope( $user->ID );
		}
		?>
		<div id="ec-store-control-panel">
			<?php if ( $user !== 'add-new-user' ) { ?>
			&nbsp;
			<h2 class="heading">
				<?php
				/* translators: %s: plugin brand */
				echo esc_html( sprintf( __( '%s Store', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ) );
				?>
			</h2>
			<?php } ?>
			<table class="form-table">
			<tr>
				<th>
					<?php
					/* translators: %s: plugin brand */
					echo esc_html( sprintf( __( 'Access to %s Control Panel', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ) );
					?>
				</th>
				<td>
					<label for="ec_store_admin_access">
						<input 
							type="checkbox"
							name="ec_store_admin_access"
							id="ec_store_admin_access"
							value="1"
							<?php echo ( $checked ) ? 'checked' : ''; ?>
						/>
						<?php
						/* translators: %s: plugin brand */
						echo esc_html( __( 'Allow this user to access your store’s control panel and change settings.', 'ecwid-shopping-cart' ) );
						?>
					</label>
					<?php if ( ! Ecwid_Config::is_wl() ) { ?>
					<a href="https://support.ecwid.com/hc/en-us/articles/207101259#how-can-i-control-access-to-the-ecwid-admin-panel-for-certain-users-" target="_blank"><?php echo esc_html( __( 'Learn more', 'ecwid-shopping-cart' ) ); ?></a>
					<?php } ?>
				</td>
			</tr>
			</table>
		</div>
		<?php
	}
}

new Ec_Store_Admin_Access();
