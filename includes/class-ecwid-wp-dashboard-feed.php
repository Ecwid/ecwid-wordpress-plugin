<?php

class Ecwid_WP_Dashboard_Feed {
	public function __construct() {
		add_action( 'wp_dashboard_setup', array( $this, 'dashboard_setup' ) );
	}
	
	public function dashboard_setup() {
		$url = 'https://www.ecwid.com/wp-json/wp/v2/posts?per_page=3&categories=1';
		$media_url = 'https://www.ecwid.com/wp-json/wp/v2/media/';
		
		$lang = get_user_locale();
		if ( $lang == 'ru_RU' ) {
			$url = 'https://www.ecwid.ru/wp-json/wp/v2/posts?per_page=3&categories=1';
			$media_url = 'https://www.ecwid.ru/wp-json/wp/v2/media/';
		}
		
		wp_enqueue_style( 'ecwid-dashboard-blog', ECWID_PLUGIN_URL . '/css/dashboard-blog.css', array( ), get_option('ecwid_plugin_version') );
		
		wp_enqueue_script( 'ecwid-dashboard-blog', ECWID_PLUGIN_URL . '/js/dashboard-blog.js', array( 'jquery' ), get_option('ecwid_plugin_version') );
		wp_localize_script( 'ecwid-dashboard-blog', 'ecwidDashboardBlog', array(
			'posts' => EcwidPlatform::cache_get( 'ecwid-dashboard-blog' ),
			'url' => $url,
			'media_url' => $media_url
		) );
		
		wp_add_dashboard_widget( 'ecwid_blog_feed', 'Ecwid Blog', array( $this, 'display' ) );
	}
	
	public function display() {
		require_once ECWID_PLUGIN_DIR . '/templates/dashboard-blog-posts.tpl.php';
	}
}

$ecwid_wp_dashboard_feed = new Ecwid_WP_Dashboard_Feed();