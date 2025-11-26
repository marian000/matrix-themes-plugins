<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: Prod Awning
 *
 */

get_header(); // Standard WP header

$cart_item_key = '';

$name = $material = $width = $drop = $color = $spec_col = $cover = $motor = $position = $sensor = $final_price_uk = $final_price_china = $awning_sqm = null;

if (isset($_GET['item'])) {
// Preluăm parametrul "item" din URL
	$obfuscated_id = isset($_GET['item']) ? sanitize_text_field($_GET['item']) : '';
	$salt = 'matrixAwningSecret'; // aceeași valoare de salt folosită la generare

	if (empty($obfuscated_id)) {
		echo '<p>Item not found.</p>';
		return;
	}

// Decodează string-ul
	$decoded = base64_decode($obfuscated_id);

// Verifică dacă string-ul începe cu salt-ul nostru
	if (strpos($decoded, $salt) !== 0) {
		echo '<p>Invalid item identifier.</p>';
		return;
	}

// Elimină salt-ul pentru a obține cart item key-ul real
	$cart_item_key = substr($decoded, strlen($salt));
	print_r($cart_item_key);

// Apoi poți utiliza $cart_item_key pentru a căuta item-ul în coș
	$cart = WC()->cart->get_cart();
	if (!isset($cart[$cart_item_key])) {
		echo '<p>Cart item not found.</p>';
		return;
	}

// Continuă cu prepopularea formularului etc.
	$item = $cart[$cart_item_key];

// Prepopulăm variabilele cu datele existente din cart item
	$name = isset($item['name']) ? $item['name'] : '';
	$material = isset($item['material']) ? $item['material'] : '';
	$width = isset($item['width']) ? $item['width'] : '';
	$drop = isset($item['drop']) ? $item['drop'] : '';
	$color = isset($item['color']) ? $item['color'] : '';
	$spec_col = isset($item['special_color_name']) ? $item['special_color_name'] : '';
	$cover = isset($item['cassette_cover']) ? $item['cassette_cover'] : '';
	$motor = isset($item['motor']) ? $item['motor'] : '';
	$position = isset($item['position']) ? $item['position'] : '';
	$sensor = isset($item['sensor']) ? $item['sensor'] : '';
	$led = isset($item['let']) ? $item['led'] : '';
	$casset_colour = isset($item['casset_colour']) ? $item['casset_colour'] : '';
	$special_colour = isset($item['special_colour']) ? $item['special_colour'] : '';
	$final_price_uk = isset($item['final_price_uk']) ? $item['final_price_uk'] : 0;
	$final_price_china = isset($item['final_price_china']) ? $item['final_price_china'] : 0;
	$awning_sqm = isset($item['awning_sqm']) ? $item['awning_sqm'] : 0;

	$item_values = array(
	  'name' => $name,
	  'material' => $material,
	  'width' => $width,
	  'drop' => $drop,
	  'color' => $color,
	  'spec_col' => $spec_col,
	  'cover' => $cover,
	  'motor' => $motor,
	  'position' => $position,
	  'sensor' => $sensor,
	  'led' => $led,
	  'casset_colour' => $casset_colour,
	  'final_price_uk' => $final_price_uk,
	  'final_price_china' => $final_price_china,
	  'awning_sqm' => $awning_sqm,
	);
}

// 1) Define your dimension price matrix (width x drop).
//    Fill with real data from your table.
//    Example: $price_matrix[width][drop] = price
$option = get_option('my_awning_settings_option');

// Matricea de prețuri
$price_matrix = array();
if (!empty($option['price_matrix_json'])) {
	$price_matrix = json_decode($option['price_matrix_json'], true);
}

// Costul suplimentar pentru culoarea Special (multi-currency)
$special_color_extra = array();
if (!empty($option['special_color_extra_json'])) {
	$special_color_extra = json_decode($option['special_color_extra_json'], true);
}

// Costul suplimentar pentru Wind & Rain Sensor (multi-currency)
$wind_rain_extra = array();
if (!empty($option['wind_rain_extra_json'])) {
	$wind_rain_extra = json_decode($option['wind_rain_extra_json'], true);
}

// Culorile de fabrică
$factory_colors = array();
if (!empty($option['factory_colors_json'])) {
	$factory_colors = json_decode($option['factory_colors_json'], true);
}

