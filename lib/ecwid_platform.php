<?php

class EcwidPlatform {

	static protected $http_use_streams = false;

	static public function esc_attr($value)
	{
		return esc_attr($value);
	}

	static public function esc_html($value)
	{
		return esc_html($value);
	}

	static public function get_price_label()
	{
		return __('Price', 'ecwid-shopping-cart');
	}

	static public function fetch_url($url)
	{
        $use_file_get_contents = get_option('ecwid_fetch_url_use_file_get_contents', false);

        if ($use_file_get_contents == 'Y') {
            $result = @file_get_contents($url);
        } else {
						if (get_option('ecwid_http_use_stream', false)) {
							self::$http_use_streams = true;
						}
            $result = wp_remote_get( $url, array( 'timeout' => get_option( 'ecwid_remote_get_timeout', 5 ) ) );

						if (get_option('ecwid_http_use_stream', false)) {
							self::$http_use_streams = false;
						}
						if (!is_array($result)) {
                $result = @file_get_contents($url);
                if (!empty($result)) {
                    update_option('ecwid_fetch_url_use_file_get_contents', true);
                }
            }
        }

        $return = array(
          'code' => '',
          'data' => '',
          'message' => ''
        );

        if (is_array($result)) {
          $return = array(
            'code' => $result['response']['code'],
            'data' => $result['body']
          );
        } elseif (is_object($result)) {

          $return = array(
            'code' => $result->get_error_code(),
            'data' => $result->get_error_data(),
            'message' => $result->get_error_message()
          );

            $get_contents = file_get_contents($url);
            if ($get_contents !== false) {
                $return = array(
                    'code' => 200,
                    'data' => $get_contents,
                    'is_file_get_contents' => true
                );
            }
        }

        return $return;
	}

	static public function http_get_request($url) {
		return self::fetch_url($url);
	}

	static public function http_post_request($url, $data = array())
	{
		$result = null;
		if (get_option('ecwid_http_use_stream', false) !== true) {
			$result = wp_remote_post(
				$url,
				array( 'body' => $data )
			);
		}

		if ( !is_array($result) ) {
			self::$http_use_streams = true;
			$result = wp_remote_post(
				$url,
				array('body' => $data)
			);
			self::$http_use_streams = false;

			if ( is_array($result) ) {
				update_option('ecwid_http_use_stream', true);
			}
		}

		return $result;
	}

	static public function http_api_transports($transports)
	{
		if (self::$http_use_streams) {
			return array('streams');
		}

		return $transports;
	}
}

add_filter('http_api_transports', array('EcwidPlatform', 'http_api_transports'));
