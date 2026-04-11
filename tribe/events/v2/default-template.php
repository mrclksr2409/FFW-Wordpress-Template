<?php
/**
 * FFW Theme — The Events Calendar default template override.
 * Custom events list with month groupings and ffw-event-card layout.
 *
 * @package FFW Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Vergangene oder kommende Termine anzeigen?
$show_past = isset( $_GET['past'] ) && '1' === $_GET['past'];

if ( function_exists( 'tribe_get_events' ) ) {
	if ( $show_past ) {
		$events = tribe_get_events( array(
			'posts_per_page' => 100,
			'ends_before'    => 'now',
			'order'          => 'DESC',
		) );
	} else {
		$events = tribe_get_events( array(
			'posts_per_page' => 100,
			'ends_after'     => 'now',
			'order'          => 'ASC',
		) );
	}
} else {
	$events = array();
}
?>
<main id="primary" class="site-main tribe-events-wrapper">

	<header class="page-header">
		<div class="container">
			<h1 class="page-title"><?php esc_html_e( 'Termine & Veranstaltungen', 'ffw-theme' ); ?></h1>
			<p class="page-description"><?php esc_html_e( 'Übungen, Veranstaltungen und Termine der Feuerwehr', 'ffw-theme' ); ?></p>
		</div>
	</header>

	<div class="container">

		<!-- Kommende / Vergangene Umschalter -->
		<div class="events-view-toggle">
			<a href="<?php echo esc_url( tribe_get_events_link() ); ?>"
			   class="btn btn--sm <?php echo ! $show_past ? 'btn--primary' : 'btn--outline'; ?>">
				<?php esc_html_e( 'Kommende Termine', 'ffw-theme' ); ?>
			</a>
			<a href="<?php echo esc_url( add_query_arg( 'past', '1', tribe_get_events_link() ) ); ?>"
			   class="btn btn--sm <?php echo $show_past ? 'btn--primary' : 'btn--outline'; ?>">
				<?php esc_html_e( 'Vergangene Termine', 'ffw-theme' ); ?>
			</a>
		</div>

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
					echo '<h2 class="einsatz-month-heading">' . esc_html( date_i18n( 'F Y', $start_ts ) ) . '</h2>';
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
				<?php if ( $show_past ) : ?>
					<p><?php esc_html_e( 'Keine vergangenen Termine vorhanden.', 'ffw-theme' ); ?></p>
				<?php else : ?>
					<p><?php esc_html_e( 'Aktuell sind keine Termine geplant.', 'ffw-theme' ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! function_exists( 'tribe_get_events' ) && current_user_can( 'manage_options' ) ) : ?>
			<div class="admin-notice">
				<p><?php esc_html_e( 'Hinweis: Das Plugin "The Events Calendar" ist nicht aktiv.', 'ffw-theme' ); ?></p>
			</div>
		<?php endif; ?>

	</div>

</main>

<style>
/* ===== Events-Seite ===== */

.events-view-toggle {
	display: flex;
	gap: var(--ffw-spacing-sm);
	margin: var(--ffw-spacing-lg) 0 var(--ffw-spacing-md);
}

/* Monatsüberschriften (geteilt mit Einsatz-Archiv) */
.einsatz-month-heading {
	font-family: var(--ffw-font-heading);
	font-size: 1.2rem;
	font-weight: 600;
	color: var(--ffw-text-muted);
	text-transform: uppercase;
	letter-spacing: 0.08em;
	margin: var(--ffw-spacing-xl) 0 var(--ffw-spacing-md);
	padding-bottom: var(--ffw-spacing-sm);
	border-bottom: 2px solid var(--ffw-color-primary);
}

.einsatz-month-heading:first-of-type {
	margin-top: var(--ffw-spacing-lg);
}

/* Event-Card Layout */
.ffw-events-list {
	display: flex;
	flex-direction: column;
	gap: var(--ffw-spacing-md);
}

.ffw-event-card {
	display: flex;
	align-items: center;
	gap: var(--ffw-spacing-lg);
	background: var(--ffw-bg-card);
	border: 1px solid var(--ffw-border-color);
	border-radius: var(--ffw-border-radius-lg);
	padding: var(--ffw-spacing-md) var(--ffw-spacing-lg);
	transition: box-shadow var(--ffw-transition), transform var(--ffw-transition);
}

.ffw-event-card:hover {
	box-shadow: var(--ffw-shadow-hover);
	transform: translateY(-2px);
}

.ffw-event-card__date {
	flex-shrink: 0;
	text-align: center;
	background: var(--ffw-color-primary);
	color: #fff;
	border-radius: var(--ffw-border-radius);
	padding: var(--ffw-spacing-sm) var(--ffw-spacing-md);
	min-width: 3.5rem;
}

.ffw-event-card__day {
	display: block;
	font-family: var(--ffw-font-heading);
	font-size: 1.8rem;
	font-weight: 700;
	line-height: 1;
}

.ffw-event-card__month {
	display: block;
	font-size: 0.7rem;
	text-transform: uppercase;
	letter-spacing: 0.05em;
	opacity: 0.9;
	margin-top: 0.2rem;
}

.ffw-event-card__body {
	flex: 1;
	min-width: 0;
}

.ffw-event-card__title {
	font-family: var(--ffw-font-heading);
	font-size: 1.1rem;
	margin: 0 0 var(--ffw-spacing-xs);
}

.ffw-event-card__title a {
	color: var(--ffw-text-primary);
	text-decoration: none;
}

.ffw-event-card__title a:hover {
	color: var(--ffw-color-primary);
}

.ffw-event-card__meta {
	display: flex;
	flex-wrap: wrap;
	gap: var(--ffw-spacing-md);
	font-size: 0.85rem;
	color: var(--ffw-text-muted);
}

.ffw-event-card__time,
.ffw-event-card__venue {
	display: flex;
	align-items: center;
	gap: 0.3rem;
}

.ffw-event-card__cats {
	margin-top: var(--ffw-spacing-xs);
	display: flex;
	flex-wrap: wrap;
	gap: 0.4rem;
}

.ffw-event-card__link {
	flex-shrink: 0;
	white-space: nowrap;
}

.events-empty {
	padding: var(--ffw-spacing-xl) 0;
	color: var(--ffw-text-muted);
}

@media (max-width: 600px) {
	.ffw-event-card {
		flex-wrap: wrap;
	}
	.ffw-event-card__link {
		width: 100%;
		text-align: center;
	}
}
</style>

<?php
get_footer();
