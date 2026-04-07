<?php
/**
 * OrderHandler: order recalculation and "Other Color" product management.
 *
 * Extracted in Task #028 from ajax-prod.php and ajax-prod-update.php.
 * Handles adding products to existing WooCommerce orders, recalculating
 * order totals (GBP, USD, tax, delivery), managing auxiliary "Other Color"
 * products, and upserting the wp_custom_orders tracking table.
 *
 * @package ShutterModule
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Ensure pricing maps are available (needed by horeka_get_materials_map).
require_once __DIR__ . '/pricing-maps.php';

/**
 * Class OrderHandler
 *
 * Provides order-level operations shared by the create (ajax-prod.php)
 * and update (ajax-prod-update.php) pricing flows.
 *
 * @since 1.0.0
 */
class OrderHandler {

	/**
	 * Product ID for "Other Color Earth" auxiliary product.
	 *
	 * @since 1.0.0
	 * @var int
	 */
	const OTHER_COLOR_EARTH_ID = 72951;

	/**
	 * Product ID for "Other Color" auxiliary product (non-Earth materials).
	 *
	 * @since 1.0.0
	 * @var int
	 */
	const OTHER_COLOR_PROD_ID = 337;

	/**
	 * POS product category IDs that skip standard recalculation.
	 *
	 * When an order item belongs to one of these categories, the order
	 * is treated as a POS order and total recalculation is skipped.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	const POS_CATEGORY_IDS = array( 20, 34, 26 );

	/**
	 * Add product to an existing WooCommerce order.
	 *
	 * Adds the newly created product as a line item to the order specified
	 * by $products['order_edit'], then sets the product shipping class.
	 *
	 * @since 1.0.0
	 * @param int   $post_id  The WooCommerce product post ID.
	 * @param array $products The parsed form data array.
	 * @return void
	 */
	public function addToOrder( $post_id, $products ) {
		$product = wc_get_product( $post_id );
		$order   = wc_get_order( $products['order_edit'] );

		if ( ! $order || ! $product ) {
			return;
		}

		// Add the new product to the edited order.
		$order->add_product( $product, $products['quantity'] );

		// Set shipping class default for product.
		$int_shipping = get_term_by( 'slug', 'ship', 'product_shipping_class' );
		if ( $product && $int_shipping ) {
			$product->set_shipping_class_id( $int_shipping->term_id );
			$product->save();
		}
	}

	/**
	 * Manage "Other Color" auxiliary products for an existing order.
	 *
	 * Iterates all order items to determine if an "Other Color" auxiliary
	 * product (72951 for Earth material, 337 for others) needs to be added.
	 * For Earth material with order SQM >= 10, the Earth color product price
	 * is set to 10% of the total basic price of all items.
	 *
	 * @since 1.0.0
	 * @param int   $order_id The WooCommerce order ID.
	 * @param int   $post_id  The product post ID being added/updated.
	 * @param array $products The parsed form data array.
	 * @return void
	 */
	public function manageOtherColorProducts( $order_id, $post_id, $products ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$products_cart      = array();
		$other_color_earth  = 0;
		$other_color        = 0;
		$color              = 0;
		$sqm_order          = 0;
		$earth_color_price  = 0;

		foreach ( $order->get_items() as $cart_item_key => $cart_item ) {
			$product_id = $cart_item['product_id'];
			$products_cart[] = $product_id;

			$col   = get_post_meta( $post_id, 'other_color', true );
			$color = intval( $color ) + intval( $col );

			$have_shuttercolour_other = get_post_meta( $product_id, 'property_shuttercolour_other', true );

			// Skip special color products.
			if ( ! in_array( $product_id, array( self::OTHER_COLOR_EARTH_ID, self::OTHER_COLOR_PROD_ID ) ) ) {
				// Calculate order SQM for earth other color condition.
				$sqm = get_post_meta( $product_id, 'property_total', true );
				if ( ! empty( $sqm ) ) {
					$sqm_order = (float) $sqm_order + (float) $sqm;
				}

				// Get material and calculate basic price.
				$materials   = horeka_get_materials_map();
				$material    = get_post_meta( $product_id, 'property_material', true );
				$basic_price = (float) $sqm * (float) get_post_meta( 1, $materials[ $material ], true );

				$earth_color_price = $earth_color_price + ( $basic_price * 0.1 );

				if ( ! empty( $have_shuttercolour_other ) && $have_shuttercolour_other != '' ) {
					if ( $material == 187 ) {
						$other_color_earth = 1;
					} else {
						$other_color = 1;
					}
				}
			}
		}

		// Add custom color as separate product.
		$property_shuttercolour_other = get_post_meta( $post_id, 'property_shuttercolour_other', true );
		if ( ! empty( $property_shuttercolour_other ) && $property_shuttercolour_other != '' ) {
			$other_color_earth_id = self::OTHER_COLOR_EARTH_ID;
			$other_color_prod_id  = self::OTHER_COLOR_PROD_ID;

			// Earth material: add other color earth product.
			if ( $products['property_material'] == 187 ) {
				$product = wc_get_product( $other_color_earth_id );
				if ( $sqm_order < 10 ) {
					// Under 10 SQM: add at default price.
					if ( ! array_key_exists( $other_color_earth_id, $products_cart ) ) {
						wc_get_order( $order_id )->add_product( $product, 1 );
					}
				} else {
					// 10+ SQM: add and set price to 10% of total basic.
					if ( ! array_key_exists( $other_color_earth_id, $products_cart ) ) {
						wc_get_order( $order_id )->add_product( $product, 1 );
					}
					foreach ( $order->get_items() as $key => $item ) {
						$item_product_id = $item['data']->get_id();
						if ( $item_product_id == $other_color_earth_id ) {
							$item['data']->set_price( $earth_color_price );
						}
					}
				}
			}

			// Non-Earth material: add standard other color product.
			if ( $products['property_material'] != 187 ) {
				$product = wc_get_product( $other_color_prod_id );
				if ( ! in_array( $other_color_prod_id, $products_cart ) ) {
					wc_get_order( $order_id )->add_product( $product, 1 );
				}
			}
		}
	}

