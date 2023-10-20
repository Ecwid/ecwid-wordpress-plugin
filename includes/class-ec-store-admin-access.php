<?php

class Ec_Store_Admin_Access {

	const CAP_MANAGE_CONTROL_PANEL = 'ec_store_manage_control_panel';

	public function __construct() {
		add_action( 'edit_user_profile', array( $this, 'print_custom_user_profile_fields' ) );
		add_action( 'show_user_profile', array( $this, 'print_custom_user_profile_fields' ) );

		add_action( 'personal_options_update', array( $this, 'save_custom_user_profile_fields' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_custom_user_profile_fields' ) );
	}

	public function save_custom_user_profile_fields( $user_id ) {
		// TODO: check if user has access to edit this role
		// if ( current_user_can( 'manage_options', $user_id ) && wp_verify_nonce( $_POST['_wp_nonce'] )
		if ( ! empty( $_POST['ec_store_admin_access'] ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing
			$user = new WP_User( $user_id );
			$user->add_cap( self::CAP_MANAGE_CONTROL_PANEL, true );
		} else {
			$user = new WP_User( $user_id );
			$user->remove_cap( self::CAP_MANAGE_CONTROL_PANEL );
		}
	}

	public static function has_scope( $user_id = null ) {
		if ( empty( $user_id ) ) {
			return current_user_can( self::CAP_MANAGE_CONTROL_PANEL );
		} else {
			return user_can( $user_id, self::CAP_MANAGE_CONTROL_PANEL );
		}

		return true;
	}

	public function print_custom_user_profile_fields( $user ) { //phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found

		$checked = self::has_scope( $user->ID );

		?>
		<div id="ec-store-control-panel">&nbsp;
			<h2 class="heading">
				<?php
				/* translators: %s: plugin brand */
				echo esc_html( sprintf( __( '%s Control Panel', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ) );
				?>
			</h2>
			<table class="form-table">
			<tr>
				<th><?php echo esc_html( __( 'Access', 'ecwid-shopping-cart' ) ); ?></th>
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
						echo esc_html( sprintf( __( 'Allow the current user to access the %s Control Panel.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ) );
						?>
					</label>
					<a href="https://support.ecwid.com/hc/en-us/articles/207101259?utm_source=wp-plugin#can-i-restrict-access-to-the-ecwid-admin-panel-to-certain-users-" target="_blank"><?php echo esc_html( __( 'More information', 'ecwid-shopping-cart' ) ); ?></a>
				</td>
			</tr>
			</table>
		</div>
		<?php
	}
}

new Ec_Store_Admin_Access();
