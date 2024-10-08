<?php

class Ecwid_WP_Dashboard_Feed {
	const CACHE_POSTS                          = 'wp-dashboard-blog-posts';
	const ACTION_AJAX_SAVE                     = 'ecwid-save-posts';
	const PARAM_LAST_ECWID_ADMIN_PREFETCH_TIME = 'last-admin-prefetch-time';

	public function __construct() {
		if ( Ecwid_Config::is_wl() ) {
			return;
		}

		add_action( 'wp_dashboard_setup', array( $this, 'dashboard_setup' ) );

		add_action( 'wp_ajax_' . self::ACTION_AJAX_SAVE, array( $this, 'ajax_save_posts' ) );
	}

	public function dashboard_setup() {

		if ( ! current_user_can( 'administrator' ) && ! current_user_can( 'editor' ) ) {
			return;
		}

		$url        = 'https://www.ecwid.com/wp-json/wp/v2/posts?per_page=3&categories=1';
		$media_url  = 'https://www.ecwid.com/wp-json/wp/v2/media/';
		$images_cdn = 'https://web-cdn.ecwid.com/wp-content/uploads/';

		wp_enqueue_style( 'ecwid-dashboard-blog', ECWID_PLUGIN_URL . '/css/dashboard-blog.css', array(), get_option( 'ecwid_plugin_version' ) );

		wp_enqueue_script( 'ecwid-dashboard-blog', ECWID_PLUGIN_URL . '/js/dashboard-blog.js', array( 'jquery' ), get_option( 'ecwid_plugin_version' ) );
		wp_localize_script(
			'ecwid-dashboard-blog',
			'ecwidDashboardBlog',
			array(
				'posts'      => EcwidPlatform::cache_get( $this->get_cache_name() ),
				'url'        => $url,
				'mediaUrl'   => $media_url,
				'imagesCDN'  => $images_cdn,
				'saveAction' => self::ACTION_AJAX_SAVE,
			)
		);

		wp_add_dashboard_widget(
			'ecwid_blog_feed',
			/* translators: %s: brand */
			sprintf( __( '%s Blog', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ),
			array( $this, 'display' )
		);
	}

	public function ajax_save_posts() {
		if ( ! current_user_can( Ecwid_Admin::get_capability() ) ) {
			die();
		}

		check_ajax_referer( 'ec_admin', '_ajax_nonce' );

		if ( ! isset( $_POST['posts'] ) ) {
			die();
		}

		$posts = map_deep( wp_unslash( $_POST['posts'] ), 'sanitize_text_field' );

		EcwidPlatform::cache_set( $this->get_cache_name(), $posts, 12 * HOUR_IN_SECONDS );

		header( 'HTTP/1.0 200 OK' );
		die();
	}

	protected function get_cache_name() {
		$name  = self::CACHE_POSTS;
		$name .= '-' . ecwid_get_current_user_locale();

		return $name;
	}


	public function display() {
		require_once ECWID_PLUGIN_DIR . '/templates/dashboard-blog-posts.tpl.php';

		if ( EcwidPlatform::get( self::PARAM_LAST_ECWID_ADMIN_PREFETCH_TIME ) > time() + HOUR_IN_SECONDS * 12 ) {
			$dashboard_url = ecwid_get_iframe_src( time(), 'dashboard' );
			?>
			<div style="display:none">
				<iframe id="ecwid-prefetch" src=""></iframe>
				<script type="text/javascript">
					jQuery(document).ready(function() {
					jQuery('#ecwid-prefetch').attr('src', '<?php esc_url( $dashboard_url ); ?>'); 
					});	
				</script>
			</div>
			<?php
			EcwidPlatform::set( self::PARAM_LAST_ECWID_ADMIN_PREFETCH_TIME, time() );
		}
	}
}

$ecwid_wp_dashboard_feed = new Ecwid_WP_Dashboard_Feed();
