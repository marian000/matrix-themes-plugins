<?php
/**
 * =============================================================================
 * PROD-1-ALL.PHP - Shutter Product Form Template
 * =============================================================================
 *
 * CONSTANTE ȘI CONFIGURĂRI:
 * - STYLE_* pentru ID-urile de stiluri
 * - MATERIAL_* pentru ID-urile de materiale
 * - Funcții helper pentru acces la meta-uri
 *
 * =============================================================================
 */

// ==== CONSTANTE PENTRU STYLE IDs ====
define('STYLE_TRACKED_BIFOLD', 35);
define('STYLE_ARCHED', 36);
define('STYLE_TRACKED_BYPASS', 37);
define('STYLE_SOLID_TRACKED_BYPASS', 38);
define('STYLE_SOLID_TRACKED_BIFOLD', 39);
define('STYLE_COMBI_TRACKED_BYPASS', 40);
define('STYLE_COMBI_TRACKED_BIFOLD', 41);
define('STYLE_SOLID_ARCHED', 42);
define('STYLE_SOLID_SPECIAL', 43);
define('STYLE_SPECIAL_SHAPED', 33);
define('STYLE_FRENCH_DOOR', 34);
define('STYLE_SOLID_COMBI_BAY', 233);

// Array cu stiluri tracked (fără frame)
define('STYLES_TRACKED_NO_FRAME', [
    STYLE_TRACKED_BIFOLD,
    STYLE_SOLID_TRACKED_BYPASS,
    STYLE_SOLID_TRACKED_BIFOLD,
    STYLE_COMBI_TRACKED_BYPASS,
    STYLE_COMBI_TRACKED_BIFOLD
]);

// ==== CONSTANTE PENTRU MATERIAL IDs ====
define('MATERIAL_GREEN', 137);
define('MATERIAL_BIOWOOD', 6);
define('MATERIAL_BIOWOOD_PLUS', 138);
define('MATERIAL_BASSWOOD_PLUS', 139);
define('MATERIAL_BASSWOOD', 147);
define('MATERIAL_EARTH', 187);
define('MATERIAL_ECOWOOD', 188);
define('MATERIAL_ECOWOOD_PLUS', 5);

// ==== CONFIGURARE STILE OPTIONS ====
// Fiecare stile are: value, title, code, img, materials (array de ID-uri material compatibile)
$STILE_OPTIONS_CONFIG = [
    // ==== 60mm STILES - Earth Only ====
    ['value' => 350, 'title' => '60mm A1002D (Std.beaded stile)', 'code' => 'FS 50.8', 'img' => 'A1002D.png', 'materials' => [MATERIAL_EARTH]],
    ['value' => 354, 'title' => '60mm A1006D (beaded D-mould)', 'code' => 'FS 50.8', 'img' => 'A1006D.png', 'materials' => [MATERIAL_EARTH]],

    // ==== 41mm STILES - Plain (100xM) ====
    // BasswoodPlus, Basswood, Biowood, BiowoodPlus
    ['value' => 376, 'title' => '41mm 1001M(plain butt)', 'code' => 'FS 50.8', 'img' => '1001M.png', 'materials' => [MATERIAL_BASSWOOD_PLUS, MATERIAL_BASSWOOD, MATERIAL_BIOWOOD, MATERIAL_BIOWOOD_PLUS]],
    ['value' => 377, 'title' => '41mm 1005M(plain D-mould)', 'code' => 'DFS 50.8', 'img' => '1005M.png', 'materials' => [MATERIAL_BASSWOOD_PLUS, MATERIAL_BASSWOOD, MATERIAL_BIOWOOD, MATERIAL_BIOWOOD_PLUS]],
    ['value' => 378, 'title' => '41mm 1003M(plain rebate)', 'code' => 'RFS 50.8', 'img' => '1003M.png', 'materials' => [MATERIAL_BASSWOOD_PLUS, MATERIAL_BASSWOOD, MATERIAL_BIOWOOD, MATERIAL_BIOWOOD_PLUS]],

    // ==== 41mm STILES - Beaded (100xM) - BasswoodPlus, Basswood, BiowoodPlus ====
    ['value' => 459, 'title' => '41mm 1002M (beaded butt)', 'code' => 'RFS 50.8', 'img' => '1002M.png', 'materials' => [MATERIAL_BASSWOOD_PLUS, MATERIAL_BASSWOOD, MATERIAL_BIOWOOD_PLUS]],
    ['value' => 460, 'title' => '41mm 1004M (beaded rebate)', 'code' => 'RFS 50.8', 'img' => '1004M.png', 'materials' => [MATERIAL_BASSWOOD_PLUS, MATERIAL_BASSWOOD, MATERIAL_BIOWOOD_PLUS]],
    ['value' => 461, 'title' => '41mm 1006M (beaded D-mould)', 'code' => 'RFS 50.8', 'img' => '1006M.png', 'materials' => [MATERIAL_BASSWOOD_PLUS, MATERIAL_BASSWOOD, MATERIAL_BIOWOOD_PLUS]],

    // ==== 41mm STILES - Beaded (T10xxM) - Biowood Only ====
    ['value' => 445, 'title' => '41mm T1002M (beaded butt)', 'code' => 'RFS 50.8', 'img' => 'T1002M.png', 'materials' => [MATERIAL_BIOWOOD]],
    ['value' => 446, 'title' => '41mm T1004M (beaded rebate)', 'code' => 'RFS 50.8', 'img' => 'T1004M.png', 'materials' => [MATERIAL_BIOWOOD]],
    ['value' => 447, 'title' => '41mm T1006M (beaded D-mould)', 'code' => 'RFS 50.8', 'img' => 'T1006M.png', 'materials' => [MATERIAL_BIOWOOD]],

    // ==== 51mm STILES - BasswoodPlus, Basswood (100xB) ====
    ['value' => 355, 'title' => '51mm 1001B (plain butt)', 'code' => 'FS 50.8', 'img' => '1001B.png', 'materials' => [MATERIAL_BASSWOOD_PLUS, MATERIAL_BASSWOOD]],
    ['value' => 356, 'title' => '51mm 1005B(plain D-mould)', 'code' => 'DFS 50.8', 'img' => '1005B.png', 'materials' => [MATERIAL_BASSWOOD_PLUS, MATERIAL_BASSWOOD]],
    ['value' => 360, 'title' => '51mm 1003B(plain rebate)', 'code' => 'RFS 50.8', 'img' => '1003B.png', 'materials' => [MATERIAL_BASSWOOD_PLUS, MATERIAL_BASSWOOD]],
    ['value' => 357, 'title' => '51mm 1002B(beaded butt)', 'code' => 'BS 50.8', 'img' => '1002B.png', 'materials' => [MATERIAL_BASSWOOD_PLUS, MATERIAL_BASSWOOD]],
    ['value' => 358, 'title' => '51mm 1006B(beaded D-mould)', 'code' => 'DBS 50.8', 'img' => '1006B.png', 'materials' => [MATERIAL_BASSWOOD_PLUS, MATERIAL_BASSWOOD]],
    ['value' => 359, 'title' => '51mm 1004B(beaded rebate)', 'code' => 'RBS 50.8', 'img' => '1004B.png', 'materials' => [MATERIAL_BASSWOOD_PLUS, MATERIAL_BASSWOOD]],

    // ==== 51mm STILES - Biowood, BiowoodPlus (T10xxK) ====
    ['value' => 370, 'title' => '51mm T1001K(plain butt)', 'code' => 'FS 50.8', 'img' => 'T1001K.png', 'materials' => [MATERIAL_BIOWOOD, MATERIAL_BIOWOOD_PLUS]],
    ['value' => 371, 'title' => '51mm T1005K(plain D-mould)', 'code' => 'DFS 50.8', 'img' => 'T1005K.png', 'materials' => [MATERIAL_BIOWOOD, MATERIAL_BIOWOOD_PLUS]],
    ['value' => 375, 'title' => '51mm T1003K(plain rebate)', 'code' => 'RFS 50.8', 'img' => 'T1003K.png', 'materials' => [MATERIAL_BIOWOOD, MATERIAL_BIOWOOD_PLUS]],
    ['value' => 372, 'title' => '51mm T1002K (beaded butt)', 'code' => 'BS 50.81', 'img' => 'T1002K.png', 'materials' => [MATERIAL_BIOWOOD, MATERIAL_BIOWOOD_PLUS]],
    ['value' => 373, 'title' => '51mm T1006K (beaded D-mould)', 'code' => 'DBS 50.8', 'img' => 'T1006K.png', 'materials' => [MATERIAL_BIOWOOD, MATERIAL_BIOWOOD_PLUS]],
    ['value' => 374, 'title' => '51mm T1004K (beaded rebate)', 'code' => 'RBS 50.8', 'img' => 'T1004K.png', 'materials' => [MATERIAL_BIOWOOD, MATERIAL_BIOWOOD_PLUS]],

    // ==== 51mm PVC STILES - Green, Ecowood, EcowoodPlus (P10xxB) ====
    ['value' => 380, 'title' => '51mm PVC-P1001B(plain butt)', 'code' => 'FS 50.8', 'img' => 'P1001B.png', 'materials' => [MATERIAL_GREEN, MATERIAL_ECOWOOD, MATERIAL_ECOWOOD_PLUS]],
    ['value' => 381, 'title' => '51mm PVC-P1005B(plain D-mould)', 'code' => 'DFS 50.8', 'img' => 'P1005B.png', 'materials' => [MATERIAL_GREEN, MATERIAL_ECOWOOD, MATERIAL_ECOWOOD_PLUS]],
    ['value' => 385, 'title' => '51mm PVC-P1003E(plain rebate)', 'code' => 'RFS 50.8', 'img' => 'P1003B.png', 'materials' => [MATERIAL_GREEN, MATERIAL_ECOWOOD, MATERIAL_ECOWOOD_PLUS]],
    ['value' => 382, 'title' => '51mm PVC-P1002B(beaded butt)', 'code' => 'BS 50.8', 'img' => 'P1002B.png', 'materials' => [MATERIAL_GREEN, MATERIAL_ECOWOOD, MATERIAL_ECOWOOD_PLUS]],
    ['value' => 383, 'title' => '51mm PVC-P1006B(beaded D-mould)', 'code' => 'DBS 50.8', 'img' => 'P1006B.png', 'materials' => [MATERIAL_GREEN, MATERIAL_ECOWOOD, MATERIAL_ECOWOOD_PLUS]],
    ['value' => 384, 'title' => '51mm PVC-P1004E(beaded rebate)', 'code' => 'RBS 50.8', 'img' => 'P1004B.png', 'materials' => [MATERIAL_GREEN, MATERIAL_ECOWOOD, MATERIAL_ECOWOOD_PLUS]],
];

// ==== MAPPING DIV NAME -> MATERIAL ID pentru checked verification ====
// Aceste nume trebuie să corespundă cu ce așteaptă JavaScript-ul în product-script-custom.js
$STILE_DIV_CONFIG = [
    'earth' => MATERIAL_EARTH,           // 187
    'ecowood' => MATERIAL_ECOWOOD,       // 188
    'basswoodPlus' => MATERIAL_BASSWOOD_PLUS, // 139
    'basswood' => MATERIAL_BASSWOOD,        // 147
    'biowood' => MATERIAL_BIOWOOD,        // 6
    'biowoodPlus' => MATERIAL_BIOWOOD_PLUS, // 138
    'green' => MATERIAL_GREEN,            // 137 - ADĂUGAT
    'ecowoodPlus' => MATERIAL_ECOWOOD_PLUS, // 5 - CORECTAT (era 137)
];

// ==== CONFIGURARE STYLE OPTIONS (property_style) ====
// Fiecare opțiune: value, title, code, img, hidden (opțional), label_text (opțional - text afișat în label)
$STYLE_OPTIONS = [
    // ALU (ascunse)
    ['value' => 27, 'title' => 'ALU Panel Only', 'code' => 'fullheight', 'img' => 'alu-panel-only.png', 'hidden' => true, 'container' => 'alu'],
    ['value' => 28, 'title' => 'ALU Fixed Shutter', 'code' => 'fullheight', 'img' => 'alu-fixed-shutter.png', 'hidden' => true, 'container' => 'alu'],
    // Standard
    ['value' => 29, 'title' => 'Full Height', 'code' => 'fullheight', 'img' => 'Full-Height.png'],
    ['value' => 31, 'title' => 'Tier-on-Tier', 'code' => 'tot', 'img' => 'Tier-On-Tier.png', 'hidden' => true],
    ['value' => 30, 'title' => 'Café Style', 'code' => 'cafe', 'img' => 'Cafe-Style.png', 'hidden' => true],
    ['value' => 32, 'title' => 'Bay Window', 'code' => 'bay', 'img' => 'Bay-Window.png', 'hidden' => true],
    ['value' => 146, 'title' => 'Bay Window Tier-on-Tier', 'code' => 'bay-tot', 'img' => 'Bay-Window-Tier-On-Tier.png', 'hidden' => true, 'label_prefix' => 'Bay Window<br/>Tier-on-Tier'],
    ['value' => 225, 'title' => 'Café Style Bay Window', 'code' => 'cafe-bay', 'img' => 'Bay-Window-Cafe-Style.png', 'hidden' => true, 'label_prefix' => 'Bay Window<br/>Café Style'],
    // Solid
    ['value' => 221, 'title' => 'Solid Flat Panel', 'code' => 'solid-flat', 'img' => 'Solid-Flat-Panel.png', 'label_text' => 'Solid Full Height'],
    ['value' => 227, 'title' => 'Solid Flat Tier-on-Tier', 'code' => 'solid-flat-tot', 'img' => 'Solid-Flat-Panel-Tier-On-Tier.png', 'hidden' => true, 'label_prefix' => 'Solid<br/>Tier-on-Tier'],
    ['value' => 226, 'title' => 'Solid Raised Café Style', 'code' => 'solid-raised-cafe-style', 'img' => 'Solid-Raised-Panel-Cafe-Style.png', 'hidden' => true, 'label_prefix' => 'Solid<br/>Café Style'],
    ['value' => 229, 'title' => 'Combi Panel', 'code' => 'solid-combi', 'img' => 'Solid-Combi-Panel.png'],
    // Solid Bay Window
    ['value' => 230, 'title' => 'Solid Panel Bay Window Full Height', 'code' => 'solid-flat-bay', 'img' => 'Solid-Bay-Window-Full-Height.png', 'label_prefix' => 'Solid Panel Bay Window Full Height'],
    ['value' => 231, 'title' => 'Solid Panel Bay Window Tier-on-Tier', 'code' => 'solid-flat-bay-tot', 'img' => 'Solid-Bay-Window-Tier-On-Tier.png', 'label_prefix' => 'Solid Panel Bay Window Tier-on-Tier'],
    ['value' => 232, 'title' => 'Solid Panel Bay Window Cafe Style', 'code' => 'solid-raised-bay-cafe-style', 'img' => 'Solid-Bay-Window-Cafe-Style.png', 'label_prefix' => 'Solid Panel Bay Window Cafe Style'],
    ['value' => 233, 'title' => 'Solid Combi Panel Bay Window', 'code' => 'solid-combi-bay', 'img' => 'Combi-Panel-Bay-Window.png', 'label_prefix' => 'Combi Panel Bay Window'],
    // Shaped
    ['value' => 36, 'title' => 'Arched Shaped', 'code' => 'shaped', 'img' => 'Arched.png', 'hidden' => true],
    ['value' => 33, 'title' => 'Special Shaped', 'code' => 'shaped', 'img' => 'Special-Shape.png', 'hidden' => true],
    ['value' => 42, 'title' => 'Solid Arched Shaped', 'code' => 'shaped', 'img' => 'SolidArched.png', 'hidden' => true],
    ['value' => 43, 'title' => 'Solid Special Shaped', 'code' => 'shaped', 'img' => 'SolidSpecial-Shape.png', 'hidden' => true],
    // French/Tracked
    ['value' => 34, 'title' => 'French Door Cut', 'code' => 'french', 'img' => 'French-Door-Cut.png', 'hidden' => true],
    ['value' => 37, 'title' => 'Tracked By-Pass', 'code' => 'tracked', 'img' => 'Tracked-By-Pass.png', 'hidden' => true],
    ['value' => 35, 'title' => 'Tracked By-Fold', 'code' => 'tracked', 'img' => 'Tracked-By-Fold.png', 'hidden' => true],
    ['value' => 38, 'title' => 'Solid Tracked By-Pass', 'code' => 'tracked', 'img' => 'Solid-Tracked-ByPass.png', 'hidden' => true],
    ['value' => 39, 'title' => 'Solid Tracked By-Fold', 'code' => 'tracked', 'img' => 'Solid-Tracked-BiFold.png', 'hidden' => true],
    // Combi Tracked
    ['value' => 40, 'title' => 'Combi Tracked By-Pass', 'code' => 'tracked', 'img' => 'Combi-Tracked-ByPass.png', 'hidden' => true, 'label_prefix' => 'Combi Tracked By-Pass'],
    ['value' => 41, 'title' => 'Combi Tracked By-Fold', 'code' => 'tracked', 'img' => 'Combi-Tracked-BiFold.png', 'hidden' => true, 'label_prefix' => 'Combi Tracked By-Fold'],
];

