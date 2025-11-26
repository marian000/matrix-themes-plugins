<?php

$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path . 'wp-load.php');
$config = include('config.php');

// Use the new QuickBooks V3 PHP SDK 6.2.0
require_once(__DIR__ . '/vendor/QuickBooks-V3-PHP-SDK-6.2.0/src/config.php');

// Include QuickBooks constants and configuration
require_once(__DIR__ . '/config/quickbooks-constants.php');
require_once(__DIR__ . '/config/quickbooks-config.php');
require_once(__DIR__ . '/includes/class-token-manager.php');

/**
 * Simple error logging function for QuickBooks integration
 */
if (!function_exists('logError')) {
    function logError($error) {
        if (is_object($error)) {
            error_log('QuickBooks Error: ' . print_r($error, true));
        } else {
            error_log('QuickBooks Error: ' . $error);
        }
    }
}

session_start();

use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Account;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\Estimate;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Item;

$dataService = DataService::Configure(array(
  'auth_mode' => 'oauth2',
  'ClientID' => $config['client_id'],
  'ClientSecret' => $config['client_secret'],
  'RedirectURI' => $config['oauth_redirect_uri'],
  'scope' => $config['oauth_scope'],
  'baseUrl' => "production",
));

$OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

//Import Facade classes you are going to use here
//For example, if you need to use Customer, add

/*  This sample performs the folowing functions:
 1.   Add a customer
 2.   Add an item
 3    Create invoice using the information above
 4.   Email invoice to customer
 5.   Receive payments for the invoice created above
*/

// Use new token manager for robust token handling
$accessToken = QuickBooks_Token_Manager::get_valid_token();

if (empty($accessToken)) {
    error_log('QuickBooks: No valid access token available');
    exit('QuickBooks authentication required');
}

$dataService->throwExceptionOnError(true);

/*
 * Update the OAuth2Token of the dataService object
 */
$dataService->updateOAuth2Token($accessToken);

// Set log location using centralized config
$dataService->setLogLocation(QuickBooks_Config::get_log_path());

/*
 * 1. Get Order info and set lines for invoice qquickbooks transform
 */

print_r($_POST);

$order_id = $_POST['id_ord_original'];
$user_id = get_current_user_id();
$user_id_customer = get_post_meta($order_id, '_customer_user', true);
$delivery_type = '';

$QB_invoice = get_post_meta($order_id, 'QB_invoice', true);
$billing_company = get_post_meta($order_id, '_billing_company', true);

