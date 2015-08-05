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
    $result = wp_remote_get($url);

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
    }

    return $return;

	}
}
