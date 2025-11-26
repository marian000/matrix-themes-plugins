<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path .
	'wp-load.php');

// $atributezzz = include(__DIR__ . '/atributes_array.php');

$atribute = get_post_meta(1, 'attributes_array', true);
//update_post_meta( 1,'attributes_array',$atributezzz );

$headerMatch = [
	"Location" => 'property_room_other',
	"Installation Type" => 'property_style',
	"Material" => 'property_material',
	"Colour" => 'property_shuttercolour',
	"Hinge:" => 'property_hingecolour',
	"Louvre Size" => 'property_bladesize',
	"Frame" => 'property_frametype',
	"Sides" => 'property_frameleft',
	"Style" => 'property_stile',
	"Tilt Rod" => 'property_controltype',
	"Width" => 'property_width',
	"Height" => 'property_height',
	"Midrail" => 'property_midrailheight',
	"Split" => null, // Use null for undefined
	"TOT" => 'property_totheight',
	"Configuration & positions" => 'property_layoutcode',
	"Posts" => 'property_posts',
	"Quoted?" => 'comments_customer',
	"SQM" => 'property_total',
];

//parse_str($_POST['prod'], $products);

// Get the raw POST data
$rawData = file_get_contents('php://input');

// Decode the JSON data
$data = json_decode($rawData, true);

//print_r($data); // Only for print array
$prodConfigs = [];