// ==== CONFIGURARE FRAMETYPE OPTIONS (property_frametype) ====
// Fiecare opțiune: value, title, code, img, prefix (opțional)
// Valorile sunt extrase din HTML-ul original pentru compatibilitate
$FRAMETYPE_OPTIONS = [
    // U-Channel
    ['value' => 291, 'title' => 'U-Channel', 'code' => 'F50', 'img' => 'u-channel.png'],
    // Basswood 4008 series
    ['value' => 307, 'title' => '4008A', 'code' => 'F50', 'img' => '4008A.png', 'prefix' => 'Basswood'],
    ['value' => 310, 'title' => '4008B', 'code' => 'F70', 'img' => '4008B.png', 'prefix' => 'Basswood'],
    ['value' => 313, 'title' => '4008C', 'code' => 'F70', 'img' => '4008C.png', 'prefix' => 'Basswood'],
    ['value' => 420, 'title' => '4108C', 'code' => 'F70', 'img' => '4108C.png', 'prefix' => 'Basswood'],
    ['value' => 353, 'title' => '4008T', 'code' => 'F90', 'img' => '4008T.png', 'prefix' => 'Basswood'],
    ['value' => 333, 'title' => '4028B', 'code' => 'F70', 'img' => '4028B.png', 'prefix' => 'Basswood'],
    // Basswood 4007 series
    ['value' => 306, 'title' => '4007A', 'code' => 'L70', 'img' => '4007A.png', 'prefix' => 'Basswood'],
    ['value' => 309, 'title' => '4007B', 'code' => 'L90', 'img' => '4007B.png', 'prefix' => 'Basswood'],
    ['value' => 312, 'title' => '4007C', 'code' => 'L90', 'img' => '4007C.png', 'prefix' => 'Basswood'],
    // Basswood 4001 series
    ['value' => 305, 'title' => '4001A', 'code' => 'L50', 'img' => '4001A.png', 'prefix' => 'Basswood'],
    ['value' => 308, 'title' => '4001B', 'code' => 'L70', 'img' => '4001B.png', 'prefix' => 'Basswood'],
    ['value' => 290, 'title' => '4011B', 'code' => 'Z3CS', 'img' => '4011B.png', 'prefix' => 'Basswood'],
    ['value' => 311, 'title' => '4001C', 'code' => 'L70', 'img' => '4001C.png', 'prefix' => 'Basswood'],
    ['value' => 332, 'title' => '4022B', 'code' => 'F70', 'img' => '4022B.png', 'prefix' => 'Basswood'],
    // Basswood other series
    ['value' => 142, 'title' => '4009', 'code' => 'D50', 'img' => '4009.png', 'prefix' => 'Basswood'],
    ['value' => 316, 'title' => '4013', 'code' => 'Z50', 'img' => '4013.png', 'prefix' => 'Basswood'],
    ['value' => 317, 'title' => '4014', 'code' => 'Z50', 'img' => '4014.png', 'prefix' => 'Basswood'],
    ['value' => 352, 'title' => '4024', 'code' => 'Z50', 'img' => '4024.png', 'prefix' => 'Basswood'],
    ['value' => 314, 'title' => '4003', 'code' => 'Z40', 'img' => '4003.png', 'prefix' => 'Basswood'],
    ['value' => 315, 'title' => '4004', 'code' => 'Z3CS', 'img' => '4004.png', 'prefix' => 'Basswood'],
    // PVC series
    ['value' => 351, 'title' => 'P4022B', 'code' => 'Z40', 'img' => 'P4022B.png', 'prefix' => 'PVC'],
    ['value' => 321, 'title' => 'P4008H', 'code' => 'F50', 'img' => 'P4008H.png', 'prefix' => 'PVC'],
    ['value' => 318, 'title' => 'P4028B', 'code' => 'F90', 'img' => 'P4028B.png', 'prefix' => 'PVC'],
    ['value' => 330, 'title' => 'P4008S', 'code' => 'F70', 'img' => 'P4008S.png', 'prefix' => 'PVC'],
    ['value' => 322, 'title' => 'P4008T', 'code' => 'F90', 'img' => 'P4008T.png', 'prefix' => 'PVC'],
    ['value' => 319, 'title' => 'P4008W', 'code' => 'F90', 'img' => 'P4008W.png', 'prefix' => 'PVC'],
    ['value' => 331, 'title' => 'P4007A', 'code' => 'F50', 'img' => 'P4007A.png', 'prefix' => 'PVC'],
    ['value' => 320, 'title' => 'P4001N', 'code' => 'L50', 'img' => 'P4001N.png', 'prefix' => 'PVC'],
    // ALU (fără prefix în label)
    ['value' => 300, 'title' => 'A4001', 'code' => 'L50', 'img' => 'A4001.png'],
    // PVC continued
    ['value' => 325, 'title' => 'P4013', 'code' => 'Z40', 'img' => 'P4013.png', 'prefix' => 'PVC'],
    ['value' => 327, 'title' => 'P4033', 'code' => 'Z50', 'img' => 'P4033.png', 'prefix' => 'PVC'],
    ['value' => 289, 'title' => 'P4023B', 'code' => 'Z50', 'img' => 'P4023B.png', 'prefix' => 'PVC'],
    ['value' => 328, 'title' => 'P4043', 'code' => 'Z40', 'img' => 'P4043.png', 'prefix' => 'PVC'],
    ['value' => 324, 'title' => 'P4073', 'code' => 'Z50', 'img' => 'P4073.png', 'prefix' => 'PVC'],
    ['value' => 304, 'title' => 'P4083', 'code' => 'Z50', 'img' => 'P4083.png', 'prefix' => 'PVC'],
    ['value' => 329, 'title' => 'P4014', 'code' => 'Z3CS', 'img' => 'P4014.png', 'prefix' => 'PVC'],
    ['value' => 303, 'title' => 'P4009', 'code' => 'Z3CS', 'img' => 'P4009.png', 'prefix' => 'PVC'],
    // ALU continued (fără prefix în label)
    ['value' => 302, 'title' => 'A4027', 'code' => 'Z50', 'img' => 'A4027.png'],
    ['value' => 301, 'title' => 'A4002', 'code' => 'Z50', 'img' => 'A4002.png'],
    // Track systems
    ['value' => 144, 'title' => 'Bottom M Track', 'code' => 'L50', 'img' => 'Bottom_M_Track.png'],
    ['value' => 143, 'title' => 'Track in Board', 'code' => 'L50', 'img' => 'Track_in_Board.png'],
];

// ==== FUNCȚII HELPER ====

/**
 * Obține valoarea meta dintr-un array pre-încărcat
 */
function get_meta_value($meta_array, $key, $default = '') {
    return isset($meta_array[$key][0]) ? $meta_array[$key][0] : $default;
}

/**
 * Generează HTML pentru un singur radio button de stile
 *
 * @param array $stile_config Configurarea stile-ului
 * @param string $current_stile Valoarea curentă selectată
 * @param int $current_material ID-ul materialului curent (pentru checked)
 * @param int $stile_index Index-ul pentru clasa stile-X (1-based)
 * @return string HTML generat
 */
function render_stile_option($stile_config, $current_stile, $current_material, $stile_index = 1) {
    // Verifică dacă stile-ul este compatibil cu materialul curent
    if (!in_array($current_material, $stile_config['materials'])) {
        return '';
    }

    $checked = ($current_stile == $stile_config['value'] && !empty($current_material)) ? 'checked' : '';
    $img_path = '/wp-content/plugins/shutter-module/imgs/' . $stile_config['img'];

    return sprintf(
        '<label>
            <br/> %s
            <input type="radio" name="property_stile"
                   data-code="%s"
                   data-title="%s"
                   value="%d" %s />
            <img class="stile-%d"
                 src="%s"/>
        </label>',
        esc_html($stile_config['title']),
        esc_attr($stile_config['code']),
        esc_attr($stile_config['title']),
        intval($stile_config['value']),
        $checked,
        $stile_index,
        esc_url($img_path)
    );
}

/**
 * Generează un bloc complet de stile options pentru un material
 *
 * @param string $div_name Numele div-ului (ex: 'earth', 'basswoodPlus')
 * @param int $material_id ID-ul materialului pentru filtrare și checked
 * @param string $property_stile Valoarea curentă selectată
 * @param array $stile_options Array-ul $STILE_OPTIONS_CONFIG
 * @param mixed $current_material Materialul curent selectat (pentru display)
 * @return string HTML generat pentru întregul bloc
 */
function render_stile_block($div_name, $material_id, $property_stile, $stile_options, $current_material = '') {
    // Afișează div-ul doar dacă materialul curent corespunde cu material_id al blocului
    $display = ($current_material == $material_id) ? 'display: block;' : 'display: none;';
    $output = '<div class="col-sm-12" id="stile-img-' . esc_attr($div_name) . '" style="' . $display . '">';
    $output .= '<div id="choose-stiletype" class="" style="display: block;">';

    $stile_index = 1;
    foreach ($stile_options as $stile) {
        // Verifică dacă stile-ul este pentru acest material
        if (!in_array($material_id, $stile['materials'], false)) {
            continue;
        }

        $checked = ($property_stile == $stile['value']) ? 'checked' : '';
        $img_path = '/wp-content/plugins/shutter-module/imgs/' . $stile['img'];

        $output .= '<label>';
        $output .= '<br/> ' . esc_html($stile['title']);
        $output .= '<input type="radio" name="property_stile"';
        $output .= ' data-code="' . esc_attr($stile['code']) . '"';
        $output .= ' data-title="' . esc_attr($stile['title']) . '"';
        $output .= ' value="' . intval($stile['value']) . '" ' . $checked . ' />';
        $output .= '<img class="stile-' . $stile_index . '"';
        $output .= ' src="' . esc_url($img_path) . '"/>';
        $output .= '</label>';

        $stile_index++;
    }

    $output .= '</div></div>';
    return $output;
}

/**
 * Generează HTML pentru un radio button generic (property_style, property_frametype, etc.)
 *
 * @param string $name Numele input-ului (ex: 'property_style', 'property_frametype')
 * @param array $option Configurarea opțiunii
 * @param mixed $current_value Valoarea curentă selectată
 * @return string HTML generat
 */
function render_radio_option($name, $option, $current_value) {
    $hidden = !empty($option['hidden']) ? 'style="display:none;"' : '';
    $checked = ($current_value == $option['value']) ? 'checked' : '';
    $img_path = '/wp-content/plugins/shutter-module/imgs/' . $option['img'];

    // Determină textul afișat în label
    $label_text = '';
    if (!empty($option['label_prefix'])) {
        // Folosește prefix personalizat (ex: "Bay Window<br/>Tier-on-Tier")
        $label_text = $option['label_prefix'];
    } elseif (!empty($option['prefix'])) {
        // Prefix simplu + title (ex: "Basswood<br/>4008A")
        $label_text = $option['prefix'] . '<br/> ' . $option['title'];
    } elseif (!empty($option['label_text'])) {
        // Text personalizat pentru label
        $label_text = '<br/> ' . $option['label_text'];
    } else {
        // Doar title
        $label_text = '<br/> ' . $option['title'];
    }

    return sprintf(
        '<label %s>
            %s
            <br/>
            <input type="radio" name="%s"
                   data-code="%s"
                   data-title="%s"
                   value="%d" %s />
            <img src="%s">
        </label>',
        $hidden,
        $label_text,
        esc_attr($name),
        esc_attr($option['code']),
        esc_attr($option['title']),
        intval($option['value']),
        $checked,
        esc_url($img_path)
    );
}

/**
 * Sanitizează parametrii GET - fără restricții mari
 */
function sanitize_get_int($key, $default = 0) {
    return isset($_GET[$key]) ? intval($_GET[$key]) : $default;
}

function sanitize_get_string($key, $default = '') {
    return isset($_GET[$key]) ? sanitize_text_field($_GET[$key]) : $default;
}

// ==== PROCESARE PARAMETRI GET (sanitizați) ====
$user_id = !empty($_GET['cust_id']) ? intval($_GET['cust_id']) : get_current_user_id();
$item_id = sanitize_get_string('item_id', '');
$clone_id = !empty($_GET['clone']) ? intval(base64_decode($_GET['clone'])) : '';
$edit_customer = (sanitize_get_string('order_edit_customer') === 'editable');
$order_edit = !empty($_GET['order_id']) ? intval($_GET['order_id']) / 1498765 / 33 : '';
$raw_product_id = sanitize_get_int('id', 0);

