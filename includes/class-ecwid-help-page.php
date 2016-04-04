<?php

class Ecwid_Help_Page {
	public function get_faqs() {
		global $faqs;

		include 'faq_entries.php';

		$result = array();
		foreach ($faqs as $faq) {
			$faq['href'] = 'https://help.ecwid.com/' . $faq['href'];
			$faq['body'] = preg_replace('!<img alt="" src="([^"]*)"!', '<img alt="" src="' . ECWID_PLUGIN_URL . '/images/help/' . '$1"', $faq['body']);
			$result[] = (object) $faq;
		}

		return $result;
	}
}