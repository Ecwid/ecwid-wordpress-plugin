<?php 

abstract class Ecwid_HTML_Meta
{
	protected function __construct()
	{
		$this->_init();
	}


	protected function _init()
	{
		add_action( 'wp_head', array( $this, 'wp_head' ) );
	}
	
	abstract public function wp_head();
	
	public static function maybe_create()
	{
		$params = Ecwid_Seo_Links::maybe_extract_html_catalog_params();
	
		$obj = null;
		if ( ! empty( $params ) ) {
			$obj = null;
	
			if ( $params['mode'] == 'product' ) {
				$obj = new Ecwid_HTML_Meta_Product( $params['id'] );
			} else if ( $params['mode'] == 'category' ) {
				$obj = new Ecwid_HTML_Meta_Category( $params['id'] );
			} 
	
		}
		
		if ( Ecwid_Seo_Links::is_noindex_page() ) {
			$obj = new Ecwid_HTML_Meta_Noindex();
		}
		
		return $obj;
	}

	// static only while ecwid_trim_description exists and meta functionality is not moved into this class
	public static function process_raw_description( $description, $length = 0 ) {
		$description = strip_tags( $description );
		$description = html_entity_decode( $description, ENT_NOQUOTES, 'UTF-8' );

		$description = preg_replace( '![\p{Z}\s]{1,}!u', ' ', $description );
		$description = trim( $description, " \t\xA0\n\r" ); // Space, tab, non-breaking space, newline, carriage return

		if ( function_exists( 'mb_substr' ) ) {
			$description = mb_substr( $description, 0, $length ? $length : ECWID_TRIMMED_DESCRIPTION_LENGTH, 'UTF-8' );
		} else {
			$description = substr( $description, 0, $length ? $length : ECWID_TRIMMED_DESCRIPTION_LENGTH );
		}
		$description = htmlspecialchars( $description, ENT_COMPAT, 'UTF-8' );

		return $description;
	}
}

abstract class Ecwid_HTML_Meta_Catalog_Entry extends Ecwid_HTML_Meta {
	
	protected $id;

	protected function __construct($id)
	{
		$this->id = $id;

		parent::__construct();
	}

	public function wp_head()
	{
		$this->_print_og_tags();
	}

	protected function _print_og_tags()
	{
		$og_tags_html = Ecwid_Static_Page::get_og_tags_html();

		echo $og_tags_html;
	}

	protected function _get_site_name()
	{
		return get_bloginfo( 'name' );
	}

	protected function _get_description( $length )
	{
		$raw = $this->_get_raw_description();

		return self::process_raw_description( $raw, $length );
	}

	abstract protected function _get_title();

	abstract protected function _get_raw_description();

	abstract protected function _get_url();

	abstract protected function _get_image_url();

	// static only while ecwid_trim_description exists and meta functionality is not moved into this class
	public static function process_raw_description( $description, $length = 0 ) {
		$description = strip_tags( $description );
		$description = html_entity_decode( $description, ENT_NOQUOTES, 'UTF-8' );

		$description = preg_replace( '![\p{Z}\s]{1,}!u', ' ', $description );
		$description = trim( $description, " \t\xA0\n\r" ); // Space, tab, non-breaking space, newline, carriage return

		if ( function_exists( 'mb_substr' ) ) {
			$description = mb_substr( $description, 0, $length ? $length : ECWID_TRIMMED_DESCRIPTION_LENGTH, 'UTF-8' );
		} else {
			$description = substr( $description, 0, $length ? $length : ECWID_TRIMMED_DESCRIPTION_LENGTH );
		}
		$description = htmlspecialchars( $description, ENT_COMPAT, 'UTF-8' );

		return $description;
	}
}

class Ecwid_HTML_Meta_Product extends Ecwid_HTML_Meta_Catalog_Entry {
	
	protected $product;
	protected function _init()
	{
		parent::_init();
		$this->product = Ecwid_Product::get_by_id( $this->id );
	}
	
	protected function _get_title()
	{
		return @$this->product->name;
	}
	
	protected function _get_raw_description()
	{
		return @$this->product->description;
	}
	
	protected function _get_url()
	{
		return @$this->product->link;
	}

	protected function _get_image_url()
	{
		return @$this->product->hdThumbnailUrl ? @$this->product->hdThumbnailUrl : @$this->product->thumbnailUrl;
	}
}

class Ecwid_HTML_Meta_Category extends Ecwid_HTML_Meta_Catalog_Entry {

	protected $category;
	protected function _init()
	{
		parent::_init();
		$this->category = Ecwid_Category::get_by_id( $this->id );
	}


	protected function _get_title()
	{
		return @$this->category->name;
	}

	protected function _get_raw_description()
	{
		return @$this->category->description;
	}
	
	protected function _get_url()
	{
		return @$this->category->link;
	}

	protected function _get_image_url()
	{
		return @$this->category->hdThumbnailUrl ? @$this->category->hdThumbnailUrl : @$this->category->thumbnailUrl;
	}
}

class Ecwid_HTML_Meta_Noindex extends Ecwid_HTML_Meta {
	public function wp_head()
	{
		echo '<meta name="robots" content="noindex">';
	}	
}

add_action( 'wp', array( 'Ecwid_HTML_Meta', 'maybe_create' ) );