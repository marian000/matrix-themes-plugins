<?php
/**
 * ProductHandler: WooCommerce product creation, update, and cart management.
 *
 * Extracts product persistence logic from ajax-prod.php and ajax-prod-update.php
 * into a dedicated class, separating pricing calculation from data storage.
 *
 * @package ShutterModule
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Ensure PricingResult is available for type hints.
require_once __DIR__ . '/class-pricing-result.php';

/**
 * Class ProductHandler
 *
 * Handles WooCommerce product creation, updating, meta persistence,
 * stale meta cleanup, cart operations, and shipping class assignment.
 *
 * @since 1.0.0
 */
class ProductHandler {

	/**
	 * Create a new WooCommerce product from configurator data.
	 *
	 * Extracted from ajax-prod.php: wp_insert_post + initial meta setup +
	 * attribute loop + pricing meta saves.
	 *
	 * @since 1.0.0
	 * @param array         $products The parsed form data from the configurator.
	 * @param PricingResult $result   The pricing calculation result.
	 * @param int           $user_id  The user ID (author of the product).
	 * @return int The new product post ID.
	 */
	public function createProduct( array $products, PricingResult $result, int $user_id ) {
		$atribute = get_post_meta( 1, 'attributes_array', true );

		$post = array(
			'post_author'  => $user_id,
			'post_content' => '',
			'post_status'  => 'publish',
			'post_title'   => $products['page_title'] . '-' . $products['property_room_other'],
			'post_parent'  => '',
			'post_type'    => 'product',
		);

		$post_id = wp_insert_post( $post, true );

		wp_set_object_terms( $post_id, 'simple', 'product_type' );

		// Set shutter category based on page title prefix.
		$pieces = explode( '-', $products['page_title'] );
		if ( ( $pieces[0] == 'Shutter' ) || ( $pieces[0] == 'prod1' ) ) {
			update_post_meta( $post_id, 'shutter_category', 'Shutter' );
		} elseif ( ( $pieces[0] == 'Shutter & Blackout Blind' ) || ( $pieces[0] == 'prod2' ) ) {
			update_post_meta( $post_id, 'shutter_category', 'Shutter & Blackout Blind' );
		}
		update_post_meta( $post_id, 'attachmentDraw', $products['attachmentDraw'] );
		update_post_meta( $post_id, '_visibility', 'visible' );
		update_post_meta( $post_id, '_stock_status', 'instock' );

		update_post_meta( $post_id, '_purchase_note', '' );
		update_post_meta( $post_id, '_featured', 'no' );
		update_post_meta( $post_id, '_sku', '' );
		update_post_meta( $post_id, '_sale_price_dates_from', '' );
		update_post_meta( $post_id, '_sale_price_dates_to', '' );

		update_post_meta( $post_id, '_sold_individually', '' );
		update_post_meta( $post_id, '_manage_stock', 'no' );
		update_post_meta( $post_id, '_backorders', 'no' );
		update_post_meta( $post_id, '_stock', '50' );
		update_post_meta( $post_id, '_stock_status', 'instock' );

		// Save pricing-related meta from PricingResult.
		$this->savePricingMeta( $post_id, $result, $products );

		// Save all product attributes and property meta.
		$this->saveProductMeta( $post_id, $products, $result, $atribute );

		// Save current material price snapshot.
		saveCurrentPriceItem( $post_id, $user_id );

		// Save USD price.
		update_post_meta( $post_id, 'dolar_price', floatval( $result->usd_price ) );

		return $post_id;
	}

	/**
	 * Update an existing WooCommerce product with new configurator data.
	 *
	 * Extracted from ajax-prod-update.php: meta updates + attribute loop +
	 * pricing meta saves.
	 *
	 * @since 1.0.0
	 * @param int           $post_id  The existing product post ID.
	 * @param array         $products The parsed form data from the configurator.
	 * @param PricingResult $result   The pricing calculation result.
	 * @param int           $user_id  The pricing user ID (for rate snapshots).
	 * @return void
	 */
	public function updateProduct( int $post_id, array $products, PricingResult $result, int $user_id ) {
		$atribute = get_post_meta( 1, 'attributes_array', true );

		// Save pricing-related meta from PricingResult.
		$this->savePricingMeta( $post_id, $result, $products );

		// Save all product attributes and property meta.
		$this->saveProductMeta( $post_id, $products, $result, $atribute );

		// Save current material price snapshot.
		saveCurrentPriceItem( $post_id, $user_id );

		// SVG storage removed for security (Task #005).
		update_post_meta( $post_id, 'attachmentDraw', $products['attachmentDraw'] );
		update_post_meta( $post_id, 'comments_customer', $products['comments_customer'] );

		// Save USD price.
		update_post_meta( $post_id, 'dolar_price', floatval( $result->usd_price ) );
	}