	/**
	 * Manage "Other Color" auxiliary products in the WooCommerce cart context.
	 *
	 * Used by the create flow (ajax-prod.php) when NOT editing an existing order.
	 * Iterates cart items to determine if an "Other Color" product needs adding.
	 * This is the extracted addOtherColorEarth() function.
	 *
	 * @since 1.0.0
	 * @param int   $post_id  The product post ID just added to cart.
	 * @param array $products The parsed form data array.
	 * @return void
	 */
	public function manageOtherColorCart( $post_id, $products ) {
		$cart = WC()->cart->get_cart();

		$products_cart      = array();
		$other_color_earth  = 0;
		$other_color        = 0;
		$sqm_order          = 0;
		$earth_color_price  = 0;
		$other_clrs_array   = array();
		$items_array        = array();

		// Iterate cart items.
		foreach ( $cart as $cart_item_key => $cart_item ) {
			$product_id      = $cart_item['product_id'];
			$products_cart[] = $product_id;
			$have_shuttercolour_other = get_post_meta( $product_id, 'property_shuttercolour_other', true );

			// Skip special color products.
			if ( ! in_array( $product_id, array( self::OTHER_COLOR_EARTH_ID, self::OTHER_COLOR_PROD_ID ) ) ) {
				$items_array[ $product_id ] = 'no_color';

				// Calculate order SQM for earth other color condition.
				$sqm = get_post_meta( $product_id, 'property_total', true );
				if ( ! empty( $sqm ) ) {
					$sqm_order = (float) $sqm_order + (float) $sqm;
				}

				// Get material and calculate basic price.
				$materials   = horeka_get_materials_map();
				$material    = get_post_meta( $product_id, 'property_material', true );
				$basic_price = (float) $sqm * (float) get_post_meta( 1, $materials[ $material ], true );

				$earth_color_price = $earth_color_price + ( $basic_price * 0.1 );

				if ( ! empty( $have_shuttercolour_other ) && $have_shuttercolour_other != '' ) {
					if ( $material == 187 ) {
						$items_array[ $product_id ] = 'have_earth_other_color';
					} else {
						$items_array[ $product_id ] = 'have_other_color';
					}
				}
			} else {
				$other_clrs_array[] = $product_id;
				if ( $product_id == self::OTHER_COLOR_EARTH_ID ) {
					$other_color_earth++; // Task #012: was $other_color_earth = $other_color_earth++ (post-increment bug)
				}
				if ( $product_id == self::OTHER_COLOR_PROD_ID || $product_id == self::OTHER_COLOR_EARTH_ID ) {
					$other_color++; // Task #012: was $other_color = $other_color++ (post-increment bug)
				}
			}
		}

		// Add custom color as separate product.
		$property_shuttercolour_other = get_post_meta( $post_id, 'property_shuttercolour_other', true );
		if ( ! empty( $property_shuttercolour_other ) && $property_shuttercolour_other != '' ) {
			$other_color_earth_id = self::OTHER_COLOR_EARTH_ID;
			$other_color_prod_id  = self::OTHER_COLOR_PROD_ID;

			// Earth material with earth other color flag and no existing earth color product.
			if ( $products['property_material'] == 187 && in_array( 'have_earth_other_color', $items_array ) && ! in_array( self::OTHER_COLOR_EARTH_ID, $other_clrs_array ) ) {
				if ( $sqm_order < 10 ) {
					if ( ! array_key_exists( $other_color_earth_id, $products_cart ) ) {
						WC()->cart->add_to_cart( $other_color_earth_id, 1 );
					}
				} else {
					if ( ! array_key_exists( $other_color_earth_id, $products_cart ) ) {
						WC()->cart->add_to_cart( $other_color_earth_id, 1 );
					}
					foreach ( $cart as $key => $item ) {
						$item_product_id = $item['data']->get_id();
						if ( $item_product_id == $other_color_earth_id ) {
							$item['data']->set_price( $earth_color_price );
						}
					}
				}
			}

			// Non-Earth material with other color flag and no existing color product.
			if ( $products['property_material'] != 187 && in_array( 'have_other_color', $items_array ) && ! in_array( self::OTHER_COLOR_PROD_ID, $other_clrs_array ) ) {
				if ( ! in_array( $other_color_prod_id, $products_cart ) ) {
					WC()->cart->add_to_cart( $other_color_prod_id, 1 );
				}
			}
		}
	}

