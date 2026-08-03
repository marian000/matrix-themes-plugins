<?php
/**
 * Standalone tests for the order reference parser.
 *
 * The project has no test suite, so this runs outside WordPress:
 *
 *     php wp-content/plugins/catchers-helpdesk-premium/tests/test-order-ref-parser.php
 *
 * Exit code 0 = all pass, 1 = at least one failure.
 */

define( 'WPINC', true );

require_once __DIR__ . '/../src/order-ref-parser.php';

$extract_cases = array(
	// Repair notifications - both references in the same subject, same number.
	// See themes/storefront-child/includes/mail-settings.php:488
	array( 'Repair Order LFR24708 for Original Order LF024708', '24708' ),
	array( 'Re:Fwd: Repair Order LFR24708 for Original Order LF024708', '24708' ),
	array( 'Not Under Warranty - Repair Order LFR24332 for Original Order LF024332', '24332' ),
	array( 'Re:Fwd: Not Under Warranty - Repair Order LFR18652 for Original Order LF018652', '18652' ),

	// Repair reference on its own.
	array( 'Repair Order LFR21794', '21794' ),
	array( 'LFR0021794', '21794' ),
	array( 'LF R021794', '21794' ),

	// Plain order references.
	array( 'Query on order LF024708', '24708' ),
	array( 'LF24708', '24708' ),
	array( 'LF0024708', '24708' ),
	array( 'LF 024708', '24708' ),
	array( 'lf024708 lowercase', '24708' ),

	// First reference wins when two different orders appear.
	array( 'LF024708 and LF024520', '24708' ),

	// No usable reference.
	array( 'SELF1234 should not match', null ),
	array( 'MYSELF42', null ),
	array( 'LF0', null ),
	array( 'LF000', null ),
	array( 'No reference at all', null ),
	array( '', null ),
	array( null, null ),
	array( 123, null ),
);

$format_cases = array(
	array( '24708', 'LF024708' ),
	array( '18652', 'LF018652' ),
	// Legacy "R"-prefixed values from the previous parser.
	array( 'R24708', 'LF024708' ),
	array( null, '' ),
	array( '', '' ),
	array( 'R', '' ),
);

$failures = 0;
$total    = 0;

foreach ( $extract_cases as $case ) {
	list( $subject, $expected ) = $case;
	$total++;

	$actual = stgh_extract_order_number_from_subject( $subject );

	if ( $actual !== $expected ) {
		$failures++;
		printf(
			"FAIL extract(%s)\n  expected: %s\n  actual:   %s\n",
			var_export( $subject, true ),
			var_export( $expected, true ),
			var_export( $actual, true )
		);
	}
}

foreach ( $format_cases as $case ) {
	list( $input, $expected ) = $case;
	$total++;

	$actual = stgh_format_order_ref( $input );

	if ( $actual !== $expected ) {
		$failures++;
		printf(
			"FAIL format(%s)\n  expected: %s\n  actual:   %s\n",
			var_export( $input, true ),
			var_export( $expected, true ),
			var_export( $actual, true )
		);
	}
}

// Round trip: every extracted reference formats back to a displayable LF0 ref.
foreach ( $extract_cases as $case ) {
	list( $subject, $expected ) = $case;

	if ( $expected === null ) {
		continue;
	}

	$total++;
	$actual = stgh_format_order_ref( stgh_extract_order_number_from_subject( $subject ) );

	if ( $actual !== 'LF0' . $expected ) {
		$failures++;
		printf(
			"FAIL roundtrip(%s)\n  expected: %s\n  actual:   %s\n",
			var_export( $subject, true ),
			var_export( 'LF0' . $expected, true ),
			var_export( $actual, true )
		);
	}
}

printf( "\n%d/%d passed\n", $total - $failures, $total );

exit( $failures === 0 ? 0 : 1 );
