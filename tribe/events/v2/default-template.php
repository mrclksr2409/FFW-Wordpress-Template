<?php
/**
 * FFW Theme — The Events Calendar default template override.
 * Custom events list with month groupings and ffw-event-card layout.
 * Single event pages use a custom FFW layout (analog zu single-einsatz.php)
 * mit Hero-Bild, Meta-Box, Content und Kalender-Export-Aktionen.
 *
 * @package FFW Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Einzelne Terminseite → individuelles FFW-Layout (analog zu single-einsatz.php)
if ( is_singular( 'tribe_events' ) ) :
	if ( have_posts() ) : the_post();
		$event_id         = get_the_ID();
		$start_ts         = strtotime( tribe_get_start_date( $event_id, false, 'Y-m-d H:i:s' ) );
		$venue_single     = function_exists( 'tribe_get_venue' ) ? tribe_get_venue( $event_id ) : '';
		$ical_link        = function_exists( 'tribe_get_single_ical_link' ) ? tribe_get_single_ical_link() : '';
		$gcal_link        = function_exists( 'tribe_get_gcal_link' ) ? tribe_get_gcal_link() : '';
	?>
	<main id="primary" class="site-main tribe-events-wrapper ffw-event-single">

		<header class="page-header">
			<div class="container">
				<h1 class="page-title"><?php the_title(); ?></h1>

				<?php if ( $start_ts ) : ?>
					<p class="page-description">
						<time datetime="<?php echo esc_attr( date( DATE_W3C, $start_ts ) ); ?>">
							<?php echo esc_html( date_i18n( get_option( 'date_format' ), $start_ts ) ); ?>
						</time>
						<?php if ( $venue_single ) : ?>
							<span class="page-header__sep">·</span>
							<?php echo esc_html( $venue_single ); ?>
						<?php endif; ?>
					</p>
				<?php endif; ?>
			</div>
		</header>

		<div class="container">
			<article id="post-<?php echo esc_attr( $event_id ); ?>" <?php post_class( 'ffw-event-report' ); ?>>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="ffw-event-report__image">
						<?php the_post_thumbnail( 'ffw-hero' ); ?>
					</div>
				<?php endif; ?>

				<?php get_template_part( 'template-parts/event/event-meta' ); ?>

				<?php if ( get_the_content() ) : ?>
					<div class="ffw-event-report__content entry-content">
						<?php the_content(); ?>
					</div>
				<?php endif; ?>

				<?php if ( $ical_link || $gcal_link ) : ?>
				<footer class="ffw-event-report__footer">
					<p class="ffw-event-report__footer-label"><?php esc_html_e( 'Termin speichern', 'ffw-theme' ); ?></p>
					<div class="ffw-event-report__actions">
						<?php if ( $gcal_link ) : ?>
							<a href="<?php echo esc_url( $gcal_link ); ?>" class="btn btn--primary btn--sm" target="_blank" rel="noopener">
								<svg aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
								<?php esc_html_e( 'Google Kalender', 'ffw-theme' ); ?>
							</a>
						<?php endif; ?>
						<?php if ( $ical_link ) : ?>
							<a href="<?php echo esc_url( $ical_link ); ?>" class="btn btn--outline btn--sm" download>
								<svg aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
								<?php esc_html_e( 'iCal-Datei', 'ffw-theme' ); ?>
							</a>
						<?php endif; ?>
					</div>
				</footer>
				<?php endif; ?>

			</article>
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