	/**
	 * Manage "Other Color" cleanup in the cart context for the update flow.
	 *
	 * Used by ajax-prod-update.php to handle cart-based other color logic
	 * including removal of color products when no items need them.
	 *
	 * @since 1.0.0
	 * @param int   $post_id  The product post ID being updated.
	 * @param array $products The parsed form data array.
	 * @return void
	 */
	public function manageOtherColorCartUpdate( $post_id, $products ) {
		$cart = WC()->cart->get_cart();

		$products_cart      = array();
		$other_color_earth  = 0;
		$other_color        = 0;
		$color              = 0;
		$sqm_order          = 0;
		$earth_color_price  = 0;

		// Iterate cart items.
		foreach ( $cart as $cart_item_key => $cart_item ) {
			$product_id      = $cart_item['product_id'];
			$products_cart[] = $product_id;

			$col   = get_post_meta( $post_id, 'other_color', true );
			$color = intval( $color ) + intval( $col );

			$have_shuttercolour_other = get_post_meta( $product_id, 'property_shuttercolour_other', true );

			// Skip special color products.
			if ( ! in_array( $product_id, array( self::OTHER_COLOR_EARTH_ID, self::OTHER_COLOR_PROD_ID ) ) ) {
				$sqm = get_post_meta( $product_id, 'property_total', true );
				if ( ! empty( $sqm ) ) {
					$sqm_order = (float) $sqm_order + (float) $sqm;
				}

				$materials   = horeka_get_materials_map();
				$material    = get_post_meta( $product_id, 'property_material', true );
				$basic_price = (float) $sqm * (float) get_post_meta( 1, $materials[ $material ], true );

				$earth_color_price = $earth_color_price + ( $basic_price * 0.1 );

				if ( ! empty( $have_shuttercolour_other ) && $have_shuttercolour_other != '' ) {
					if ( $material == 187 ) {
						$other_color_earth = 1;
					} else {
						$other_color = 1;
					}
				}
			}
		}

		// Add custom color as separate product.
		$property_shuttercolour_other = get_post_meta( $post_id, 'property_shuttercolour_other', true );
		if ( ! empty( $property_shuttercolour_other ) && $property_shuttercolour_other != '' ) {
			$other_color_earth_id = self::OTHER_COLOR_EARTH_ID;
			$other_color_prod_id  = self::OTHER_COLOR_PROD_ID;

			// Earth material.
			if ( $products['property_material'] == 187 ) {
				if ( $sqm_order < 10 ) {
					if ( ! array_key_exists( $other_color_earth_id, $products_cart ) ) {
						WC()->cart->add_to_cart( $other_color_earth_id, 1 );
					}
				} else {
					if ( ! array_key_exists( $other_color_earth_id, $products_cart ) ) {
						WC()->cart->add_to_cart( $other_color_earth_id, 1 );
					}
					foreach ( $cart as $key => $item ) {
						$item_product_id = $item['data']->get_id();
						if ( $item_product_id == $other_color_earth_id ) {
							$item['data']->set_price( $earth_color_price );
						}
					}
				}
			}

			// Non-Earth material.
			if ( $products['property_material'] != 187 ) {
				if ( ! in_array( $other_color_prod_id, $products_cart ) ) {
					WC()->cart->add_to_cart( $other_color_prod_id, 1 );
				}
			}
		}

		// Remove color products from cart when no items need them.
		if ( $other_color_earth == 0 ) {
			$product_cart_id = WC()->cart->generate_cart_id( self::OTHER_COLOR_EARTH_ID );
			$cart_item_key   = WC()->cart->find_product_in_cart( $product_cart_id );
			if ( $cart_item_key ) {
				WC()->cart->remove_cart_item( $cart_item_key );
			}
		}
		if ( $other_color == 0 ) {
			$product_cart_id = WC()->cart->generate_cart_id( self::OTHER_COLOR_PROD_ID );
			$cart_item_key   = WC()->cart->find_product_in_cart( $product_cart_id );
			if ( $cart_item_key ) {
				WC()->cart->remove_cart_item( $cart_item_key );
			}
		}
	}