if ($QB_invoice == true) {
	echo 'already exists';
} else {

	$order = wc_get_order($order_id);

// Retrieve the customer ID from the order
	$customer_id = $order->get_user_id();

	if ($customer_id) {
		// Retrieve the billing company from the customer's user meta
		$billing_company = get_user_meta($customer_id, 'billing_company', true);
	}

	$order_data = $order->get_data();
	$items = $order->get_items();
	$_product = '';
//    $order_total = wc_format_decimal($order->get_total(), 2);
//    $vat = $order_data['total_tax'];
	$order_total = number_format((double)$order->get_total(), 2);
	$vat = number_format($order_data['total_tax'], 2);

	$country_code = WC()->countries->countries[$order->get_shipping_country()];
//    print_r($country_code);
	if ($country_code == 'United Kingdom (UK)' || $country_code == 'Ireland') {
		$tax_rate = UK_TAX_RATE;
	} else {
		$tax_rate = OTHER_COUNTRY_TAX_RATE;
	}

	$json = array();
	$lines = array();
	$line_color = array();

	$earth_array = array();
	$green_array = array();
	$biowood_array = array();
	$biowoodPlus_array = array();
	$supreme_array = array();
	$ecowood_array = array();
	$ecowoodPlus_array = array();

	foreach ($items as $item_id => $item_data) {

		$product_id = $item_data['product_id'];
		$property_total = get_post_meta($product_id, 'property_total', true);
		$property_material = get_post_meta($product_id, 'property_material', true);
		$property_category = get_post_meta($product_id, 'shutter_category', true);
		$price = get_post_meta($product_id, '_price', true);
		$material_price = '';

		if ($property_material == 187) {
			if (!empty(get_user_meta($user_id, 'Earth', true)) || (get_user_meta($user_id, 'Earth', true) > 0)) {
				$material_price = get_user_meta($user_id, 'Earth', true);
			} else {
				$material_price = get_post_meta(1, 'Earth', true);
			} /*teo Earth price*/
			$earth_array[$product_id] = $item_data;
		}
		if ($property_material == 137) {
			if (!empty(get_user_meta($user_id, 'Green', true)) || (get_user_meta($user_id, 'Green', true) > 0)) {
				$material_price = get_user_meta($user_id, 'Green', true);
			} else {
				$material_price = get_post_meta(1, 'Green', true);
			} /*teo Earth price*/
			$green_array[$product_id] = $item_data;
		}
		if ($property_material == 138) {
			if (!empty(get_user_meta($user_id, 'BiowoodPlus', true)) || (get_user_meta($user_id, 'BiowoodPlus', true) > 0)) {
				$material_price = get_user_meta($user_id, 'BiowoodPlus', true);
			} else {
				$material_price = get_post_meta(1, 'BiowoodPlus', true);
			} /*teo Earth price*/
			$biowoodPlus_array[$product_id] = $item_data;
		}
		if ($property_material == 6) {
			if (!empty(get_user_meta($user_id, 'Biowood', true)) || (get_user_meta($user_id, 'Biowood', true) > 0)) {
				$material_price = get_user_meta($user_id, 'Biowood', true);
			} else {
				$material_price = get_post_meta(1, 'Biowood', true);
			} /*teo Earth price*/
			$biowood_array[$product_id] = $item_data;
		}
		if ($property_material == 139) {
			if (!empty(get_user_meta($user_id, 'Supreme', true)) || (get_user_meta($user_id, 'Supreme', true) > 0)) {
				$material_price = get_user_meta($user_id, 'Supreme', true);
			} else {
				$material_price = get_post_meta(1, 'Supreme', true);
			} /*teo Earth price*/
			$supreme_array[$product_id] = $item_data;
		}
		if ($property_material == 188) {
			if (!empty(get_user_meta($user_id, 'Ecowood', true)) || (get_user_meta($user_id, 'Ecowood', true) > 0)) {
				$material_price = get_user_meta($user_id, 'Ecowood', true);
			} else {
				$material_price = get_post_meta(1, 'Ecowood', true);
			} /*teo Earth price*/
			$ecowood_array[$product_id] = $item_data;
		}
		if ($property_material == 5) {
			if (!empty(get_user_meta($user_id, 'EcowoodPlus', true)) || (get_user_meta($user_id, 'EcowoodPlus', true) > 0)) {
				$material_price = get_user_meta($user_id, 'EcowoodPlus', true);
			} else {
				$material_price = get_post_meta(1, 'EcowoodPlus', true);
			} /*teo Earth price*/
			$ecowoodPlus_array[$product_id] = $item_data;
		}

//        green 137 - biowood 138 - supreme 139 - earth 187 - ecowood 188

		// Make an array with materials array( material => id material from QuickBooks )
		//$qb_materials = array('137' => 24, '138' => 8, '139' => 13, '187' => 71, '188' => 67);

		// If order have Other colour item  - QB id - 42
		if (in_array($product_id, QuickBooks_Config::OTHER_COLOR_PRODUCT_IDS)) {
			$SalesItemLineDetail = array();

			$line_color['Amount'] = QuickBooks_Config::OTHER_COLOR_UNIT_PRICE;
			$line_color['DetailType'] = "SalesItemLineDetail";
			$line_color['SalesItemLineDetail']['Qty'] = 1;
			$line_color['SalesItemLineDetail']['UnitPrice'] = QuickBooks_Config::OTHER_COLOR_UNIT_PRICE;
			$line_color['SalesItemLineDetail']['ItemRef'] = array('value' => OTHER_COLOR_ITEM_ID);
			$line_color['SalesItemLineDetail']['TaxCodeRef'] = array('value' => $tax_rate);
		}

		// sandbox 4620816365021649600
		// MultiPanel Display 3098 - Twin Sample Bags 1020 - Spare Parts Box 1026  - Painted Color Swatches 1030 - Stained Color Samples 1032 - Mike's Magic Sticks
		// OLD $qb_pos = array('3098' => 73, '1020' => 57, '1026' => 74, '1030' => 76, '1032' => 75);
		//ID: 74874 - Biowood Large Sample Panel
		//ID: 17065 - Ecowood Large Sample Panel
		//ID: 74880 - Earth Large Aluminium Sample Panel
		//ID: 74886 - Earth Large Aluminium Sample Panel

		$qb_pos = QuickBooks_Config::get_pos_items_mapping();

		// If order have POS items - QB id - 42
		if (array_key_exists($product_id, $qb_pos)) {

			$SalesItemLineDetail = array();

			$line['Amount'] = number_format($price, 2, '.', '') * $item_data['quantity'];
			$line['DetailType'] = "SalesItemLineDetail";
			$line['SalesItemLineDetail']['Qty'] = 1;
			$line['SalesItemLineDetail']['UnitPrice'] = number_format($price, 2, '.', '') * $item_data['quantity'];
			$line['SalesItemLineDetail']['ItemRef'] = array('value' => $qb_pos[$product_id]);
			$line['SalesItemLineDetail']['TaxCodeRef'] = array('value' => $tax_rate);

			$lines[] = $line;
		}

		// Set delivery type
		$_product = wc_get_product($product_id);
	}

	$nr_black = array('24' => 0, '8' => 0, '13' => 0, '71' => 0, '67' => 0, '5' => 0, '6' => 0);
	$nr_batten = array('24' => 0, '8' => 0, '13' => 0, '71' => 0, '67' => 0, '5' => 0, '6' => 0);
	$nr_shutter = array('24' => 0, '8' => 0, '13' => 0, '71' => 0, '67' => 0, '5' => 0, '6' => 0);

	$materials_array = array($earth_array, $green_array, $biowood_array, $biowoodPlus_array, $supreme_array, $ecowood_array, $ecowoodPlus_array);

	// sandbox 4620816365021649600
	$array_materials = QuickBooks_Config::get_material_names();
	//        green 137 - biowoodPlus 138 - supreme 139 - earth 187 - ecowood 188  ecowoodPlus - 5 biowood - 6
	//$qb_materials = array('137' => 35, '138' => 31, '139' => 36, '187' => 33, '188' => 34, '3098' => 37, '1020' => 38, '1026' => 39, '1032' => 40, '1030' => 41, '337'=>42);
	$qb_materials = QuickBooks_Config::get_materials_mapping();
	foreach ($materials_array as $material_items) {
		$amount = 0;
		$amount_cat_black = 0;
		$amount_cat_batten = 0;
		$property_material = '';
		// [$product_id] = $item_data;

		// Aici se calculeaza sumele
		foreach ($material_items as $product_id => $item_data) {
			$price = get_post_meta($product_id, '_price', true);
			$property_material = get_post_meta($product_id, 'property_material', true);

			$property_category = get_post_meta($product_id, 'shutter_category', true);

			$sqm = get_post_meta($product_id, 'property_total', true);
			$quantity = get_post_meta($product_id, 'quantity', true);
			if ($sqm > 0) {
				$train_price = get_user_meta($user_id_customer, 'train_price', true);
				if ($train_price === null || $train_price === '') {
					$train_price = get_post_meta(1, 'train_price', true);
				}
				$new_price = floatval($sqm * 1) * floatval($train_price);
			} else {
				$new_price = 0;
			}
			// $new_price = 0;
			$price = number_format($price + $new_price, 2, '.', '');

			if ($property_category == 'Shutter & Blackout Blind') {
				$amount_cat_black = $amount_cat_black + floatval($price) * $item_data['quantity'];
			} elseif ($property_category == 'Batten') {
				$amount_cat_batten = $amount_cat_batten + floatval($price) * $item_data['quantity'];
			} else {
				$amount = $amount + (floatval($price) * $item_data['quantity']);
			}
		}

//            if (array_key_exists($property_material, $qb_materials)) {
//                $material_id = $qb_materials[$property_material];
//
//                $SalesItemLineDetail = array();
//
//                if ($property_category != 'Shutter & Blackout Blind' || $property_category != 'Batten') {
//                    $line['Amount'] = number_format($amount, 2, '.', '');
//                    $line['Description'] = "";
//                    $line['DetailType'] = "SalesItemLineDetail";
//                    $line['SalesItemLineDetail']['Qty'] = 1;
//                    $line['SalesItemLineDetail']['UnitPrice'] = number_format($amount, 2, '.', '');
//                    $line['SalesItemLineDetail']['ItemRef'] = array('value' => $material_id);
//                    $line['SalesItemLineDetail']['TaxCodeRef'] = array('value' => 15);
//
//                    $lines[] = $line;
//                }
//            }

		// Aici se printeaza pentru QB
		foreach ($material_items as $product_id => $item_data) {
			$price = get_post_meta($product_id, '_price', true);
			$property_material = get_post_meta($product_id, 'property_material', true);

			$property_category = get_post_meta($product_id, 'shutter_category', true);

			$sqm = get_post_meta($product_id, 'property_total', true);
			$quantity = get_post_meta($product_id, 'quantity', true);
			if ($sqm > 0) {
				$train_price = get_user_meta($user_id_customer, 'train_price', true);
				if ($train_price === null || $train_price === '') {
					$train_price = get_post_meta(1, 'train_price', true);
				}
				$new_price = floatval($sqm * $quantity) * floatval($train_price);
			} else {
				$new_price = 0;
			}
			// $new_price = 0;
			$price = number_format($price + $new_price, 2, '.', '');

			if ($property_category == 'Shutter & Blackout Blind') {
				if (array_key_exists($property_material, $qb_materials)) {
					$material_id = $qb_materials[$property_material];
					if ($nr_black[$material_id] != 1) {
						$line['Amount'] = number_format($amount_cat_black, 2, '.', '');
						$line['Description'] = "Shutter & Blackout Blind";
						$line['DetailType'] = "SalesItemLineDetail";
						$line['SalesItemLineDetail']['Qty'] = 1;
						$line['SalesItemLineDetail']['UnitPrice'] = number_format($amount_cat_black, 2, '.', '');
						$line['SalesItemLineDetail']['ItemRef'] = array('value' => $material_id);
						$line['SalesItemLineDetail']['TaxCodeRef'] = array('value' => $tax_rate);

						$lines[] = $line;

						$nr_black[$material_id] = 1;
					}
				}
			} elseif ($property_category == 'Batten') {
				if (array_key_exists($property_material, $qb_materials)) {
					$material_id = $qb_materials[$property_material];
					if ($nr_batten[$material_id] != 1) {
						$line['Amount'] = number_format($amount_cat_batten, 2, '.', '');
						$line['Description'] = $array_materials[$material_id];
						$line['DetailType'] = "SalesItemLineDetail";
						$line['SalesItemLineDetail']['Qty'] = 1;
						$line['SalesItemLineDetail']['UnitPrice'] = number_format($amount_cat_batten, 2, '.', '');
						$line['SalesItemLineDetail']['ItemRef'] = array('value' => BATTEN_ITEM_ID);
						$line['SalesItemLineDetail']['TaxCodeRef'] = array('value' => $tax_rate);

						$lines[] = $line;

						$nr_batten[$material_id] = 1;
					}
				}
			} elseif ($property_category != 'Shutter & Blackout Blind' && $property_category != 'Batten') {
				if (array_key_exists($property_material, $qb_materials)) {
					$material_id = $qb_materials[$property_material];
					if ($nr_shutter[$material_id] != 1) {
						$line['Amount'] = number_format($amount, 2, '.', '');
						$line['Description'] = "";
						$line['DetailType'] = "SalesItemLineDetail";
						$line['SalesItemLineDetail']['Qty'] = 1;
						$line['SalesItemLineDetail']['UnitPrice'] = number_format($amount, 2, '.', '');
						$line['SalesItemLineDetail']['ItemRef'] = array('value' => $material_id);
						$line['SalesItemLineDetail']['TaxCodeRef'] = array('value' => $tax_rate);

						$lines[] = $line;

						$nr_shutter[$material_id] = 1;
					}
				}
			}
		}
	}

	if (!empty($line_color)) {
		$lines[] = $line_color;
	}

	// Set delivery type
	$shipclass = $_product->get_shipping_class();
	if ($shipclass === 'air') {
		echo 'Air Delivery fee';
		$delivery_type = AIR_DELIVERY_ITEM_ID;

	} else {
		echo 'Delivery';
		$delivery_type = STANDARD_DELIVERY_ITEM_ID;

	}
	$delivery_price = $order_data['shipping_total'];

	$line_delivery = array();

	$SalesItemLineDetail = array();

	$line_delivery['Amount'] = number_format($delivery_price, 2, '.', '');
	$line_delivery['DetailType'] = "SalesItemLineDetail";
	$line_delivery['SalesItemLineDetail']['Qty'] = 1;
	$line_delivery['SalesItemLineDetail']['UnitPrice'] = number_format($delivery_price, 2, '.', '');
	$line_delivery['SalesItemLineDetail']['ItemRef'] = array('value' => $delivery_type);
	$line_delivery['SalesItemLineDetail']['TaxCodeRef'] = array('value' => $tax_rate);

	$lines[] = $line_delivery;
	//print_r($lines);

	/* -------------------------------------------------------------- */

	/*
	 * 1. Get CustomerRef and ItemRef
	 */
	$customerRef = getCustomerObj($dataService, $order_data, $billing_company);
	$itemRef = getItemObj($dataService);

	/*
	 * 2. Create Invoice using the CustomerRef and ItemRef
	 */

	$total = array(
	  "Amount" => $order_total,
	  "DetailType" => "SubTotalLineDetail",
	);
	$lines[] = $total;
	$json['DepartmentRef'] = array("value" => DEFAULT_DEPARTMENT_REF);
	$json['Line'] = $lines;
	$json['CustomerRef'] = array("value" => $customerRef->Id);
	$json['CustomerMemo'] = array("value" => 'LF0' . $order->get_order_number() . ' - ' . get_post_meta($order_id, 'cart_name', true) . '');
	$json['SalesTermRef'] = array("value" => DEFAULT_SALES_TERMS_REF);
	$customer_id = (int)$order->user_id;
	$user_info = get_userdata($customer_id);
	$user_email = $user_info->user_email;

	// Use centralized email logic
	$billing_email = QuickBooks_Config::get_customer_billing_email($customer_id, $user_email);

	$json['BillEmail'] = array("Address" => $billing_email);
	$json['BillEmailBcc'] = array("Address" => QuickBooks_Config::BILLING_BCC_EMAIL);

	print_r($json);

//	$estimateObj = Estimate::create($json);  // Create the Estimate object
	$estimateObj = Invoice::create($json);  // Create the invoice object

	$resultingEstimateObj = $dataService->Add($estimateObj);  // Add the Estimate to QuickBooks
	$estimateId = $resultingEstimateObj->Id;  // This is the ID of the created Estimate

// Output the created estimate ID and response details
	echo "Created estimate Id={$estimateId}. Reconstructed response body below:\n";
	$result = json_encode($resultingEstimateObj, JSON_PRETTY_PRINT);
	print_r($result . "\n\n\n");

// Update post meta to track that the estimate has been created
	if ($result) {
		update_post_meta($order_id, 'QB_estimate', true);  // Update post meta with 'QB_estimate' instead of 'QB_invoice'
		update_post_meta($order_id, 'QB_invoice', true);
	}

	/*
	 * 3. Email Estimate to customer
	 */
	$resultingMailObj = $dataService->sendEmail($resultingEstimateObj, $resultingEstimateObj->BillEmail->Address);  // Send the Estimate via email
	echo "Sent mail. Reconstructed response body below:\n";
	$result = json_encode($resultingMailObj, JSON_PRETTY_PRINT);
	print_r($result . "\n\n\n");
//	sendLogMatrixMail('QB Estimate', $estimateObj, "Created estimate");
}

