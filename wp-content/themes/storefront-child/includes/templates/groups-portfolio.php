<?php

/**
 * Obține utilizatorii după companie
 * @param string $company Numele companiei sau ID-ul
 * @return array Array de obiecte WP_User
 */
function get_user_ids_by_billing_company($company_name)
{
    // Get users with matching billing company
    $users = get_users(array(
        'meta_key' => 'billing_company',
        'meta_value' => $company_name,
        'fields' => 'ids', // Only return IDs to be more efficient
    ));

    return $users;
}


?>

<!-- Latest compiled and minified CSS -->
<!--<link rel="stylesheet"-->
<!--      href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
<!---->
<!-- Latest compiled JavaScript -->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
<!-- Use Font Awesome Free CDN modified-->
<!--<link rel="stylesheet"-->
<!--      href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">-->

<style>
    #container {
        min-width: 310px;
        max-width: 1024px;
        height: 400px;
        margin: 0 auto;
    }

    .grup-section-header {
        display: flex;
        align-items: baseline;
        gap: 12px;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid #1A6DB2;
    }

    .grup-section-header h3 {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 700;
        color: #212529;
    }

    .grup-section-subtitle {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .grup-table-card {
        background: #fff;
        border: 1px solid rgba(26, 54, 126, 0.1);
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .grup-table-card table {
        box-shadow: none;
        border: none;
        border-radius: 0;
        margin: 0;
    }

    .grup-table thead th {
        position: sticky;
        top: 0;
        background: #12447E;
        color: #fff;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        padding: 10px 12px;
        border-color: rgba(255, 255, 255, 0.15);
    }

    .grup-table tbody td {
        font-size: 0.85rem;
        padding: 8px 12px;
        color: #495057;
        vertical-align: middle;
    }

    .grup-table tbody tr:hover td {
        background-color: rgba(26, 109, 178, 0.05);
    }

    .grup-table-totals td {
        background: #f0f4f8 !important;
        font-weight: 700 !important;
        color: #12447E !important;
        font-size: 0.85rem;
        border-top: 2px solid #1A6DB2;
        padding: 10px 12px;
    }

    .grup-filter-card {
        max-width: 100%;
    }

    .grup-table-card .dataTables_wrapper {
        padding: 12px 16px 16px;
        overflow: scroll;
    }

    .grup-table-card .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 5px 10px;
        font-size: 0.85rem;
        outline: none;
        transition: border-color 0.2s;
    }

    .grup-table-card .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #1A6DB2;
        box-shadow: 0 0 0 2px rgba(26, 109, 178, 0.15);
    }

    .grup-table-card .dataTables_info {
        font-size: 0.8rem;
        color: #6c757d;
        padding: 8px 0 0;
    }

    .dt-buttons {
        margin-bottom: 10px;
    }

    .dt-buttons .dt-button,
    .dt-buttons button,
    .dt-buttons a {
        background: #f8f9fa !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 5px !important;
        padding: 6px 16px !important;
        font-size: 0.8rem !important;
        font-weight: 500 !important;
        color: #495057 !important;
        cursor: pointer;
        transition: all 0.2s;
        margin-right: 4px;
        display: inline-block;
    }

    .dt-buttons .dt-button:hover,
    .dt-buttons button:hover,
    .dt-buttons a:hover {
        background: #1A6DB2 !important;
        border-color: #1A6DB2 !important;
        color: #fff !important;
    }

    .user-message {
        border-left: 3px solid #1A6DB2;
        background: #f8f9fa;
        padding: 10px 14px;
        margin-bottom: 8px;
        border-radius: 0 6px 6px 0;
        font-size: 0.85rem;
        color: #495057;
    }

    .container {
        max-width: 86vw;
        padding: 100px 20px;
    }

    .table-responsive {
        overflow-x: scroll;
    }

    .grup-row-empty td {
        color: #adb5bd;
    }

    .grup-table td.text-end {
        font-variant-numeric: tabular-nums;
    }

</style>


