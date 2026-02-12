<?php

/**
 * Groups Portfolio SQM — SQM-only view with user filter
 */

function get_user_ids_by_billing_company_sqm($company_name)
{
    $users = get_users(array(
        'meta_key' => 'billing_company',
        'meta_value' => $company_name,
        'fields' => 'ids',
    ));
    return $users;
}

?>

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

    #user-filter-select {
        min-height: 38px;
    }

</style>


<div id="primary"
    class="content-area container">
    <main id="main"
        class="site-main"
        role="main">

        <h2>Groups Portfolio SQM</h2>

        <?php
        $user_id = get_current_user_id();

        global $wpdb;
        ?>

        <div class="grup-section-header">
            <h3>Search Group Info</h3>
            <span class="grup-section-subtitle">Filter by group, company and date range — SQM only</span>
        </div>

        <div class="card grup-filter-card mb-4">
            <div class="card-body">
                <h6 class="card-title text-muted mb-3">Filter</h6>
                <?php
                $month = date("m");
                $currentYear = date("Y");
                $luni = array('01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');

                $last10Years = [];
                for ($i = 0; $i < 10; $i++) {
                    $last10Years[] = $currentYear - $i;
                }

                $posted_from_month = isset($_POST['luna_from']) ? $_POST['luna_from'] : $month;
                $posted_from_year  = isset($_POST['an_from']) ? $_POST['an_from'] : $currentYear;
                $posted_to_month   = isset($_POST['luna_to']) ? $_POST['luna_to'] : $month;
                $posted_to_year    = isset($_POST['an_to']) ? $_POST['an_to'] : $currentYear;
                ?>
                <form action="" method="POST">
                    <?php wp_nonce_field('grup_portfolio_sqm_nonce', 'grup_portfolio_sqm_nonce_field'); ?>
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
                        <div class="col-sm-6 col-lg-2">
                            <label for="q_status_order_id_eq" class="form-label">Group</label>
                            <?php
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

                        <!-- Company filter -->
                        <div class="col-sm-6 col-lg-2">
                            <label for="user-filter-select" class="form-label">Filter by Company</label>
                            <select id="user-filter-select" name="user_filter[]" class="form-select" multiple>
                                <?php
                                // Pre-populate with companies from current group
                                if ($effective_group_name === 'all_users') {
                                    $filter_users = get_users(array('fields' => array('ID'), 'orderby' => 'display_name', 'order' => 'ASC'));
                                } else {
                                    $filter_user_ids = get_post_meta(1, $effective_group_name, true);
                                    $filter_users = !empty($filter_user_ids) ? get_users(array('include' => $filter_user_ids, 'fields' => array('ID'), 'orderby' => 'display_name', 'order' => 'ASC')) : array();
                                }
                                $posted_user_filter = isset($_POST['user_filter']) ? array_map('intval', $_POST['user_filter']) : array();
                                // Build unique company list (company => user_id)
                                $company_options = array();
                                foreach ($filter_users as $fu) {
                                    $fu_company = get_user_meta($fu->ID, 'billing_company', true);
                                    if (!empty($fu_company) && !isset($company_options[$fu_company])) {
                                        $company_options[$fu_company] = $fu->ID;
                                    }
                                }
                                ksort($company_options);
                                foreach ($company_options as $co_name => $co_uid) {
                                    $co_selected = in_array((int)$co_uid, $posted_user_filter) ? 'selected' : '';
                                    echo '<option value="' . esc_attr($co_uid) . '" ' . $co_selected . '>' . esc_html($co_name) . '</option>';
                                }
                                ?>
                            </select>
                            <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple</small>
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
                        <div class="col-sm-3 col-lg-1">
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

        $user_id = get_current_user_id();
        $allowed_user_ids = [1, 2, 18, 192, 354];

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

        // Apply user filter — intersect with selected users
        if (isset($_POST['user_filter']) && !empty($_POST['user_filter'])) {
            $selected_users = array_map('intval', $_POST['user_filter']);
            $group = array_intersect($group, $selected_users);
        }

        $i = 1;
        // Date range: From / To
        $from_month = isset($_POST['luna_from']) ? $_POST['luna_from'] : date("m");
        $from_year  = isset($_POST['an_from']) ? $_POST['an_from'] : date("Y");
        $to_month   = isset($_POST['luna_to']) ? $_POST['luna_to'] : date("m");
        $to_year    = isset($_POST['an_to']) ? $_POST['an_to'] : date("Y");

        $date_after  = date('Y-m-d', mktime(0, 0, 0, $from_month, 0, $from_year));
        $date_before = date('Y-m-d', strtotime('+1 month', strtotime($to_year . '-' . $to_month . '-1')));

        $args = array(
            'customer_id' => $group,
            'limit' => -1,
            'type' => 'shop_order',
            'status' => array('wc-on-hold', 'wc-completed', 'wc-pending', 'wc-processing', 'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision'),
            'orderby' => 'date',
            'date_query' => array(
                'after' => $date_after,
                'before' => $date_before,
            ),
            'return' => 'ids',
        );

        $orders = wc_get_orders($args);

        $group_companies = array();

        // === BATCH DATA LOADING ===

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

        // 4. Material ID mapping
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

            // Skip awning orders — SQM-only view
            if ($type_order === 'awning') {
                continue;
            }

            // Materials — inline extraction
            $order = wc_get_order($id_order);
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

        // === FILL MISSING COMPANIES ===
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
            return $b['sqm'] <=> $a['sqm'];
        });

        // Prime user meta cache
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
            <h3>Dealer Portfolio — SQM</h3>
            <span class="grup-section-subtitle"><?php echo $range_label; ?></span>
        </div>

        <div id="grup-month" class="tab-pane">
            <div class="grup-table-card">
                <div class="table-responsive">
                    <table id="grup-portfolio-sqm-table" class="table table-bordered table-striped table-hover grup-table">
                        <thead>
                            <tr>
                                <th>Nr.</th>
                                <th>User</th>
                                <th>Total SQM</th>
                                <th>Ecowood</th>
                                <th>EcowoodPlus</th>
                                <th>Biowood</th>
                                <th>BiowoodPlus</th>
                                <th>BasswoodPlus</th>
                                <th>Basswood</th>
                                <th>Earth</th>
                                <th>Green</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
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

                            foreach ($group_companies as $company => $data) {
                                $total_sqm += $data['sqm'];
                                $total_sqm_earth += (float)($data['Earth'] ?? 0);
                                $total_sqm_ecowood += (float)($data['Ecowood'] ?? 0);
                                $total_sqm_ecowoodPlus += (float)($data['EcowoodPlus'] ?? 0);
                                $total_sqm_green += (float)($data['Green'] ?? 0);
                                $total_sqm_biowood += (float)($data['Biowood'] ?? 0);
                                $total_sqm_biowoodPlus += (float)($data['BiowoodPlus'] ?? 0);
                                $total_sqm_basswoodPlus += (float)($data['BasswoodPlus'] ?? 0);
                                $total_sqm_basswood += (float)($data['Basswood'] ?? 0);

                                $user_id = isset($data['user_id']) ? $data['user_id'] : 0;

                                // User details for modal
                                $phone_number = get_user_meta($user_id, 'billing_phone', true);
                                $shipping_address = array(
                                    'address_1' => get_user_meta($user_id, 'shipping_address_1', true),
                                    'postcode' => get_user_meta($user_id, 'shipping_postcode', true),
                                );
                            ?>
                                <tr<?php echo ($data['sqm'] == 0) ? ' class="grup-row-empty"' : ''; ?>>
                                    <td><?php echo $i; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                            data-bs-name="<?php echo esc_html($company); ?>"
                                            data-bs-phone="<?php echo esc_html($phone_number); ?>"
                                            data-bs-postcode="<?php echo esc_html($shipping_address['postcode']); ?>"
                                            data-bs-dealer="<?php echo esc_attr($user_id); ?>"
                                            data-bs-address="<?php echo esc_html($shipping_address['address_1']); ?>">
                                            <?php echo esc_html($company); ?>
                                        </button>
                                    </td>
                                    <td data-order="<?php echo $data['sqm']; ?>"><?php echo number_format($data['sqm'], 2); ?></td>
                                    <td data-order="<?php echo (float)($data['Ecowood'] ?? 0); ?>"><?php echo number_format((float)($data['Ecowood'] ?? 0), 2); ?></td>
                                    <td data-order="<?php echo (float)($data['EcowoodPlus'] ?? 0); ?>"><?php echo number_format((float)($data['EcowoodPlus'] ?? 0), 2); ?></td>
                                    <td data-order="<?php echo (float)($data['Biowood'] ?? 0); ?>"><?php echo number_format((float)($data['Biowood'] ?? 0), 2); ?></td>
                                    <td data-order="<?php echo (float)($data['BiowoodPlus'] ?? 0); ?>"><?php echo number_format((float)($data['BiowoodPlus'] ?? 0), 2); ?></td>
                                    <td data-order="<?php echo (float)($data['BasswoodPlus'] ?? 0); ?>"><?php echo number_format((float)($data['BasswoodPlus'] ?? 0), 2); ?></td>
                                    <td data-order="<?php echo (float)($data['Basswood'] ?? 0); ?>"><?php echo number_format((float)($data['Basswood'] ?? 0), 2); ?></td>
                                    <td data-order="<?php echo (float)($data['Earth'] ?? 0); ?>"><?php echo number_format((float)($data['Earth'] ?? 0), 2); ?></td>
                                    <td data-order="<?php echo (float)($data['Green'] ?? 0); ?>"><?php echo number_format((float)($data['Green'] ?? 0), 2); ?></td>
                                </tr>
                            <?php
                                $i++;
                            }
                            ?>
                        </tbody>

                        <tfoot>
                            <tr class="grup-table-totals">
                                <td></td>
                                <td>Totals</td>
                                <td><?php echo number_format($total_sqm, 2); ?></td>
                                <td><?php echo number_format($total_sqm_ecowood, 2); ?></td>
                                <td><?php echo number_format($total_sqm_ecowoodPlus, 2); ?></td>
                                <td><?php echo number_format($total_sqm_biowood, 2); ?></td>
                                <td><?php echo number_format($total_sqm_biowoodPlus, 2); ?></td>
                                <td><?php echo number_format($total_sqm_basswoodPlus, 2); ?></td>
                                <td><?php echo number_format($total_sqm_basswood, 2); ?></td>
                                <td><?php echo number_format($total_sqm_earth, 2); ?></td>
                                <td><?php echo number_format($total_sqm_green, 2); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </main>