/*
 * 4. Receive payments for the invoice created above
 */
//    $paymentObj = Payment::create([
//        "CustomerRef" => [
//            "value" => $customerRef->Id
//        ],
//        "TotalAmt" => 100.00,
//        "Line" => [
//            "Amount" => 100.00,
//            "LinkedTxn" => [
//                "TxnId" => $invoiceId,
//                "TxnType" => "Invoice"
//            ]
//        ]
//    ]);
//    $resultingPaymentObj = $dataService->Add($paymentObj);
//    $paymentId = $resultingPaymentObj->Id;
//    echo "Created payment Id={$paymentId}. Reconstructed response body below:\n";
//    $result = json_encode($resultingPaymentObj, JSON_PRETTY_PRINT);
//    print_r($result . "\n\n\n");

//}

/**
 * Generate a GUID to associate with account names
 *
 * @return string A unique GUID
 */
function getGUID()
{
	if (function_exists('com_create_guid')) {
		return com_create_guid();
	} else {
		mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
		$charid = strtoupper(md5(uniqid(rand(), true)));
		$hyphen = chr(45);// "-"
		$uuid = // "{"
		  $hyphen . substr($charid, 0, 8);
		return $uuid;
	}
}

/**
 * Find an existing customer by DisplayName, or create a new one if not found
 *
 * @param object $dataService QuickBooks DataService instance
 * @param array $order_data Order data including billing information
 * @param string $billing_company Billing company name
 * @return object|null Customer object from QuickBooks or null on failure
 */