<div id="primary"
    class="content-area container">
    <main id="main"
        class="site-main"
        role="main">

        <h2>Users Group</h2>

        <!-- Multi Cart Template -->
        <?php if (is_active_sidebar('multicart_widgett')) : ?>
            <div id="bmc-woocom-multisession-2"
                class="widget bmc-woocom-multisession-widget">
                <?php //dynamic_sidebar( 'multicart_widgett' ); 
                ?>
            </div>
            <!-- #primary-sidebar -->
        <?php endif;

        $user_id = get_current_user_id();
        $meta_key = 'wc_multiple_shipping_addresses';

        global $wpdb;
        $addresses = $wpdb->get_results($wpdb->prepare(
            "SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key = %s",
            $user_id,
            '_woocom_multisession'
        ));

        /*
				 * Create Group
				 * Delete Group
				 * Rename Group
				 */
        //  include_once(get_stylesheet_directory() . '/views/grup-users/grup-malipulation.php');

        /*
				 * Search Group
				 */
        ?>

        <div class="grup-section-header">
            <h3>Search Group Info</h3>
            <span class="grup-section-subtitle">Filter by group and date range</span>
        </div>

        <div class="card grup-filter-card mb-4">
            <div class="card-body">
                <h6 class="card-title text-muted mb-3">Filter</h6>
                <?php
                $month = date("m");
                $currentYear = date("Y");
                $luni = array('01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');

                // Build last 10 years in descending order
                $last10Years = [];
                for ($i = 0; $i < 10; $i++) {
                    $last10Years[] = $currentYear - $i;
                }

                // Determine current values (from POST or defaults)
                $posted_from_month = isset($_POST['luna_from']) ? $_POST['luna_from'] : $month;
                $posted_from_year  = isset($_POST['an_from']) ? $_POST['an_from'] : $currentYear;
                $posted_to_month   = isset($_POST['luna_to']) ? $_POST['luna_to'] : $month;
                $posted_to_year    = isset($_POST['an_to']) ? $_POST['an_to'] : $currentYear;
                ?>
                <form action="" method="POST">
                    <!-- Quick presets -->
                    <div class="mb-3 d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm date-preset" data-fm="<?php echo $month; ?>" data-fy="<?php echo $currentYear; ?>" data-tm="<?php echo $month; ?>" data-ty="<?php echo $currentYear; ?>">This Month</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm date-preset" data-fm="01" data-fy="<?php echo $currentYear; ?>" data-tm="03" data-ty="<?php echo $currentYear; ?>">Q1 <?php echo $currentYear; ?></button>
                        <button type="button" class="btn btn-outline-secondary btn-sm date-preset" data-fm="04" data-fy="<?php echo $currentYear; ?>" data-tm="06" data-ty="<?php echo $currentYear; ?>">Q2 <?php echo $currentYear; ?></button>
                        <button type="button" class="btn btn-outline-secondary btn-sm date-preset" data-fm="07" data-fy="<?php echo $currentYear; ?>" data-tm="09" data-ty="<?php echo $currentYear; ?>">Q3 <?php echo $currentYear; ?></button>
                        <button type="button" class="btn btn-outline-secondary btn-sm date-preset" data-fm="10" data-fy="<?php echo $currentYear; ?>" data-tm="12" data-ty="<?php echo $currentYear; ?>">Q4 <?php echo $currentYear; ?></button>
                        <button type="button" class="btn btn-outline-secondary btn-sm date-preset" data-fm="01" data-fy="<?php echo $currentYear; ?>" data-tm="12" data-ty="<?php echo $currentYear; ?>">Full Year <?php echo $currentYear; ?></button>
                        <button type="button" class="btn btn-outline-secondary btn-sm date-preset" data-fm="01" data-fy="<?php echo $currentYear - 1; ?>" data-tm="12" data-ty="<?php echo $currentYear - 1; ?>">Full Year <?php echo $currentYear - 1; ?></button>
                    </div>

                    <div class="row g-3 align-items-end">
                        <!-- Group selector -->
                        <div class="col-sm-6 col-lg-3">
                            <label for="q_status_order_id_eq" class="form-label">Group</label>
                            <?php
                                // Determine the effective selected group (matches data query logic below)
                                $effective_group_name = 'all_users';
                                if (isset($_POST['group_name']) && $_POST['group_name'] !== '') {
                                    $effective_group_name = $_POST['group_name'];
                                } elseif ($user_id === 192) {
                                    $effective_group_name = 'andrew_clients';
                                } elseif ($user_id === 354) {
                                    $effective_group_name = 'alex_clients';
                                }
                            ?>
                            <select id="q_status_order_id_eq"
                                name="group_name"
                                class="form-select">
                                <option value="all_users" <?php echo ($effective_group_name === 'all_users') ? 'selected' : ''; ?>>All Dealers</option>
                                <?php
                                $groups_created = get_post_meta(1, 'groups_created', true);
                                foreach ($groups_created as $key => $group_name) {
                                    $selected = ($effective_group_name === $group_name) ? 'selected' : '';
                                    echo '<option value="' . esc_attr($group_name) . '" ' . $selected . '>' . esc_html($group_name) . '</option>';
                                } ?>
                            </select>
                        </div>

                        <!-- FROM: month + year -->
                        <div class="col-sm-3 col-lg-2">
                            <label for="select-luna-from" class="form-label">From month</label>
                            <select id="select-luna-from" name="luna_from" class="form-select">
                                <?php foreach ($luni as $luna => $name_month) {
                                    $selected = ($posted_from_month == $luna) ? 'selected' : ''; ?>
                                    <option value="<?php echo $luna; ?>" <?php echo $selected; ?>>
                                        <?php echo $name_month; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-3 col-lg-2">
                            <label for="select-an-from" class="form-label">From year</label>
                            <select id="select-an-from" name="an_from" class="form-select">
                                <?php foreach ($last10Years as $an) {
                                    $selected = ($posted_from_year == $an) ? 'selected' : ''; ?>
                                    <option value="<?php echo $an; ?>" <?php echo $selected; ?>>
                                        <?php echo $an; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- TO: month + year -->
                        <div class="col-sm-3 col-lg-2">
                            <label for="select-luna-to" class="form-label">To month</label>
                            <select id="select-luna-to" name="luna_to" class="form-select">
                                <?php foreach ($luni as $luna => $name_month) {
                                    $selected = ($posted_to_month == $luna) ? 'selected' : ''; ?>
                                    <option value="<?php echo $luna; ?>" <?php echo $selected; ?>>
                                        <?php echo $name_month; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-3 col-lg-1">
                            <label for="select-an-to" class="form-label">To year</label>
                            <select id="select-an-to" name="an_to" class="form-select">
                                <?php foreach ($last10Years as $an) {
                                    $selected = ($posted_to_year == $an) ? 'selected' : ''; ?>
                                    <option value="<?php echo $an; ?>" <?php echo $selected; ?>>
                                        <?php echo $an; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-sm-3 col-lg-2">
                            <input type="submit"
                                name="submit"
                                value="Select"
                                class="btn btn-primary w-100">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php

        //		print_r($_POST);
        $user_id = get_current_user_id();
        $allowed_user_ids = [1, 2, 18, 192, 354]; // Array of allowed user IDs. Change these to the actual IDs you want to allow.

        if (isset($_POST['group_name']) && $_POST['group_name'] !== '') {
            if ($_POST['group_name'] === 'all_users') {
                $users = get_users(array('fields' => array('ID')));
                $group = array();
                foreach ($users as $user) {
                    $group[] = $user->ID;
                }
            } else {
                $group = get_post_meta(1, sanitize_text_field($_POST['group_name']), true);
            }
        } else {
            if ($user_id === 192) {
                $group = get_post_meta(1, "andrew_clients", true);
            } elseif ($user_id === 354) {
                $group = get_post_meta(1, "alex_clients", true);
            } else {
                $users = get_users(array('fields' => array('ID')));
                $group = array();
                foreach ($users as $user) {
                    $group[] = $user->ID;
                }
            }
        }

        $i = 1;
        // Date range: From / To
        $from_month = isset($_POST['luna_from']) ? $_POST['luna_from'] : date("m");
        $from_year  = isset($_POST['an_from']) ? $_POST['an_from'] : date("Y");
        $to_month   = isset($_POST['luna_to']) ? $_POST['luna_to'] : date("m");
        $to_year    = isset($_POST['an_to']) ? $_POST['an_to'] : date("Y");

        // Build date range: start of from_month to end of to_month
        // Strict month bounds: first day of from_month → last day of to_month.
        // Full datetime strings + inclusive=true because WP_Date_Query treats
        // bare date strings as exclusive, silently dropping the boundary days.
        $date_after  = date('Y-m-d H:i:s', mktime(0, 0, 0, $from_month, 1, $from_year));
        $date_before = date('Y-m-d H:i:s', mktime(23, 59, 59, $to_month, (int) date('t', strtotime("$to_year-$to_month-01")), $to_year));

        $args = array(
            'customer_id' => $group,
            'limit' => -1,
            'type' => 'shop_order',
            'status' => array('wc-on-hold', 'wc-completed', 'wc-pending', 'wc-processing', 'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision'),
            'orderby' => 'date',
            'date_query' => array(
                array(
                    'after'     => $date_after,
                    'before'    => $date_before,
                    'inclusive' => true,
                ),
            ),
            'return' => 'ids',
        );

        $orders = wc_get_orders($args);

        $group_companies = array();

        // === BATCH DATA LOADING (eliminates thousands of per-order queries) ===

        // 1. Batch fetch wp_custom_orders SQM data
        $custom_order_data = fetch_custom_order_data($orders);

        // 2. Batch fetch order post meta (_customer_user, type_order)
        $order_meta_cache = array();
        if (!empty($orders)) {
            $order_ids_str = implode(',', array_map('intval', $orders));
            $meta_rows = $wpdb->get_results(
                "SELECT post_id, meta_key, meta_value FROM {$wpdb->postmeta}
                 WHERE post_id IN ($order_ids_str) AND meta_key IN ('_customer_user', 'type_order')",
                ARRAY_A
            );
            foreach ($meta_rows as $row) {
                $order_meta_cache[$row['post_id']][$row['meta_key']] = $row['meta_value'];
            }
        }

        // 3. Batch fetch billing_company for all customer IDs from orders
        $all_customer_ids = array();
        foreach ($order_meta_cache as $order_id => $meta) {
            if (!empty($meta['_customer_user'])) {
                $all_customer_ids[] = (int) $meta['_customer_user'];
            }
        }
        $all_customer_ids = array_unique($all_customer_ids);

        $company_cache = array();
        if (!empty($all_customer_ids)) {
            $user_ids_str = implode(',', $all_customer_ids);
            $company_rows = $wpdb->get_results(
                "SELECT user_id, meta_value FROM {$wpdb->usermeta}
                 WHERE user_id IN ($user_ids_str) AND meta_key = 'billing_company'",
                ARRAY_A
            );
            foreach ($company_rows as $row) {
                $company_cache[$row['user_id']] = $row['meta_value'];
            }
        }

        // 4. Material ID mapping (constant)
        $material_ids_map = array(
            187 => 'Earth',
            137 => 'Green',
            138 => 'BiowoodPlus',
            6 => 'Biowood',
            139 => 'BasswoodPlus',
            147 => 'Basswood',
            188 => 'Ecowood',
            5 => 'EcowoodPlus',
        );

        // === MAIN ORDER LOOP ===
        foreach ($orders as $id_order) {
            // Use batch-cached data instead of per-order queries
            $property_total = isset($custom_order_data[$id_order]) ? $custom_order_data[$id_order]['sqm'] : 0;
            $user_id = isset($order_meta_cache[$id_order]['_customer_user']) ? $order_meta_cache[$id_order]['_customer_user'] : '';
            $company = isset($company_cache[$user_id]) ? $company_cache[$user_id] : '';
            $type_order = isset($order_meta_cache[$id_order]['type_order']) ? $order_meta_cache[$id_order]['type_order'] : '';

            if (!empty($user_id)) {
                $group_companies[$company]['user_id'] = $user_id;
            }
            if (array_key_exists($company, $group_companies)) {
                $group_companies[$company]['sqm'] += $property_total;
            } else {
                $group_companies[$company]['sqm'] = $property_total;
            }

            // Load order object once (needed for items + subtotal)
            $order = wc_get_order($id_order);

            // Check if order is awning type
            if ($type_order === 'awning') {
                if (!isset($group_companies[$company]['type'])) {
                    $group_companies[$company]['type'] = $type_order;
                    $group_companies[$company]['awning_items'] = 0;
                    $group_companies[$company]['awning_subtotal'] = 0;
                }
                $group_companies[$company]['awning_items'] += count($order->get_items());
                $group_companies[$company]['awning_subtotal'] += $order->get_subtotal();
            }

            // Materials — inline extraction (avoids duplicate wc_get_order + redundant attributes_array fetch)
            foreach ($order->get_items() as $item) {
                $product_id = $item['product_id'];
                $material_id = get_post_meta($product_id, 'property_material', true);
                $material = isset($material_ids_map[$material_id]) ? $material_ids_map[$material_id] : null;
                if ($material) {
                    $sqm = (float) get_post_meta($product_id, 'property_total', true);
                    $group_companies[$company][$material] = (float)($group_companies[$company][$material] ?? 0) + $sqm;
                }
            }
        }

        // === FILL MISSING COMPANIES (batch query instead of per-user get_user_meta) ===
        $materials = array('Earth' => 0, 'Green' => 0, 'Biowood' => 0, 'BiowoodPlus' => 0, 'BasswoodPlus' => 0, 'Basswood' => 0, 'Ecowood' => 0, 'EcowoodPlus' => 0);
        if (!empty($group)) {
            $group_ids_str = implode(',', array_map('intval', $group));
            $group_company_rows = $wpdb->get_results(
                "SELECT user_id, meta_value FROM {$wpdb->usermeta}
                 WHERE user_id IN ($group_ids_str) AND meta_key = 'billing_company'",
                ARRAY_A
            );
            foreach ($group_company_rows as $row) {
                $company = $row['meta_value'];
                if (!array_key_exists($company, $group_companies)) {
                    $group_companies[$company]['sqm'] = 0;
                    $group_companies[$company]['user_id'] = $row['user_id'];
                    foreach ($materials as $key => $value) {
                        $group_companies[$company][$key] = 0;
                    }
                }
            }
        }

        uasort($group_companies, function ($a, $b) {
            // Compară valorile 'sqm'
            // Pentru sortare ascendentă, folosește:
            //			return $a['sqm'] <=> $b['sqm'];

            // Pentru sortare descendentă, inversează comparația:
            return $b['sqm'] <=> $a['sqm'];
        });

        //		echo '<pre>';
        //		print_r($group_companies);
        //		echo '</pre>';

        // Obținem valorile default pentru prețurile materialelor
        $default_EcowoodPlus = get_post_meta(1, 'EcowoodPlus', true);
        $default_Ecowood = get_post_meta(1, 'Ecowood', true);
        $default_BiowoodPlus = get_post_meta(1, 'BiowoodPlus', true);
        $default_Biowood = get_post_meta(1, 'Biowood', true);
        $default_Green = get_post_meta(1, 'Green', true);
        $default_BasswoodPlus = get_post_meta(1, 'BasswoodPlus', true);
        $default_Basswood = get_post_meta(1, 'Basswood', true);
        $default_Earth = get_post_meta(1, 'Earth', true);

        // Prime user meta cache in a single query (eliminates ~1700 individual get_user_meta calls)
        $render_user_ids = array_filter(array_column($group_companies, 'user_id'));
        if (!empty($render_user_ids)) {
            cache_users($render_user_ids);
        }

        $months = array('01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');
        ?>

        <?php
        // Build range label
        if ($from_month === $to_month && $from_year === $to_year) {
            $range_label = esc_html($months[$from_month]) . ' ' . esc_html($from_year);
        } elseif ($from_year === $to_year) {
            $range_label = esc_html($months[$from_month]) . ' – ' . esc_html($months[$to_month]) . ' ' . esc_html($from_year);
        } else {
            $range_label = esc_html($months[$from_month]) . ' ' . esc_html($from_year) . ' – ' . esc_html($months[$to_month]) . ' ' . esc_html($to_year);
        }
        ?>
        <div class="grup-section-header">
            <h3>Dealer Portfolio</h3>
            <span class="grup-section-subtitle"><?php echo $range_label; ?></span>
        </div>

        <div id="grup-month" class="tab-pane">
            <div class="grup-table-card">
                <div class="table-responsive">
                    <table id="grup-portfolio-table" class="table table-bordered table-striped table-hover grup-table">
                        <!-- Tabelul de antet -->
                        <thead>
                            <tr>
                                <th>Nr.</th>
                                <th>User</th>
                                <th>Total SQM</th>
                                <th>Ecowood £</th>
                                <th>Ecowood SQM</th>
                                <th>EcowoodPlus £</th>
                                <th>EcowoodPlus SQM</th>
                                <th>Biowood £</th>
                                <th>Biowood SQM</th>
                                <th>BiowoodPlus £</th>
                                <th>BiowoodPlus SQM</th>
                                <th>BasswoodPlus £</th>
                                <th>BasswoodPlus SQM</th>
                                <th>Basswood £</th>
                                <th>Basswood SQM</th>
                                <th>Earth £</th>
                                <th>Earth SQM</th>
                                <th>Green £</th>
                                <th>Green SQM</th>
                                <th>Awnings £</th>
                                <th>Awnings Items</th>
                            </tr>
                        </thead>

                        <!-- Corpul tabelului -->
                        <tbody>
                            <?php

                            // Inițializăm variabilele pentru totaluri
                            $i = 1;
                            $total_sqm = 0;
                            $total_sqm_earth = 0;
                            $total_sqm_ecowood = 0;
                            $total_sqm_ecowoodPlus = 0;
                            $total_sqm_green = 0;
                            $total_sqm_biowood = 0;
                            $total_sqm_biowoodPlus = 0;
                            $total_sqm_basswoodPlus = 0;
                            $total_sqm_basswood = 0;
                            $total_awning_subtotal = 0;
                            $total_awning_items = 0;

                            // Parcurgem array-ul $companys_sqm (cheia este ID-ul userului)
                            foreach ($group_companies as $company => $data) {
                                // Acumulăm totalul SQM pentru raport
                                $total_sqm += $data['sqm'];
                                $total_sqm_earth += (float)($data['Earth'] ?? 0);
                                $total_sqm_ecowood += (float)($data['Ecowood'] ?? 0);
                                $total_sqm_ecowoodPlus += (float)($data['EcowoodPlus'] ?? 0);
                                $total_sqm_green += (float)($data['Green'] ?? 0);
                                $total_sqm_biowood += (float)($data['Biowood'] ?? 0);
                                $total_sqm_biowoodPlus += (float)($data['BiowoodPlus'] ?? 0);
                                $total_sqm_basswoodPlus += (float)($data['BasswoodPlus'] ?? 0);
                                $total_sqm_basswood += (float)($data['Basswood'] ?? 0);
                                $total_awning_subtotal += (float)($data['awning_subtotal'] ?? 0);
                                $total_awning_items += (float)($data['awning_items'] ?? 0);

                                // Use stored user_id directly (no more get_user_ids_by_billing_company query)
                                $user_id = isset($data['user_id']) ? $data['user_id'] : 0;

                                // Preluăm valorile din user meta; dacă nu există, folosim valorile default
                                $ecowood = get_user_meta($user_id, 'Ecowood', true);
                                if (empty($ecowood)) {
                                    $ecowood = $default_Ecowood;
                                }
                                $ecowoodPlus = get_user_meta($user_id, 'EcowoodPlus', true);
                                if (empty($ecowoodPlus)) {
                                    $ecowoodPlus = $default_EcowoodPlus;
                                }
                                $biowood = get_user_meta($user_id, 'Biowood', true);
                                if (empty($biowood)) {
                                    $biowood = $default_Biowood;
                                }
                                $biowoodPlus = get_user_meta($user_id, 'BiowoodPlus', true);
                                if (empty($biowoodPlus)) {
                                    $biowoodPlus = $default_BiowoodPlus;
                                }
                                $green = get_user_meta($user_id, 'Green', true);
                                if (empty($green)) {
                                    $green = $default_Green;
                                }
                                $basswood = get_user_meta($user_id, 'Supreme', true);  // legacy meta key
                                if (empty($basswood)) {
                                    $basswood = $default_Basswood;
                                }
                                $basswoodPlus = get_user_meta($user_id, 'BasswoodPlus', true);
                                if (empty($basswoodPlus)) {
                                    $basswoodPlus = $default_BasswoodPlus;
                                }
                                $earth = get_user_meta($user_id, 'Earth', true);
                                if (empty($earth)) {
                                    $earth = $default_Earth;
                                }

                                // Preluăm telefonul și adresa de livrare a userului
                                $phone_number = get_user_meta($user_id, 'billing_phone', true);
                                $shipping_address = array(
                                    'first_name' => get_user_meta($user_id, 'shipping_first_name', true),
                                    'last_name' => get_user_meta($user_id, 'shipping_last_name', true),
                                    'company' => get_user_meta($user_id, 'shipping_company', true),
                                    'address_1' => get_user_meta($user_id, 'shipping_address_1', true),
                                    'address_2' => get_user_meta($user_id, 'shipping_address_2', true),
                                    'city' => get_user_meta($user_id, 'shipping_city', true),
                                    'state' => get_user_meta($user_id, 'shipping_state', true),
                                    'postcode' => get_user_meta($user_id, 'shipping_postcode', true),
                                    'country' => get_user_meta($user_id, 'shipping_country', true),
                                );
                            ?>
                                <tr<?php echo ($group_companies[$company]['sqm'] == 0) ? ' class="grup-row-empty"' : ''; ?>>
                                    <td><?php echo $i; ?></td>
                                    <td>
                                        <!-- Butonul deschide un modal cu detaliile utilizatorului -->
                                        <button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                            data-bs-name="<?php echo esc_attr((string)($company ?? '')); ?>"
                                            data-bs-phone="<?php echo esc_attr((string)($phone_number ?? '')); ?>"
                                            data-bs-postcode="<?php echo esc_attr((string)($shipping_address['postcode'] ?? '')); ?>"
                                            data-bs-dealer="<?php echo esc_attr($user_id); ?>"
                                            data-bs-address="<?php echo esc_attr((string)($shipping_address['address_1'] ?? '')); ?>">
                                            <?php echo esc_html($company); ?>
                                        </button>
                                    </td>
                                    <td data-order="<?php echo $group_companies[$company]['sqm']; ?>"><?php echo number_format($group_companies[$company]['sqm'], 2); ?> SQM</td>
                                    <td><?php echo esc_html($ecowood); ?></td>
                                    <td><?php echo number_format($group_companies[$company]['Ecowood'], 2); ?></td>
                                    <td><?php echo esc_html($ecowoodPlus); ?></td>
                                    <td><?php echo number_format($group_companies[$company]['EcowoodPlus'], 2); ?></td>
                                    <td><?php echo esc_html($biowood); ?></td>
                                    <td><?php echo number_format($group_companies[$company]['Biowood'], 2); ?></td>
                                    <td><?php echo esc_html($biowoodPlus); ?></td>
                                    <td><?php echo number_format($group_companies[$company]['BiowoodPlus'], 2); ?></td>
                                    <td><?php echo esc_html($basswoodPlus); ?></td>
                                    <td><?php echo number_format($group_companies[$company]['BasswoodPlus'], 2); ?></td>
                                    <td><?php echo esc_html($basswood); ?></td>
                                    <td><?php echo number_format($group_companies[$company]['Basswood'], 2); ?></td>
                                    <td><?php echo esc_html($earth); ?></td>
                                    <td><?php echo number_format($group_companies[$company]['Earth'], 2); ?></td>
                                    <td><?php echo esc_html($green); ?></td>
                                    <td><?php echo number_format((float)($group_companies[$company]['Green'] ?? 0), 2); ?></td>
                                    <td><?php echo number_format($group_companies[$company]['awning_subtotal'], 2); ?></td>
                                    <td><?php echo number_format($group_companies[$company]['awning_items'], 2); ?></td>
                                    </tr>
                                <?php
                                $i++;
                            }
                                ?>
                        </tbody>

                        <!-- Tabelul de totaluri (footer) -->
                        <tfoot>
                            <tr class="grup-table-totals">
                                <td></td>
                                <td>Totals</td>
                                <td>Total SQM <?php echo number_format($total_sqm, 2); ?></td>
                                <td></td>
                                <td><?php echo number_format($total_sqm_ecowood, 2); ?></td>
                                <td></td>
                                <td><?php echo number_format($total_sqm_ecowoodPlus, 2); ?></td>
                                <td></td>
                                <td><?php echo number_format($total_sqm_biowood, 2); ?></td>
                                <td></td>
                                <td><?php echo number_format($total_sqm_biowoodPlus, 2); ?></td>
                                <td></td>
                                <td><?php echo number_format($total_sqm_basswoodPlus, 2); ?></td>
                                <td></td>
                                <td><?php echo number_format($total_sqm_basswood, 2); ?></td>
                                <td></td>
                                <td><?php echo number_format($total_sqm_earth, 2); ?></td>
                                <td></td>
                                <td><?php echo number_format($total_sqm_green, 2); ?></td>
                                <td><?php echo number_format($total_awning_subtotal, 2); ?></td>
                                <td><?php echo number_format($total_awning_items, 2); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <!-- *********************************************************************************************
			End Total user sqm
			*********************************************************************************************	-->

    </main>
    <!-- #main -->
</div>
<!-- #primary -->

<?php
$months_cart = array();
for ($i = 11; $i >= 0; $i--) {
    $month = date('M y', mktime(0, 0, 0, date('m') - $i, 1, date('Y')));
    $months_cart[] = $month;
}
?>

<script>
    console.log('[groups-portfolio] script loaded');
    jQuery(document).ready(function() {
        console.log('[groups-portfolio] ready fired');

        // Cache commonly used DOM elements to optimize performance
        var exampleModal = jQuery('#exampleModal');
        var messageText = jQuery('#message-text');
        var dealerIdField = jQuery('#dealer-id');
        var dealerNotesNonce = '<?php echo wp_create_nonce('matrix_dealer_notes'); ?>';
        var ajaxUrl = (typeof _wpUtilSettings !== 'undefined' && _wpUtilSettings.ajax && _wpUtilSettings.ajax.url)
            ? _wpUtilSettings.ajax.url
            : (typeof ajaxurl !== 'undefined' ? ajaxurl : '/wp-admin/admin-ajax.php');

        // Quick date preset buttons
        jQuery('.date-preset').on('click', function() {
            var btn = jQuery(this);
            jQuery('#select-luna-from').val(btn.data('fm'));
            jQuery('#select-an-from').val(btn.data('fy'));
            jQuery('#select-luna-to').val(btn.data('tm'));
            jQuery('#select-an-to').val(btn.data('ty'));
            // Highlight active preset
            jQuery('.date-preset').removeClass('btn-primary').addClass('btn-outline-secondary');
            btn.removeClass('btn-outline-secondary').addClass('btn-primary');
        });

        // Initialize DataTable (guarded — DataTable may not be loaded in admin)
        try {
            if (jQuery.fn.DataTable) {
                jQuery('#grup-portfolio-table').DataTable({
                    paging: false,
                    dom: 'Bfrtip',
                    buttons: ['copy', 'csv', 'excel', 'print'],
                    order: [
                        [2, 'desc']
                    ],
                    columnDefs: [{
                        targets: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
                        className: 'text-end'
                    }]
                });
            } else {
                console.warn('[groups-portfolio] DataTable plugin not loaded');
            }
        } catch (e) {
            console.error('[groups-portfolio] DataTable init failed', e);
        }

        // Populate modal BEFORE Bootstrap opens it. Delegated on document so DataTable
        // redraws / dynamic rows still match. Reads via native getAttribute to bypass
        // any jQuery .data() cache or Bootstrap dataset parsing quirks.
        function populateDealerModal(buttonEl) {
            if (!buttonEl) return;
            var name = buttonEl.getAttribute('data-bs-name') || '';
            var phone = buttonEl.getAttribute('data-bs-phone') || '';
            var address = buttonEl.getAttribute('data-bs-address') || '';
            var dealerId = buttonEl.getAttribute('data-bs-dealer') || '';
            var postcode = buttonEl.getAttribute('data-bs-postcode') || '';

            console.log('[dealer-modal]', { name: name, phone: phone, address: address, dealerId: dealerId, postcode: postcode });

            var modal = jQuery('#exampleModal');
            modal.find('.dealer-name').text(name);
            modal.find('.dealer-phone').text(phone);
            modal.find('.dealer-postcode').text(postcode);
            modal.find('.dealer-address').text(address);

            modal.find('.modal-title').text('Notes for ' + name);
            modal.find('.modal-body input#dealer-name').val(name);
            modal.find('.modal-body input#dealer-phone').val(phone);
            modal.find('.modal-body input#dealer-postcode').val(postcode);
            modal.find('.modal-body input#dealer-address').val(address);
            modal.find('.modal-body input#dealer-id').val(dealerId);

            jQuery('.user-message').remove();
            if (dealerId) {
                fetchMessages(dealerId);
            }
        }

        // Primary: delegated click before Bootstrap opens modal
        jQuery(document).on('click', '[data-bs-target="#exampleModal"]', function() {
            populateDealerModal(this);
        });

        // Fallback: Bootstrap show event (in case click handler missed it)
        jQuery('#exampleModal').on('show.bs.modal', function(event) {
            if (event.relatedTarget) {
                populateDealerModal(event.relatedTarget);
            }
        });

        // Event listener for when the 'Send Message' button is clicked
        jQuery('.btn-primary.send-notes').click(function() {
            var message = messageText.val().trim(); // Get and trim the whitespace from the input message
            if (message) {
                sendMessage(dealerIdField.val(), message); // Send message if not empty
            } else {
                alert('Please enter a message.'); // Alert if message is empty
            }
        });

        // Function to fetch messages for a given dealer and update the modal
        function fetchMessages(dealerId) {
            jQuery.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: {
                    action: 'get_user_messages',
                    user_id: dealerId,
                    nonce: dealerNotesNonce
                },
                success: function(response) {
                    if (response.success) {
                        displayMessages(response.data); // Display messages if AJAX call was successful
                    } else {
                        console.error('Failed to retrieve messages: ', response.data); // Log errors to console
                    }
                },
                error: function() {
                    console.error('Failed to retrieve messages.'); // Log AJAX errors to console
                }
            });
        }

        // Function to send a new message to the server
        function sendMessage(dealerId, message) {
            jQuery.ajax({
                url: _wpUtilSettings.ajax.url,
                type: 'POST',
                data: {
                    action: 'save_user_message', // WP AJAX action
                    user_id: dealerId, // Dealer ID
                    message: message, // Message text
                    nonce: dealerNotesNonce
                },
                success: function(response) {
                    if (response.success) {
                        messageText.val(''); // Clear the textarea after message is sent
                        fetchMessages(dealerId);

                    } else {
                        console.error('Error saving message: ', response.data); // Log save errors to console
                    }
                },
                error: function() {
                    console.error('Error saving message.'); // Log AJAX errors to console
                }
            });
        }

        // Function to append messages to the modal body just above the textarea
        function displayMessages(messages) {
            jQuery('.user-message').remove();
            messages.forEach(function(message) {
                var div = jQuery('<div>').addClass('user-message').text(message);
                messageText.before(div);
            });
        }


    });
</script>


<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="exampleModalLabel">Notes for </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="p-3 bg-light rounded mb-3">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold mb-0">Dealer</label>
                                <span class="d-block dealer-name text-muted"></span>
                                <input type="hidden" class="form-control" id="dealer-name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold mb-0">Phone</label>
                                <span class="d-block dealer-phone text-muted"></span>
                                <input type="hidden" class="form-control" id="dealer-phone">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold mb-0">Address</label>
                                <span class="d-block dealer-address text-muted"></span>
                                <input type="hidden" class="form-control" id="dealer-address">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold mb-0">Postcode</label>
                                <span class="d-block dealer-postcode text-muted"></span>
                                <input type="hidden" class="form-control" id="dealer-postcode">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Message:</label>
                        <textarea class="form-control" id="message-text" style="height: 200px"></textarea>
                    </div>
                    <input type="hidden" class="form-control" id="dealer-id">

                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary send-notes">Send notes</button>
            </div>
        </div>
    </div>
</div>