	/**
	 * Save all product attributes and property values as post meta.
	 *
	 * This is the large foreach loop common to both create and update flows.
	 * It iterates over the $products array, saves each field as post meta,
	 * counts T/G/B/C posts, and builds the WooCommerce _product_attributes array.
	 *
	 * @since 1.0.0
	 * @param int           $post_id  The product post ID.
	 * @param array         $products The parsed form data.
	 * @param PricingResult $result   The pricing calculation result.
	 * @param array         $atribute The global attributes lookup array (from post ID 1).
	 * @return void
	 */
	private function saveProductMeta( int $post_id, array $products, PricingResult $result, $atribute ) {
		$g = 0;
		$t = 0;
		$b = 0;
		$c = 0;
		$i = 0;

		$product_attributes = array();
		foreach ( $products as $name_attr => $id ) {
			update_post_meta( $post_id, $name_attr, $id );

			// Uppercase layout codes: property_layoutcode and property_layoutcodeN (per-section).
			if ( $name_attr == 'property_layoutcode' || preg_match( '/^property_layoutcode\d+$/', $name_attr ) ) {
				update_post_meta( $post_id, $name_attr, strtoupper( $id ) );
			}

			if ( ! is_array( $id ) ) {

				if ( $name_attr == "property_t1" || $name_attr == "property_t2" || $name_attr == "property_t3" || $name_attr == "property_t4" || $name_attr == "property_t5" || $name_attr == "property_t6" || $name_attr == "property_t7" || $name_attr == "property_t8" || $name_attr == "property_t9" || $name_attr == "property_t10" || $name_attr == "property_t11" || $name_attr == "property_t12" || $name_attr == "property_t13" || $name_attr == "property_t14" || $name_attr == "property_t15" ) {
					if ( $id !== '' && $id !== '0' && $id !== 0 ) {
						$t++;
					}
				} elseif ( $name_attr == "property_g1" || $name_attr == "property_g2" || $name_attr == "property_g3" || $name_attr == "property_g4" || $name_attr == "property_g5" || $name_attr == "property_g6" || $name_attr == "property_g7" || $name_attr == "property_g8" || $name_attr == "property_g9" || $name_attr == "property_g10" || $name_attr == "property_g11" || $name_attr == "property_g12" || $name_attr == "property_g13" || $name_attr == "property_g14" || $name_attr == "property_g15" ) {
					if ( $id !== '' && $id !== '0' && $id !== 0 ) {
						$g++;
					}
				} elseif ( $name_attr == "property_bp1" || $name_attr == "property_bp2" || $name_attr == "property_bp3" || $name_attr == "property_bp4" || $name_attr == "property_bp5" || $name_attr == "property_bp6" || $name_attr == "property_bp7" || $name_attr == "property_bp8" || $name_attr == "property_bp9" || $name_attr == "property_bp10" || $name_attr == "property_bp11" || $name_attr == "property_bp12" || $name_attr == "property_bp13" || $name_attr == "property_bp14" || $name_attr == "property_bp15" ) {
					if ( $id !== '' && $id !== '0' && $id !== 0 ) {
						$b++;
					}
				} elseif ( $name_attr == "property_c1" || $name_attr == "property_c2" || $name_attr == "property_c3" || $name_attr == "property_c4" || $name_attr == "property_c5" || $name_attr == "property_c6" || $name_attr == "property_c7" || $name_attr == "property_c8" || $name_attr == "property_c9" || $name_attr == "property_c10" || $name_attr == "property_c11" || $name_attr == "property_c12" || $name_attr == "property_c13" || $name_attr == "property_c14" || $name_attr == "property_c15" ) {
					if ( $id !== '' && $id !== '0' && $id !== 0 ) {
						$c++;
					}
				}

				if ( is_array( $atribute ) && ( is_string( $id ) || is_int( $id ) ) && array_key_exists( $id, $atribute ) ) {

					if ( $name_attr == "product_id" || $name_attr == "property_width" || $name_attr == "property_height" || $name_attr == "property_solidpanelheight" || $name_attr == "property_midrailheight" || $name_attr == "property_builtout" || $name_attr == "property_layoutcode" || $name_attr == "property_opendoor" || $name_attr == "quantity_split" || $name_attr == "property_ba1" || $name_attr == "property_ba2" || $name_attr == "property_ba3" || $name_attr == "property_ba4" || $name_attr == "property_ba5" || $name_attr == "property_ba6" || $name_attr == "property_ba7" || $name_attr == "property_ba8" || $name_attr == "property_ba9" || $name_attr == "property_ba10" || $name_attr == "property_ba11" || $name_attr == "property_ba12" || $name_attr == "property_ba13" || $name_attr == "property_ba14" || $name_attr == "property_ba15" || $name_attr == "property_bp1" || $name_attr == "property_bp2" || $name_attr == "property_bp3" || $name_attr == "property_bp4" || $name_attr == "property_bp5" || $name_attr == "property_bp6" || $name_attr == "property_bp7" || $name_attr == "property_bp8" || $name_attr == "property_bp9" || $name_attr == "property_bp10" || $name_attr == "property_bp11" || $name_attr == "property_bp12" || $name_attr == "property_bp13" || $name_attr == "property_bp14" || $name_attr == "property_bp15" || $name_attr == "property_t1" || $name_attr == "property_t2" || $name_attr == "property_t3" || $name_attr == "property_t4" || $name_attr == "property_t5" || $name_attr == "property_t6" || $name_attr == "property_t7" || $name_attr == "property_t8" || $name_attr == "property_t9" || $name_attr == "property_t10" || $name_attr == "property_t11" || $name_attr == "property_t12" || $name_attr == "property_t13" || $name_attr == "property_t14" || $name_attr == "property_t15" || $name_attr == "property_g1" || $name_attr == "property_g2" || $name_attr == "property_g3" || $name_attr == "property_g4" || $name_attr == "property_g5" || $name_attr == "property_g6" || $name_attr == "property_g7" || $name_attr == "property_g8" || $name_attr == "property_g9" || $name_attr == "property_g10" || $name_attr == "property_g11" || $name_attr == "property_g12" || $name_attr == "property_g13" || $name_attr == "property_g14" || $name_attr == "property_g15" || $name_attr == "property_c1" || $name_attr == "property_c2" || $name_attr == "property_c3" || $name_attr == "property_c4" || $name_attr == "property_c5" || $name_attr == "property_c6" || $name_attr == "property_c7" || $name_attr == "property_c8" || $name_attr == "property_c9" || $name_attr == "property_c10" || $name_attr == "property_c11" || $name_attr == "property_c12" || $name_attr == "property_c13" || $name_attr == "property_c14" || $name_attr == "property_c15" ) {
						$name_attr = explode( '_', $name_attr );
						$product_attributes[ ( $name_attr[0] . ' ' . $name_attr[1] . ' ' . $name_attr[2] ) ] = array(
							'name'         => wc_clean( $name_attr[0] . ' ' . $name_attr[1] . ' ' . $name_attr[2] ),
							'value'        => $id,
							'position'     => $i,
							'is_visible'   => 1,
							'is_variation' => 0,
							'is_taxonomy'  => 0,
						);
						$i++;
					} else {
						// Explode the string into an array using underscore as the delimiter.
						$name_attr = explode( '_', $name_attr );

						// Ensure there are at least 3 elements by padding with an empty string if needed.
						$name_attr = array_pad( $name_attr, 3, '' );

						// Combine the first three parts into the attribute key and name.
						$combined_name = trim( $name_attr[0] . ' ' . $name_attr[1] . ' ' . $name_attr[2] );

						$product_attributes[ $combined_name ] = array(
							'name'         => wc_clean( $combined_name ),
							'value'        => $atribute[ $id ] ?? '',
							'position'     => $i,
							'is_visible'   => 1,
							'is_variation' => 0,
							'is_taxonomy'  => 0,
						);
						$i++;
					}
				} else {
					$name_attr = explode( '_', $name_attr );
					$name_attr = array_pad( $name_attr, 3, '' );
					$product_attributes[ ( $name_attr[0] . ' ' . $name_attr[1] . ' ' . $name_attr[2] ) ] = array(
						'name'         => wc_clean( $name_attr[0] . ' ' . $name_attr[1] . ' ' . $name_attr[2] ),
						'value'        => $id,
						'position'     => $i,
						'is_visible'   => 1,
						'is_variation' => 0,
						'is_taxonomy'  => 0,
					);
					$i++;
				}
			}
		}

		// Save pricing results to post meta.
		update_post_meta( $post_id, 'sections_price', $result->sections_price );
		update_post_meta( $post_id, 'individual_counter', $result->individual_counter );
		unset( $product_attributes['shutter_svg'] );
		update_post_meta( $post_id, '_product_attributes', $product_attributes );

		update_post_meta( $post_id, '_price', floatval( $result->gbp_price ) );
		update_post_meta( $post_id, 'counter_t', $t );
		update_post_meta( $post_id, 'counter_b', $b );
		update_post_meta( $post_id, 'counter_c', $c );
		update_post_meta( $post_id, 'counter_g', $g );
		update_post_meta( $post_id, '_regular_price', floatval( $result->gbp_price ) );
		update_post_meta( $post_id, '_sale_price', floatval( $result->gbp_price ) );
	}

