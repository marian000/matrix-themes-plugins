<?php
/**
 * View: Per-Material Pricing Overrides.
 *
 * Included from user-editinfo.php via include_once.
 * Renders an accordion (HTML5 <details>) with one panel per material,
 * each containing a table of GBP/USD surcharge inputs.
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

/* ─── Dependencies ────────────────────────────────────────────────────── */

require_once get_stylesheet_directory() . '/includes/material-pricing-keys.php';

/* ─── Data Setup ──────────────────────────────────────────────────────── */

$materials      = matrix_get_material_ids();
$surcharge_keys = matrix_get_per_material_option_keys();

// Keys that have no USD (-dolar) variant.
$no_usd_keys = matrix_get_per_material_usd_excludes();

// Determine if the inputs should be readonly (employees inherit from parent dealer).
$is_employee = ( in_array( 'employe', $roles, true )
	|| in_array( 'senior_salesman', $roles, true )
	|| in_array( 'salesman', $roles, true ) );
$is_readonly = $is_employee || ! current_user_can( 'edit_user', $user->ID );
$readonly_attr = $is_readonly ? ' readonly' : '';

// Human-readable labels for each surcharge option key.
$option_labels = matrix_get_per_material_option_labels();
?>

<style>
	.matrix-material-pricing details summary {
		cursor: pointer;
		padding: 8px 0;
		font-weight: 600;
		font-size: 14px;
	}
	.matrix-material-pricing details summary:hover {
		color: #0073aa;
	}
	.matrix-material-pricing .form-table th {
		width: 200px;
		padding: 8px 10px;
	}
	.matrix-material-pricing .form-table td {
		padding: 8px 10px;
	}
	.matrix-material-pricing .form-table thead th {
		font-weight: 600;
		border-bottom: 1px solid #c3c4c7;
	}
	.matrix-material-pricing input.readonly-field {
		background-color: #f0f0f1;
		color: #50575e;
	}
</style>

<div class="matrix-material-pricing">

<h3><?php echo esc_html( 'Per-Material Pricing Options' ); ?></h3>
<p class="description">
	Set per-material surcharge overrides for this user. Leave blank to inherit the general user price.
</p>

<?php if ( $is_employee ) : ?>
	<p><em>Per-material pricing is inherited from the parent dealer and cannot be edited directly.</em></p>
<?php endif; ?>

<?php foreach ( $materials as $material_id => $material_name ) : ?>
	<details>
		<summary>
			<strong><?php echo esc_html( $material_name . ' (ID: ' . $material_id . ')' ); ?></strong>
		</summary>
		<table class="form-table">
			<thead>
				<tr>
					<th><?php echo esc_html( 'Option' ); ?></th>
					<th><?php echo esc_html( 'GBP Price' ); ?> (&pound;)</th>
					<th><?php echo esc_html( 'USD Price' ); ?> ($)</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ( $surcharge_keys as $option_key ) :
				// Build meta keys.
				$gbp_meta_key = matrix_get_per_material_meta_key( $material_id, $option_key, 'gbp' );
				$usd_meta_key = matrix_get_per_material_meta_key( $material_id, $option_key, 'usd' );

				// Current saved values.
				$gbp_value = get_user_meta( $user->ID, $gbp_meta_key, true );
				$usd_value = get_user_meta( $user->ID, $usd_meta_key, true );

				// Placeholder: the rate that actually applies when this field is left blank.
				// Mirrors PricingConfig::getPerMaterialRate() fallback order:
				// Level 2 (user general) > Level 3 (per-material global) > Level 4 (general global).
				$gbp_placeholder = get_user_meta( $user->ID, $option_key, true );
				if ( $gbp_placeholder === '' ) {
					$gbp_placeholder = get_post_meta( 1, $gbp_meta_key, true );
				}
				if ( $gbp_placeholder === '' ) {
					$gbp_placeholder = get_post_meta( 1, $option_key, true );
				}
				// G_post without any configured rate (L2, L3, L4) falls back to a hardcoded 3%.
				if ( $gbp_placeholder === '' && $option_key === 'G_post' ) {
					$gbp_placeholder = '3';
				}

				$usd_placeholder = get_user_meta( $user->ID, $option_key . '-dolar', true );
				if ( $usd_placeholder === '' ) {
					$usd_placeholder = get_post_meta( 1, $usd_meta_key, true );
				}
				if ( $usd_placeholder === '' ) {
					$usd_placeholder = get_post_meta( 1, $option_key . '-dolar', true );
				}

				// Display label.
				$label = isset( $option_labels[ $option_key ] ) ? $option_labels[ $option_key ] : $option_key;

				// POST field names.
				$gbp_field_name = 'mat_price-' . $material_id . '-' . $option_key;
				$usd_field_name = 'mat_price_dolar-' . $material_id . '-' . $option_key;

				// Is this key excluded from USD?
				$has_usd = ! in_array( $option_key, $no_usd_keys, true );

				// CSS class for readonly.
				$input_class = $is_readonly ? 'small-text readonly-field' : 'small-text';
			?>
				<tr>
					<th>
						<label for="<?php echo esc_attr( $gbp_field_name ); ?>">
							<?php echo esc_html( $label ); ?>
						</label>
					</th>
					<td>
						<input type="number"
						       step="0.01"
						       min="0"
						       id="<?php echo esc_attr( $gbp_field_name ); ?>"
						       name="<?php echo esc_attr( $gbp_field_name ); ?>"
						       value="<?php echo esc_attr( $gbp_value ); ?>"
						       placeholder="<?php echo esc_attr( $gbp_placeholder ); ?>"
						       class="<?php echo esc_attr( $input_class ); ?>"
						       <?php echo $readonly_attr; // Already a safe literal string. ?> />
					</td>
					<td>
						<?php if ( $has_usd ) : ?>
						<input type="number"
						       step="0.01"
						       min="0"
						       id="<?php echo esc_attr( $usd_field_name ); ?>"
						       name="<?php echo esc_attr( $usd_field_name ); ?>"
						       value="<?php echo esc_attr( $usd_value ); ?>"
						       placeholder="<?php echo esc_attr( $usd_placeholder ); ?>"
						       class="<?php echo esc_attr( $input_class ); ?>"
						       <?php echo $readonly_attr; ?> />
						<?php else : ?>
						<span class="description">&mdash;</span>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</details>
<?php endforeach; ?>

</div>