	/**
	 * Manage "Other Color" auxiliary product for individual orders.
	 *
	 * Simplified version used by the individual (multi-section) flow.
	 * Only adds product 337 (non-Earth other color). Does not handle
	 * Earth-specific color product 72951 (individual products don't use it).
	 *
	 * @since 1.0.0
	 * @param int   $order_id The WooCommerce order ID.
	 * @param int   $post_id  The product post ID being added/updated.
	 * @param array $products The parsed form data array.
	 * @return void
	 */
	public function manageOtherColorProductsIndividual( $order_id, $post_id, $products ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$products_cart = array();
		foreach ( $order->get_items() as $cart_item_key => $cart_item ) {
			$products_cart[] = $cart_item['product_id'];
		}

		$property_shuttercolour_other = get_post_meta( $post_id, 'property_shuttercolour_other', true );
		if ( ! empty( $property_shuttercolour_other ) ) {
			$other_color_prod_id = self::OTHER_COLOR_PROD_ID;
			if ( ! in_array( $other_color_prod_id, $products_cart ) ) {
				$product = wc_get_product( $other_color_prod_id );
				$order->add_product( $product, $products['quantity'] );
			}
		}
	}

	/**
	 * Manage "Other Color" auxiliary product in cart for individual flow.
	 *
	 * Simplified version used by the individual (multi-section) create flow.
	 * Only adds product 337 (non-Earth other color) to the cart.
	 *
	 * @since 1.0.0
	 * @param int   $post_id  The product post ID just added to cart.
	 * @param array $products The parsed form data array.
	 * @return void
	 */
	public function manageOtherColorCartIndividual( $post_id, $products ) {
		$cart = WC()->cart->get_cart();

		$products_cart = array();
		foreach ( $cart as $cart_item_key => $cart_item ) {
			$products_cart[] = $cart_item['product_id'];
		}

		$property_shuttercolour_other = get_post_meta( $post_id, 'property_shuttercolour_other', true );
		if ( ! empty( $property_shuttercolour_other ) ) {
			$other_color_prod_id = self::OTHER_COLOR_PROD_ID;
			if ( ! in_array( $other_color_prod_id, $products_cart ) ) {
				WC()->cart->add_to_cart( $other_color_prod_id, 1 );
			}
		}
	}

	/**
	 * Manage "Other Color" cleanup in cart for individual update flow.
	 *
	 * Same as cart individual but also updates cart item quantity for the
	 * updated product and removes the color product when no items need it.
	 *
	 * @since 1.0.0
	 * @param int   $post_id  The product post ID being updated.
	 * @param array $products The parsed form data array.
	 * @return void
	 */
	public function manageOtherColorCartUpdateIndividual( $post_id, $products ) {
		global $woocommerce;
		$cart = WC()->cart->get_cart();

		$products_cart = array();
		foreach ( $cart as $cart_item_key => $cart_item ) {
			$product_id      = $cart_item['product_id'];
			$products_cart[] = $product_id;

			// Update quantity for the updated product.
			if ( $product_id == $post_id ) {
				$woocommerce->cart->set_quantity( $cart_item_key, $products['quantity'] );
			}
		}

		$property_shuttercolour_other = get_post_meta( $post_id, 'property_shuttercolour_other', true );
		if ( ! empty( $property_shuttercolour_other ) ) {
			$other_color_prod_id = self::OTHER_COLOR_PROD_ID;
			if ( ! in_array( $other_color_prod_id, $products_cart ) ) {
				WC()->cart->add_to_cart( $other_color_prod_id, 1 );
			}
		}
	}

