<?php
/**
 * FFW Theme — Einsatz Meta Fields Display
 * Reads post meta and taxonomies from the Einsatzverwaltung plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id          = get_the_ID();
$alarmzeit        = get_post_meta( $post_id, 'einsatz_alarmzeit',     true );
$einsatzende      = get_post_meta( $post_id, 'einsatz_einsatzende',   true );
$einsatzort       = get_post_meta( $post_id, 'einsatz_einsatzort',    true );
$einsatzleiter    = get_post_meta( $post_id, 'einsatz_einsatzleiter', true );
$mannschaft       = get_post_meta( $post_id, 'einsatz_mannschaft',    true );
$fehlalarm        = get_post_meta( $post_id, 'einsatz_fehlalarm',     true );
$lrn              = get_post_meta( $post_id, 'einsatz_lrn',           true );

// Taxonomies
$fahrzeuge         = get_the_terms( $post_id, 'fahrzeug' );
$einsatzarten      = get_the_terms( $post_id, 'einsatzart' );
$alarmierungsarten = get_the_terms( $post_id, 'alarmierungsart' );

// ------------------------------------------------------------------
// Group vehicles by evw_unit
//
// Both 'fahrzeug' and 'evw_unit' are taxonomies on the 'einsatz' CPT.
// The association between a specific fahrzeug term and its evw_unit is
// stored as term meta on the fahrzeug term by the Einsatzverwaltung
// plugin. We try the common meta keys the plugin may use.
// ------------------------------------------------------------------

// Build a lookup map of evw_unit terms on this post (term_id => WP_Term)
$units_on_post = array();
$einheiten     = get_the_terms( $post_id, 'evw_unit' );
if ( ! empty( $einheiten ) && ! is_wp_error( $einheiten ) ) {
	foreach ( $einheiten as $unit ) {
		$units_on_post[ $unit->term_id ] = $unit;
	}
}

$vehicles_by_unit = array(); // unit_term_id => fahrzeug[]  (key 0 = ungrouped)
$unit_labels      = array(); // unit_term_id => unit name

if ( ! empty( $fahrzeuge ) && ! is_wp_error( $fahrzeuge ) ) {
	// Term meta keys Einsatzverwaltung may use for the unit association
	$candidate_keys = array( 'unit', 'evw_unit', 'fahrzeug_unit', 'einheit' );

	foreach ( $fahrzeuge as $fahrzeug ) {
		$matched_unit_id = 0;

		foreach ( $candidate_keys as $meta_key ) {
			$val = get_term_meta( $fahrzeug->term_id, $meta_key, true );
			if ( $val ) {
				$val = (int) $val;
				// Accept only if it actually appears as a unit on this post
				if ( $val > 0 && isset( $units_on_post[ $val ] ) ) {
					$matched_unit_id = $val;
					break;
				}
			}
		}

		if ( $matched_unit_id ) {
			$unit_labels[ $matched_unit_id ]      = $units_on_post[ $matched_unit_id ]->name;
			$vehicles_by_unit[ $matched_unit_id ][] = $fahrzeug;
		} else {
			$vehicles_by_unit[0][] = $fahrzeug;
		}
	}

	// Sort: named units alphabetically, ungrouped (0) always last
	uksort( $vehicles_by_unit, function ( $a, $b ) use ( $unit_labels ) {
		if ( $a === 0 ) return 1;
		if ( $b === 0 ) return -1;
		return strcmp( $unit_labels[ $a ] ?? '', $unit_labels[ $b ] ?? '' );
	} );
}

// Show unit headings only when at least 2 distinct named units exist
$has_unit_grouping = count( array_filter( array_keys( $vehicles_by_unit ), fn( $k ) => $k !== 0 ) ) >= 1
	&& ( count( $vehicles_by_unit ) > 1 || ! isset( $vehicles_by_unit[0] ) );
?>
<div class="einsatz-meta-wrapper">

	<?php if ( $fehlalarm ) : ?>
		<div class="einsatz-fehlalarm-badge">
			<?php esc_html_e( 'Fehlalarm', 'ffw-theme' ); ?>
		</div>
	<?php endif; ?>

	<dl class="einsatz-meta">

		<?php if ( $alarmzeit ) :
			$ts = strtotime( $alarmzeit );
		?>
		<div class="einsatz-meta__item">
			<dt>
				<svg class="einsatz-meta__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
				</svg>
				<?php esc_html_e( 'Datum', 'ffw-theme' ); ?>
			</dt>
			<dd><?php echo esc_html( date_i18n( get_option( 'date_format' ), $ts ) ); ?></dd>
		</div>

		<div class="einsatz-meta__item">
			<dt>
				<svg class="einsatz-meta__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
				</svg>
				<?php esc_html_e( 'Alarmzeit', 'ffw-theme' ); ?>
			</dt>
			<dd><?php echo esc_html( date_i18n( get_option( 'time_format' ), $ts ) ); ?> Uhr</dd>
		</div>
		<?php endif; ?>

		<?php if ( $einsatzende ) : ?>
		<div class="einsatz-meta__item">
			<dt>
				<svg class="einsatz-meta__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
				</svg>
				<?php esc_html_e( 'Einsatzende', 'ffw-theme' ); ?>
			</dt>
			<dd><?php echo esc_html( date_i18n( get_option( 'time_format' ), strtotime( $einsatzende ) ) ); ?> Uhr</dd>
		</div>
		<?php endif; ?>

		<?php if ( $einsatzort ) : ?>
		<div class="einsatz-meta__item">
			<dt>
				<svg class="einsatz-meta__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
				</svg>
				<?php esc_html_e( 'Einsatzort', 'ffw-theme' ); ?>
			</dt>
			<dd><?php echo esc_html( $einsatzort ); ?></dd>
		</div>
		<?php endif; ?>

		<?php if ( ! empty( $einsatzarten ) && ! is_wp_error( $einsatzarten ) ) : ?>
		<div class="einsatz-meta__item">
			<dt>
				<svg class="einsatz-meta__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M14.5 10c-.83 0-1.5-.67-1.5-1.5v-5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5v5c0 .83-.67 1.5-1.5 1.5z"/><path d="M20.5 10H19V8.5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
				</svg>
				<?php esc_html_e( 'Einsatzart', 'ffw-theme' ); ?>
			</dt>
			<dd><?php echo esc_html( implode( ', ', wp_list_pluck( $einsatzarten, 'name' ) ) ); ?></dd>
		</div>
		<?php endif; ?>

		<?php if ( ! empty( $alarmierungsarten ) && ! is_wp_error( $alarmierungsarten ) ) : ?>
		<div class="einsatz-meta__item">
			<dt>
				<svg class="einsatz-meta__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>
				</svg>
				<?php esc_html_e( 'Alarmierungsart', 'ffw-theme' ); ?>
			</dt>
			<dd><?php echo esc_html( implode( ', ', wp_list_pluck( $alarmierungsarten, 'name' ) ) ); ?></dd>
		</div>
		<?php endif; ?>

		<?php if ( $mannschaft ) : ?>
		<div class="einsatz-meta__item">
			<dt>
				<svg class="einsatz-meta__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
				</svg>
				<?php esc_html_e( 'Mannschaft', 'ffw-theme' ); ?>
			</dt>
			<dd><?php echo esc_html( $mannschaft ); ?></dd>
		</div>
		<?php endif; ?>

		<?php if ( $einsatzleiter ) : ?>
		<div class="einsatz-meta__item">
			<dt><?php esc_html_e( 'Einsatzleiter', 'ffw-theme' ); ?></dt>
			<dd><?php echo esc_html( $einsatzleiter ); ?></dd>
		</div>
		<?php endif; ?>

		<?php if ( $lrn ) : ?>
		<div class="einsatz-meta__item">
			<dt><?php esc_html_e( 'Lfd. Nr.', 'ffw-theme' ); ?></dt>
			<dd><?php echo esc_html( $lrn ); ?></dd>
		</div>
		<?php endif; ?>

	</dl>

	<?php if ( ! empty( $vehicles_by_unit ) ) : ?>
		<div class="einsatz-fahrzeuge">
			<p class="einsatz-fahrzeuge__label"><?php esc_html_e( 'Eingesetzte Fahrzeuge', 'ffw-theme' ); ?></p>

			<?php foreach ( $vehicles_by_unit as $bucket_id => $bucket_vehicles ) :
				$show_heading = $has_unit_grouping && $bucket_id !== 0 && isset( $unit_labels[ $bucket_id ] );
			?>
				<?php if ( $show_heading ) : ?>
					<p class="einsatz-fahrzeuge__unit-label"><?php echo esc_html( $unit_labels[ $bucket_id ] ); ?></p>
				<?php endif; ?>

				<div class="einsatz-tags einsatz-fahrzeuge__group">
					<?php foreach ( $bucket_vehicles as $fahrzeug ) : ?>
						<a href="<?php echo esc_url( get_term_link( $fahrzeug ) ); ?>" class="einsatz-tag">
							<?php echo esc_html( $fahrzeug->name ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

</div>
