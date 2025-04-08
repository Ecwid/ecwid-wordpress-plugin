<?php
class Ec_Store_Oembed {

	public static function print_content( $data ) { //phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
		echo self::get_content(); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public static function get_content() {
		$post_content = get_post( get_the_ID() )->post_content;
		$shortcodes   = array();

		foreach ( Ecwid_Shortcode_Base::get_store_shortcode_names() as $shortcode_name ) {
			$shortcodes = ecwid_find_shortcodes( $post_content, $shortcode_name );
			if ( $shortcodes ) {
				break;
			}
		}

		if ( ! $shortcodes || ! isset( $shortcodes[0] ) || ! isset( $shortcodes[0][3] ) ) {
			return;
		}

		$attributes = $shortcodes[0][3];
		if ( ! preg_match( '/default_category_id=.([\\d]*)./', $attributes, $matches ) ) {
			return;
		}

		$root_category_id = intval( $matches[1] ) <= 0 ? 0 : $matches[1];

		$api = new Ecwid_Api_V3();

		$categories = $api->get_categories( array( 'parent' => $root_category_id ) );

		$max_items = 5;

		$items = array();

		$see_more = false;
		$result   = '';
		if ( ! empty( $categories->items ) ) {
			foreach ( $categories->items as $category ) {
				$category                 = Ecwid_Category::get_by_id( $category->id );
				$items[ $category->url ] = $category->name;
				if ( count( $items ) >= $max_items ) {
					$see_more = true;
					break;
				}
			}
		}

		$api = new Ecwid_Api_V3();

		$category = $api->get_category( $root_category_id );

		if ( $category ) {
			$trimmed = ecwid_trim_description( $category->description );
			$result .= '<div>' . ecwid_trim_description( $category->description );

			$descr_length   = function_exists( 'mb_strlen' ) ? mb_strlen( $category->description ) : strlen( $category->description );
			$trimmed_length = function_exists( 'mb_strlen' ) ? mb_strlen( $trimmed ) : strlen( $trimmed );

			if ( $trimmed_length < $descr_length && $trimmed_length == ECWID_TRIMMED_DESCRIPTION_LENGTH ) {
				$result .= '... <a class="wp-embed-more" href="' . get_permalink() . '">' . __( 'See more', 'ecwid-shopping-cart' ) . '</a>';
			}
			$result .= '</div>';
		}

		if ( ! $see_more ) {
			$products = $api->search_products( array( 'category' => $root_category_id ) );

			if ( $products->items ) {
				foreach ( $products->items as $product ) {
					$product                 = Ecwid_Product::get_by_id( $product->id );
					$items[ $product->url ] = $product->name;
					if ( count( $items ) >= $max_items ) {
						$see_more = true;
						break;
					}
				}
			}
		}

		$result .= '<ul>';
		if ( $items ) {
			foreach ( $items as $url => $title ) {
				$result .= '<li><a href="' . esc_attr( $url ) . '">' . esc_html( $title ) . '</a></li>';
			}
		}

		if ( $see_more ) {
			$result .= '<li><a class="wp-embed-more" href="' . get_permalink() . '">' . __( 'See more', 'ecwid-shopping-cart' ) . '</a></li>';
		}

		$result .= '</ul>';

		return $result;
	}

	public static function ecwid_oembed_url( $url, $permalink, $format ) {

		if ( ! Ecwid_Seo_Links::is_product_browser_url() ) {
			return $url;
		}

        if( Ecwid_Seo_Links::is_slugs_without_ids_enabled() ) {
            $permalink = trailingslashit( $permalink );
            $slug = Ecwid_Static_Page::get_current_storefront_page_slug();
            $permalink .= $slug;
        } else {
            $params = Ecwid_Seo_Links::maybe_extract_html_catalog_params();

            if ( $params['mode'] == 'product' ) {
                $product   = Ecwid_Product::get_by_id( $params['id'] );
                $permalink = $product->link;
            } elseif ( $params['mode'] == 'category' ) {
                $category  = Ecwid_Category::get_by_id( $params['id'] );
                $permalink = $category->link;
            }
        }

		$url = add_query_arg(
			array(
				'url'    => rawurlencode( $permalink ),
				'format' => ( 'json' !== $format ) ? $format : false,
			),
			$url
		);

		return $url;
	}
}

add_filter( 'embed_content', 'Ec_Store_Oembed::print_content', 10, 1 );
add_filter( 'oembed_endpoint_url', 'Ec_Store_Oembed::ecwid_oembed_url', 10, 3 );