function getCustomerObj($dataService, $order_data, $billing_company)
{
	// Retrieve the customer company name from the order data
	$customerCompany = $order_data['billing']['company'];

	// Query QuickBooks for an existing customer by CompanyName
	$customerArray = $dataService->Query("select * from Customer where CompanyName='" . $billing_company . "'");
	if ($customerArray && is_array($customerArray) && count($customerArray) > 0) {
		return current($customerArray); // Return the first matched customer
	}

	// Log an error if the query failed
	$error = $dataService->getLastError();
	if ($error) {
		logError($error);
		return null; // Exit function if there's an error
	}
//
//	// Generate a unique GUID and append it to CompanyName to ensure uniqueness
//	$uniqueCompanyName = $customerCompany ? $customerCompany . '-' . getGUID() : 'Guest-' . getGUID();
//
//	// Prepare customer data for creation
//	$customerRequestObj = Customer::create([
//	  "CompanyName" => $uniqueCompanyName,
//	  "DisplayName" => $uniqueCompanyName,
//	  "GivenName" => $order_data['billing']['first_name'] ?: 'NoGivenName',
//	  "FamilyName" => $order_data['billing']['last_name'] ?: 'NoFamilyName'
//	]);
//
//	// Create the customer in QuickBooks
//	$customerResponseObj = $dataService->Add($customerRequestObj);
//	$error = $dataService->getLastError();
//	if ($error) {
//		logError($error);
//		return null; // Exit if there was an error during customer creation
//	} else {
//		echo "Created Customer with DisplayName={$uniqueCompanyName}.\n\n";
//		return $customerResponseObj; // Return the created customer object
//	}
}