	/**
	 * Recalculate full order totals after adding/editing an item.
	 *
	 * Iterates all order items, calculates GBP totals (price + train delivery),
	 * applies tax (20% for UK/Ireland, 0% for others), sums USD prices and SQM,
	 * updates WooCommerce order meta, and upserts the wp_custom_orders table.
	 *
	 * POS orders (items in categories 20, 34, 26) skip recalculation entirely.
	 *
	 * @since 1.0.0
	 * @param int $order_id    The WooCommerce order ID.
	 * @param int $customer_id The customer user ID for train price lookup.
	 * @return void
	 */
	public function recalculateOrder( $order_id, $customer_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$user_id_customer = get_post_meta( $order_id, '_customer_user', true );
		$items            = $order->get_items();
		$total_dolar      = 0;
		$sum_total_dolar  = 0;
		$totaly_sqm       = 0;
		$new_total        = 0;
		$sum_tax          = 0;

		$tax_rate = $this->getTaxRate( $order_id );

		$pos      = false;
		$_product = null;

		foreach ( $items as $item_id => $item_data ) {

			if ( $this->isPosItem( $item_data['product_id'] ) ) {
				$pos = true;
			} else {

				$quantity = get_post_meta( $item_data['product_id'], 'quantity', true );
				$price    = get_post_meta( $item_data['product_id'], '_price', true );
				$total    = $price * $quantity;
				$sqm      = get_post_meta( $item_data['product_id'], 'property_total', true );

				if ( $sqm > 0 ) {
					$train_price = get_user_meta( $user_id_customer, 'train_price', true );
					if ( $train_price === null || $train_price === '' ) {
						$train_price = get_post_meta( 1, 'train_price', true );
					}
					$new_price = floatval( $sqm * $quantity ) * floatval( $train_price );
					update_post_meta( $item_data['product_id'], 'train_delivery', $new_price );
				} else {
					$new_price = 0;
				}

				$total = number_format( $total + $new_price, 2 );
				$tax   = number_format( ( $tax_rate * $total ) / 100, 2 );

				$line_tax_data = array(
					'total' => array(
						1 => $tax,
					),
					'subtotal' => array(
						1 => $tax,
					),
				);

				if ( $quantity == 0 || empty( $quantity ) ) {
					$quantity = 1;
					$total    = $price * $quantity;
					$tax      = ( $tax_rate * $total ) / 100;
				}

				wc_update_order_item_meta( $item_id, '_qty', $quantity, $prev_value = '' );
				wc_update_order_item_meta( $item_id, '_line_tax', $tax, $prev_value = '' );
				wc_update_order_item_meta( $item_id, '_line_subtotal_tax', $tax, $prev_value = '' );
				wc_update_order_item_meta( $item_id, '_line_subtotal', $total, $prev_value = '' );
				wc_update_order_item_meta( $item_id, '_line_total', $total, $prev_value = '' );
				wc_update_order_item_meta( $item_id, '_line_tax_data', $line_tax_data );

				// USD price.
				$product_id = $item_data['product_id'];
				$prod_qty   = get_post_meta( $product_id, 'quantity', true );

				// Quantity validation (from update flow).
				if ( empty( $prod_qty ) || $prod_qty <= 0 ) {
					$prod_qty = 1;
					update_post_meta( $product_id, 'quantity', 1 );
				}

				$dolar_price = get_post_meta( $product_id, 'dolar_price', true );

				$sum_total_dolar = floatval( $sum_total_dolar ) + floatval( $dolar_price ) * floatval( $prod_qty );

				$sqm                = get_post_meta( $product_id, 'property_total', true );
				$property_total_sqm = floatval( $sqm ) * floatval( $prod_qty );
				$totaly_sqm         = $totaly_sqm + $property_total_sqm;

				$new_total = $new_total + $total + $tax;
				$sum_tax   = $sum_tax + floatval( $tax );

				$_product = wc_get_product( $product_id );
			}
		}

		if ( $pos === false ) {
			$tax_shipping_total = 0;
			foreach ( $order->get_items( 'tax' ) as $item_id => $item_tax ) {
				$tax_shipping_total = $item_tax->get_shipping_tax_total();
			}

			$order_data    = $order->get_data();
			$order_status  = $order_data['status'];
			$total_price   = $new_total + $order_data['shipping_total'] + $tax_shipping_total;
			update_post_meta( $order_id, '_order_total', $total_price );

			// Seteaza cart_tax si total_tax pentru a include VAT pe sea freight
			$order_fresh = wc_get_order( $order_id );
			if ( $order_fresh ) {
				$order_fresh->set_cart_tax( $sum_tax );
				$order_fresh->set_total_tax( $sum_tax + $tax_shipping_total );
				$order_fresh->save();
			}

			// Log VAT + sea freight calculation
			if ( function_exists( 'my_custom_log' ) ) {
				$subtotal_no_tax = $new_total - $sum_tax;
				my_custom_log(
					'VAT Recalc [OrderHandler]',
					sprintf(
						'Order #%d | Subtotal(+train): £%.2f | cart_tax: £%.2f | shipping_tax: £%.2f | total_tax: £%.2f | total: £%.2f | tax_rate: %s%%',
						$order_id,
						$subtotal_no_tax,
						$sum_tax,
						$tax_shipping_total,
						$sum_tax + $tax_shipping_total,
						$total_price,
						$tax_rate
					)
				);
			}

			// Upsert wp_custom_orders table.
			$property_total_gbp    = $order->get_total();
			$property_subtotal_gbp = $order->get_subtotal();
			$order_shipping_total  = $order->shipping_total;

			$upsert_data = array(
				'usd_price'      => $sum_total_dolar,
				'gbp_price'      => $property_total_gbp,
				'subtotal_price' => $property_subtotal_gbp,
				'sqm'            => $totaly_sqm,
				'shipping_cost'  => $order_shipping_total,
				'status'         => $order_status,
			);

			// For insert, add extra fields.
			$insert_extras = array(
				'reference'  => 'LF0' . $order->get_order_number() . ' - ' . get_post_meta( $order_id, 'cart_name', true ),
				'delivery'   => $_product ? $_product->get_shipping_class() : '',
				'createTime' => $order_data['date_created']->date( 'Y-m-d H:i:s' ),
			);

			$this->upsertCustomOrder( $order_id, $upsert_data, $insert_extras );
		}
	}

