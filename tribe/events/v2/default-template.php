<?php
/**
 * FFW Theme — The Events Calendar default template override.
 * Custom events list with month groupings and ffw-event-card layout.
 * Single event pages are rendered via TEC's own view to preserve detail pages.
 *
 * @package FFW Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Einzelne Terminseite → TEC's eigenes Rendering nutzen (Detail-Ansicht)
if ( is_singular( 'tribe_events' ) ) :
	if ( have_posts() ) : the_post(); ?>
	<main id="primary" class="site-main tribe-events-wrapper">
		<header class="page-header">
			<div class="container">
				<h1 class="page-title"><?php the_title(); ?></h1>
			</div>
		</header>
		<div class="container">
			<?php tribe_events_before_html(); ?>
			<?php tribe_get_view(); ?>
			<?php tribe_events_after_html(); ?>
		</div>
	</main>
	<?php endif;
	get_footer();
	return;
endif;

if ( function_exists( 'tribe_get_events' ) ) {
	$events = tribe_get_events( array(
		'posts_per_page' => 100,
		'ends_after'     => 'now',
		'order'          => 'ASC',
	) );
} else {
	$events = array();
}
?>
<main id="primary" class="site-main tribe-events-wrapper">

	<header class="page-header">
		<div class="container">
			<div class="page-header__inner">
				<div>
					<h1 class="page-title"><?php esc_html_e( 'Termine & Veranstaltungen', 'ffw-theme' ); ?></h1>
					<p class="page-description"><?php esc_html_e( 'Übungen, Veranstaltungen und Termine der Feuerwehr', 'ffw-theme' ); ?></p>
				</div>
				<?php if ( function_exists( 'tribe_get_ical_link' ) ) : ?>
				<a href="<?php echo esc_url( tribe_get_ical_link() ); ?>" class="btn btn--outline btn--sm" download>
					<svg viewBox="0 0 16 16" width="16" height="16" fill="none" aria-hidden="true"><path d="M8 1v9M4.5 6.5 8 10l3.5-3.5M3 13h10" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
					<?php esc_html_e( 'Kalender herunterladen', 'ffw-theme' ); ?>
				</a>
				<?php endif; ?>
			</div>
		</div>
	</header>

	<div class="container events-list-container">

		<?php if ( ! empty( $events ) ) : ?>

			<?php
			$active_month = null;

			foreach ( $events as $event ) :
				$event_id   = $event->ID;
				$event_link = tribe_get_event_link( $event_id );
				$start_ts   = strtotime( tribe_get_start_date( $event_id, false, 'Y-m-d H:i:s' ) );
				$end_time   = tribe_get_end_date( $event_id, false, 'H:i' );
				$start_time = tribe_get_start_date( $event_id, false, 'H:i' );
				$venue      = function_exists( 'tribe_get_venue' ) ? tribe_get_venue( $event_id ) : '';
				$event_cats = get_the_terms( $event_id, 'tribe_events_cat' );

				// Monats-Gruppierung
				$month_key = date( 'Y-m', $start_ts );
				if ( $month_key !== $active_month ) {
					if ( null !== $active_month ) {
						echo '</div>'; // vorherigen Monatsblock schließen
					}
					$active_month = $month_key;
					echo '<h2 class="ffw-month-heading">' . esc_html( date_i18n( 'F Y', $start_ts ) ) . '</h2>';
					echo '<div class="ffw-events-list">';
				}
			?>
			<article class="ffw-event-card">
				<div class="ffw-event-card__date">
					<span class="ffw-event-card__day"><?php echo esc_html( date_i18n( 'j', $start_ts ) ); ?></span>
					<span class="ffw-event-card__month"><?php echo esc_html( date_i18n( 'M Y', $start_ts ) ); ?></span>
				</div>
				<div class="ffw-event-card__body">
					<h3 class="ffw-event-card__title">
						<a href="<?php echo esc_url( $event_link ); ?>"><?php echo esc_html( get_the_title( $event_id ) ); ?></a>
					</h3>
					<div class="ffw-event-card__meta">
						<span class="ffw-event-card__time">
							<svg viewBox="0 0 16 16" width="14" height="14" fill="none" aria-hidden="true"><circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.3"/><path d="M8 4.5V8l2.5 2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
							<?php echo esc_html( $start_time );
							if ( $end_time && $end_time !== $start_time ) echo ' – ' . esc_html( $end_time ); ?>
						</span>
						<?php if ( $venue ) : ?>
							<span class="ffw-event-card__venue">
								<svg viewBox="0 0 16 16" width="14" height="14" fill="none" aria-hidden="true"><path d="M8 1.5C5.515 1.5 3.5 3.515 3.5 6c0 3.5 4.5 8.5 4.5 8.5s4.5-5 4.5-8.5c0-2.485-2.015-4.5-4.5-4.5Z" stroke="currentColor" stroke-width="1.3"/><circle cx="8" cy="6" r="1.5" stroke="currentColor" stroke-width="1.3"/></svg>
								<?php echo esc_html( $venue ); ?>
							</span>
						<?php endif; ?>
					</div>
					<?php if ( ! empty( $event_cats ) && ! is_wp_error( $event_cats ) ) : ?>
						<div class="ffw-event-card__cats">
							<?php foreach ( $event_cats as $cat ) : ?>
								<span class="tag"><?php echo esc_html( $cat->name ); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
				<a href="<?php echo esc_url( $event_link ); ?>" class="ffw-event-card__link btn btn--outline btn--sm">
					<?php esc_html_e( 'Details', 'ffw-theme' ); ?>
				</a>
			</article>
			<?php
			endforeach;

			if ( null !== $active_month ) {
				echo '</div>'; // letzten Monatsblock schließen
			}
			?>

		<?php else : ?>
			<div class="events-empty">
				<p><?php esc_html_e( 'Aktuell sind keine Termine geplant.', 'ffw-theme' ); ?></p>
			</div>
		<?php endif; ?>

		<?php if ( ! function_exists( 'tribe_get_events' ) && current_user_can( 'manage_options' ) ) : ?>
			<div class="admin-notice">
				<p><?php esc_html_e( 'Hinweis: Das Plugin "The Events Calendar" ist nicht aktiv.', 'ffw-theme' ); ?></p>
			</div>
		<?php endif; ?>

	</div>

</main>

<?php
get_footer();
