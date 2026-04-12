<?php
/**
 * FFW Theme — The Events Calendar list view single event override.
 *
 * @package FFW Theme
 */

use Tribe\Events\Views\V2\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$event           = $this->get( 'event' );
$event_id        = $event->ID;
$start_date_html = tribe_get_start_date( $event_id, false, tribe_get_datetime_format( true ) );
$end_date_html   = tribe_get_end_date( $event_id, false, tribe_get_datetime_format( true ) );
$event_url       = tribe_get_event_link( $event_id );
$image           = tribe_get_event_featured_image( $event_id, 'ffw-thumb', false );
?>
<article <?php tribe_events_the_classes(); ?> class="tribe-event-item tribe-events-calendar-list__event-article ffw-event-card">

	<div class="ffw-event-card__date-badge">
		<span class="ffw-event-card__day"><?php echo esc_html( tribe_get_start_date( $event_id, false, 'd' ) ); ?></span>
		<span class="ffw-event-card__month"><?php echo esc_html( tribe_get_start_date( $event_id, false, 'M' ) ); ?></span>
	</div>

	<div class="ffw-event-card__body">
		<header class="tribe-events-calendar-list__event-header">
			<h3 class="tribe-events-calendar-list__event-title ffw-event-card__title">
				<a href="<?php echo esc_url( $event_url ); ?>" class="url"><?php echo esc_html( get_the_title( $event_id ) ); ?></a>
			</h3>

			<div class="tribe-events-schedule tribe-clearfix ffw-event-card__time">
				<?php echo tribe_events_event_schedule_details( $event_id, '', '', false ); ?>
			</div>

			<?php if ( tribe_get_venue( $event_id ) ) : ?>
				<address class="tribe-events-venue ffw-event-card__venue">
					<svg aria-hidden="true" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
						<circle cx="12" cy="10" r="3"/>
					</svg>
					<?php echo tribe_get_venue( $event_id ); ?>
				</address>
			<?php endif; ?>
		</header>

		<?php if ( $image ) : ?>
			<div class="ffw-event-card__image">
				<a href="<?php echo esc_url( $event_url ); ?>" tabindex="-1" aria-hidden="true">
					<?php echo $image; ?>
				</a>
			</div>
		<?php endif; ?>

		<div class="tribe-events-schedule tribe-clearfix ffw-event-card__actions">
			<a href="<?php echo esc_url( $event_url ); ?>" class="btn btn--outline btn--sm">
				<?php esc_html_e( 'Details', 'ffw-theme' ); ?>
			</a>
		</div>
	</div>
</article>