	/**
	 * Save pricing-result meta values to the product.
	 *
	 * Extracted from both ajax-prod.php and ajax-prod-update.php: the block
	 * that saves blackout SQM, Earth basic price, material rate snapshot,
	 * deep buildout flag, and ring pull cleanup.
	 *
	 * @since 1.0.0
	 * @param int           $post_id  The product post ID.
	 * @param PricingResult $result   The pricing calculation result.
	 * @param array         $products The parsed form data.
	 * @return void
	 */
	private function savePricingMeta( int $post_id, PricingResult $result, array $products ) {
		// Save blackout SQM meta (frametype 171 only).
		if ( ! empty( $result->sqm_parts ) ) {
			update_post_meta( $post_id, 'sqm_parts', $result->sqm_parts );
			update_post_meta( $post_id, 'sqm_value_parts', $result->sqm_value_blackout );
		}

		// Save Earth basic price meta (Earth material only).
		if ( $result->has_alu_discount ) {
			update_post_meta( $post_id, 'basic_earth_price', floatval( $result->basic_earth_price ) );
		}

		// Save per-SQM rate snapshot on the product.
		if ( $result->material_key ) {
			update_post_meta( $post_id, 'price_item_' . $result->material_key, $result->material_rate );
		}

		// Save deep buildout flag.
		if ( $result->has_deep_buildout ) {
			update_post_meta( $post_id, 'sum_build_frame', true );
		}

		// Ring pull cleanup: delete ringpull meta for non-ringpull styles.
		if ( ! in_array( intval( $products['property_style'] ), PricingConfig::RINGPULL_STYLES ) ) {
			delete_post_meta( $post_id, 'property_ringpull_volume', '' );
			delete_post_meta( $post_id, 'property_ringpull', 'YES' );
		}
	}