</div>

<script>
    jQuery(document).ready(function() {

        // Quick date preset buttons
        jQuery('.date-preset').on('click', function() {
            var btn = jQuery(this);
            jQuery('#select-luna-from').val(btn.data('fm'));
            jQuery('#select-an-from').val(btn.data('fy'));
            jQuery('#select-luna-to').val(btn.data('tm'));
            jQuery('#select-an-to').val(btn.data('ty'));
            jQuery('.date-preset').removeClass('btn-primary').addClass('btn-outline-secondary');
            btn.removeClass('btn-outline-secondary').addClass('btn-primary');
        });

        // Initialize DataTable with export buttons
        jQuery('#grup-portfolio-sqm-table').DataTable({
            paging: false,
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'print'],
            order: [
                [2, 'desc']
            ],
            columnDefs: [{
                targets: [2, 3, 4, 5, 6, 7, 8, 9, 10],
                className: 'text-end'
            }]
        });

        // Company filter: reload options when group changes
        jQuery('#q_status_order_id_eq').on('change', function() {
            var group_name = jQuery(this).val();
            var userSelect = jQuery('#user-filter-select');

            userSelect.html('<option>Loading...</option>').prop('disabled', true);

            jQuery.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'get_group_users',
                    group_name: group_name,
                    nonce: '<?php echo wp_create_nonce('grup_portfolio_sqm_nonce'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        var options = '';
                        var seen = {};
                        response.data.users.forEach(function(user) {
                            if (!seen[user.company]) {
                                seen[user.company] = true;
                                options += '<option value="' + user.id + '">' + user.company + '</option>';
                            }
                        });
                        userSelect.html(options).prop('disabled', false);
                    } else {
                        userSelect.html('<option>Error loading users</option>');
                    }
                },
                error: function() {
                    userSelect.html('<option>Error loading users</option>');
                }
            });
        });

        // Modal logic
        var exampleModal = jQuery('#exampleModal');
        var messageText = jQuery('#message-text');
        var dealerIdField = jQuery('#dealer-id');

        jQuery('#exampleModal').on('show.bs.modal', function(event) {
            var button = jQuery(event.relatedTarget);
            var name = button.data('bs-name');
            var phone = button.data('bs-phone');
            var address = button.data('bs-address');
            var dealerId = button.data('bs-dealer');
            var postcode = button.data('bs-postcode');

            jQuery('.dealer-name').text(name);
            jQuery('.dealer-phone').text(phone);
            jQuery('.dealer-postcode').text(postcode);
            jQuery('.dealer-address').text(address);

            var modalTitle = jQuery(this).find('.modal-title');
            jQuery(this).find('.modal-body input#dealer-name').val(name);
            jQuery(this).find('.modal-body input#dealer-phone').val(phone);
            jQuery(this).find('.modal-body input#dealer-postcode').val(postcode);
            jQuery(this).find('.modal-body input#dealer-address').val(address);
            jQuery(this).find('.modal-body input#dealer-id').val(dealerId);
            modalTitle.text('Notes for ' + name);

            jQuery('.user-message').remove();
            fetchMessages(dealerId);
        });

        jQuery('.btn-primary.send-notes').click(function() {
            var message = messageText.val().trim();
            if (message) {
                sendMessage(dealerIdField.val(), message);
            } else {
                alert('Please enter a message.');
            }
        });

        function fetchMessages(dealerId) {
            jQuery.ajax({
                url: _wpUtilSettings.ajax.url,
                type: 'POST',
                data: {
                    action: 'get_user_messages',
                    user_id: dealerId
                },
                success: function(response) {
                    if (response.success) {
                        displayMessages(response.data);
                    }
                }
            });
        }

        function sendMessage(dealerId, message) {
            jQuery.ajax({
                url: _wpUtilSettings.ajax.url,
                type: 'POST',
                data: {
                    action: 'save_user_message',
                    user_id: dealerId,
                    message: message
                },
                success: function(response) {
                    if (response.success) {
                        messageText.val('');
                        fetchMessages(dealerId);
                    }
                }
            });
        }

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