// Culorile de fabrică
$factory_materials = array();
if (!empty($option['factory_materials_json'])) {
	$factory_materials = json_decode($option['factory_materials_json'], true);
}

// Cassette covers
$cassette_covers = array();
if (!empty($option['cassette_covers_json'])) {
	$cassette_covers = json_decode($option['cassette_covers_json'], true);
}

?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">


            <div class="page-content" style="background-color: #F7F7F7">
                <div class="page-content-area container-fluid">
                    <div class="page-header">
                        <h1><?php echo isset($_GET['item']) ? 'Update Awning' : "Add Awning"; ?></h1>
                    </div>

                    <div class="row">
                        <div class="col-md-8">

                            <form id="<?php echo isset($_GET['item']) ? 'updateAwningForm' : "awningForm"; ?>" class="form-horizontal" method="post" action="">
                                <!-- Câmp ascuns pentru cart_item_key -->
								<?php if (isset($_GET['item'])) { ?>
                                    <input type="hidden" name="cart_item_key" value="<?php echo esc_attr($cart_item_key); ?>"/>
								<?php } ?>


                                <div class="panel panel-danger panel-title">Awning Design</div>

                                <div class="row">
                                    <!-- Input pentru Width (mm) -->
                                    <div class="col-sm-6">
                                        <label for="name" class="control-label">Awning Name</label>
                                        <div class="">
                                            <input type="text" name="name" id="name" value="<?php echo esc_attr($name); ?>" class="form-control"
                                                   placeholder="Enter Name"/>
                                            <p class="help-block" id="nameHelp" style="color:red;"></p>
                                        </div>
                                    </div>


                                    <!-- Color -->
                                    <div class="col-sm-6">
                                        <label for="material" class="control-label">Material</label>
                                        <div class="">
                                            <select name="material" id="material" class="form-control">
												<?php $i = 0; ?>
												<?php foreach ($factory_materials as $val => $label): ?>
                                                    <option value="<?php echo esc_attr($val); ?>"
													  <?php
													  // Dacă $material nu este gol, folosim selected($material, $val)
													  // Altfel, dacă $material este gol, selectăm prima opțiune (când $i === 0)
													  echo !empty($material) ? selected($material, $val, false) : ($i === 0 ? 'selected="selected"' : '');
													  ?>>
														<?php echo esc_html($label); ?>
                                                    </option>
													<?php $i++; ?>
												<?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <!-- Input pentru Width (mm) -->
                                    <div class="col-sm-6">
                                        <label for="width" class="control-label">Width (mm)</label>
                                        <div class="">
                                            <input type="text" name="width" id="width" value="<?php echo esc_attr($width); ?>" class="form-control"
                                                   placeholder="Enter width in mm"/>
                                            <p class="help-block" id="widthHelp" style="color:red;"></p>
                                        </div>
                                    </div>

                                    <!-- Input pentru Drop (mm) -->
                                    <div class="col-sm-6">
                                        <div class="">
                                            <label for="drop" class="control-label">Drop (mm)</label>
                                            <div class="">
                                                <input type="text" name="drop" id="drop" value="<?php echo esc_attr($drop); ?>" class="form-control"
                                                       placeholder="Enter drop in mm"/>
                                                <p class="help-block" id="dropHelp" style="color:red;"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <!-- Color -->
                                    <div class="col-sm-4">
                                        <label for="color" class="control-label">Fabric Colour</label>
                                        <div class="">
                                            <select name="color" id="color" class="form-control">
												<?php $i = 0; ?>
                                                <option value="">-- Select Colour --</option>
												<?php foreach ($factory_colors as $val => $label): ?>
                                                    <option value="<?php echo esc_attr($val); ?>"
													  <?php
													  // Dacă $material nu este gol, folosim selected($material, $val)
													  // Altfel, dacă $material este gol, selectăm prima opțiune (când $i === 0)
													  echo !empty($color) ? selected($color, $val, false) : '';
													  ?>>
														<?php echo esc_html($label); ?>
                                                    </option>
													<?php $i++; ?>
												<?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>


                                    <!-- Cassette Cover -->
									<?php
									// Setează valoarea implicită "Yes" dacă $motor este gol
									$casset_value = !empty($casset_colour) ? $casset_colour : 'White';
									?>
                                    <div class="col-sm-4">
                                        <label for="cassette_colour" class="control-label">Cassette Colour</label>
                                        <div class="">
                                            <select name="cassette_colour" id="cassette_colour" class="form-control">
                                                <option value="White" <?php echo selected($casset_value, 'White'); ?>>White</option>
                                                <option value="Grey" <?php echo selected($casset_value, 'Grey'); ?>>Grey</option>
                                            </select>
                                        </div>
                                    </div>


                                    <!-- Cassette Cover -->
                                    <div class="col-sm-4">
                                        <label for="cassette_cover" class="control-label">Cassette Cover Colour</label>
                                        <div class="">
                                            <select name="cassette_cover" id="cassette_cover" class="form-control">
                                                <option value="">-- Select cover --</option>
												<?php $i = 0; ?>
												<?php foreach ($cassette_covers as $val => $label): ?>
                                                    <option value="<?php echo esc_attr($val); ?>"
													  <?php
													  // Dacă $material nu este gol, folosim selected($material, $val)
													  // Altfel, dacă $material este gol, selectăm prima opțiune (când $i === 0)
													  echo !empty($cover) ? selected($cover, $val, false) : '';
													  ?>>
														<?php echo esc_html($label); ?>
                                                    </option>
													<?php $i++; ?>
												<?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <!-- Special Color Name (hidden by default) -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="" id="specialColorGroup" style="margin-top: 20px; <?php echo ($spec_col != '') ? '' : 'display:none;'; ?>">
                                            <label for="special_color_name" class="control-label">Special Color Name</label>

                                            <input type="text" name="special_color_name" id="special_color_name" value="<?php echo esc_attr($spec_col); ?>"
                                                   class="form-control"
                                                   placeholder="Enter custom color"/>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">


									<?php
									// Setează valoarea implicită "Yes" dacă $motor este gol
									$motor_value = !empty($motor) ? $motor : 'Yes';
									?>
                                    <!-- Motor (Yes / No) -->
                                    <div class="col-sm-3">
                                        <label class="control-label">Motorised</label>
                                        <div class="">
                                            <label class="radio-inline">
                                                <input type="radio" name="motor" value="Yes" <?php checked($motor_value, 'Yes'); ?>> Yes
                                            </label>
                                        </div>
                                    </div>

									<?php
									// Setează valoarea implicită "Yes" dacă $motor este gol
									$sensor_value = !empty($sensor) ? $sensor : 'No';
									?>
                                    <!-- Wind & Rain Sensor -->
                                    <div class="col-sm-3">
                                        <label class="control-label">Wind & Rain Sensor</label>
                                        <div class="">
                                            <label class="radio-inline">
                                                <input type="radio" name="sensor" value="No" <?php checked($sensor_value, 'No'); ?>> No
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="sensor" value="Yes" <?php checked($sensor_value, 'Yes'); ?>> Yes
                                                (+£<?php echo (int)$wind_rain_extra['UK']; ?>)
                                            </label>
                                        </div>
                                    </div>


									<?php
									// Setează valoarea implicită "Yes" dacă $motor este gol
									$led_value = !empty($led) ? $led : 'Yes';
									?>
                                    <!-- Wind & Rain Sensor -->
                                    <div class="col-sm-3">
                                        <label class="control-label">LED Arm Lights</label>
                                        <div class="">
                                            <label class="radio-inline">
                                                <input type="radio" name="led" value="Yes" <?php checked($led_value, 'Yes'); ?>> Yes
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Position -->
                                    <div class="col-sm-3">
                                        <label for="position" class="control-label">Motor / Handle Position</label>
                                        <div class="">
                                            <select name="position" id="position" class="form-control">
												<?php
												$positions = array('Left', 'Right');
												foreach ($positions as $pos) {
													$selected = ($pos === $position) ? 'selected="selected"' : '';
													echo '<option value="' . $pos . '" ' . $selected . '>' . $pos . '</option>';
												}
												?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <hr>


                                <!-- Hidden fields to store final prices -->
                                <input type="hidden" name="special_colour" id="special_colour"
                                       value="<?php echo !empty($special_colour) ? $special_colour : 0; ?>"/>
                                <input type="hidden" name="final_price_uk" id="final_price_uk" value="<?php echo $final_price_uk; ?>"/>
                                <input type="hidden" name="final_price_china" id="final_price_china" value="<?php echo $final_price_china; ?>"/>
                                <input type="hidden" name="awning_sqm" id="awning_sqm" value="<?php echo $awning_sqm; ?>"/>


                                <!-- Hidden field to store final price -->
                                <input type="hidden" name="final_price" id="final_price" value="0"/>


                                <!-- Submit Button -->
                                <div class="submit-button">
                                    <div class="">


										<?php
										if (isset($_GET['item'])) {
											echo '<button type="submit" class="btn btn-primary update-awning">Update Awning</button>';
										} else {
											echo '<button type="submit" class="btn btn-primary add-awning">Add Awning</button>';
										}
										?>
                                        </button>
                                    </div>
                                </div>

								<?php
								if (current_user_can('administrator')) {
									?>
                                    <div class="row"></div>
                                    <!-- Price Display -->
                                    <div class="col-sm-4">
                                        <label class="control-label">Calculated Price (UK)</label>
                                        <div class="">
                                            <p class="form-control-static"><strong id="displayPrice_uk">£<?php echo $final_price_uk; ?></strong></p>
                                        </div>
                                    </div>

                                    <?php if(get_current_user_id() == 1){ ?>
                                    <div class="col-sm-4">
                                        <label class="control-label">Calculated Price (China)</label>
                                        <div class="">
                                            <p class="form-control-static"><strong id="displayPrice_china">$<?php echo $final_price_china; ?></strong></p>
                                        </div>
                                    </div>
									<?php } ?>
								<?php } ?>

                            </form>

                        </div>
                        <div class="col-md-4">
                            <a href="#" class="thumbnail">
								<?php
								$product_name = 'awning product'; // Înlocuiește cu numele dorit
								$product = get_page_by_title( $product_name, OBJECT, 'product' );

								if ( $product ) {
									$product_id = $product->ID;
								} else {
									$product_id = 0; // Nu a fost găsit produsul
								}
								$image_id = get_post_thumbnail_id($product_id);
								$image = wp_get_attachment_image($image_id, 'full'); // get the HTML code for the post default thumbnail image
								echo $image; // display the image
								?>
                            </a>

                            <a href="#" class="thumbnail color-image" style="display: none;">
                                <img width="559" height="429" src="" class="attachment-full size-full" alt="" decoding="async" fetchpriority="high"
                                     sizes="(max-width: 559px) 100vw, 559px">
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </main>


        <script>
            (function ($) {
                // Setăm valorile minime și maxime pentru width și drop în milimetri
                var minWidth = 2300, maxWidth = 6000;
                var minDrop = 1500, maxDrop = 3000;

                // Convert PHP price matrix and extra costs to JS
                var priceMatrix = <?php echo json_encode($price_matrix); ?>;
                var specialExtra = <?php echo json_encode($special_color_extra); ?>;
                var sensorExtra = <?php echo json_encode($wind_rain_extra); ?>;

                console.log('priceMatrix', priceMatrix);


                // Definim lista monedelor pentru care dorim calculul
                var currencies = ['UK', 'China'];

                var widthRanges = [
                    // 2.3m -> interval 2300..2999 mm
                    {min: 2300, max: 2300, key: "2.3"},
                    // 3m -> interval 3000..3299 mm
                    {min: 2301, max: 3000, key: "3.0"},
                    // 4m -> interval 4000..4999 mm
                    {min: 3001, max: 4000, key: "4.0"},
                    // 5m -> interval 5000..5999 mm
                    {min: 4001, max: 5000, key: "5.0"},
                    // 6m -> interval 6000..6999 mm
                    {min: 5001, max: 6000, key: "6.0"}
                ];

                var dropRanges = [
                    // 1.5m -> interval 0..1499 mm
                    {min: 1500, max: 1500, key: "1.5"},
                    // 2m -> interval 1500..1999 mm
                    {min: 1501, max: 2000, key: "2.0"},
                    // 2.5m -> interval 2000..2499 mm
                    {min: 2001, max: 2500, key: "2.5"},
                    // 3m -> interval 2500..3000 mm
                    {min: 2501, max: 3000, key: "3.0"}
                ];


// O funcție ajutătoare care primește un array de range-uri (ex. widthRanges)
// și o valoare numerică (ex. 2500) și returnează "1.5" sau "2.3" etc.
// Dacă nu se potrivește niciun interval, returnează null
                function findKeyByRange(value, rangeArray) {
                    for (var i = 0; i < rangeArray.length; i++) {
                        if (value >= rangeArray[i].min && value <= rangeArray[i].max) {
                            return rangeArray[i].key;
                        }
                    }
                    return null; // nu s-a găsit un interval potrivit
                }

                // Show/hide special color input
                $('#color').on('change', function () {
                    if ($(this).val() === 'Special') {
                        $('#specialColorGroup').show();
                    } else {
                        $('#specialColorGroup').hide();
                        $('#special_color_name').val('');
                    }
                    updatePrice();
                });

                // Actualizează prețul la evenimentele de schimbare sau keyup
                $('#width, #drop, #color, [name="motor"], [name="sensor"], #cassette_cover, #special_color_name').on('change keyup', function () {
                    // console.log('change');
                    updatePrice();
                });

                function updatePrice() {
                    var w = parseFloat($('#width').val());  // ex. 2500 mm
                    var d = parseFloat($('#drop').val());   // ex. 1600 mm
                    var color = $('#color').val();
                    var sensor = $('[name="sensor"]:checked').val();
                    var valid = true;

                    var sqm = (d / 1000) * (w / 1000);
                    $('#awning_sqm').val(sqm.toFixed(2));

                    // Validăm lățimea
                    if (w > 0) {
                        if (isNaN(w) || w < minWidth || w > maxWidth) {
                            $('#widthHelp').text('Please enter a valid width between ' + minWidth + ' and ' + maxWidth + ' mm.');
                            valid = false;
                        } else {
                            $('#widthHelp').text('');
                        }
                    }

                    // Validăm drop-ul
                    if (d > 0) {
                        if (isNaN(d) || d < minDrop || d > maxDrop) {
                            $('#dropHelp').text('Please enter a valid drop between ' + minDrop + ' and ' + maxDrop + ' mm.');
                            valid = false;
                        } else {
                            $('#dropHelp').text('');
                        }
                    }

                    // If both drop and width are provided, perform dynamic validation based on price matrix.
                    // The price matrix is structured as:
                    // priceMatrix[drop_key][width_key] = { "UK": price, "China": price }
                    // Drop and width keys are stored as strings representing meters (e.g. "2.0", "3.3")
                    if (!isNaN(d) && !isNaN(w)) {
                        // Get the drop key using dropRanges
                        var dropKey = findKeyByRange(d, dropRanges);  // e.g., "2.0"
                        if (!dropKey) {
                            // $('#dropHelp').text('Invalid drop value.');
                            valid = false;
                        } else {
                            // Check available width keys for this drop from priceMatrix.
                            // Note: Ensure your priceMatrix is available in JS.
                            // Example priceMatrix structure (keys as strings in meters):
                            // { "2.0": { "3.0": { "UK": 660.0, "China":702.7 }, "4.0": { ... } } }
                            var availableWidthsObj = priceMatrix[dropKey];
                            if (!availableWidthsObj) {
                                $('#widthHelp').text('No available widths for the selected drop.');
                                valid = false;
                            } else {
                                // Get available width keys and convert them to numbers (in meters)
                                var availableWidths = Object.keys(availableWidthsObj).map(function (key) {
                                    return parseFloat(key);
                                });
                                // Sort ascending
                                availableWidths.sort(function (a, b) {
                                    return a - b;
                                });
                                // The minimum width allowed (in meters) from the matrix
                                var minWidthFromMatrix = availableWidths.length ? availableWidths[0] : null;
                                if (minWidthFromMatrix !== null) {
                                    // Convert to mm
                                    var minWidthAllowed = minWidthFromMatrix * 1000;
                                    if (w < minWidthAllowed) {
                                        $('#widthHelp').text('For the selected drop, width must be at least ' + minWidthAllowed + ' mm.');
                                        valid = false;
                                    } else {
                                        // Clear any previous error message
                                        $('#widthHelp').text('');
                                    }
                                } else {
                                    $('#widthHelp').text('No valid width available for the selected drop.');
                                    valid = false;
                                }
                            }
                        }
                    }

                    // Dacă inputurile nu sunt valide, oprește actualizarea prețului
                    if (!valid) {
                        // Poți seta și o valoare de fallback pentru preț, dacă dorești:
                        $('#displayPrice_uk').text('Invalid input');
                        $('#displayPrice_china').text('Invalid input');
                        return;
                    }

                    // Găsim cheile de width și drop pe baza range-urilor
                    var widthKey = findKeyByRange(w, widthRanges);   // ex. "2.3"
                    var dropKey = findKeyByRange(d, dropRanges);    // ex. "1.5"

                    // Verificăm dacă am găsit cheile
                    if (!widthKey || !dropKey) {
                        // Înseamnă că combinația nu e permisă (sau e un interval invalid)
                        // Afișăm un mesaj de eroare
                        $('#displayPrice_uk').text('Not allowed');
                        $('#displayPrice_china').text('Not allowed');
                        $('#final_price_uk').val(0);
                        $('#final_price_china').val(0);
                        $('#awning_sqm').val(0);
                        return;
                    }

                    // De aici calculăm normal
                    var finalPrices = {};
                    $.each(currencies, function (index, curr) {
                        // Inițial 0
                        finalPrices[curr] = 0;
                        console.log('widthKey', widthKey);
                        console.log('dropKey', dropKey);
                        // Verificăm dacă priceMatrix[widthKey][dropKey] există și are un preț pt curr
                        if (priceMatrix[dropKey] && priceMatrix[dropKey][widthKey] && priceMatrix[dropKey][widthKey][curr]) {
                            console.log('priceMatrix[dropKey][widthKey][curr]', priceMatrix[dropKey][widthKey][curr]);

                            // ex. priceMatrix["2.3"]["1.5"]["UK"]
                            finalPrices[curr] = parseFloat(priceMatrix[dropKey][widthKey][curr]) || 0;
                        } else {
                            console.log('priceMatrix[dropKey][widthKey]', priceMatrix[dropKey][widthKey]);
                            // Dacă e marcat cu "-", fie nu există cheie, fie e un string special
                            // Putem verifica explicit:
                            // if (priceMatrix[widthKey][dropKey][curr] === '-') => e invalid
                            // dar mai simplu, dacă nu există, lăsăm 0
                        }

                        // Convertim specialExtra[curr] la număr, cu fallback la 0
                        var specialExtraVal = parseFloat(specialExtra[curr]) || 0;

// Convertim widthKey și dropKey la numere
                        var widthKeyVal = parseFloat(widthKey) || 0;
                        var dropKeyVal = parseFloat(dropKey) || 0;


                        // Adăugăm costuri suplimentare
                        if (color === 'Special') {
                            if (curr == 'UK') {
                                var priceSpecial = parseFloat(specialExtra[curr]);
                                console.log('priceSpecial', priceSpecial);
// Update the text of the option with value "Special"
//                                 $('select#color option[value="Special"]').text('Special (+' + priceSpecial.toFixed(2) + ')');

                                $('#special_colour').val(priceSpecial.toFixed(2));
                                finalPrices[curr] += priceSpecial;
                            } else {

// Calculăm raportul numai dacă widthKeyVal este diferit de 0
                                var ratio = (widthKeyVal !== 0) ? (4 * w / d) : 0;

// Actualizăm finalPrices pentru moneda curentă fără erori
                                var priceSpecial = specialExtraVal + ratio;
                                console.log('priceSpecial china', priceSpecial);
// Update the text of the option with value "Special"

                                finalPrices[curr] += priceSpecial;
                            }
                        }
                        if (sensor === 'Yes') {
                            finalPrices[curr] += parseFloat(sensorExtra[curr]) || 0;
                        }

                        finalPrices[curr] = finalPrices[curr].toFixed(2);

                        // prefix
                        var prefix = '$';
                        if (curr === 'UK') {
                            prefix = '£';
                        } else if (curr === 'China') {
                            prefix = '$';
                        }

                        // Afișăm
                        $('#displayPrice_' + curr.toLowerCase()).text(prefix + finalPrices[curr]);
                        $('#final_price_' + curr.toLowerCase()).val(finalPrices[curr]);

                    });
                }

                // Când utilizatorul schimbă opțiunea din selectorul #color
                $('#color').on('change', function () {
                    // Preluăm valoarea selectată (ex. "0681", "7133" etc.)
                    var selectedFabric = $(this).val();

                    // Construim noul path pentru imagine
                    // Presupunem că imaginile sunt stocate în /wp-content/themes/storefront-child/img/
                    // și că fișierele au extensia .jpg
                    var newSrc = '<?php echo home_url(); ?>' + '/wp-content/themes/storefront-child/imgs/' + selectedFabric + '.jpg';

                    // Actualizăm atributul 'src' al imaginii din .col-md-4 > a.thumbnail > img
                    $('div.col-md-4 a.thumbnail.color-image img').attr('src', newSrc);
                    $('div.col-md-4 a.thumbnail.color-image img').removeAttr('srcset');
                    $('div.col-md-4 a.thumbnail.color-image').show();
                    if (selectedFabric == 'Special') {
                        $('div.col-md-4 a.thumbnail.color-image').hide();
                    }
                });

                // Apel inițial
                // updatePrice();

                $('#awningForm').on('submit', function (e) {
                    e.preventDefault(); // Oprește trimiterea tradițională a formularului

                    // Colectăm datele formularului sub forma unui array de obiecte
                    var formArray = $(this).serializeArray();
                    // Convertim array-ul într-un obiect
                    var formDataObj = {};
                    $.each(formArray, function (index, field) {
                        formDataObj[field.name] = field.value;
                    });
                    // Adăugăm nonce-ul pentru securitate
                    formDataObj.security = '<?php echo wp_create_nonce("custom_awning_nonce"); ?>';

                    // Construim obiectul de date pentru AJAX
                    var dataToSend = {
                        action: 'add_custom_awning',
                        formData: formDataObj
                    };

                    console.log('awningForm ', dataToSend);

                    $.ajax({
                        url: '<?php echo admin_url("admin-ajax.php"); ?>',
                        type: 'POST',
                        data: dataToSend,
                        success: function (response) {
                            console.log('response', response);
                            if (response.success) {
                                // Redirecționează către pagina coșului
                                window.location.href = '<?php echo wc_get_checkout_url(); ?>';
                            } else {
                                alert(response.data.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log('Eroare AJAX:', error);
                        }
                    });
                });


                // Interceptează evenimentul de submit pentru formularul de editare
                $('#updateAwningForm').on('submit', function (e) {
                    e.preventDefault(); // Oprește trimiterea tradițională a formularului

                    // Colectăm datele formularului ca un array de obiecte
                    var formArray = $(this).serializeArray();
                    // Convertim array-ul într-un obiect
                    var formDataObj = {};
                    $.each(formArray, function (index, field) {
                        formDataObj[field.name] = field.value;
                    });

                    // Adăugăm nonce-ul pentru securitate
                    formDataObj.security = '<?php echo wp_create_nonce("custom_awning_nonce"); ?>';

                    // Construim obiectul de date pentru AJAX
                    var dataToSend = {
                        action: 'update_awning_item',
                        formData: formDataObj
                    };

                    console.log('Data sent via AJAX:', dataToSend);

                    $.ajax({
                        url: '<?php echo admin_url("admin-ajax.php"); ?>',
                        type: 'POST',
                        data: dataToSend,
                        success: function (response) {
                            console.log('Response:', response);
                            if (response.success) {
                                // Redirecționează către pagina coșului după actualizare
                                window.location.href = '<?php echo wc_get_checkout_url(); ?>';
                            } else {
                                alert(response.data.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log('AJAX error:', error);
                        }
                    });
                });

            })(jQuery);
        </script>


    </div>

    <style>
        hr {
            margin: 1.4em 0;
        }

        .form-horizontal .control-label {
            padding-top: 0;
        }

        .submit-button {
            padding: 30px 0;
        }

        .panel-title {
            padding: 10px 15px;
            margin-top: 0;
            margin-bottom: 30px;
            font-family: arial;
            font-size: 16px;
            font-weight: bold;
            color: white;
            background-color: #D34A37;
            border-radius: 7px;
        }

        .page-header h1 {
            font-family: "Source Sans Pro";
            padding: 0;
            margin: 0 8px;
            font-size: 32px;
            font-weight: bold;
            color: #12447e;
        }
    </style>

<?php
get_footer();