$dealer_id = get_user_meta($user_id, 'company_parent', true);

// ==== INTEROGĂRI BAZĂ DE DATE (cu prepared statements) ====
global $wpdb;

// Query 1: Multiple shipping addresses
$meta_key = 'wc_multiple_shipping_addresses';
$addresses = $wpdb->get_var($wpdb->prepare(
    "SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key = %s",
    $user_id, $meta_key
));
if ($addresses) {
    $addresses = maybe_unserialize($addresses);
}

// Query 2: Multisession data (SECURIZAT cu prepared statement)
$multisession_data = $wpdb->get_var($wpdb->prepare(
    "SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key = %s",
    $user_id, '_woocom_multisession'
));
$userialize_data = $multisession_data ? maybe_unserialize($multisession_data) : [];

// Query 3: Session data (SECURIZAT cu prepared statement)
$session_key = isset($userialize_data['customer_id']) ? $userialize_data['customer_id'] : 0;
$session_value = $wpdb->get_var($wpdb->prepare(
    "SELECT session_value FROM {$wpdb->prefix}woocommerce_sessions WHERE session_key = %s",
    $session_key
));
$userialize_session = $session_value ? maybe_unserialize($session_value) : [];

// ==== INIȚIALIZARE VARIABILE ====
$i = 1;
$carts_sort = isset($userialize_data['carts']) ? $userialize_data['carts'] : [];
if (!empty($carts_sort)) {
    ksort($carts_sort);
}
$total_elements = count($carts_sort) + 1;

// Variabile pentru formular
$cart_name = '';
$attachment = '';
$attachmentDraw = '';
$product_id = '';
$property_solidtype = '';
$property_style = '';
$property_room_other = '';
$property_frametype = '';
$property_tposttype = null;
$property_stile = '';
$material = '138'; // Default: BiowoodPlus - sincronizat cu valoarea default din input property_material
$property_builtout = '';
$nr_code_prod = [];
$items_name = [];
$first_item = null;

// Variabile pentru frame
$frameleft_first = '';
$frameright_first = '';
$frametop_first = '';
$framebottom_first = '';

// Variabile pentru culori și control
$hingecolour_first = '';
$shuttercolour_first = '';
$property_colour_other = '';
$controltype_first = '';
$bladesize_first = '';
$positioncritical_first = '';
$property_doubleClosingLouvres_first = '';

// Găsește cart_name
foreach ($carts_sort as $key => $carts) {
    if (isset($userialize_data['customer_id']) && $userialize_data['customer_id'] == $key) {
        $cart_name = $carts['name'];
    }
}

// ==== PROCESARE PRODUS EXISTENT (EDIT MODE) ====
if ($raw_product_id > 0) {
    $product_id = $raw_product_id / 1498765 / 33;
    echo "<input type='hidden' id='pod_item_id' val='" . esc_attr($product_id) . "'>";

    // OPTIMIZARE: Încarcă toate meta-urile o singură dată
    $product_meta = get_post_meta($product_id);

    // Extrage valorile din array-ul pre-încărcat
    $property_style = get_meta_value($product_meta, 'property_style');
    $property_order_edit = get_meta_value($product_meta, 'order_edit');
    if (empty($order_edit)) {
        $order_edit = $property_order_edit;
    }

    $property_room_other = get_meta_value($product_meta, 'property_room_other');
    $attachment = get_meta_value($product_meta, 'attachment');
    $attachmentDraw = get_meta_value($product_meta, 'attachmentDraw');
    $property_horizontaltpost = get_meta_value($product_meta, 'property_horizontaltpost');
    $bay_post_type = get_meta_value($product_meta, 'bay-post-type');
    $t_post_type = get_meta_value($product_meta, 't-post-type');
    $material = get_meta_value($product_meta, 'property_material');
    $property_builtout = get_meta_value($product_meta, 'property_builtout');
    $property_solidtype = get_meta_value($product_meta, 'property_solidtype');
    $property_trackedtype = get_meta_value($product_meta, 'property_trackedtype');
    $property_freefolding = get_meta_value($product_meta, 'property_freefolding');
    $property_bypasstype = get_meta_value($product_meta, 'property_bypasstype');
    $property_colour_other = get_meta_value($product_meta, 'property_shuttercolour_other');
    $property_lightblocks = get_meta_value($product_meta, 'property_lightblocks');
    $property_tracksnumber = get_meta_value($product_meta, 'property_tracksnumber');
    $property_tposttype = get_meta_value($product_meta, 'property_tposttype');
    $comments_customer = get_meta_value($product_meta, 'comments_customer');
    $property_frametype = get_meta_value($product_meta, 'property_frametype');
    $frameleft_first = get_meta_value($product_meta, 'property_frameleft');
    $frameright_first = get_meta_value($product_meta, 'property_frameright');
    $frametop_first = get_meta_value($product_meta, 'property_frametop');
    $framebottom_first = get_meta_value($product_meta, 'property_framebottom');
    $property_stile = get_meta_value($product_meta, 'property_stile');

    // Property categories
    $property_categories = [
        'g' => get_meta_value($product_meta, 'counter_g', 0),
        't' => get_meta_value($product_meta, 'counter_t', 0),
        'c' => get_meta_value($product_meta, 'counter_c', 0),
        'b' => get_meta_value($product_meta, 'counter_b', 0),
    ];

    foreach ($property_categories as $category_prefix => $count) {
        $nr_code_prod[$category_prefix] = $count;
    }

    // Verifică cart
    $cart = WC()->cart->get_cart();
    foreach ($cart as $cart_item_key => $cart_item) {
        $products = $cart_item['data']->get_id();
        // Procesare dacă e nevoie
    }

} else {
    // ==== MOD NOU PRODUS ====
    $cart = WC()->cart->get_cart();

    if (count($cart) >= 1 && $edit_customer == false) {
        foreach ($cart as $cart_item_key => $cart_item) {
            $product_id_cart = $cart_item['product_id'];
            $room_other = get_post_meta($product_id_cart, 'property_room_other', true);
            $items_name[] = str_replace("'", "", $room_other);

            // Gestionare clone_id
            if (!empty($clone_id) && $clone_id == $product_id_cart) {
                update_post_meta($product_id_cart, 'clone_prod_id', $clone_id);
            } elseif (!empty($clone_id) && $clone_id != $product_id_cart) {
                update_post_meta($product_id_cart, 'clone_prod_id', '');
            }
        }

        if ($clone_id == '' && isset($product_id_cart)) {
            $clone_id = get_post_meta($product_id_cart, 'clone_prod_id', true);
        }

        $first_item = array_shift($cart);
        $first_prod_id = !empty($clone_id) ? $clone_id : (isset($first_item['product_id']) ? $first_item['product_id'] : 0);

        if ($first_prod_id) {
            // OPTIMIZARE: Încarcă toate meta-urile o singură dată pentru primul produs
            $first_meta = get_post_meta($first_prod_id);

            $material = get_meta_value($first_meta, 'property_material');
            $property_style = get_meta_value($first_meta, 'property_style');
            $bladesize_first = get_meta_value($first_meta, 'property_bladesize');
            $positioncritical_first = get_meta_value($first_meta, 'property_midrailpositioncritical');

            // Frame Type, Left, Right, Top, Bottom, Buildout, Stile (doar pentru stiluri non-tracked)
            if (!in_array($property_style, STYLES_TRACKED_NO_FRAME)) {
                $property_frametype = get_meta_value($first_meta, 'property_frametype');
                $frameleft_first = get_meta_value($first_meta, 'property_frameleft');
                $frameright_first = get_meta_value($first_meta, 'property_frameright');
                $frametop_first = get_meta_value($first_meta, 'property_frametop');
                $framebottom_first = get_meta_value($first_meta, 'property_framebottom');
                $property_builtout = get_meta_value($first_meta, 'property_builtout');
                $property_stile = get_meta_value($first_meta, 'property_stile');
            }

            // Hinge Colour, Shutter Colour, Control Type
            $hingecolour_first = get_meta_value($first_meta, 'property_hingecolour');
            $shuttercolour_first = get_meta_value($first_meta, 'property_shuttercolour');
            $property_colour_other = get_meta_value($first_meta, 'property_shuttercolour_other');
            $controltype_first = get_meta_value($first_meta, 'property_controltype');
            $property_doubleClosingLouvres_first = get_meta_value($first_meta, 'property_double_closing_louvres');
        }
    }
}


?>


<!-- stylesheet -->
<link href="/wp-content/themes/storefront-child/canvas-demo/_assets/literallycanvas.css" rel="stylesheet">

<!-- dependency: React.js -->
<script src="//cdnjs.cloudflare.com/ajax/libs/react/0.14.7/react-with-addons.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/react/0.14.7/react-dom.js"></script>

<!-- Literally Canvas -->
<script src="/wp-content/themes/storefront-child/canvas-demo/static/js/literallycanvas.js"></script>


<!-- you really ought to include react-dom, but for react 0.14 you don't strictly have to. -->
<script src="/wp-content/themes/storefront-child/canvas-demo/_js_libs/react-0.14.3.js"></script>
<script src="/wp-content/themes/storefront-child/canvas-demo/_js_libs/literallycanvas.js"></script>


