<?php

class EcwidPlatform {

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
            $result = file_get_contents($url);
        } else {
            $result = wp_remote_get( $url, array( 'timeout' => get_option( 'ecwid_remote_get_timeout', 5 ) ) );
            if (!is_array($result)) {
                $result = file_get_contents($url);
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
}