/*
   Find if an Item is present , if not create new Item
 */
function getItemObj($dataService)
{

	$itemName = 'Aluminium Shutters';
	$itemArray = $dataService->Query("select * from Item WHERE Name='" . $itemName . "'");
//    $itemId = 'Aluminium Shutters';
//    $itemArray = $dataService->Query("select * from Item WHERE Id='" . $itemId . "'");
	$error = $dataService->getLastError();
	if ($error) {
		logError($error);
	} else {
		if (sizeof($itemArray) > 0) {
			return current($itemArray);
		}
	}

	// Fetch IncomeAccount, ExoenseAccount and AssetAccount Refs needed to create an Item
	$incomeAccount = getIncomeAccountObj($dataService);
	$expenseAccount = getExpenseAccountObj($dataService);
	$assetAccount = getAssetAccountObj($dataService);

	// Create Item
	$dateTime = new \DateTime('NOW');
	$ItemObj = Item::create([
	  "Name" => $itemName,
	  "Description" => "This is the sales description.",
	  "Active" => true,
	  "FullyQualifiedName" => "Office Supplies",
	  "Taxable" => true,
	  "UnitPrice" => 25,
	  "Type" => "Inventory",
	  "IncomeAccountRef" => [
		"value" => $incomeAccount->Id,
	  ],
	  "PurchaseDesc" => "This is the purchasing description.",
	  "PurchaseCost" => 35,
	  "ExpenseAccountRef" => [
		"value" => $expenseAccount->Id,
	  ],
	  "AssetAccountRef" => [
		"value" => $assetAccount->Id,
	  ],
	  "TrackQtyOnHand" => true,
	  "QtyOnHand" => 100,
	  "InvStartDate" => $dateTime,
	]);
	$resultingItemObj = $dataService->Add($ItemObj);
	$itemId = $resultingItemObj->Id;  // This needs to be passed in the Invoice creation later
	echo "Created item Id={$itemId}. Reconstructed response body below:\n";
	$result = json_encode($resultingItemObj, JSON_PRETTY_PRINT);
	print_r($result . "\n\n\n");
	return $resultingItemObj;
}