<div class="main-content">
    <div class="page-content" style="background-color: #F7F7F7">
        <div class="page-content-area">
            <div class="page-header">
				<?php

				if (!empty($_GET['order_id'])) {
					$cart_name = get_post_meta($order_edit, 'cart_name', true);
				}
				if (!empty($_GET['id'])) { ?>
                    <h1>Update Shutter
                        - <?php echo $cart_name; ?></h1> <?php } elseif (!empty($_GET['order_id'])) { ?>
                    <h1>Add Shutter</h1>
				<?php } else { ?>
                    <h1>Add Shutter
                        - <?php echo $cart_name; ?></h1> <?php }
				//				echo $clone_id;
				?>
            </div>
			<?php
			if (!empty($_GET['id'])) {
				// Arrays holding the property prefixes and their counts
				$propertyPrefixes = ['g', 't', 'c', 'bp', 'ba'];
				$propertyCounts = [
				  'g' => $nr_code_prod['g'],
				  't' => $nr_code_prod['t'],
				  'c' => $nr_code_prod['c'],
				  'bp' => $nr_code_prod['b'],
				  'ba' => $nr_code_prod['b'],
				];

// Loop through each property prefix
				foreach ($propertyPrefixes as $prefix) {
					$count = $propertyCounts[$prefix];
					for ($i = 1; $i <= $count; $i++) {
						// Dynamically build the property name
						$propertyName = "property_{$prefix}{$i}";
						// Retrieve the meta value
						$value = get_post_meta($product_id, $propertyName, true);
						// Output the input field
						echo '<input type="hidden" id="' . $prefix . $i . '" name="' . $prefix . $i . '" value="' . htmlspecialchars($value) . '">' . PHP_EOL;
					}
				}
			}
			?>

            <input type="hidden" id="property_frametype" name="property_frametype_hidden"
                   value="<?php echo $property_frametype; ?>">
			<?php
			if (!empty($_GET['id'])) {
				echo '<input type="hidden" id="type_item" name="type_item" value="update_item">';
			}
			?>
            <div class="row">
                <form <?php if (!empty($_GET['id'])) {
					echo 'edit="yes"';
				} else {
					echo 'edit="no"';
				} ?> accept-charset="UTF-8" action="/order_products/add_single_product" data-type="json"
                     enctype="multipart/form-data" id="add-product-single-form" method="post">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-sm-9">
                                <strong>Order Reference:</strong> <?php echo $cart_name; ?>
                                <br/>
                                <br/>
                            </div>
                            <div class="col-sm-3 pull-right"> Total Square Meters&nbsp;
                                <input class="input-small" id="property_total" name="property_total" type="text"
                                       value=""/>
                            </div>
                        </div>
                        <div style="display:none"></div>
                        <input type="hidden" name="product_id_updated" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="customer_id" value="<?php echo $user_id; ?>">
                        <input type="hidden" name="dealer_id" value="<?php echo $dealer_id; ?>">
                        <input type="hidden" name="edit_customer" value="<?php echo $edit_customer; ?>">
                        <input type="hidden" name="order_edit" value="<?php echo $order_edit; ?>">
                        <input type="hidden" name="cart_items_name" value='<?php echo json_encode($items_name); ?>'>


                        <input type="hidden" id="property_frametype_hidden" name="property_frametype"
                               value="<?php echo $property_frametype; ?>">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel-default done">
                                        <div class="panel-heading" role="tab" id="headingStyle">
                                            <h4 class="panel-title">
                                                <a role="button" class="" data-toggle="collapse"
                                                   data-parent="#accordion" href="#collapseStyle" aria-expanded="true"
                                                   aria-controls="collapseStyle">
                                                    <strong>Shutters Design</strong>

                                                    <span type="button" class="icon--edit edit" tabindex="2">
                                                        <i class="fas fa-pen fa fa-pencil"></i> Edit</span>
                                                    <!--                                                    <span type="button" class="js-next-step next-step"-->
                                                    <!--                                                          tabindex="2">Next step</span>-->
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseStyle" class="panel-collapse collapse in" role="tabpanel"
                                             aria-labelledby="headingStyle" aria-expanded="true" style="">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-sm-3" style="display:none;"> Room:
                                                        <br/>
                                                        <input class="property-select" id="property_room"
                                                               name="property_room" type="text" value="94"/>
                                                    </div>
                                                    <div class="col-sm-3" id="room-other" style="display: block"> Room
                                                        Name:
                                                        <br/>
                                                        <input required class="input-medium required"
                                                               id="property_room_other" name="property_room_other"
                                                               style="height: 30px" type="text"
                                                               value="<?php echo $property_room_other; ?>"/>
                                                    </div>
                                                    <div class="col-sm-3"> Material:
                                                        <br/>
                                                        <input class="property-select" id="property_material"
                                                               name="property_material" type="text"
                                                               value="<?php if (!empty($_GET['id'])) {
															       echo get_post_meta($product_id, 'property_material', true);
														       } elseif ($first_item) {
															       echo $material;
														       } else {
															       echo '138';
														       } ?>"/>
                                                        <input id="product_id" name="product_id" type="hidden"
                                                               value=""/>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:1em;">
                                                    <div class="col-sm-12"> Installation Style:
                                                        <div id="choose-style">
                                                            <?php
                                                            // Generează opțiunile de style dinamic din $STYLE_OPTIONS
                                                            foreach ($STYLE_OPTIONS as $option) {
                                                                echo render_radio_option('property_style', $option, $property_style);
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-2" id="shape-section-draw" style="display: none">
                                                        Draw Shape
                                                        <br/>
                                                        <button id="btnDrawModal" type="button" class="btn btn-primary"
                                                                data-toggle="modal" data-target="#drawModal"
                                                                style="display: block;">
                                                            Open Drawing Pad
                                                        </button>
                                                        <img id="frontend-image-draw"
                                                             src="<?php echo $attachmentDraw; ?>"/>
                                                        <input type="text" id="attachmentDraw" name="attachmentDraw"
                                                               style="visibility: hidden;"
                                                               value="<?php if ($attachmentDraw) {
															       echo $attachmentDraw;
														       } ?>"/>

                                                    </div>

                                                    <div class="col-sm-3" id="shape-section" style="display: block">
                                                        Attach shape drawing
                                                        <br/>
                                                        <div id="shape-upload-container">
                                                            <span id="provided-shape"> </span>
                                                            <!-- <input id="attachment" name="wp_custom_attachment" type="file" /> -->
                                                            <button id="frontend-button" type="button"
                                                                    value="Select File to Upload"
                                                                    class="button btn btn-primary"
                                                                    style="position: relative; z-index: 1;">Select File
                                                                to Upload
                                                            </button>
                                                            <img id="frontend-image" src="<?php echo $attachment; ?>"/>
                                                            <input type="text" id="attachment" name="attachment"
                                                                   style="visibility: hidden;"
                                                                   value="<?php if ($attachment) {
																       echo $attachment;
															       } ?>"/>
                                                        </div>
                                                    </div>


                                                </div>
                                                <div class="row" style="margin-top:1em;  margin-bottom: 10px;">
                                                    <div class="col-sm-2"> Width (mm):
                                                        <br/>
                                                        <input class="required number input-medium" id="property_width"
                                                               name="property_width" type="number"
                                                               value="<?php echo get_post_meta($product_id, 'property_width', true); ?>"/>
                                                    </div>
                                                    <div class="col-sm-2"> Height (mm):
                                                        <br/>
                                                        <input class="required number input-medium" id="property_height"
                                                               name="property_height" type="number"
                                                               value="<?php echo get_post_meta($product_id, 'property_height', true); ?>"/>
                                                    </div>
                                                    <div class="col-sm-2" id="midrail-height"> Midrail Height (mm):
                                                        <br/>
                                                        <input class="number input-medium" id="property_midrailheight"
                                                               name="property_midrailheight" type="number"
                                                               value="<?php echo get_post_meta($product_id, 'property_midrailheight', true); ?>"/>
                                                    </div>
                                                    <div class="col-sm-2" id="midrail-height2"> Midrail Height 2 (mm):
                                                        <br/>
                                                        <input class="number input-medium" id="property_midrailheight2"
                                                               name="property_midrailheight2" type="number"
                                                               value="<?php echo get_post_meta($product_id, 'property_midrailheight2', true); ?>"/>
                                                    </div>
                                                    <div class="col-sm-2" id="midrail-divider"> Hidden Divider 1 (mm):
                                                        <br/>
                                                        <input class=" number input-medium"
                                                               id="property_midraildivider1"
                                                               name="property_midraildivider1" type="number"
                                                               value="<?php echo get_post_meta($product_id, 'property_midraildivider1', true); ?>"/>
                                                    </div>
                                                    <div class="col-sm-2" id="midrail-divider2"> Hidden Divider 2 (mm):
                                                        <br/>
                                                        <input class=" number input-medium"
                                                               id="property_midraildivider2"
                                                               name="property_midraildivider2" type="number"
                                                               value="<?php echo get_post_meta($product_id, 'property_midraildivider2', true); ?>"/>
                                                    </div>
                                                    <div id="solidtype" class="col-sm-2" style="display: none"> Solid
                                                        Type:
                                                        <br/>
                                                        <div id="property_solidtype_raised">
                                                            <input name="property_solidtype" type="radio" id="property_solidtype_raised"
															  <?php if ($property_solidtype == 'raised') echo 'checked'; ?>
                                                                   value="raised"/> Double Raised
                                                        </div>
                                                        <br/>
                                                        <div id="property_solidtype_flat">
                                                            <input name="property_solidtype" type="radio"
															  <?php if ($property_solidtype == 'flat') echo 'checked'; ?>
                                                                   value="flat"/> <span>Flat</span></div>
                                                    </div>
                                                    <div class="col-sm-2" id="solid-panel-height" style="display:none;">
                                                        Solid Panel Height (mm):
                                                        <br/>
                                                        <input class="number input-medium"
                                                               id="property_solidpanelheight"
                                                               name="property_solidpanelheight" type="number"
                                                               value="<?php echo get_post_meta($product_id, 'property_solidpanelheight', true); ?>"/>
                                                    </div>
                                                    <div class="col-sm-2" id="midrail-position-critical"> Position is
                                                        Critical:
                                                        <br/>
                                                        <div class="input-group-container">
                                                            <div class="input-group">

                                                                <input class="property-select"
                                                                       id="property_midrailpositioncritical"
                                                                       name="property_midrailpositioncritical"
                                                                       type="text"
                                                                       value="<?php
																       echo get_post_meta($product_id, 'property_midrailpositioncritical', true); ?>"/>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 tot-height" style="display: none"> T-o-T Height
                                                        (mm):
                                                        <br/>
                                                        <input class="required number input-medium"
                                                               id="property_totheight" name="property_totheight"
                                                               type="number"
                                                               value="<?php echo get_post_meta($product_id, 'property_totheight', true); ?>"/>
                                                    </div>
                                                    <div class="col-sm-2 tot-height horizontal-t-post"
                                                         style="display: none">
                                                        Horizontal T Post
                                                        <br/>
                                                        <input id="property_horizontaltpost"
                                                               name="property_horizontaltpost" type="checkbox"
                                                               value="Yes"
														  <?php if ($property_horizontaltpost == 'Yes') {
															  echo "checked";
														  } ?> />
                                                    </div>
                                                    <div class="col-sm-2"> Louvre Size:
                                                        <br/>
                                                        <input class="property-select required bladesize_porperty"
                                                               id="property_bladesize" name="property_bladesize"
                                                               type="text"
                                                               value="<?php
														       if ($first_item) {
															       echo $bladesize_first;
														       } else {
															       echo get_post_meta($product_id, 'property_bladesize', true);
														       }
														       ?>"/>
                                                    </div>
                                                    <div class="col-sm-4" id="locks2"
                                                         style="display:none">
                                                        <div>
                                                            <label>
                                                                With Louver lock:
                                                                <br/>
                                                                <select name="property_louver_lock">
																	<?php
																	$property_louver_lock = get_post_meta($product_id, 'property_louver_lock', true);
																	$select = ($property_louver_lock == 'Yes') ? 'selected' : ''; ?>
                                                                    <option value="No">No
                                                                    </option>
                                                                    <option value="Yes" <?php echo $select; ?>>Yes
                                                                    </option>
                                                                </select>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div id="trackedtype" class="col-sm-2" style="display: none">Track
                                                        Installation type:
                                                        <br/>
                                                        <input name="property_trackedtype" type="radio"
														  <?php if ($property_trackedtype == 'inside recess') echo 'checked'; ?>
                                                               value="inside recess"/> Inside recess
                                                        <br/>
                                                        <input name="property_trackedtype" type="radio"
														  <?php if ($property_trackedtype == 'outside recess') echo 'checked'; ?>
                                                               value="outside recess"/> Outside recess
                                                    </div>

                                                    <div id="free-folding" class="col-sm-2" style="display: none">Free
                                                        folding :
                                                        <br/>
                                                        <input name="property_freefolding" type="radio"
														  <?php
														  $defaultCheck = empty($property_freefolding) ? 'checked' : '';
														  ?>
														  <?php if ($property_freefolding == 'yes') echo 'checked'; ?>
                                                               value="yes" id="freefyes"/> <label for="freefyes">Yes</label>
                                                        <br/>
                                                        <input name="property_freefolding" type="radio"
															<?php if ($property_freefolding == 'no') echo 'checked';
															echo $defaultCheck; ?> value="no" id="freefno"//> <label for="freefno">No</label>
                                                    </div>
                                                    <div id="bypasstype" class="col-sm-2" style="display: none"> By-pass
                                                        Type:
                                                        <br/>
                                                        <input name="property_bypasstype" type="radio"
														  <?php if ($property_bypasstype == 'closed') echo 'checked'; ?>
                                                               value="closed"/> Closed
                                                        <br/>
                                                        <input name="property_bypasstype" type="radio"
														  <?php if ($property_bypasstype == 'open') echo 'checked'; ?>
                                                               value="open"/> Open
                                                    </div>
                                                    <div id="lightblocks" class="col-sm-2" style="display: none"> Light
                                                        Blocks:
                                                        <br/>
                                                        <input name="property_lightblocks" type="radio"
														  <?php if ($property_lightblocks == 'Yes') echo 'checked'; ?>
                                                               value="Yes"/> Yes
                                                        <br/>
                                                        <input name="property_lightblocks" type="radio"
														  <?php if ($property_lightblocks == 'No') echo 'checked';
														  if (empty($property_lightblocks)) echo 'checked';
														  ?>
                                                               value="No"/> No
                                                    </div>
                                                    <div class="col-sm-4" id="doubleClosingLouvres"
                                                         style="">
                                                        <div>
                                                            <label>
                                                                Double closing louvres:
                                                                <br/>
                                                                <select name="property_double_closing_louvres">
																	<?php

																	if ($first_item) {
																		echo $property_doubleClosingLouvres_first;
																		$select = ($property_doubleClosingLouvres_first == 'Yes') ? 'selected' : '';
																	} else {
																		$property_doubleClosingLouvres = get_post_meta($product_id, 'property_double_closing_louvres', true);
																		$select = ($property_doubleClosingLouvres == 'Yes') ? 'selected' : '';
																	}
																	?>
                                                                    <option value="No">No
                                                                    </option>
                                                                    <option value="Yes" <?php echo $select; ?>>Yes
                                                                    </option>
                                                                </select>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-bottom: 10px;">
                                                    <div class="col-sm-3 property_fit"> Measure Type:
                                                        <br/>
                                                        <div class="input-group-container">
                                                            <div class="input-group">
																<?php if (!empty($_GET['id'])) { ?>
                                                                    <input class="property-select" id="property_fit"
                                                                           name="property_fit" type="text"
                                                                           value="<?php echo get_post_meta($product_id, 'property_fit', true); ?>"/>
																<?php } else { ?>
                                                                    <input class="property-select" id="property_fit"
                                                                           name="property_fit" type="text"
                                                                           value="outside"/>
																<?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <hr/>
                                                        <button class="btn btn-info show-next-panel"
                                                                next-panel="#headingOne"> Next
                                                            <i class="fa fa-chevron-right"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default inactive">
                                        <div class="panel-heading" role="tab" id="headingOne">
                                            <h4 class="panel-title">
                                                <a role="button" class="" data-toggle="collapse"
                                                   data-parent="#accordion" href="#collapseOne" aria-expanded="true"
                                                   aria-controls="collapseOne">
                                                    <strong>Frame &amp; Stile Design</strong>
                                                    <span type="button" class="icon--edit edit" tabindex="2">
                                                        <i class="fas fa-pen fa fa-pencil"></i> Edit</span>
                                                    <!--                                                    <span type="button" class="js-next-step next-step"-->
                                                    <!--                                                          tabindex="2">Next step</span>-->
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseOne" class="panel-collapse collapse " role="tabpanel"
                                             aria-labelledby="headingOne">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-sm-12"> Frame Type:
                                                        <p id="required-choices-frametype">
                                                            <i>Please select Material &amp; Style in order to view
                                                                available Frame Type choices</i>
                                                        </p>
                                                        <div id="choose-frametype">
                                                            <?php
                                                            // Generează opțiunile de frametype dinamic din $FRAMETYPE_OPTIONS
                                                            foreach ($FRAMETYPE_OPTIONS as $option) {
                                                                echo render_radio_option('property_frametype', $option, $property_frametype);
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row frames" style="margin-top:1em; margin-bottom: 20px;">
                                                    <div class="col-sm-12">
														<?php if (!empty($_GET['id']) || !empty($first_item)) { ?>
                                                            <div class="pull-left" id="frame-left"> Frame Left
                                                                <i class="fa fa-arrow-left"></i>
                                                                <br/>
                                                                <input class="property-select" id="property_frameleft"
                                                                       name="property_frameleft" type="text"
                                                                       value="<?php
																       if ($first_item) {
																	       echo $frameleft_first;
																       } else {
																	       echo get_post_meta($product_id, 'property_frameleft', true);
																       }
																       ?>"/>
                                                            </div>
                                                            <div class="pull-left" id="frame-right"> Frame Right
                                                                <i class="fa fa-arrow-right"></i>
                                                                <br/>
                                                                <input class="property-select" id="property_frameright"
                                                                       name="property_frameright" type="text"
                                                                       value="<?php
																       if ($first_item) {
																	       echo $frameright_first;
																       } else {
																	       echo get_post_meta($product_id, 'property_frameright', true);
																       }
																       ?>"/>
                                                            </div>
                                                            <div class="pull-left" id="frame-top"> Frame Top

                                                                <i class="fa fa-arrow-up"></i>
                                                                <br/>
                                                                <input class="property-select" id="property_frametop"
                                                                       name="property_frametop" type="text"
                                                                       value="<?php
																       if ($first_item) {
																	       echo $frametop_first;
																       } else {
																	       echo get_post_meta($product_id, 'property_frametop', true);
																       }
																       ?>"/>
                                                            </div>
                                                            <div class="pull-left" id="frame-bottom"> Frame Bottom
                                                                <i class="fa fa-arrow-down"></i>
                                                                <br/>
                                                                <input class="property-select" id="property_framebottom"
                                                                       name="property_framebottom" type="text"
                                                                       value="<?php
																       if (!empty($first_item)) {
																	       echo $framebottom_first;
																       } else {
																	       echo get_post_meta($product_id, 'property_framebottom', true);
																       }
																       ?>"/>
                                                            </div> <?php } else { ?>
                                                            <div class="pull-left" id="frame-left"> Frame Left
                                                                <i class="fa fa-arrow-left"></i>
                                                                <br/>
                                                                <input class="property-select" id="property_frameleft"
                                                                       name="property_frameleft" type="text"
                                                                       value="70"/>
                                                            </div>
                                                            <div class="pull-left" id="frame-right"> Frame Right
                                                                <i class="fa fa-arrow-right"></i>
                                                                <br/>
                                                                <input class="property-select" id="property_frameright"
                                                                       name="property_frameright" type="text"
                                                                       value="75"/>
                                                            </div>
                                                            <div class="pull-left" id="frame-top"> Frame Top
                                                                <i class="fa fa-arrow-up"></i>
                                                                <br/>
                                                                <input class="property-select" id="property_frametop"
                                                                       name="property_frametop" type="text" value="80"/>
                                                            </div>
                                                            <div class="pull-left" id="frame-bottom"> Frame Bottom
                                                                <i class="fa fa-arrow-down"></i>
                                                                <br/>
                                                                <input class="property-select" id="property_framebottom"
                                                                       name="property_framebottom" type="text"
                                                                       value="85"/>
                                                            </div> <?php }
														$display = (!empty($property_builtout)) ? 'inline' : 'none';
														$add_buildout = (empty($property_builtout)) ? '' : 'display: none';
														?>
                                                        <div class="pull-left">
                                                            <span id="add-buildout"
                                                                  style="<?php echo $add_buildout; ?>">
                                                                <br/>
                                                                <button class="btn btn-info" style="padding: 0 12px">Add
                                                                    buildout</button>
                                                            </span>
                                                            <span id="buildout"
                                                                  style="display: <?php echo $display; ?>"> Buildout:
                                                                <br/> <input class="input-small" id="property_builtout"
                                                                             name="property_builtout"
                                                                             placeholder="Enter buildout"
                                                                             style="height: 30px;"
                                                                             type="text"
                                                                             value="<?php
                                                                             echo $property_builtout;
                                                                             ?>"/>
                                                                <button class="btn btn-danger btn-input"
                                                                        id="remove-buildout"> <strong>X</strong> </button>
                                                            </span>
                                                        </div>
                                                        <!-- <div class="pull-left">                                   Stile:                                   <br/>                                   <input class="property-select" id="property_stile" name="property_stile" type="text" value="<?php echo get_post_meta($product_id, 'property_stile', true); ?>"                                   />                                 </div> -->
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <?php
                                                    // Generează blocurile de stile options dinamic
                                                    foreach ($STILE_DIV_CONFIG as $div_name => $mat_id) {
                                                        echo render_stile_block($div_name, $mat_id, $property_stile, $STILE_OPTIONS_CONFIG, $material);
                                                    }
                                                    ?>
                                                </div>
                                                <!-- <div class="row">                               <div class="col-sm-4">                                     <div class="">                                       Stile:                                       <br/>                                       <input class="property-select required" id="property_stile" name="property_stile" type="text" value="<?php echo get_post_meta($product_id, 'property_stile', true); ?>" />                                     </div>                                     <br/>                               </div>                             </div> -->
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <hr/>
                                                        <button class="btn btn-info show-next-panel"
                                                                next-panel="#headingColour"> Next
                                                            <i class="fa fa-chevron-right"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default inactive">
                                        <div class="panel-heading" role="tab" id="headingColour">
                                            <h4 class="panel-title">
                                                <a role="button" class="" data-toggle="collapse"
                                                   data-parent="#accordion" href="#collapseColour" aria-expanded="true"
                                                   aria-controls="collapseColour">
                                                    <strong>Colour, Hinges, Control &amp;
                                                        Configuration Design</strong>
                                                    <span type="button" class="icon--edit edit" tabindex="2">
                                                        <i class="fas fa-pen fa fa-pencil"></i> Edit</span>
                                                    <!--                                                    <span type="button" class="js-next-step next-step"-->
                                                    <!--                                                          tabindex="2">Next step</span>-->
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseColour" class="panel-collapse collapse " role="tabpanel"
                                             aria-labelledby="headingColour">
                                            <div class="panel-body">
                                                <div class="row" id="step4-info">
                                                    <div class="col-sm-12">
                                                        <div class="alert alert-info" role="alert"
                                                             style="display: none;">
                                                            Waive the waterproof warranty!
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3"> Hinge Colour:
                                                        <br/>
                                                        <input class="property-select" id="property_hingecolour"
                                                               name="property_hingecolour" type="text"
                                                               value="<?php
														       if ($first_item) {
															       echo $hingecolour_first;
														       } else {
															       echo get_post_meta($product_id, 'property_hingecolour', true);
														       }
														       ?>"/>
                                                    </div>
                                                    <div class="col-sm-3"> Shutter Colour:
                                                        <br/>
                                                        <input class="property-select" id="property_shuttercolour"
                                                               name="property_shuttercolour" type="text"
                                                               value="<?php
														       if ($first_item) {
															       echo $shuttercolour_first;
														       } else {
															       echo get_post_meta($product_id, 'property_shuttercolour', true);
														       }
														       ?>"/>
                                                    </div>
                                                    <div class="col-sm-3" id="colour-other" style="display: none"> Other
                                                        Colour:
                                                        <br/>
                                                        <input id="property_shuttercolour_other"
                                                               name="property_shuttercolour_other" style="height: 30px"
                                                               type="text"
                                                               value="<?php echo $property_colour_other; ?>"/>
                                                    </div>
                                                    <div class="col-sm-3"> Control Type:
                                                        <br/>

                                                        <input class="property-select" id="property_controltype"
                                                               name="property_controltype" type="text"
                                                               value="<?php
														       if ($first_item) {
															       echo $controltype_first;
														       } else {
															       echo get_post_meta($product_id, 'property_controltype', true);
														       }
														       ?>"/>
                                                    </div>
                                                </div>
                                                <div class="row layout-row" style="margin-top:1em;">
                                                    <div class="col-sm-6" id="layoutcode-column"> Layout Configuration:
                                                        <br/>
                                                        <div class="input-group-container">
                                                            <div class="input-group">

                                                                <input class="required input-medium"
                                                                       id="property_layoutcode"
                                                                       name="property_layoutcode"
                                                                       style="text-transform:uppercase" type="text"
                                                                       value="<?php echo strtoupper(get_post_meta($product_id, 'property_layoutcode', true)); ?>"/>
                                                                <input class="required input-medium"
                                                                       id="property_layoutcode_tracked"
                                                                       name="property_layoutcode_tracked"
                                                                       style="display: none; text-transform:uppercase"
                                                                       type="text"
                                                                       value="<?php echo strtoupper(get_post_meta($product_id, 'property_layoutcode_tracked', true)); ?>"/>
                                                            </div>
                                                            <div class="note-ecowood-angle"
                                                                 style="<?php if ($material != 188) {
																     echo 'display: none';
															     } ?>;">Note: this
                                                                material allows only 90 and 135 bay
                                                                angles
                                                            </div>
                                                        </div>
                                                        <div class="input-group-container">
                                                            <div class="input-group">
                                                                <div id="tracksnumber" style="display: none"> Number of
                                                                    Tracks:
                                                                    <br/>
                                                                    <label class="radio-inline">
                                                                        <input name="property_tracksnumber" type="radio"
																		  <?php if ($property_tracksnumber == '2') echo 'checked'; ?>
                                                                               value="2" checked/> 2
                                                                    </label>
                                                                    <label class="radio-inline">
                                                                        <input name="property_tracksnumber" type="radio"
																		  <?php if ($property_tracksnumber == '3') echo 'checked'; ?>
                                                                               value="3"/> 3
                                                                    </label>
                                                                    <label class="radio-inline">
                                                                        <input name="property_tracksnumber" type="radio"
																		  <?php if ($property_tracksnumber == '4') echo 'checked'; ?>
                                                                               value="4"/> 4
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
													<?php
													$property_opendoor = get_post_meta($product_id, 'property_opendoor', true);
													$show_dooropen = !empty($property_opendoor) ? 'block' : 'none';
													?>
                                                    <div class="col-sm-6"
                                                         style="display: <?php echo $show_dooropen; ?>;"> Door to open
                                                        first:
                                                        <br/>
                                                        <select id="property_opendoor" name="property_opendoor">
                                                            <option value=""></option>
                                                            <option value="Right" <?php if ($property_opendoor == 'Right') {
																echo 'selected';
															} ?>>Right
                                                            </option>
                                                            <option value="Left" <?php if ($property_opendoor == 'Left') {
																echo 'selected';
															} ?>>Left
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row extra-columns-row">
                                                    <div class="col-sm-12">
														<?php if ($nr_code_prod) {
															foreach ($nr_code_prod as $key => $val) {
																for ($i = 1; $i < $val + 1; $i++) {
																	if ($key == 'b') { ?>
                                                                        <div class="pull-left extra-column">
                                                            <span class="extra-column-label">Bay
                                                                Post<?php echo $i; ?></span>:
                                                                            <br>
                                                                            <div class="input-group">

                                                                                <input name="property_bp<?php echo $i; ?>"
                                                                                       id="property_bp<?php echo $i; ?>"
                                                                                       class="input-small required"
                                                                                       type="text"
                                                                                       value="<?php echo get_post_meta($product_id, 'property_bp' . $i, true); ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="pull-left extra-column">
                                                            <span class="extra-column-label">Bay Angle
                                                                <?php echo $i; ?></span>:
                                                                            <br>
                                                                            <div class="input-group">

                                                                                <input name="property_ba<?php echo $i; ?>"
                                                                                       id="property_ba<?php echo $i; ?>"
                                                                                       class="input-small required"
                                                                                       type="text"
                                                                                       value="<?php echo get_post_meta($product_id, 'property_ba' . $i, true); ?>">

                                                                            </div>
                                                                        </div>
																	<?php } elseif ($key == 't') { ?>
                                                                        <div class="pull-left extra-column">
                                                            <span
                                                              class="extra-column-label">T-Post<?php echo $i; ?></span>:
                                                                            <br>
                                                                            <div class="input-group">

                                                                                <input name="property_t<?php echo $i; ?>"
                                                                                       id="property_t<?php echo $i; ?>"
                                                                                       class="input-small required"
                                                                                       type="text"
                                                                                       value="<?php echo get_post_meta($product_id, 'property_t' . $i, true); ?>">
                                                                            </div>
                                                                        </div> <!-- disabled by teo -->
                                                                        <!-- /disabled by teo --> <?php } elseif ($key == 'g') { ?>
                                                                        <div class="pull-left extra-column">
                                                            <span
                                                              class="extra-column-label">G-Post<?php echo $i; ?></span>:
                                                                            <br>
                                                                            <div class="input-group">

                                                                                <input name="property_g<?php echo $i; ?>"
                                                                                       id="property_g<?php echo $i; ?>"
                                                                                       class="input-small required"
                                                                                       type="text"
                                                                                       value="<?php echo get_post_meta($product_id, 'property_g' . $i, true); ?>">
                                                                            </div>
                                                                        </div> <!-- disabled by teo -->
                                                                        <!-- /disabled by teo --> <?php } elseif ($key == 'c') { ?>
                                                                        <div class="pull-left extra-column">
                                                            <span class="extra-column-label">C-Post <?php echo $i; ?>
                                                            </span>:
                                                                            <br>
                                                                            <div class="input-group">

                                                                                <input name="property_c<?php echo $i; ?>"
                                                                                       id="property_c<?php echo $i; ?>"
                                                                                       class="input-small required"
                                                                                       type="text"
                                                                                       value="<?php echo get_post_meta($product_id, 'property_c' . $i, true); ?>">
                                                                            </div>
                                                                        </div> <?php }
																}
															}
														} ?>
                                                    </div>
                                                </div>
                                                <div class="row extra-columns-buildout-row">
                                                    <div class="col-sm-12">
														<?php if ($nr_code_prod) {
															foreach ($nr_code_prod as $key => $val) {
																for ($i = 1; $i < $val + 1; $i++) {
																	if ($key == 'b') { ?><?php if ($i == 1) { ?>
                                                                        <div class="pull-left extra-column-buildout <?php if ($material == '188') {
																			echo 'b-angle-select-type';
																		} ?>">
                                                            <span class="extra-column-label">B-Post
                                                                Type<?php echo $i; ?></span>
                                                                            <select id="buildout-select"
                                                                                    name="bay-post-type">
																				<?php if ($bay_post_type == 'normal') { ?>
                                                                                    <option value="normal" selected>
                                                                                        Normal
                                                                                    </option>
                                                                                    <option value="flexible">Flexible
                                                                                    </option>
																				<?php } elseif ($bay_post_type == 'flexible') { ?>
                                                                                    <option value="normal">Normal
                                                                                    </option>
                                                                                    <option value="flexible" selected>
                                                                                        Flexible
                                                                                    </option>
																				<?php } ?>
                                                                            </select>
                                                                            <br>
                                                                        </div>
                                                                        <div class="pull-left extra-column-buildout property_b_buildout1"
																		  <?php if ($bay_post_type == 'flexible') { ?>
                                                                              style="display: none;" <?php } ?>>
                                                                            <span class="extra-column-label">B-Post Buildout </span>:
                                                                            <br>
                                                                            <div class="input-group">

                                                                                <input name="property_b_buildout<?php echo $i; ?>"
                                                                                       id="property_b_buildout<?php echo $i; ?>"
                                                                                       class="input-small"
                                                                                       type="checkbox" value="yes"
																				  <?php if (get_post_meta($product_id, 'property_b_buildout' . $i, true) == 'yes') {
																					  echo 'checked';
																				  } ?>>
                                                                            </div>
                                                                        </div>
																	<?php } ?><?php } elseif ($key == 't') { ?>

																		<?php if ($i == 1) { ?>
                                                                            <div class="pull-left extra-column-buildout <?php if ($material == '188') {
																				echo 't-angle-select-type';
																			} ?>">
                                                                                <span class="extra-column-label">T-Post Style :</span><br>
                                                                                <select id="buildout-select"
                                                                                        name="t-post-type">
																					<?php if ($t_post_type == 'normal') { ?>
                                                                                        <option value="normal" selected>
                                                                                            Normal
                                                                                        </option>
                                                                                        <option value="adjustable">
                                                                                            Adjustable
                                                                                        </option>
																					<?php } elseif ($t_post_type == 'adjustable') { ?>
                                                                                        <option value="normal">Normal
                                                                                        </option>
                                                                                        <option value="adjustable"
                                                                                                selected>Adjustable
                                                                                        </option>
																					<?php } else { ?>
                                                                                        <option value="normal" selected>Normal
                                                                                        </option>
                                                                                        <option value="adjustable">Adjustable
                                                                                        </option>
																					<?php } ?>
                                                                                </select>
                                                                                <br>
                                                                            </div>
                                                                            <div class="pull-left extra-column">
                                                                                <span class="extra-column-label">T-Post Buildout</span>:
                                                                                <br>
                                                                                <div class="input-group">

                                                                                    <input name="property_t_buildout<?php echo $i; ?>"
                                                                                           id="property_t_buildout<?php echo $i; ?>"
                                                                                           class="input-small"
                                                                                           type="checkbox" value="yes"
																					  <?php if (get_post_meta($product_id, 'property_t_buildout' . $i, true) == 'yes') {
																						  echo 'checked';
																					  } ?>>
                                                                                </div>
                                                                            </div>
																		<?php } ?><?php } elseif ($key == 'g') { ?><?php if ($i == 1) { ?>
                                                                        <div class="pull-left extra-column">
                                                                            <span class="extra-column-label">G-Post Buildout</span>:
                                                                            <br>
                                                                            <div class="input-group">

                                                                                <input name="property_g_buildout<?php echo $i; ?>"
                                                                                       id="property_g_buildout<?php echo $i; ?>"
                                                                                       class="input-small"
                                                                                       type="checkbox" value="yes"
																				  <?php if (get_post_meta($product_id, 'property_g_buildout' . $i, true) == 'yes') {
																					  echo 'checked';
																				  } ?>>
                                                                            </div>
                                                                        </div>
																	<?php } ?><?php } elseif ($key == 'c') { ?><?php if ($i == 1) { ?>
                                                                        <div class="pull-left extra-column">
                                                                            <span class="extra-column-label">C-Post Buildout</span>:
                                                                            <br>
                                                                            <div class="input-group">

                                                                                <input name="property_c_buildout<?php echo $i; ?>"
                                                                                       id="property_c_buildout<?php echo $i; ?>"
                                                                                       class="input-small"
                                                                                       type="checkbox" value="yes"
																				  <?php if (get_post_meta($product_id, 'property_c_buildout' . $i, true) == 'yes') {
																					  echo 'checked';
																				  } ?>>
                                                                            </div>
                                                                        </div>
																	<?php } ?><?php }
																}
															}
														} ?>
                                                    </div>
                                                </div>

												<?php
												/**
												 * verify if layout contain T to show t-post
												 */
												$containT = str_contains(get_post_meta($product_id, 'property_layoutcode', true), 'T');

												?>
                                                <div class="row">
                                                    <div class="col-sm-3 tpost-type"
                                                         style="<?php if ($containT) {
														     echo 'display: block';
													     } else {
														     echo 'display: none';
													     } ?>"> T-Post Type:
                                                        <br/>
                                                    </div>
                                                    <div class="tpost-type" style="<?php
													if (!empty($material) && $material == 187 && $containT) {
														echo 'display: block;';
													} else {
														echo 'display: none;';
													}
													?>">
                                                        <div class="col-sm-12 tpost-img type-img-earth" style="<?php
														if ($material == 187 && !empty($containT)) {
															$t_earth = true;
															echo 'display: block;';
														} else {
															$t_earth = false;
															echo 'display: none;';
														}
														?>">
															<?php echo $property_tposttype; ?>
                                                            <label>
                                                                <br/> <b>A7001</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="A7001 - Earth Standard T-Post"
                                                                       value="442"
																  <?php if ($property_tposttype == '442' && $t_earth == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/A7001.png"/>
                                                            </label>
                                                            <!-- <img src="/wp-content/plugins/shutter-module/imgs/T-PostTypes.png" /> -->
                                                        </div>
                                                    </div>
                                                    <div class="tpost-type" style="<?php
													$material = get_post_meta($product_id, 'property_material', true);
													if ($material == 188 && $containT) {
														echo 'display: block;';
													} else {
														echo 'display: none;';
													}
													?>">
                                                        <div class="col-sm-12 tpost-img type-img-ecowood"
                                                             style="<?php
														     $material = get_post_meta($product_id, 'property_material', true);
														     if (!empty($material) && $material == 188 && $containT) {
															     $t_eco = true;
															     echo 'display: block;';
														     } else {
															     $t_eco = false;
															     echo 'display: none;';
														     }
														     ?>">
                                                            <label>PVC
                                                                <br/> <b>P7030</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8" data-title="P7030"
                                                                       value="444"
																  <?php if ($property_tposttype == '444' && $t_eco == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7030.png"/>
                                                            </label>
                                                            <label>PVC
                                                                <br/> <b>P7032</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7032 - Standard T-Post" value="437"
																  <?php if ($property_tposttype == '437' && $t_eco == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7032.png"/>
                                                            </label>
                                                            <label>PVC
                                                                <br/> <b>P7201</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7201 - T-Post with insert"
                                                                       value="439"
																  <?php if ($property_tposttype == '439' && $t_eco == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7201.png"/>
                                                            </label>
                                                            <!-- <img src="/wp-content/plugins/shutter-module/imgs/T-PostTypes.png" /> -->
                                                        </div>
                                                    </div>
                                                    <div class="tpost-type" style="<?php
													$material = get_post_meta($product_id, 'property_material', true);
													if (!empty($material) && $material == 139 && $containT) {
														echo 'display: block;';
													} else {
														echo 'display: none;';
													}
													?>">
                                                        <div class="col-sm-12 tpost-img type-img-basswoodPlus"
                                                             style="<?php
														     $material = get_post_meta($product_id, 'property_material', true);
														     if (!empty($material) && $material == 139) {
															     $t_sup = true;
															     echo 'display: block;';
														     } else {
															     $t_sup = false;
															     echo 'display: none;';
														     }
														     ?>">
                                                            <label>
                                                                Basswood
                                                                <br/> <b>7001</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="7001 - Basswood Standard T-Post"
                                                                       value="438"
																  <?php if ($property_tposttype == '438' && $t_sup == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/7001.png"/>
                                                            </label>
                                                            <label>
                                                                Basswood
                                                                <br/> <b>7011</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="7011 - Large T-Post" value="441"
																  <?php if ($property_tposttype == '441' && $t_sup == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/7011.png"/>
                                                            </label>
                                                            <label>
                                                                Basswood
                                                                <br/> <b>7032</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8" data-title="7032"
                                                                       value="443"
																  <?php if ($property_tposttype == '443' && $t_sup == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/7032.png"/>
                                                            </label>

                                                            <label>
                                                                Basswood
                                                                <br/> <b>7201</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="7201 - T-Post with insert"
                                                                       value="439"
																  <?php if ($property_tposttype == '439' && $t_sup == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/7201.png"/>
                                                            </label>
                                                            <!-- <img src="/wp-content/plugins/shutter-module/imgs/T-PostTypes.png" /> -->
                                                        </div>
                                                    </div>
                                                    <div class="tpost-type" style="<?php
													$material = get_post_meta($product_id, 'property_material', true);
													if (!empty($material) && $material == 147 && $containT) {
														echo 'display: block;';
													} else {
														echo 'display: none;';
													}
													?>">
                                                        <div class="col-sm-12 tpost-img type-img-basswood"
                                                             style="<?php
														     $material = get_post_meta($product_id, 'property_material', true);
														     if (!empty($material) && $material == 147) {
															     $t_sup = true;
															     echo 'display: block;';
														     } else {
															     $t_sup = false;
															     echo 'display: none;';
														     }
														     ?>">
                                                            <label>
                                                                Basswood
                                                                <br/> <b>7001</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="7001 - Basswood Standard T-Post"
                                                                       value="438"
																  <?php if ($property_tposttype == '438' && $t_sup == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/7001.png"/>
                                                            </label>
                                                            <label>
                                                                Basswood
                                                                <br/> <b>7011</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="7011 - Large T-Post" value="441"
																  <?php if ($property_tposttype == '441' && $t_sup == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/7011.png"/>
                                                            </label>
                                                            <label>
                                                                Basswood
                                                                <br/> <b>7032</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8" data-title="7032"
                                                                       value="443"
																  <?php if ($property_tposttype == '443' && $t_sup == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/7032.png"/>
                                                            </label>

                                                            <label>
                                                                Basswood
                                                                <br/> <b>7201</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="7201 - T-Post with insert"
                                                                       value="439"
																  <?php if ($property_tposttype == '439' && $t_sup == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/7201.png"/>
                                                            </label>
                                                            <!-- <img src="/wp-content/plugins/shutter-module/imgs/T-PostTypes.png" /> -->
                                                        </div>
                                                    </div>
                                                    <div class="tpost-type" style="<?php
													$material = get_post_meta($product_id, 'property_material', true);
													if (!empty($material) && $material == 138 && $containT) {
														echo 'display: block;';
													} else {
														echo 'display: none;';
													}
													?>">
                                                        <div class="col-sm-12 tpost-img type-img-biowood"
                                                             style="<?php
														     $material = get_post_meta($product_id, 'property_material', true);
														     if (!empty($material) && $material == 6) {
															     $t_bio = true;
															     echo 'display: block;';
														     } else {
															     $t_bio = false;
															     echo 'display: none;';
														     }
														     ?>">
                                                            <!-- here was basswood t-posts -->
                                                            <label style="display:block;visibility: visible;">PVC
                                                                <br/> <b>P7030</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8" data-title="P7030"
                                                                       value="444"
																  <?php if ($property_tposttype == '444' && $t_bio == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7030.png"/>
                                                            </label>
                                                            <label>PVC
                                                                <br/> <b>P7032</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7032 - Standard T-Post" value="437"
																  <?php if ($property_tposttype == '437' && $t_bio == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7032.png"/>
                                                            </label>
                                                            <label>PVC
                                                                <br/> <b>P7201</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7201 - T-Post with insert"
                                                                       value="439"
																  <?php if ($property_tposttype == '439' && $t_bio == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7201.png"/>
                                                            </label>
                                                        </div>
                                                        <div class="col-sm-12 tpost-img type-img-biowoodPlus"
                                                             style="<?php
														     $material = get_post_meta($product_id, 'property_material', true);
														     if (!empty($material) && $material == 138) {
															     $t_bio = true;
															     echo 'display: block;';
														     } else {
															     $t_bio = false;
															     echo 'display: none;';
														     }
														     ?>">
                                                            <label>
                                                                Basswood
                                                                <br/> <b>7001</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="7001 - Basswood Standard T-Post"
                                                                       value="438"
																  <?php if ($property_tposttype == '438' && $t_bio == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/7001.png"/>
                                                            </label>
                                                            <label>
                                                                Basswood
                                                                <br/> <b>7011</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="7011 - Large T-Post" value="441"
																  <?php if ($property_tposttype == '441' && $t_sup == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/7011.png"/>
                                                            </label>
                                                            <label>
                                                                Basswood
                                                                <br/> <b>7032</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8" data-title="7032"
                                                                       value="443"
																  <?php if ($property_tposttype == '443' && $t_sup == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/7032.png"/>
                                                            </label>

                                                            <label>
                                                                Basswood
                                                                <br/> <b>7201</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="7201 - T-Post with insert"
                                                                       value="439"
																  <?php if ($property_tposttype == '439' && $t_sup == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/7201.png"/>
                                                            </label>
                                                            <label style="display:block;visibility: visible;">PVC
                                                                <br/> <b>P7030</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8" data-title="P7030"
                                                                       value="444"
																  <?php if ($property_tposttype == '444' && $t_bio == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7030.png"/>
                                                            </label>
                                                            <label>PVC
                                                                <br/> <b>P7032</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7032 - Standard T-Post" value="437"
																  <?php if ($property_tposttype == '437' && $t_bio == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7032.png"/>
                                                            </label>
                                                            <label>PVC
                                                                <br/> <b>P7201</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7201 - T-Post with insert"
                                                                       value="439"
																  <?php if ($property_tposttype == '439' && $t_bio == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7201.png"/>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="tpost-type" style="<?php
													$material = get_post_meta($product_id, 'property_material', true);
													if (!empty($material) && $material == 137 && $containT) {
														echo 'display: block;';
													} else {
														echo 'display: none;';
													}
													?>">
                                                        <div class="col-sm-12 tpost-img type-img-ecowoodPlus" style="<?php
														$material = get_post_meta($product_id, 'property_material', true);
														if (!empty($material) && $material == 137 && $containT) {
															$t_grn = true;
															echo 'display: block;';
														} else {
															$t_grn = false;
															echo 'display: none;';
														}
														?>">
                                                            <label>PVC
                                                                <br/> <b>P7030</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8" data-title="P7030"
                                                                       value="444"
																  <?php if ($property_tposttype == '444' && $t_grn == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7030.png"/>
                                                            </label>
                                                            <label>PVC
                                                                <br/> <b>P7032</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7032 - Standard T-Post" value="437"
																  <?php if ($property_tposttype == '437' && $t_grn == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7032.png"/>
                                                            </label>
                                                            <label>PVC
                                                                <br/> <b>P7201</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7201 - T-Post with insert"
                                                                       value="439"
																  <?php if ($property_tposttype == '439' && $t_grn == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7201.png"/>
                                                            </label>
                                                            <!-- <img src="/wp-content/plugins/shutter-module/imgs/T-PostTypes.png" /> -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:1em;">
                                                    <div class="col-sm-4" id="spare-louvres">
                                                        <div>
                                                            <label>
																<?php
																if (!empty(get_user_meta($user_id, 'Spare_Louvres', true)) || (get_user_meta($user_id, 'Spare_Louvres', true) > 0)) {
																	$adaos_spare = get_user_meta($user_id, 'Spare_Louvres', true);
																} else {
																	$adaos_spare = get_post_meta(1, 'Spare_Louvres', true);
																}

																$property_sparelouvres = get_post_meta($product_id, 'property_sparelouvres', true) ?>
                                                                <select id="property_sparelouvres"
                                                                        name="property_sparelouvres">
                                                                    <option value="No" <?php if ($property_sparelouvres == 'No') {
																		echo 'selected';
																	} ?>>No
                                                                    </option>
                                                                    <option value="Yes" <?php if ($property_sparelouvres == 'Yes') {
																		echo 'selected';
																	} ?>>Yes
                                                                    </option>
                                                                </select>
                                                                <!-- <input class="ace" id="property_sparelouvres" name="property_sparelouvres" type="checkbox" <?php if ($property_sparelouvres == "Yes") {
																	echo 'checked';
																} ?> value="Yes"     /> -->
                                                                <span class="lbl"> Include 2 x Spare Louvres (£<?php echo $adaos_spare; ?> +
                                                                    VAT)</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:1em;">
                                                    <div class="col-sm-4" id="ring-pull" style="display:none;">
                                                        <div>
                                                            <label>
																<?php $property_ringpull = get_post_meta($product_id, 'property_ringpull', true) ?>
                                                                <select id="property_ringpull" name="property_ringpull">
                                                                    <option value="No" <?php if ($property_ringpull == 'No') {
																		echo 'selected';
																	} ?>>Choose Option #</option>
                                                                    <option value="Stainless Steel" <?php if ($property_ringpull == 'Stainless Steel') {
																		echo 'selected';
																	} ?>>Stainless Steel</option>
                                                                    <option value="Antique Brass" <?php if ($property_ringpull == 'Antique  Brass') {
																		echo 'selected';
																	} ?>>Antique Brass</option>
                                                                    <option value="Brass" <?php if ($property_ringpull == 'Brass') {
																		echo 'selected';
																	} ?>>Brass</option>
                                                                    <option value="Black" <?php if ($property_ringpull == 'Black') {
																		echo 'selected';
																	} ?>>Black</option>
                                                                </select>
                                                                <!-- <input class="ace" id="property_ringpull" name="property_ringpull" type="checkbox" <?php if ($property_ringpull != '') {
																	echo 'checked';
																} ?> value="yes"                                     /> -->
                                                                <span class="lbl"> Ring Pull</span>
                                                            </label>
                                                        </div>
                                                        <div>
                                                            <label>
																<?php $property_ringpull_volume = get_post_meta($product_id, 'property_ringpull_volume', true) ?>
                                                                <input type="number" id="property_ringpull_volume"
                                                                       name="property_ringpull_volume"
                                                                       value="<?php echo $property_ringpull_volume; ?>">
                                                                <br>
                                                                <span class="lbl"> How Many? (please specify position on
                                                                    the comment field)</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:1em;">
                                                    <div id="locks" style="display:block">
                                                        <div class="col-sm-12">
                                                            <label>
			                                                    <?php
			                                                    // Verificăm dacă $product_id este setat
			                                                    if (isset($product_id) && !empty($product_id)) {
				                                                    $property_locks = get_post_meta($product_id, 'property_locks', true);
			                                                    } else {
				                                                    $property_locks = '';
			                                                    }

			                                                    // Corectăm valoarea CSS
			                                                    $showByLock = $property_locks == 'Yes' ? 'block' : 'none';
			                                                    ?>
                                                                <select id="property_locks" name="property_locks">
                                                                    <option value="No" <?php selected($property_locks, 'No'); ?>>No</option>
                                                                    <option value="Yes" <?php selected($property_locks, 'Yes'); ?>>Yes</option>
                                                                </select>
                                                                <span class="lbl">Top & Bottom Locks</span>
                                                            </label>
                                                        </div>

                                                        <!--                            <div class="col-sm-4" id="section_locks_volume"-->
                                                        <!--                                 style="display:--><?php //echo $showByLock; ?><!--">-->
                                                        <!--                              <label>-->
                                                        <!--																--><?php //$property_locks_volume = get_post_meta($product_id, 'property_locks_volume', true); ?>
                                                        <!--                                <input type="number" id="property_locks_volume"-->
                                                        <!--                                       name="property_locks_volume"-->
                                                        <!--                                       value="--><?php //echo $property_locks_volume; ?><!--">-->
                                                        <!--                                <span class="lbl"> How many total locks</span>-->
                                                        <!--                              </label>-->
                                                        <!--                            </div>-->
                                                        <!--                            <div class="col-sm-4" id="section_lock_position"-->
                                                        <!--                                 style="display:--><?php //echo $showByLock; ?><!--">-->
                                                        <!--                              <label>-->
                                                        <!--                                <select name="property_lock_position">-->
                                                        <!--																	--><?php
														//																	$property_locks_volume = get_post_meta($product_id, 'property_lock_position', true);
														//																	$select_top_bot = ($property_locks_volume == 'Top & Bottom Lock') ? 'selected' : '';
														//																	$select_Central = ($property_locks_volume == 'Central Lock') ? 'selected' : '';
														//																	?>
                                                        <!--                                  <option value="">Please Select</option>-->
                                                        <!--                                  <option value="Central Lock" --><?php //echo $select_Central; ?><!--
<!--                                    Central Lock-->
                                                        <!--                                  </option>-->
                                                        <!--                                  <option value="Top & Bottom Lock" --><?php //echo $select_top_bot; ?><!--
<!--                                    Top & Bottom Lock-->
                                                        <!--                                  </option>-->
                                                        <!--                                </select>-->
                                                        <!--                                <span class="lbl"> Position Locks</span>-->
                                                        <!--                              </label>-->
                                                        <!--                            </div>-->


                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:1em;">
                                                    <div class="col-sm-4" id="central_lock_position" style="display: none;">
                                                        <label>
															<?php
															$property_central_lock = get_post_meta($product_id, 'property_central_lock', true);
															?>

                                                            <select id="property_central_lock" name="property_central_lock">
                                                                <option value="No" <?php if ($property_central_lock == 'No') {
																	echo 'selected';
																} ?>>No
                                                                </option>
                                                                <option value="Yes" <?php if ($property_central_lock == 'Yes') {
																	echo 'selected';
																} ?>>Yes
                                                                </option>
                                                            </select>
                                                            <span class="lbl"> Central Lock</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <hr/>
                                                        <button class="btn btn-info show-next-panel"
                                                                next-panel="#headingLayout"> Next
                                                            <i class="fa fa-chevron-right"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default inactive">
                                        <div class="panel-heading" role="tab" id="headingLayout">
                                            <h4 class="panel-title">
                                                <a role="button" class="" data-toggle="collapse"
                                                   data-parent="#accordion" href="#collapseLayout" aria-expanded="true"
                                                   aria-controls="collapseLayout">
                                                    <strong>Confirm Drawing</strong>
                                                    <span type="button" class="icon--edit edit" tabindex="2">
                                                        <i class="fas fa-pen fa fa-pencil"></i> Edit</span>
                                                    <span type="button" class="js-next-step next-step"
                                                          tabindex="2">Final step</span></a>
                                            </h4>
                                        </div>
                                        <div id="collapseLayout" class="panel-collapse collapse drawing-panel"
                                             role="tabpanel" aria-labelledby="headingLayout">
                                            <div class="panel-body">
                                                <!-- drawing -->
                                                <div class="row">
                                                    <div class="col-lg-8 col-md-11 col-sm-12">
                                                        <div style="display: none">
                                                            <textarea id="drawingConfig"
                                                                      style="width:300px;height:150px;"></textarea>
                                                            <input id="splitHeight" type="number" name="quantity_split"
                                                                   min="0" max="5000" step="10">
                                                            <button id="runButton">Run</button>
                                                            <textarea id="drawingConfigScaled"
                                                                      style="width:300px;height:150px;"></textarea>
                                                            <button id="runButtonScaled">Run</button>
                                                        </div>
                                                        <div id="canvas_container1" class="canvas_container"
                                                             style="min-height: 500px;border: 1px solid #aaa;background-image: url('/wp-content/plugins/shutter-module/imgs/drawing_graph.png');">
                                                        </div>
                                                        <br/>
                                                        <button class="btn btn-info print-drawing" style="z-index: 10;">
                                                            <i class="fa fa-print"></i> Print
                                                        </button>
                                                        <textarea id="shutter_svg" name="shutter_svg"
                                                                  style="display:none"></textarea>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6"> Comments:
                                                        <br/>
														<?php
														$comments_customer = get_post_meta($product_id, 'comments_customer', true);
														//                                                        print_r($comments_customer);
														if (!empty($product_id)) {
															?>
                                                            <textarea id="comments_customer" data-edit="1234" name="comments_customer"
                                                                      rows="5"
                                                                      style="width: 100%"><?php
																echo $comments_customer; ?></textarea>
															<?php
														} else {
															echo ' <textarea id="comments_customer" name="comments_customer" rows="5" style="width: 100%"></textarea>';
														}
														?>
                                                        <hr/>
                                                        <div id="nowarranty" style="display:none"> I accept that there
                                                            is no warranty for this item
                                                            <br/>
                                                            <input id="property_nowarranty" name="property_nowarranty"
                                                                   type="checkbox" value="Yes"/>
                                                        </div>
                                                        <div class="quantity">
                                                            Number of this:
															<?php
															if (!empty($_GET['id'])) {
																?>
                                                                <!--                                                                    <input id="quantity" class="input-text qty text" min="1" max="" name="quantity" value="--><?php //echo $quant
																?>
                                                                <!--" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric" aria-labelledby="" type="number">-->
                                                                <input id="quantity" class="input-text qty text" min="1"
                                                                       max="" name="quantity"
                                                                       value="<?php echo get_post_meta($product_id, 'quantity', true) ?>"
                                                                       title="Qty" size="4" pattern="[0-9]*"
                                                                       inputmode="numeric" aria-labelledby=""
                                                                       type="number">

																<?php
															} else {
																?>
                                                                <input id="quantity" class="input-text qty text" min="1"
                                                                       max="" name="quantity" value="1" title="Qty"
                                                                       size="4"
                                                                       pattern="[0-9]*" inputmode="numeric"
                                                                       aria-labelledby=""
                                                                       type="number">
																<?php
															}
															?>
                                                        </div>
														<?php
														if (!empty($_GET['id']) && !empty($_GET['cust_id'] || $edit_customer)) {
															?>
                                                            <input type="hidden" name="order_item_id"
                                                                   value="<?php echo $item_id; ?>">
                                                            <input id="page_title" name="page_title" type="hidden"
                                                                   value="<?php echo get_the_title(); ?>"/>
                                                            <input class="" id="panels_left_right"
                                                                   name="panels_left_right"
                                                                   type="hidden"
                                                                   value="<?php echo strtoupper(get_post_meta($product_id, 'panels_left_right', true)); ?>"/>
                                                            <button class="btn btn-primary update-btn-admin">Update
                                                                Product
                                                                <i class="fa fa-chevron-right"></i>
                                                            </button>
                                                            <img src="/wp-content/uploads/2018/06/Spinner-1s-200px.gif"
                                                                 alt="" class="spinner" style="display:none;"/>
															<?php
														} elseif (!empty($_GET['id']) && empty($_GET['cust_id'])) { ?>
                                                            <input id="page_title" name="page_title" type="hidden"
                                                                   value="<?php echo get_the_title(); ?>"/>
                                                            <input class="" id="panels_left_right"
                                                                   name="panels_left_right"
                                                                   type="hidden"
                                                                   value="<?php echo strtoupper(get_post_meta($product_id, 'panels_left_right', true)); ?>"/>
                                                            <button class="btn btn-primary update-btn">Update Product
                                                                <i class="fa fa-chevron-right"></i>
                                                            </button> <?php
														} else {
															?>
                                                            <input id="page_title" name="page_title" type="hidden"
                                                                   value="<?php echo get_the_title(); ?>"/>
                                                            <input class="" id="panels_left_right"
                                                                   name="panels_left_right"
                                                                   type="hidden" value=""/>
                                                            <button type="button" class="btn btn-success" onclick="console.log('🔵 ONCLICK DIRECT PE BUTON');"> Add to Quote
                                                                <i class="fa fa-chevron-right"></i>
                                                            </button> <?php
														}
														?>
                                                        <img src="/wp-content/uploads/2018/06/Spinner-1s-200px.gif"
                                                             alt="" class="spinner" style="display:none;"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- the following is used as a prototype in order to add new fields while adding a layout code -->
                        <div class="col-sm-2" id="extra-column" style="display: none">
                            <span class="extra-column-label">Label</span>:
                            <br/>
                            <div class="input-group">

                                <input type="text" name="property_extra_column" id="property_extra_column"
                                       class="input-small">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12"></div>
                        </div>
                        <hr/>
                        <style type="text/css">
                            .input-group-addon-container {
                                float: left
                            }

                            .input-group-addon-container .input-group-addon {
                                height: 30px;
                                border-left: 1px solid #cccccc;
                            }

                            #add-product-single-form .input-group {
                                width: 100%
                            }

                            #choose-style label > input {
                                /* HIDE RADIO */
                                /* display: none; */
                            }

                            #choose-style label {
                                display: block;
                                float: left;
                                width: 100px;
                                text-align: center;
                                border: 2px solid gray;
                                margin-right: 1em;
                                font-size: 10px;
                                font-weight: bold;
                                color: black;
                                background-color: #ced3e4;
                                border-radius: 7px;
                                height: 170px;
                            }

                            #choose-style label > input + img {
                                /* IMAGE STYLES */
                                cursor: pointer;
                                border: 4px solid transparent;
                            }

                            #choose-style label > input:checked + img {
                                /* (CHECKED) IMAGE STYLES */
                                border: 4px solid #438EB9;
                            }

                            #choose-frametype label > input,
                            #choose-stiletype label > input {
                                /* HIDE RADIO */
                                /* display: none; */
                            }

                            #choose-frametype label,
                            #choose-stiletype label {
                                display: block;
                                float: left;
                                width: 100px;
                                text-align: center;
                                border: 2px solid gray;
                                background-color: #ced3e4;
                                border-radius: 7px;
                                margin-right: 1em;
                                font-size: 10px;
                                font-weight: bold;
                                color: black;
                            }

                            #choose-frametype label > input + img,
                            #choose-stiletype label > input + img {
                                /* IMAGE STYLES */
                                cursor: pointer;
                                border: 4px solid transparent;
                            }

                            #choose-frametype label > input:checked + img,
                            #choose-stiletype label > input:checked + img {
                                /* (CHECKED) IMAGE STYLES */
                                border: 4px solid #438EB9;
                            }

                            .tpost-type label > input + img {
                                /* IMAGE STYLES */
                                cursor: pointer;
                                border: 4px solid transparent;
                            }

                            .tpost-type label > input:checked + img {
                                /* (CHECKED) IMAGE STYLES */
                                border: 4px solid #438EB9;
                            }

                            .extra-column input {
                                width: 4em;
                            }

                            .extra-column {
                                padding-right: 0.5em;
                                display: table;
                            }

                            /* .extra-columns-row .pull-left.extra-column {                                 display: inline-block;                             } */
                            div.error-field {
                                display: block;
                            }

                            .error-field,
                            input.error-field {
                                border: 2px solid red;
                            }

                            .error-text {
                                color: red;
                                font-weight: bold;
                            }

                            .select2-result-label img,
                            .select2-container img {
                                display: inline;
                            }

                            .select2-container#s2id_property_style .select2-choice,
                            #s2id_property_style .select2-result {
                                height: 57px;
                            }

                            .extra-column-label {
                                font-size: 0.8em
                            }

                            input.layout-code {
                                font-size: 0.8em;
                            }

                            input.property-select {
                                /* display: block !important; */
                            }

                            .collapse {
                                /* display: block; */
                            }

                            .btn.btn-info.show-next-panel {
                                /* display: none; */
                            }

                            .modal {
                                top: 20%;
                            }

                            #property_layoutcode {
                                text-transform: uppercase;
                            }

                            div.select2-container.b-angle-select {
                                display: none !important;
                            }

                            b {
                                font-weight: 900 !important;
                            }
                        </style>
                        <div class="show-prod-info"></div>
                    </div>
                </form>
            </div>
        </div>
    </div> <!-- Modal Start -->
    <div id="errorModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="errorModalLabel">Configuration errors</h3>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button class="btn btn-close" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End -->

    <!-- Modal Start Draw -->
    <div id="drawModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="drawModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title inline"><b>Draw</b></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">

                    <div class="col-sm-12" id="shape-section-draw" style="display: block">
                        <br/>

                        <div class="fs-container col-xs-8">
                            <div id="lc" style='height: 700px;'></div>
                        </div>
                        <div class="images col-xs-4">
                            <p>Select Type: </p>
                            <button class="btn btn-small clear-shape" style="display: none;">Clear Shape</button>
                            <div class="arched shapes" style="display:none;">

                                <label style="">
                                    <br> Arched 1
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="shaped"
                                           data-title="Arched Shaped 1" value="36">
                                    <img src="/wp-content/plugins/shutter-module/imgs/Arched/Arched1.png">
                                </label>

                                <label style="">
                                    <br> Arched 2
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="shaped"
                                           data-title="Arched Shaped 2" value="36">
                                    <img src="/wp-content/plugins/shutter-module/imgs/Arched/Arched2.png">
                                </label>

                                <label style="">
                                    <br> Arched 3
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="shaped"
                                           data-title="Arched Shaped 3" value="36">
                                    <img src="/wp-content/plugins/shutter-module/imgs/Arched/Arched3.png">
                                </label>

                                <label style="">
                                    <br> CircleR
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="tracked"
                                           data-title="Tracked By-Pass" value="37">
                                    <img src="/wp-content/plugins/shutter-module/imgs/Arched/CircleR.png">
                                </label>

                                <label style="">
                                    <br> CircleS
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="shaped"
                                           data-title="Special Shaped" value="33">
                                    <img src="/wp-content/plugins/shutter-module/imgs/Arched/CircleS.png">
                                </label>

                                <label>
                                    <br> SemiR
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-title="No Shaped" value="33">
                                    <img src="/wp-content/plugins/shutter-module/imgs/Arched/SemiR.png">
                                </label>

                                <label>
                                    <br> SemiS
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-title="No Shaped" value="33">
                                    <img src="/wp-content/plugins/shutter-module/imgs/Arched/SemiS.png">
                                </label>

                            </div>
                            <div class="special shapes" style="display:none;">

                                <label style="">
                                    <br> Hexa
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="shaped"
                                           data-title="Special Shaped" value="33">
                                    <img src="/wp-content/plugins/shutter-module/imgs/SpecialShapes/Hexa.png">
                                </label>

                                <label style="">
                                    <br> Octo
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="shaped"
                                           data-title="Arched Shaped" value="36">
                                    <img src="/wp-content/plugins/shutter-module/imgs/SpecialShapes/Octo.png">
                                </label>

                                <label style="">
                                    <br> Rake1
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="tracked"
                                           data-title="Tracked By-Pass" value="37">
                                    <img src="/wp-content/plugins/shutter-module/imgs/SpecialShapes/Rake1.png">
                                </label>

                                <label style="">
                                    <br> Rake2
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="tracked"
                                           data-title="Tracked By-Pass" value="37">
                                    <img src="/wp-content/plugins/shutter-module/imgs/SpecialShapes/Rake2.png">
                                </label>

                            </div>
                            <div class="french shapes" style="display:none;">

                                <label style="">
                                    <br> FrDoorCurvedLeft
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="tracked"
                                           data-title="Tracked By-Pass" value="37">
                                    <img src="/wp-content/plugins/shutter-module/imgs/FrDoors/FrDoorCurvedLeft.png">
                                </label>

                                <label style="">
                                    <br> FrDoorCurvedRight
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="shaped"
                                           data-title="Special Shaped" value="33">
                                    <img src="/wp-content/plugins/shutter-module/imgs/FrDoors/FrDoorCurvedRight.png">
                                </label>

                                <label style="">
                                    <br> FrDoorSquareLeft
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="shaped"
                                           data-title="Special Shaped" value="33">
                                    <img src="/wp-content/plugins/shutter-module/imgs/FrDoors/FrDoorSquareLeft.png">
                                </label>

                                <label style="">
                                    <br> FrDoorSquareRight
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="shaped"
                                           data-title="Special Shaped" value="33">
                                    <img src="/wp-content/plugins/shutter-module/imgs/FrDoors/FrDoorSquareRight.png">
                                </label>

                            </div>


                            <div class="col-xs-12">
                                <br>
                                <button class="btn btn-default export-canvas" data-action="export-as-png"
                                        value="Export as PNG">Attach drawing
                                </button>
                                <img class="exported-image" src="" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-close" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End -->

    <script type="text/javascript">
        // Debug mode - setează true pentru a vedea mesajele în consolă
        var DEBUG_MODE = false;
        function debugLog() {
            if (DEBUG_MODE && console && console.log) {
                console.log.apply(console, arguments);
            }
        }

        jQuery(document).ready(function () {

            var litCanv;


            LC.defineShape('Semicircle', {
                constructor: function (args) {
                    if (args == null) {
                        args = {};
                    }
                    this.x = args.x || 0;
                    this.y = args.y || 0;
                    this.width = args.width || 0;
                    this.height = args.height || 0;
                    this.strokeWidth = args.strokeWidth || 1;
                    this.strokeColor = args.strokeColor || 'black';
                    return this.fillColor = args.fillColor || 'transparent';
                },
                getBoundingRect: function () {
                    return {
                        x: this.x - this.strokeWidth / 2,
                        y: this.y - this.strokeWidth / 2,
                        width: this.width + this.strokeWidth,
                        height: this.height + this.strokeWidth
                    };
                },
                toJSON: function () {
                    return {
                        x: this.x,
                        y: this.y,
                        width: this.width,
                        height: this.height,
                        strokeWidth: this.strokeWidth,
                        strokeColor: this.strokeColor,
                        fillColor: this.fillColor
                    };
                },
                fromJSON: function (data) {
                    return LC.createShape('Semicircle', data);
                },
                move: function (moveInfo) {
                    if (moveInfo == null) {
                        moveInfo = {};
                    }
                    this.x = this.x - moveInfo.xDiff;
                    return this.y = this.y - moveInfo.yDiff;
                },
                setUpperLeft: function (upperLeft) {
                    if (upperLeft == null) {
                        upperLeft = {};
                    }
                    this.x = upperLeft.x;
                    return this.y = upperLeft.y;
                }
            });

            /* Define canvas and SVG renderers */

            LC.defineCanvasRenderer('Semicircle', function (ctx, shape) {
                var centerX, centerY, halfHeight, halfWidth;
                ctx.save();
                halfWidth = Math.floor(shape.width / 2);
                halfHeight = Math.floor(shape.height / 2);
                centerX = shape.x + halfWidth;
                centerY = shape.y + halfHeight;
                ctx.translate(centerX, shape.y);
                ctx.scale(1, Math.abs(shape.height / shape.width));
                ctx.beginPath();
                ctx.arc(0, 0, Math.abs(halfWidth), Math.PI, 0, false);
                ctx.closePath();
                ctx.restore();
                ctx.fillStyle = shape.fillColor;
                ctx.fill();
                ctx.lineWidth = shape.strokeWidth;
                ctx.strokeStyle = shape.strokeColor;
                return ctx.stroke();
            });


            var MyTool = function (lc) { // take lc as constructor arg
                var self = this;

                return {
                    name: 'MyTool',
                    iconName: 'semicircle',
                    strokeWidth: lc.opts.defaultStrokeWidth,
                    optionsStyle: 'stroke-width',

                    didBecomeActive: function (lc) {
                        var onPointerDown = function (pt) {
                            self.currentShape = LC.createShape('Semicircle', {
                                x: pt.x,
                                y: pt.y,
                                strokeWidth: 10,
                                strokeColor: 'rgba(0, 0, 31, 1)',
                                fillColor: 'transparent'
                            });
                            lc.setShapesInProgress([self.currentShape]);
                            lc.repaintLayer('main');
                        };

                        var onPointerDrag = function (pt) {
                            self.currentShape.width = pt.x - self.currentShape.x;
                            self.currentShape.height = pt.y - self.currentShape.y;
                            lc.setShapesInProgress([self.currentShape]);
                            lc.repaintLayer('main');
                        };

                        var onPointerUp = function (pt) {
                            self.currentShape.width = pt.x - self.currentShape.x;
                            self.currentShape.height = pt.y - self.currentShape.y;
                            lc.setShapesInProgress([]);
                            lc.saveShape(self.currentShape);
                        };

                        var onPointerMove = function (pt) {
                            debugLog("Mouse moved to", pt);
                        };

                        // lc.on() returns a function that unsubscribes us. capture it.
                        self.unsubscribeFuncs = [
                            lc.on('lc-pointerdown', onPointerDown),
                            lc.on('lc-pointerdrag', onPointerDrag),
                            lc.on('lc-pointerup', onPointerUp),
                            lc.on('lc-pointermove', onPointerMove)
                        ];
                    },

                    willBecomeInactive: function (lc) {
                        // call all the unsubscribe functions
                        self.unsubscribeFuncs.map(function (f) {
                            f()
                        });
                    }
                }

            };

            LC.defaultTools = [
                LC.tools.Line,
                LC.tools.Text,
                LC.tools.Ellipse,
                LC.tools.Rectangle,
                LC.tools.Pencil,
                LC.tools.Eraser,
                //LC.tools.Polygon,
                //LC.tools.Eyedropper
            ];


            // on click make draw pad show
            jQuery('#btnDrawModal').click(function () {
                // on select shape show clear shape button

                // config draw
                var backgroundImage = new Image();
                backgroundImage.src = '';

                debugLog('draw modal open');
                // config draw
                // var backgroundImage = new Image()
                // backgroundImage.src = '';

                setTimeout(function () {

                    var lcsec = LC.init(document.getElementById("lc"), {
                        imageURLPrefix: '/wp-content/themes/storefront-child/canvas-demo/_assets/lc-images',
                        primaryColor: '#000',
                        secondaryColor: 'transparent',
                        backgroundColor: '#fff',
                        toolbarPosition: 'bottom',
                        defaultStrokeWidth: 2,
                        strokeWidths: [2],
                        tools: LC.defaultTools.concat([MyTool]),
                        backgroundShapes: [
                            LC.createShape(
                                'Image', {
                                    x: 250,
                                    y: 75,
                                    image: backgroundImage,
                                    scale: 1
                                }),
                        ],
                        imageSize: {
                            width: null,
                            height: null
                        }
                    });

                    litCanv = lcsec;

                }, 500);

            });

            // on click make draw pad show
            jQuery('#shape-section-draw .images > .shapes > label').click(function () {
                // on select shape show clear shape button
                jQuery('#shape-section-draw .images .clear-shape').show();

                var itemImage = jQuery(this).find('img').attr('src');
                debugLog(this);
                debugLog(itemImage);

                // config draw
                var backgroundImage = new Image();
                // backgroundImage.src = 'http://local-matrix-demo/wp-content/plugins/shutter-module/imgs/drawing_graph.png';
                backgroundImage.src = itemImage;

                var lc = LC.init(document.getElementById("lc"), {
                    imageURLPrefix: '/wp-content/themes/storefront-child/canvas-demo/_assets/lc-images',
                    primaryColor: '#000',
                    secondaryColor: 'transparent',
                    backgroundColor: '#fff',
                    toolbarPosition: 'bottom',
                    defaultStrokeWidth: 2,
                    strokeWidths: [2],
                    tools: LC.defaultTools.concat([MyTool]),
                    backgroundShapes: [
                        LC.createShape(
                            'Image', {
                                x: 250,
                                y: 75,
                                image: backgroundImage,
                                scale: 1
                            }),
                    ],
                    imageSize: {
                        width: null,
                        height: null
                    }
                });

                litCanv = lc;

                // var img = new Image();
                // img.src = 'http://placekitten.com/200/300';
                // lc.saveShape(LC.createShape('EllipseDemo', {x: 100, y: 100, image: img}));


                jQuery('.lc-options.horz-toolbar > div > div:first-child').click();
                jQuery('.lc-options.horz-toolbar > div > div:nth-child(2)').click();

                jQuery('.lc-pick-tool.toolbar-button.thin-button[title="Line"]').click(function () {
                    if (!jQuery('.lc-options.horz-toolbar > div > div:first-child').hasClass(
                        'selected')) {
                        jQuery('.lc-options.horz-toolbar > div > div:first-child').click();
                    }
                    if (!jQuery('.lc-options.horz-toolbar > div > div:nth-child(2)').hasClass(
                        'selected')) {
                        jQuery('.lc-options.horz-toolbar > div > div:nth-child(2)').click();
                    }
                });

                jQuery('#shape-section-draw .images .clear-shape').click(function () {
                    jQuery('#shape-section-draw .images > .shapes > label input').prop('checked',
                        false);

                    var backgroundImage = new Image()
                    backgroundImage.src = '';

                    var lc = LC.init(document.getElementById("lc"), {
                        imageURLPrefix: '/wp-content/themes/storefront-child/canvas-demo/_assets/lc-images',
                        primaryColor: '#000',
                        secondaryColor: 'transparent',
                        backgroundColor: '#fff',
                        toolbarPosition: 'bottom',
                        defaultStrokeWidth: 2,
                        strokeWidths: [2],
                        tools: LC.defaultTools.concat([MyTool]),
                        backgroundShapes: [
                            LC.createShape(
                                'Image', {
                                    x: 250,
                                    y: 75,
                                    image: backgroundImage,
                                    scale: 1
                                }),
                        ],
                        imageSize: {
                            width: null,
                            height: null
                        }
                    });

                    litCanv = lc;

                    jQuery('.lc-options.horz-toolbar > div > div:first-child').click();
                    jQuery('.lc-options.horz-toolbar > div > div:nth-child(2)').click();

                    jQuery('.lc-pick-tool.toolbar-button.thin-button[title="Line"]').click(
                        function () {
                            if (!jQuery('.lc-options.horz-toolbar > div > div:first-child')
                                .hasClass('selected')) {
                                jQuery('.lc-options.horz-toolbar > div > div:first-child')
                                    .click();
                            }
                            if (!jQuery('.lc-options.horz-toolbar > div > div:nth-child(2)')
                                .hasClass('selected')) {
                                jQuery('.lc-options.horz-toolbar > div > div:nth-child(2)')
                                    .click();
                            }
                        });
                });

            });


            // export image
            var imageExport = new Image();
            $('.export-canvas').on('click', function () {

                imageExport.src = litCanv.getImage().toDataURL();
                debugLog(imageExport.src);
                $('.exported-image').attr('src', imageExport.src);
                var roomName = jQuery('input#property_room_other').val();

                $.ajax({
                    method: "POST",
                    url: "/wp-content/plugins/shutter-module/ajax/ajax-export-draw-shape.php",
                    data: {
                        imageSrc: imageExport.src,
                        roomName: roomName
                    }
                })
                    .done(function (data) {

                        debugLog(data);
                        var imageUrl = data;
                        $('.exported-image').attr('src', imageUrl);
                        $('input[name="attachmentDraw"]').val(imageUrl);
                        $('img#frontend-image-draw').attr('src', imageUrl);

                        alert('Image Uploaded!');
                        // alert(data);

                    });
            });

        });
    </script>
</div>
</div>