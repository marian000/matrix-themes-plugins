<?php
/**
 * Shutter Configurator Form Template
 *
 * Clean presentation layer separated from business logic
 * Uses data passed from Shutter_Configurator class
 *
 * @package ShutterModule
 * @since 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

// Extract data for easier template usage
$user_data = $template_data['user'] ?? array();
$product_data = $template_data['product'] ?? array();
$cart_data = $template_data['cart'] ?? array();
$session_data = $template_data['session'] ?? array();

// Helper functions for template
/**
 * Get product property value with fallback
 */
function get_product_property($key, $default = '') {
    global $product_data;
    return $product_data[$key] ?? $default;
}

/**
 * Check if property value matches
 */
function is_property_selected($key, $value) {
    return get_product_property($key) === $value;
}

/**
 * Echo checked attribute if condition is true
 */
function checked_if($condition) {
    echo $condition ? 'checked="checked"' : '';
}

/**
 * Echo selected attribute if condition is true
 */
function selected_if($condition) {
    echo $condition ? 'selected="selected"' : '';
}

/**
 * Safely echo escaped value
 */
function safe_echo($value) {
    echo esc_attr($value);
}
?>

<div class="shutter-configurator-wrap">
    <?php wp_nonce_field('shutter_configurator_action', 'shutter_nonce'); ?>
    
    <!-- Header Section -->
    <div class="configurator-header">
        <h1 class="configurator-title">
            <?php if (!empty($_GET['id'])): ?>
                <?php esc_html_e('Update Shutter', 'shutter-module'); ?> - <?php safe_echo($session_data['cart_name']); ?>
            <?php elseif (!empty($_GET['order_id'])): ?>
                <?php esc_html_e('Add Shutter', 'shutter-module'); ?>
            <?php else: ?>
                <?php esc_html_e('Add Shutter', 'shutter-module'); ?> - <?php safe_echo($session_data['cart_name']); ?>
            <?php endif; ?>
        </h1>
    </div>

    <!-- Configuration Form -->
    <form id="shutter-configurator-form" 
          method="post" 
          enctype="multipart/form-data"
          data-edit-mode="<?php echo !empty($_GET['id']) ? 'yes' : 'no'; ?>">
        
        <!-- Hidden Fields -->
        <input type="hidden" name="action" value="save_shutter_configuration">
        <input type="hidden" name="product_id_updated" value="<?php safe_echo($product_data['id'] ?? ''); ?>">
        <input type="hidden" name="customer_id" value="<?php safe_echo($user_data['id']); ?>">
        <input type="hidden" name="dealer_id" value="<?php safe_echo($user_data['dealer_id']); ?>">
        <input type="hidden" name="edit_customer" value="<?php safe_echo($user_data['edit_customer'] ? '1' : '0'); ?>">
        <input type="hidden" name="order_edit" value="<?php safe_echo($user_data['order_edit']); ?>">
        <input type="hidden" name="cart_items_name" value='<?php echo esc_attr(wp_json_encode($cart_data['items_name'])); ?>'>

        <!-- Dynamic Properties Hidden Fields -->
        <?php if (!empty($product_data['dynamic_properties'])): ?>
            <?php foreach ($product_data['dynamic_properties'] as $category => $properties): ?>
                <?php foreach ($properties as $index => $value): ?>
                    <?php if ($category === 'b'): ?>
                        <input type="hidden" id="bp<?php echo $index; ?>" name="bp<?php echo $index; ?>" value="<?php safe_echo($value['bp']); ?>">
                        <input type="hidden" id="ba<?php echo $index; ?>" name="ba<?php echo $index; ?>" value="<?php safe_echo($value['ba']); ?>">
                    <?php else: ?>
                        <input type="hidden" id="<?php echo $category . $index; ?>" name="<?php echo $category . $index; ?>" value="<?php safe_echo($value); ?>">
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="configurator-panels">
            <!-- Panel 1: Shutter Design -->
            <div class="configurator-panel" id="panel-design">
                <div class="panel-header">
                    <h3><?php esc_html_e('Shutters Design', 'shutter-module'); ?></h3>
                    <button type="button" class="panel-toggle" aria-expanded="true">
                        <span class="toggle-icon">−</span>
                    </button>
                </div>
                
                <div class="panel-body">
                    <div class="form-row">
                        <!-- Room Name -->
                        <div class="form-group">
                            <label for="property_room_other">
                                <?php esc_html_e('Room Name', 'shutter-module'); ?> <span class="required">*</span>
                            </label>
                            <input type="text" 
                                   id="property_room_other" 
                                   name="property_room_other" 
                                   class="form-control required" 
                                   value="<?php safe_echo(get_product_property('property_room_other')); ?>" 
                                   required>
                        </div>

                        <!-- Material -->
                        <div class="form-group">
                            <label for="property_material"><?php esc_html_e('Material', 'shutter-module'); ?></label>
                            <select id="property_material" name="property_material" class="form-control property-select">
                                <option value="138" <?php selected_if(is_property_selected('property_material', '138')); ?>>Biowood Plus</option>
                                <option value="139" <?php selected_if(is_property_selected('property_material', '139')); ?>>Supreme</option>
                                <option value="187" <?php selected_if(is_property_selected('property_material', '187')); ?>>Earth</option>
                                <option value="188" <?php selected_if(is_property_selected('property_material', '188')); ?>>Ecowood</option>
                                <option value="137" <?php selected_if(is_property_selected('property_material', '137')); ?>>Ecowood Plus</option>
                                <option value="6" <?php selected_if(is_property_selected('property_material', '6')); ?>>Biowood</option>
                            </select>
                        </div>
                    </div>

                    <!-- Installation Style -->
                    <div class="form-section">
                        <h4><?php esc_html_e('Installation Style', 'shutter-module'); ?></h4>
                        <div class="style-options">
                            <?php
                            $installation_styles = array(
                                '29' => array('title' => 'Full Height', 'image' => 'Full-Height.png'),
                                '221' => array('title' => 'Solid Full Height', 'image' => 'Solid-Flat-Panel.png'),
                                '229' => array('title' => 'Combi Panel', 'image' => 'Solid-Combi-Panel.png'),
                                '230' => array('title' => 'Solid Panel Bay Window Full Height', 'image' => 'Solid-Bay-Window-Full-Height.png'),
                                '231' => array('title' => 'Solid Panel Bay Window Tier-on-Tier', 'image' => 'Solid-Bay-Window-Tier-On-Tier.png'),
                                '232' => array('title' => 'Solid Panel Bay Window Cafe Style', 'image' => 'Solid-Bay-Window-Cafe-Style.png'),
                                '233' => array('title' => 'Combi Panel Bay Window', 'image' => 'Combi-Panel-Bay-Window.png'),
                            );
                            ?>
                            
                            <?php foreach ($installation_styles as $value => $style): ?>
                                <label class="style-option">
                                    <input type="radio" 
                                           name="property_style" 
                                           value="<?php echo esc_attr($value); ?>" 
                                           data-title="<?php echo esc_attr($style['title']); ?>"
                                           <?php checked_if(is_property_selected('property_style', $value)); ?>>
                                    <img src="<?php echo esc_url(plugins_url('imgs/' . $style['image'], dirname(__FILE__))); ?>" 
                                         alt="<?php echo esc_attr($style['title']); ?>">
                                    <span class="style-title"><?php echo esc_html($style['title']); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Dimensions -->
                    <div class="form-section">
                        <h4><?php esc_html_e('Dimensions (mm)', 'shutter-module'); ?></h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="property_width"><?php esc_html_e('Width', 'shutter-module'); ?> <span class="required">*</span></label>
                                <input type="number" 
                                       id="property_width" 
                                       name="property_width" 
                                       class="form-control required" 
                                       value="<?php safe_echo(get_product_property('property_width')); ?>" 
                                       min="1" 
                                       step="1" 
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label for="property_height"><?php esc_html_e('Height', 'shutter-module'); ?> <span class="required">*</span></label>
                                <input type="number" 
                                       id="property_height" 
                                       name="property_height" 
                                       class="form-control required" 
                                       value="<?php safe_echo(get_product_property('property_height')); ?>" 
                                       min="1" 
                                       step="1" 
                                       required>
                            </div>
                            
                            <div class="form-group" id="midrail-height-group">
                                <label for="property_midrailheight"><?php esc_html_e('Midrail Height', 'shutter-module'); ?></label>
                                <input type="number" 
                                       id="property_midrailheight" 
                                       name="property_midrailheight" 
                                       class="form-control" 
                                       value="<?php safe_echo(get_product_property('property_midrailheight')); ?>" 
                                       min="0" 
                                       step="1">
                            </div>
                        </div>
                    </div>

                    <!-- Louvre Size -->
                    <div class="form-section">
                        <div class="form-group">
                            <label for="property_bladesize"><?php esc_html_e('Louvre Size', 'shutter-module'); ?></label>
                            <select id="property_bladesize" name="property_bladesize" class="form-control property-select required">
                                <option value=""><?php esc_html_e('Select Louvre Size', 'shutter-module'); ?></option>
                                <option value="63" <?php selected_if(is_property_selected('property_bladesize', '63')); ?>>63mm</option>
                                <option value="76" <?php selected_if(is_property_selected('property_bladesize', '76')); ?>>76mm</option>
                                <option value="89" <?php selected_if(is_property_selected('property_bladesize', '89')); ?>>89mm</option>
                                <option value="114" <?php selected_if(is_property_selected('property_bladesize', '114')); ?>>114mm</option>
                            </select>
                        </div>
                    </div>

                    <!-- Measure Type -->
                    <div class="form-section">
                        <div class="form-group">
                            <label for="property_fit"><?php esc_html_e('Measure Type', 'shutter-module'); ?></label>
                            <select id="property_fit" name="property_fit" class="form-control property-select">
                                <option value="outside" <?php selected_if(is_property_selected('property_fit', 'outside')); ?>>Outside</option>
                                <option value="inside" <?php selected_if(is_property_selected('property_fit', 'inside')); ?>>Inside</option>
                            </select>
                        </div>
                    </div>

                    <!-- File Attachments -->
                    <div class="form-section">
                        <div class="form-row">
                            <div class="form-group">
                                <label><?php esc_html_e('Attach Shape Drawing', 'shutter-module'); ?></label>
                                <div class="file-upload-container">
                                    <button type="button" id="frontend-button" class="btn btn-secondary">
                                        <?php esc_html_e('Select File to Upload', 'shutter-module'); ?>
                                    </button>
                                    <input type="hidden" id="attachment" name="attachment" value="<?php safe_echo(get_product_property('attachment')); ?>">
                                    <?php if (get_product_property('attachment')): ?>
                                        <img id="frontend-image" src="<?php echo esc_url(get_product_property('attachment')); ?>" alt="" class="uploaded-image">
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?php esc_html_e('Draw Shape', 'shutter-module'); ?></label>
                                <button type="button" id="btn-draw-modal" class="btn btn-primary" data-toggle="modal" data-target="#draw-modal">
                                    <?php esc_html_e('Open Drawing Pad', 'shutter-module'); ?>
                                </button>
                                <input type="hidden" id="attachment-draw" name="attachmentDraw" value="<?php safe_echo(get_product_property('attachmentDraw')); ?>">
                                <?php if (get_product_property('attachmentDraw')): ?>
                                    <img id="frontend-image-draw" src="<?php echo esc_url(get_product_property('attachmentDraw')); ?>" alt="" class="uploaded-image">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel 2: Frame & Stile Design -->
            <div class="configurator-panel" id="panel-frame">
                <div class="panel-header">
                    <h3><?php esc_html_e('Frame & Stile Design', 'shutter-module'); ?></h3>
                    <button type="button" class="panel-toggle" aria-expanded="false">
                        <span class="toggle-icon">+</span>
                    </button>
                </div>
                
                <div class="panel-body" style="display: none;">
                    <!-- Frame Type Selection -->
                    <div class="form-section">
                        <h4><?php esc_html_e('Frame Type', 'shutter-module'); ?></h4>
                        <div class="frame-options" id="frame-type-options">
                            <p class="info-text"><?php esc_html_e('Please select Material & Style to view available Frame Type choices', 'shutter-module'); ?></p>
                        </div>
                    </div>

                    <!-- Frame Measurements -->
                    <div class="form-section frames-section">
                        <h4><?php esc_html_e('Frame Measurements', 'shutter-module'); ?></h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="property_frameleft"><?php esc_html_e('Frame Left', 'shutter-module'); ?> ←</label>
                                <select id="property_frameleft" name="property_frameleft" class="form-control property-select">
                                    <option value="70" <?php selected_if(is_property_selected('property_frameleft', '70')); ?>>70mm</option>
                                    <option value="75" <?php selected_if(is_property_selected('property_frameleft', '75')); ?>>75mm</option>
                                    <option value="80" <?php selected_if(is_property_selected('property_frameleft', '80')); ?>>80mm</option>
                                    <option value="85" <?php selected_if(is_property_selected('property_frameleft', '85')); ?>>85mm</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="property_frameright"><?php esc_html_e('Frame Right', 'shutter-module'); ?> →</label>
                                <select id="property_frameright" name="property_frameright" class="form-control property-select">
                                    <option value="70" <?php selected_if(is_property_selected('property_frameright', '70')); ?>>70mm</option>
                                    <option value="75" <?php selected_if(is_property_selected('property_frameright', '75')); ?>>75mm</option>
                                    <option value="80" <?php selected_if(is_property_selected('property_frameright', '80')); ?>>80mm</option>
                                    <option value="85" <?php selected_if(is_property_selected('property_frameright', '85')); ?>>85mm</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="property_frametop"><?php esc_html_e('Frame Top', 'shutter-module'); ?> ↑</label>
                                <select id="property_frametop" name="property_frametop" class="form-control property-select">
                                    <option value="70" <?php selected_if(is_property_selected('property_frametop', '70')); ?>>70mm</option>
                                    <option value="75" <?php selected_if(is_property_selected('property_frametop', '75')); ?>>75mm</option>
                                    <option value="80" <?php selected_if(is_property_selected('property_frametop', '80')); ?>>80mm</option>
                                    <option value="85" <?php selected_if(is_property_selected('property_frametop', '85')); ?>>85mm</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="property_framebottom"><?php esc_html_e('Frame Bottom', 'shutter-module'); ?> ↓</label>
                                <select id="property_framebottom" name="property_framebottom" class="form-control property-select">
                                    <option value="70" <?php selected_if(is_property_selected('property_framebottom', '70')); ?>>70mm</option>
                                    <option value="75" <?php selected_if(is_property_selected('property_framebottom', '75')); ?>>75mm</option>
                                    <option value="80" <?php selected_if(is_property_selected('property_framebottom', '80')); ?>>80mm</option>
                                    <option value="85" <?php selected_if(is_property_selected('property_framebottom', '85')); ?>>85mm</option>
                                </select>
                            </div>
                        </div>

                        <!-- Buildout Option -->
                        <div class="form-group buildout-section">
                            <div class="buildout-toggle" id="add-buildout" <?php echo get_product_property('property_builtout') ? 'style="display:none;"' : ''; ?>>
                                <button type="button" class="btn btn-secondary"><?php esc_html_e('Add Buildout', 'shutter-module'); ?></button>
                            </div>
                            <div class="buildout-input" id="buildout" <?php echo get_product_property('property_builtout') ? '' : 'style="display:none;"'; ?>>
                                <label for="property_builtout"><?php esc_html_e('Buildout', 'shutter-module'); ?></label>
                                <div class="input-with-remove">
                                    <input type="text" 
                                           id="property_builtout" 
                                           name="property_builtout" 
                                           class="form-control" 
                                           placeholder="<?php esc_attr_e('Enter buildout', 'shutter-module'); ?>"
                                           value="<?php safe_echo(get_product_property('property_builtout')); ?>">
                                    <button type="button" class="btn btn-danger btn-sm" id="remove-buildout">
                                        <strong>✕</strong>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stile Selection -->
                    <div class="form-section">
                        <h4><?php esc_html_e('Stile Type', 'shutter-module'); ?></h4>
                        <div class="stile-options" id="stile-type-options">
                            <p class="info-text"><?php esc_html_e('Stile options will appear based on selected material', 'shutter-module'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel 3: Colors & Controls -->
            <div class="configurator-panel" id="panel-colors">
                <div class="panel-header">
                    <h3><?php esc_html_e('Colour, Hinges, Control & Configuration Design', 'shutter-module'); ?></h3>
                    <button type="button" class="panel-toggle" aria-expanded="false">
                        <span class="toggle-icon">+</span>
                    </button>
                </div>
                
                <div class="panel-body" style="display: none;">
                    <div class="form-row">
                        <!-- Hinge Colour -->
                        <div class="form-group">
                            <label for="property_hingecolour"><?php esc_html_e('Hinge Colour', 'shutter-module'); ?></label>
                            <select id="property_hingecolour" name="property_hingecolour" class="form-control property-select">
                                <option value=""><?php esc_html_e('Select Hinge Colour', 'shutter-module'); ?></option>
                                <option value="white" <?php selected_if(is_property_selected('property_hingecolour', 'white')); ?>>White</option>
                                <option value="black" <?php selected_if(is_property_selected('property_hingecolour', 'black')); ?>>Black</option>
                                <option value="brass" <?php selected_if(is_property_selected('property_hingecolour', 'brass')); ?>>Brass</option>
                            </select>
                        </div>

                        <!-- Shutter Colour -->
                        <div class="form-group">
                            <label for="property_shuttercolour"><?php esc_html_e('Shutter Colour', 'shutter-module'); ?></label>
                            <select id="property_shuttercolour" name="property_shuttercolour" class="form-control property-select">
                                <option value=""><?php esc_html_e('Select Shutter Colour', 'shutter-module'); ?></option>
                                <option value="white" <?php selected_if(is_property_selected('property_shuttercolour', 'white')); ?>>White</option>
                                <option value="cream" <?php selected_if(is_property_selected('property_shuttercolour', 'cream')); ?>>Cream</option>
                                <option value="other" <?php selected_if(is_property_selected('property_shuttercolour', 'other')); ?>>Other</option>
                            </select>
                        </div>

                        <!-- Other Colour (conditional) -->
                        <div class="form-group" id="colour-other" style="display: <?php echo is_property_selected('property_shuttercolour', 'other') ? 'block' : 'none'; ?>;">
                            <label for="property_shuttercolour_other"><?php esc_html_e('Other Colour', 'shutter-module'); ?></label>
                            <input type="text" 
                                   id="property_shuttercolour_other" 
                                   name="property_shuttercolour_other" 
                                   class="form-control" 
                                   value="<?php safe_echo(get_product_property('property_shuttercolour_other')); ?>">
                        </div>

                        <!-- Control Type -->
                        <div class="form-group">
                            <label for="property_controltype"><?php esc_html_e('Control Type', 'shutter-module'); ?></label>
                            <select id="property_controltype" name="property_controltype" class="form-control property-select">
                                <option value=""><?php esc_html_e('Select Control Type', 'shutter-module'); ?></option>
                                <option value="tilt_rod" <?php selected_if(is_property_selected('property_controltype', 'tilt_rod')); ?>>Tilt Rod</option>
                                <option value="hidden_tilt" <?php selected_if(is_property_selected('property_controltype', 'hidden_tilt')); ?>>Hidden Tilt</option>
                            </select>
                        </div>
                    </div>

                    <!-- Layout Configuration -->
                    <div class="form-section">
                        <h4><?php esc_html_e('Layout Configuration', 'shutter-module'); ?></h4>
                        <div class="form-group">
                            <label for="property_layoutcode"><?php esc_html_e('Layout Code', 'shutter-module'); ?></label>
                            <input type="text" 
                                   id="property_layoutcode" 
                                   name="property_layoutcode" 
                                   class="form-control required" 
                                   style="text-transform: uppercase;" 
                                   value="<?php echo esc_attr(strtoupper(get_product_property('property_layoutcode'))); ?>" 
                                   placeholder="<?php esc_attr_e('Enter layout code', 'shutter-module'); ?>">
                        </div>
                    </div>

                    <!-- Additional Options -->
                    <div class="form-section">
                        <h4><?php esc_html_e('Additional Options', 'shutter-module'); ?></h4>
                        
                        <!-- Double Closing Louvres -->
                        <div class="form-group">
                            <label for="property_double_closing_louvres"><?php esc_html_e('Double Closing Louvres', 'shutter-module'); ?></label>
                            <select id="property_double_closing_louvres" name="property_double_closing_louvres" class="form-control">
                                <option value="No" <?php selected_if(is_property_selected('property_double_closing_louvres', 'No')); ?>>No</option>
                                <option value="Yes" <?php selected_if(is_property_selected('property_double_closing_louvres', 'Yes')); ?>>Yes</option>
                            </select>
                        </div>

                        <!-- Spare Louvres -->
                        <div class="form-group">
                            <label for="property_sparelouvres"><?php esc_html_e('Include 2 x Spare Louvres', 'shutter-module'); ?></label>
                            <select id="property_sparelouvres" name="property_sparelouvres" class="form-control">
                                <option value="No" <?php selected_if(is_property_selected('property_sparelouvres', 'No')); ?>>No</option>
                                <option value="Yes" <?php selected_if(is_property_selected('property_sparelouvres', 'Yes')); ?>>Yes</option>
                            </select>
                        </div>

                        <!-- Locks -->
                        <div class="form-group">
                            <label for="property_locks"><?php esc_html_e('Top & Bottom Locks', 'shutter-module'); ?></label>
                            <select id="property_locks" name="property_locks" class="form-control">
                                <option value="No" <?php selected_if(is_property_selected('property_locks', 'No')); ?>>No</option>
                                <option value="Yes" <?php selected_if(is_property_selected('property_locks', 'Yes')); ?>>Yes</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel 4: Confirm Drawing -->
            <div class="configurator-panel" id="panel-drawing">
                <div class="panel-header">
                    <h3><?php esc_html_e('Confirm Drawing', 'shutter-module'); ?></h3>
                    <button type="button" class="panel-toggle" aria-expanded="false">
                        <span class="toggle-icon">+</span>
                    </button>
                </div>
                
                <div class="panel-body" style="display: none;">
                    <div class="form-row">
                        <!-- Drawing Canvas -->
                        <div class="form-group drawing-section">
                            <div id="canvas-container" class="canvas-container">
                                <!-- Canvas will be populated by JavaScript -->
                            </div>
                            <button type="button" class="btn btn-secondary print-drawing">
                                <i class="fa fa-print"></i> <?php esc_html_e('Print', 'shutter-module'); ?>
                            </button>
                            <textarea id="shutter_svg" name="shutter_svg" style="display:none;"></textarea>
                        </div>

                        <!-- Comments and Final Actions -->
                        <div class="form-group comments-section">
                            <label for="comments_customer"><?php esc_html_e('Comments', 'shutter-module'); ?></label>
                            <textarea id="comments_customer" 
                                      name="comments_customer" 
                                      rows="5" 
                                      class="form-control"><?php echo esc_textarea(get_product_property('comments_customer')); ?></textarea>

                            <!-- Quantity -->
                            <div class="quantity-section">
                                <label for="quantity"><?php esc_html_e('Quantity', 'shutter-module'); ?></label>
                                <input type="number" 
                                       id="quantity" 
                                       name="quantity" 
                                       class="form-control" 
                                       value="<?php safe_echo(get_product_property('quantity', '1')); ?>" 
                                       min="1" 
                                       step="1">
                            </div>

                            <!-- Submit Buttons -->
                            <div class="submit-section">
                                <input type="hidden" id="panels_left_right" name="panels_left_right" value="">
                                
                                <?php if (!empty($_GET['id']) && (!empty($_GET['cust_id']) || $user_data['edit_customer'])): ?>
                                    <button type="submit" class="btn btn-primary update-btn-admin">
                                        <?php esc_html_e('Update Product', 'shutter-module'); ?>
                                        <i class="fa fa-chevron-right"></i>
                                    </button>
                                <?php elseif (!empty($_GET['id'])): ?>
                                    <button type="submit" class="btn btn-primary update-btn">
                                        <?php esc_html_e('Update Product', 'shutter-module'); ?>
                                        <i class="fa fa-chevron-right"></i>
                                    </button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-success">
                                        <?php esc_html_e('Add to Quote', 'shutter-module'); ?>
                                        <i class="fa fa-chevron-right"></i>
                                    </button>
                                <?php endif; ?>
                                
                                <img src="<?php echo esc_url(admin_url('images/spinner.gif')); ?>" 
                                     alt="" 
                                     class="spinner" 
                                     style="display:none;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Total Square Meters Display -->
<div class="total-display">
    <label><?php esc_html_e('Total Square Meters', 'shutter-module'); ?></label>
    <input type="text" id="property_total" name="property_total" class="form-control" readonly>
</div>

<?php
// Load drawing modal template
include SHUTTER_MODULE_PATH . 'templates/drawing-modal.php';
?> 