	/**
	 * Clean stale meta when layout changes during product update.
	 *
	 * Extracted from ajax-prod-update.php: when a layout code changes,
	 * old T/B/G/C post meta keys that no longer apply are removed.
	 *
	 * @since 1.0.0
	 * @param int    $post_id     The product post ID.
	 * @param string $layout_code The new layout code string.
	 * @return void
	 */
	public function cleanStaleMeta( int $post_id, string $layout_code ) {
		if ( empty( $layout_code ) ) {
			return;
		}

		// If layout code does not contain T or t, delete T-post meta.
		if ( strpos( $layout_code, 't' ) === false && strpos( $layout_code, 'T' ) === false ) {
			delete_post_meta( $post_id, 'property_tposttype' );
			delete_post_meta( $post_id, 't-post-type' );
			for ( $i = 0; $i < 5; $i++ ) {
				delete_post_meta( $post_id, 'property_t' . $i );
			}
		}

		// If layout code does not contain B or b, delete B-post and BA meta.
		if ( strpos( $layout_code, 'b' ) === false && strpos( $layout_code, 'B' ) === false ) {
			delete_post_meta( $post_id, 'property_bposttype' );
			for ( $i = 0; $i < 5; $i++ ) {
				delete_post_meta( $post_id, 'property_bp' . $i );
				delete_post_meta( $post_id, 'property_ba' . $i );
			}
		}

		// If layout code does not contain G or g, delete G-post meta.
		if ( strpos( $layout_code, 'g' ) === false && strpos( $layout_code, 'G' ) === false ) {
			for ( $i = 0; $i < 5; $i++ ) {
				delete_post_meta( $post_id, 'property_g' . $i );
			}
		}

		// If layout code does not contain C or c, delete C-post meta.
		if ( strpos( $layout_code, 'c' ) === false && strpos( $layout_code, 'C' ) === false ) {
			for ( $i = 0; $i < 5; $i++ ) {
				delete_post_meta( $post_id, 'property_c' . $i );
			}
		}
	}

	/**
	 * Add a product to the WooCommerce cart with quantity.
	 *
	 * Extracted from ajax-prod.php non-order-edit path.
	 *
	 * @since 1.0.0
	 * @param int   $post_id  The product post ID.
	 * @param array $products The parsed form data (needs 'quantity' key).
	 * @return void
	 */
	public function addToCart( int $post_id, array $products ) {
		global $woocommerce;
		$woocommerce->cart->add_to_cart( $post_id, $products['quantity'] );
	}

	/**
	 * Set shipping class on a product.
	 *
	 * Extracted from both ajax-prod.php and ajax-prod-update.php.
	 * Task #013: ensures the correct $post_id is used (not a stale loop variable).
	 *
	 * @since 1.0.0
	 * @param int    $post_id The product post ID.
	 * @param string $class   The shipping class slug. Default 'ship'.
	 * @return void
	 */
	public function setShippingClass( int $post_id, string $class = 'ship' ) {
		$product      = wc_get_product( $post_id );
		$int_shipping = get_term_by( 'slug', $class, 'product_shipping_class' );
		if ( $product && $int_shipping ) {
			$product->set_shipping_class_id( $int_shipping->term_id );
			$product->save();
		}
	}
}
