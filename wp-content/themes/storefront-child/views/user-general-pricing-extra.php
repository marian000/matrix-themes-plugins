<?php
/**
 * View: Additional Pricing Overrides.
 *
 * Renders input fields for the 9 pricing keys that have global defaults
 * in page-show.php but had no per-user override UI.
 *
 * Available variables from the parent scope:
 *   $user  (WP_User)  -- the user being edited.
 *   $roles (array)    -- $user->roles.
 *
 * @since 1.0.0
 * @package Storefront_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Determine readonly state (employees inherit from parent dealer).
$is_employee = ( in_array( 'employe', $roles, true )
	|| in_array( 'senior_salesman', $roles, true )
	|| in_array( 'salesman', $roles, true ) );
$is_readonly  = $is_employee || ! current_user_can( 'edit_user', $user->ID );
$readonly_attr = $is_readonly ? ' readonly' : '';

// Definition of the 9 missing keys.
// Format: meta_key => array( 'label', 'post_field_gbp', 'post_field_usd' )
$extra_pricing_fields = array(
	'Security_S/S_Hinge' => array(
		'label'    => 'Security S/S Hinge %',
		'post_gbp' => 'price-Security_SS_Hinge',
		'post_usd' => 'price-dolar-Security_SS_Hinge',
	),
	'Hidden_Tilt_with_Louver_Lock' => array(
		'label'    => 'Hidden Tilt with Louver Lock %',
		'post_gbp' => 'price-Hidden_Tilt_with_Louver_Lock',
		'post_usd' => 'price-dolar-Hidden_Tilt_with_Louver_Lock',
	),
	'Louver_lock' => array(
		'label'    => 'Louver Lock +',
		'post_gbp' => 'price-Louver_lock',
		'post_usd' => 'price-dolar-Louver_lock',
	),
	'Central_Lock' => array(
		'label'    => 'Central Lock +',
		'post_gbp' => 'price-Central_Lock',
		'post_usd' => 'price-dolar-Central_Lock',
	),
	'Top_Bottom_Lock' => array(
		'label'    => 'Top/Bottom Lock +',
		'post_gbp' => 'price-Top_Bottom_Lock',
		'post_usd' => 'price-dolar-Top_Bottom_Lock',
	),
	'Light_block' => array(
		'label'    => 'Light Block %',
		'post_gbp' => 'price-Light_block',
		'post_usd' => 'price-dolar-Light_block',
	),
	'motor' => array(
		'label'    => 'Motor +',
		'post_gbp' => 'price-motor',
		'post_usd' => 'price-dolar-motor',
	),
	'remote' => array(
		'label'    => 'Remote +',
		'post_gbp' => 'price-remote',
		'post_usd' => 'price-dolar-remote',
	),
	'panel_width_price' => array(
		'label'    => 'Panel Width < 200 +',
		'post_gbp' => 'price-panel_width_price',
		'post_usd' => 'price-dolar-panel_width_price',
	),
);

// NOTE: panel_width_price USD global is stored under legacy key 'dolar_price-panel_width_price'
// in page-show.php, not 'panel_width_price-dolar'. The placeholder will show empty until
// the global key is normalised (follow-up task). The user override ('panel_width_price-dolar')
// works correctly via PricingConfig::getUsdRate().
?>

<hr>
<h2>Additional Pricing Overrides</h2>
<p class="description">
	Per-user price overrides for locks, accessories and special surcharges.
	Leave a field blank to use the global setting (shown as placeholder).
</p>

<details class="matrix-extra-pricing">
	<summary><strong>Locks, Accessories &amp; Special Surcharges (9 keys)</strong></summary>

	<table class="form-table matrix-extra-pricing-table">
		<thead>
			<tr>
				<th>Field</th>
				<th>GBP Override</th>
				<th>USD Override</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ( $extra_pricing_fields as $meta_key => $field_def ) :
			// Saved user meta values (empty string = no override set).
			$gbp_value = get_user_meta( $user->ID, $meta_key, true );
			$usd_value = get_user_meta( $user->ID, $meta_key . '-dolar', true );

			// Placeholder: global fallback from post_meta(1).
			$gbp_placeholder = get_post_meta( 1, $meta_key, true );
			$usd_placeholder = get_post_meta( 1, $meta_key . '-dolar', true );
		?>
			<tr>
				<td><label><?php echo esc_html( $field_def['label'] ); ?></label></td>
				<td>
					<input
						type="text"
						name="<?php echo esc_attr( $field_def['post_gbp'] ); ?>"
						value="<?php echo esc_attr( $gbp_value ); ?>"
						placeholder="<?php echo esc_attr( $gbp_placeholder ); ?>"
						class="regular-text"
						<?php echo $readonly_attr; ?>
					>
				</td>
				<td>
					<input
						type="text"
						name="<?php echo esc_attr( $field_def['post_usd'] ); ?>"
						value="<?php echo esc_attr( $usd_value ); ?>"
						placeholder="<?php echo esc_attr( $usd_placeholder ); ?>"
						class="regular-text"
						<?php echo $readonly_attr; ?>
					>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

</details>