	/**
	 * Check if a product belongs to POS categories.
	 *
	 * @since 1.0.0
	 * @param int $product_id The WooCommerce product ID.
	 * @return bool True if the product is in a POS category.
	 */
	private function isPosItem( $product_id ) {
		foreach ( self::POS_CATEGORY_IDS as $cat_id ) {
			if ( has_term( $cat_id, 'product_cat', $product_id ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Calculate tax rate based on shipping country.
	 *
	 * UK and Ireland get 20% tax rate; all others get 0%.
	 *
	 * @since 1.0.0
	 * @param int $order_id The WooCommerce order ID.
	 * @return float The tax rate percentage (20 or 0).
	 */
	private function getTaxRate( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return 0;
		}

		$country_code = WC()->countries->countries[ $order->get_shipping_country() ];
		if ( $country_code == 'United Kingdom (UK)' || $country_code == 'Ireland' ) {
			return 20;
		}

		return 0;
	}

	/**
	 * Update or insert a wp_custom_orders record.
	 *
	 * Uses $wpdb->prepare() for the existence check and $wpdb->update()
	 * / $wpdb->insert() (array-based, already safe) for the mutations.
	 *
	 * @since 1.0.0
	 * @param int   $order_id      The WooCommerce order ID.
	 * @param array $data          Shared fields for both update and insert.
	 * @param array $insert_extras Extra fields only needed for INSERT (reference, delivery, createTime).
	 * @return void
	 */
	private function upsertCustomOrder( $order_id, $data, $insert_extras = array() ) {
		global $wpdb;

		$idOrder   = intval( $order_id );
		$tablename = $wpdb->prefix . 'custom_orders';

		$orderExist = $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM `{$tablename}` WHERE `idOrder` = %d",
			$idOrder
		) );

		if ( $orderExist != 0 ) {
			// Update existing record.
			$where = array(
				'idOrder' => $order_id,
			);
			$wpdb->update( $tablename, $data, $where );
		} else {
			// Insert new record.
			$insert_data = array_merge(
				array( 'idOrder' => $order_id ),
				$data,
				$insert_extras
			);

			// Format shipping_cost for insert.
			if ( isset( $insert_data['shipping_cost'] ) ) {
				$insert_data['shipping_cost'] = number_format( (float) $insert_data['shipping_cost'], 2 );
			}

			$wpdb->insert( $tablename, $insert_data );
		}
	}
}
