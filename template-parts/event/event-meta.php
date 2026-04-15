<?php
/**
 * FFW Theme — Event Meta Fields Display
 * Reads event data from The Events Calendar plugin and renders it
 * in the FFW meta-wrapper style (analog zu einsatz-meta.php).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'tribe_get_start_date' ) ) {
	return;
}

$event_id   = get_the_ID();
$start_ts   = strtotime( tribe_get_start_date( $event_id, false, 'Y-m-d H:i:s' ) );
$end_ts     = strtotime( tribe_get_end_date( $event_id, false, 'Y-m-d H:i:s' ) );
$all_day    = function_exists( 'tribe_event_is_all_day' ) ? tribe_event_is_all_day( $event_id ) : false;
$multi_day  = function_exists( 'tribe_event_is_multiday' ) ? tribe_event_is_multiday( $event_id ) : false;

$time_fmt   = get_option( 'time_format' );
$date_fmt   = get_option( 'date_format' );

// Ensure the "Datum" row never duplicates the time — strip any time-format
// tokens (and leftover separators) so the time only appears in the "Uhrzeit" row.
$date_fmt = preg_replace( '/[aAgGhHisu]/', '', $date_fmt );
$date_fmt = preg_replace( '/[,;:\-]\s*$/', '', $date_fmt );
$date_fmt = trim( preg_replace( '/\s{2,}/', ' ', $date_fmt ) );
if ( '' === $date_fmt ) {
	$date_fmt = 'j. F Y';
}

$venue_id       = function_exists( 'tribe_get_venue_id' ) ? tribe_get_venue_id( $event_id ) : 0;
$venue          = $venue_id && function_exists( 'tribe_get_venue' ) ? tribe_get_venue( $event_id ) : '';
$venue_address  = $venue_id && function_exists( 'tribe_get_full_address' ) ? tribe_get_full_address( $event_id ) : '';
$venue_map_link = $venue_id && function_exists( 'tribe_get_map_link' ) ? tribe_get_map_link( $event_id ) : '';

$organizer_ids = function_exists( 'tribe_get_organizer_ids' ) ? tribe_get_organizer_ids( $event_id ) : array();

$cost          = function_exists( 'tribe_get_cost' ) ? tribe_get_cost( $event_id, true ) : '';
$website_url   = function_exists( 'tribe_get_event_website_url' ) ? tribe_get_event_website_url( $event_id ) : '';
$event_cats    = get_the_terms( $event_id, 'tribe_events_cat' );
$event_tags    = get_the_terms( $event_id, 'post_tag' );

$is_past       = function_exists( 'tribe_is_past_event' ) ? tribe_is_past_event( $event_id ) : ( $end_ts && $end_ts < current_time( 'timestamp' ) );
$is_recurring  = function_exists( 'tribe_is_recurring_event' ) ? tribe_is_recurring_event( $event_id ) : false;
?>
<div class="einsatz-meta-wrapper ffw-event-meta-wrapper">

	<?php if ( $is_past || $is_recurring ) : ?>
		<div class="ffw-event-badges">
			<?php if ( $is_past ) : ?>
				<span class="ffw-event-badge ffw-event-badge--past"><?php esc_html_e( 'Vergangen', 'ffw-theme' ); ?></span>
			<?php endif; ?>
			<?php if ( $is_recurring ) : ?>
				<span class="ffw-event-badge ffw-event-badge--recurring"><?php esc_html_e( 'Wiederkehrend', 'ffw-theme' ); ?></span>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<dl class="einsatz-meta ffw-event-meta">

		<?php if ( $start_ts ) : ?>
		<div class="einsatz-meta__item">
			<dt>
				<svg class="einsatz-meta__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
				</svg>
				<?php esc_html_e( 'Datum', 'ffw-theme' ); ?>
			</dt>
			<dd>
				<?php
				if ( $multi_day && $end_ts ) {
					printf(
						/* translators: 1: start date, 2: end date */
						esc_html__( '%1$s – %2$s', 'ffw-theme' ),
						esc_html( date_i18n( $date_fmt, $start_ts ) ),
						esc_html( date_i18n( $date_fmt, $end_ts ) )
					);
				} else {
					echo esc_html( date_i18n( $date_fmt, $start_ts ) );
				}
				?>
			</dd>
		</div>
		<?php endif; ?>

		<?php if ( $start_ts && ! $all_day ) : ?>
		<div class="einsatz-meta__item">
			<dt>
				<svg class="einsatz-meta__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
				</svg>
				<?php esc_html_e( 'Uhrzeit', 'ffw-theme' ); ?>
			</dt>
			<dd>
				<?php
				echo esc_html( date_i18n( $time_fmt, $start_ts ) );
				if ( $end_ts && $end_ts !== $start_ts ) {
					echo ' – ' . esc_html( date_i18n( $time_fmt, $end_ts ) );
				}
				?> <?php esc_html_e( 'Uhr', 'ffw-theme' ); ?>
			</dd>
		</div>
		<?php elseif ( $all_day ) : ?>
		<div class="einsatz-meta__item">
			<dt>
				<svg class="einsatz-meta__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
				</svg>
				<?php esc_html_e( 'Uhrzeit', 'ffw-theme' ); ?>
			</dt>
			<dd><?php esc_html_e( 'Ganztägig', 'ffw-theme' ); ?></dd>
		</div>
		<?php endif; ?>

		<?php if ( $venue ) : ?>
		<div class="einsatz-meta__item einsatz-meta__item--wide">
			<dt>
				<svg class="einsatz-meta__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
				</svg>
				<?php esc_html_e( 'Veranstaltungsort', 'ffw-theme' ); ?>
			</dt>
			<dd>
				<strong><?php echo esc_html( $venue ); ?></strong>
				<?php if ( $venue_address ) : ?>
					<span class="ffw-event-meta__address"><?php echo wp_kses_post( $venue_address ); ?></span>
				<?php endif; ?>
				<?php if ( $venue_map_link ) : ?>
					<a href="<?php echo esc_url( $venue_map_link ); ?>" class="ffw-event-meta__map-link" target="_blank" rel="noopener">
						<?php esc_html_e( 'Auf Karte anzeigen', 'ffw-theme' ); ?>
						<svg aria-hidden="true" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 17L17 7"/><polyline points="7 7 17 7 17 17"/></svg>
					</a>
				<?php endif; ?>
			</dd>
		</div>
		<?php endif; ?>

		<?php if ( ! empty( $organizer_ids ) ) : ?>
		<div class="einsatz-meta__item">
			<dt>
				<svg class="einsatz-meta__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
				</svg>
				<?php esc_html_e( 'Veranstalter', 'ffw-theme' ); ?>
			</dt>
			<dd>
				<?php
				$organizer_names = array();
				foreach ( $organizer_ids as $oid ) {
					$organizer_names[] = esc_html( tribe_get_organizer( $oid ) );
				}
				echo implode( ', ', $organizer_names );
				?>
			</dd>
		</div>
		<?php endif; ?>

		<?php if ( $cost ) : ?>
		<div class="einsatz-meta__item">
			<dt>
				<svg class="einsatz-meta__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
				</svg>
				<?php esc_html_e( 'Kosten', 'ffw-theme' ); ?>
			</dt>
			<dd><?php echo esc_html( $cost ); ?></dd>
		</div>
		<?php endif; ?>

		<?php if ( $website_url ) : ?>
		<div class="einsatz-meta__item">
			<dt>
				<svg class="einsatz-meta__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
				</svg>
				<?php esc_html_e( 'Webseite', 'ffw-theme' ); ?>
			</dt>
			<dd>
				<a href="<?php echo esc_url( $website_url ); ?>" target="_blank" rel="noopener">
					<?php echo esc_html( preg_replace( '#^https?://(www\.)?#', '', untrailingslashit( $website_url ) ) ); ?>
				</a>
			</dd>
		</div>
		<?php endif; ?>

	</dl>

	<?php if ( ! empty( $event_cats ) && ! is_wp_error( $event_cats ) ) : ?>
		<div class="einsatz-fahrzeuge ffw-event-terms">
			<p class="einsatz-fahrzeuge__label"><?php esc_html_e( 'Kategorien', 'ffw-theme' ); ?></p>
			<div class="einsatz-tags">
				<?php foreach ( $event_cats as $cat ) : ?>
					<a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="einsatz-tag einsatz-tag--outline">
						<?php echo esc_html( $cat->name ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $event_tags ) && ! is_wp_error( $event_tags ) ) : ?>
		<div class="einsatz-fahrzeuge einsatz-fahrzeuge--ext ffw-event-terms">
			<p class="einsatz-fahrzeuge__label"><?php esc_html_e( 'Schlagwörter', 'ffw-theme' ); ?></p>
			<div class="einsatz-tags">
				<?php foreach ( $event_tags as $tag ) : ?>
					<a href="<?php echo esc_url( get_term_link( $tag ) ); ?>" class="einsatz-tag einsatz-tag--outline">
						<?php echo esc_html( $tag->name ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

</div>