if ($data) {
	$response = [];
//	echo "<pre>";
//	print_r($data); // Print the entire decoded data array for debugging
//	echo "</pre>";

	foreach ($data as $product) {
		$productId = $product['id'];
		$tableHeader = $product['tableHeader'];
		$configurations = $product['configurations'];
		$productConfig = [];

//		echo "Product ID: " . $productId . "<br>";

		if ($configurations[0][2] == "") {
			continue;
		}
//		echo "Configurations: <br>";

		foreach ($configurations as $key => $config) {
			// Initialize the formatted row and other necessary variables
			$formattedRow = [];
			$currentHeader = '';
			$newIndex = 0;
			$layoutCode = [];
			$layoutPosts = [];
			$layoutMisc = [];

			// Iterate through each value in the current configuration row
			foreach ($config as $index => $value) {
				// Check if the value is not empty
				if (!empty($value)) {
					// Check if the table header for the current index is not empty
					if (!empty($tableHeader[$index])) {
						// Get the corresponding header match for the current table header
						$currentHeader = $headerMatch[$tableHeader[$index]] ?? null;
						// Reset the new index counter
						$newIndex = 0;
					} else {
						// Increment the new index counter for subsequent empty headers
						$newIndex++;
						if ($newIndex > 1) {
							// Remove the last character of the current header if necessary
							$currentHeader = substr($currentHeader, 0, -1);
						}
						// Append the new index to the current header
						$currentHeader .= $newIndex;
					}

					// If the current header is not null or empty
					if ($currentHeader) {
						// Replace spaces in the current header with underscores
						$currentHeader = str_replace(' ', '_', $currentHeader);

						// Skip adding 'property_total' from the first row to the formatted row
						if ($key === 0 && $currentHeader == "property_total") continue;

						// Format 'property_total' value to 4 decimal places
						if ($currentHeader == "property_total") {
							$value = number_format($value, 4, '.', '');
						}

						// Add the current header and its value to the formatted row
						$formattedRow[$currentHeader] = $value;

						// Handle 'property_layoutcode' values separately
						if (strpos($currentHeader, 'property_layoutcode') !== false) {
							if ($key === 0 && $value !== 'CFG') {
								// Collect layout code values from the first row
								if ($value === 'CB') {
									$layoutCode[] = 'B';
								} else {
									$layoutCode[] = $value;
								}
							} elseif ($key === 1 && $value !== 'Posts' && $value !== 'ROL') {
								// Collect layout posts values from the second row
								$layoutPosts[] = $value;
							} elseif ($key === 2) {
								// Collect miscellaneous layout values from the third row
								$layoutMisc[] = $value;
							}
						}
					}
				}
			}

			// Consolidate collected layout codes into the formatted row
			if (!empty($layoutCode)) {
				$formattedRow["property_layoutcode"] = $layoutCode;
				for ($i = 1; $i < 15; $i++) {
					// Remove individual layout code properties
					unset($formattedRow["property_layoutcode" . $i]);
				}
			}

			// Consolidate collected layout posts into the formatted row
			if (!empty($layoutPosts)) {
				$formattedRow["property_layoutcode_posts"] = $layoutPosts;
				for ($i = 1; $i < 15; $i++) {
					// Remove individual layout code properties
					unset($formattedRow["property_layoutcode" . $i]);
				}
			}

			// Consolidate collected miscellaneous layout values into the formatted row
			if (!empty($layoutMisc)) {
				$formattedRow["property_misc"] = $layoutMisc;
				for ($i = 1; $i < 15; $i++) {
					// Remove individual layout code properties
					unset($formattedRow["property_layoutcode" . $i]);
				}
			}

			// generate new product config array by combining all the configurations of formatted row
			if ($key === 0) {
				// make $formattedRow["property_layoutcode"] from array elements as a string
				$layoutCodeCorrected = implode('', $formattedRow["property_layoutcode"]);

				$productConfig["page_title"] = "prod1-all";
				$productConfig["property_room_other"] = $formattedRow["property_room_other"];
				$productConfig["property_material"] = $formattedRow["property_material"];
				$productConfig["property_style"] = $formattedRow["property_style"];
				$productConfig["property_width"] = $formattedRow["property_width"];
				$productConfig["property_height"] = $formattedRow["property_height"];
				$productConfig["property_midrailheight"] = $formattedRow["property_midrailheight"];
				$productConfig["property_bladesize"] = $formattedRow["property_bladesize"];
				$productConfig["property_frametype"] = $formattedRow["property_frametype"];
				$productConfig["property_stile"] = $formattedRow["property_stile"];
				$productConfig["property_layoutcode"] = $layoutCodeCorrected;
				$productConfig["property_layoutcode_array"] = $formattedRow["property_layoutcode"];
				$productConfig["property_shuttercolour"] = $formattedRow["property_shuttercolour"];
				$productConfig["property_controltype"] = $formattedRow["property_controltype"];
			}
			if ($key === 1) {
				$productConfig["property_frameleft"] = 70; // yes
				$productConfig["property_frameright"] = 75; // yes
				$productConfig["property_framebottom"] = 85; // yes
				$productConfig["property_frametop"] = 80; // yes
				$productConfig["property_layoutcode_posts"] = $formattedRow["property_layoutcode_posts"];
				$productConfig["property_total"] = $formattedRow["property_total"];
				// for each $productConfig["property_layoutcode_array"] element, add the corresponding $formattedRow["property_layoutcode_posts"] element
				foreach ($productConfig["property_layoutcode_array"] as $index => $code) {
					// if $code is B or C or T then add the corresponding $formattedRow["property_layoutcode_posts"] element to a new array with 2 elements, one is the length and the other is the angle, angle is the index of $code and length is thix index -1
					if ($code == 'B' || $code == 'C' || $code == 'T' || $code == 'CB') {
						$length = $productConfig["property_layoutcode_posts"][$index - 1];
						$angle = $productConfig["property_layoutcode_posts"][$index];
						$productConfig["property_layoutcode_posts_bay"][] = ['code' => $code, 'post' => $length, 'angle' => $angle];
					}
				}
			}
			if ($key === 2) {
				$productConfig["property_shuttercolour"] = $formattedRow["property_shuttercolour1"];
				$productConfig["property_totheight"] = $formattedRow["property_totheight"];
			}

			// for each productConfig value exists in array $atribute replace it with the corresponding key from $atribute
			foreach ($productConfig as $key => $value) {
				if (in_array($value, $atribute)) {
					// if in array value then take the key and replace the value with the key
					$productConfig[$key] = array_search($value, $atribute);
				}
			}

			// Output the formatted row as a JSON string for debugging
//			echo json_encode($formattedRow) . ' <br><br>';
			$response[$productId][] = $formattedRow;
		}
		$prodConfigs[] = $productConfig;
	}
	echo json_encode($prodConfigs);
//	echo json_encode($atribute);
//	echo json_encode($response);
} else {
	echo "No data received or JSON decoding failed.";
}
?>