/*
  Find if an account of Income type exists, if not, create one
*/
function getIncomeAccountObj($dataService)
{

	$accountArray = $dataService->Query("select * from Account where AccountType='" . INCOME_ACCOUNT_TYPE . "' and AccountSubType='" . INCOME_ACCOUNT_SUBTYPE . "'");
	$error = $dataService->getLastError();
	if ($error) {
		logError($error);
	} else {
		if (sizeof($accountArray) > 0) {
			return current($accountArray);
		}
	}

	// Create Income Account
	$incomeAccountRequestObj = Account::create([
	  "AccountType" => INCOME_ACCOUNT_TYPE,
	  "AccountSubType" => INCOME_ACCOUNT_SUBTYPE,
	  "Name" => "IncomeAccount-" . getGUID(),
	]);
	$incomeAccountObject = $dataService->Add($incomeAccountRequestObj);
	$error = $dataService->getLastError();
	if ($error) {
		logError($error);
	} else {
		echo "Created Income Account with Id={$incomeAccountObject->Id}.\n\n";
		return $incomeAccountObject;
	}
}


/*
  Find if an account of "Cost of Goods Sold" type exists, if not, create one
*/
function getExpenseAccountObj($dataService)
{

	$accountArray = $dataService->Query("select * from Account where AccountType='" . EXPENSE_ACCOUNT_TYPE . "' and AccountSubType='" . EXPENSE_ACCOUNT_SUBTYPE . "'");
	$error = $dataService->getLastError();
	if ($error) {
		logError($error);
	} else {
		if (sizeof($accountArray) > 0) {
			return current($accountArray);
		}
	}

	// Create Expense Account
	$expenseAccountRequestObj = Account::create([
	  "AccountType" => EXPENSE_ACCOUNT_TYPE,
	  "AccountSubType" => EXPENSE_ACCOUNT_SUBTYPE,
	  "Name" => "ExpenseAccount-" . getGUID(),
	]);
	$expenseAccountObj = $dataService->Add($expenseAccountRequestObj);
	$error = $dataService->getLastError();
	if ($error) {
		logError($error);
	} else {
		echo "Created Expense Account with Id={$expenseAccountObj->Id}.\n\n";
		return $expenseAccountObj;
	}
}


/*
  Find if an account of "Other Current Asset" type exists, if not, create one
*/
function getAssetAccountObj($dataService)
{

	$accountArray = $dataService->Query("select * from Account where AccountType='" . ASSET_ACCOUNT_TYPE . "' and AccountSubType='" . ASSET_ACCOUNT_SUBTYPE . "'");
	$error = $dataService->getLastError();
	if ($error) {
		logError($error);
	} else {
		if (sizeof($accountArray) > 0) {
			return current($accountArray);
		}
	}

	// Create Asset Account
	$assetAccountRequestObj = Account::create([
	  "AccountType" => ASSET_ACCOUNT_TYPE,
	  "AccountSubType" => ASSET_ACCOUNT_SUBTYPE,
	  "Name" => "AssetAccount-" . getGUID(),
	]);
	$assetAccountObj = $dataService->Add($assetAccountRequestObj);
	$error = $dataService->getLastError();
	if ($error) {
		logError($error);
	} else {
		echo "Created Asset Account with Id={$assetAccountObj->Id}.\n\n";
		return $assetAccountObj;
	}
}


//    $result = invoiceAndBilling();
?>
