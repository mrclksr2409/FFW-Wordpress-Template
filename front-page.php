<?php
/**
 * FFW Theme — Homepage template.
 * Elementor Pro can fully override this via Theme Builder.
 */

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) :
	$hero_title    = get_theme_mod( 'ffw_hero_title', get_bloginfo( 'name' ) );
	$hero_subtitle = get_theme_mod( 'ffw_hero_subtitle', get_bloginfo( 'description' ) );
	$hero_image    = get_theme_mod( 'ffw_hero_image', '' );
	$hero_cta_text = get_theme_mod( 'ffw_hero_cta_text', __( 'Einsätze ansehen', 'ffw-theme' ) );
	$hero_cta_url  = get_theme_mod( 'ffw_hero_cta_url', get_post_type_archive_link( 'einsatz' ) );
?>
<main id="primary" class="site-main homepage">

	<!-- ===== HERO SECTION ===== -->
	<section class="hero-section" <?php if ( $hero_image ) : ?>style="background-image: url('<?php echo esc_url( $hero_image ); ?>')"<?php endif; ?>>
		<div class="hero-overlay"></div>
		<div class="hero-content container">
			<div class="hero-badge"><?php esc_html_e( 'Freiwillige Feuerwehr', 'ffw-theme' ); ?></div>
			<h1 class="hero-title"><?php echo esc_html( $hero_title ); ?></h1>
			<?php if ( $hero_subtitle ) : ?>
				<p class="hero-subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
			<?php endif; ?>
			<div class="hero-actions">
				<?php if ( $hero_cta_url ) : ?>
					<a href="<?php echo esc_url( $hero_cta_url ); ?>" class="btn btn--primary btn--lg">
						<?php echo esc_html( $hero_cta_text ); ?>
					</a>
				<?php endif; ?>
				<a href="tel:112" class="btn btn--outline btn--lg">
					<?php esc_html_e( 'Notruf 112', 'ffw-theme' ); ?>
				</a>
			</div>
		</div>
		<div class="hero-scroll-hint" aria-hidden="true">
			<span></span>
		</div>
	</section>

	<!-- ===== STATS BAR ===== -->
	<?php
	// Auto: Fahrzeuge aus der 'fahrzeug'-Taxonomy zählen (Einsatzverwaltung)
	$auto_vehicle_count = 0;
	if ( taxonomy_exists( 'fahrzeug' ) ) {
		$vehicle_terms = get_terms( array( 'taxonomy' => 'fahrzeug', 'hide_empty' => false ) );
		if ( ! is_wp_error( $vehicle_terms ) ) {
			$auto_vehicle_count = count( $vehicle_terms );
		}
	}

	// Auto: Einsätze im aktuellen Jahr zählen (Einsatzverwaltung CPT 'einsatz')
	$auto_year_count = 0;
	if ( post_type_exists( 'einsatz' ) ) {
		$year_query = new WP_Query( array(
			'post_type'      => 'einsatz',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'no_found_rows'  => false,
			'date_query'     => array( array( 'year' => (int) date( 'Y' ) ) ),
		) );
		$auto_year_count = $year_query->found_posts;
		wp_reset_postdata();
	}
	?>
	<section class="stats-bar" aria-label="<?php esc_attr_e( 'Kurzübersicht', 'ffw-theme' ); ?>">
		<div class="container">
			<div class="stats-grid">
				<div class="stat-item">
					<span class="stat-number"><?php echo esc_html( get_theme_mod( 'ffw_stat_members', '60+' ) ); ?></span>
					<span class="stat-label"><?php esc_html_e( 'Aktive Mitglieder', 'ffw-theme' ); ?></span>
				</div>
				<div class="stat-item">
					<span class="stat-number"><?php echo $auto_vehicle_count > 0 ? esc_html( $auto_vehicle_count ) : esc_html( get_theme_mod( 'ffw_stat_vehicles', '5' ) ); ?></span>
					<span class="stat-label"><?php esc_html_e( 'Fahrzeuge', 'ffw-theme' ); ?></span>
				</div>
				<div class="stat-item">
					<span class="stat-number"><?php echo $auto_year_count > 0 ? esc_html( $auto_year_count ) : esc_html( get_theme_mod( 'ffw_stat_operations', '100+' ) ); ?></span>
					<span class="stat-label"><?php echo esc_html( sprintf( __( 'Einsätze %d', 'ffw-theme' ), (int) date( 'Y' ) ) ); ?></span>
				</div>
				<div class="stat-item">
					<span class="stat-number">24/7</span>
					<span class="stat-label"><?php esc_html_e( 'Einsatzbereit', 'ffw-theme' ); ?></span>
				</div>
			</div>
		</div>
	</section>

	<!-- ===== LATEST OPERATIONS ===== -->
	<?php
	$einsatz_query = new WP_Query(
		array(
			'post_type'      => 'einsatz',
			'posts_per_page' => 4,
			'post_status'    => 'publish',
		)
	);

	if ( $einsatz_query->have_posts() ) :
	?>
	<section class="home-section home-section--einsaetze">
		<div class="container">
			<div class="section-heading">
				<div>
					<p class="section-label"><?php esc_html_e( 'Aktuell', 'ffw-theme' ); ?></p>
					<h2><?php esc_html_e( 'Letzte Einsätze', 'ffw-theme' ); ?></h2>
				</div>
			</div>

			<div class="ffw-events-list">
				<?php
				while ( $einsatz_query->have_posts() ) :
					$einsatz_query->the_post();
					$e_id         = get_the_ID();
					$alarmzeit    = get_post_meta( $e_id, 'einsatz_alarmzeit', true );
					$einsatzort   = get_post_meta( $e_id, 'einsatz_einsatzort', true );
					$fehlalarm    = get_post_meta( $e_id, 'einsatz_fehlalarm', true );
					$einsatzarten = get_the_terms( $e_id, 'einsatzart' );
					$fahrzeuge    = get_the_terms( $e_id, 'fahrzeug' );
					$ts           = $alarmzeit ? strtotime( $alarmzeit ) : get_the_date( 'U' );
				?>
				<article class="ffw-event-card ffw-event-card--einsatz">
					<div class="ffw-event-card__date">
						<span class="ffw-event-card__day"><?php echo esc_html( date_i18n( 'j', $ts ) ); ?></span>
						<span class="ffw-event-card__month"><?php echo esc_html( date_i18n( 'M Y', $ts ) ); ?></span>
					</div>
					<div class="ffw-event-card__body">
						<h3 class="ffw-event-card__title">
							<?php if ( $fehlalarm ) : ?>
								<span class="einsatz-tag einsatz-tag--fehlalarm"><?php esc_html_e( 'Fehlalarm', 'ffw-theme' ); ?></span>
							<?php endif; ?>
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
						<div class="ffw-event-card__meta">
							<?php if ( ! empty( $einsatzarten ) && ! is_wp_error( $einsatzarten ) ) : ?>
								<span class="ffw-event-card__type">
									<svg viewBox="0 0 16 16" width="14" height="14" fill="none" aria-hidden="true"><path d="M8 1.5l1.8 3.6 4 .6-2.9 2.8.7 4-3.6-1.9L4.4 12.5l.7-4L2.2 5.7l4-.6Z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>
									<?php echo esc_html( $einsatzarten[0]->name ); ?>
								</span>
							<?php endif; ?>
							<?php if ( $alarmzeit ) : ?>
								<span class="ffw-event-card__time">
									<svg viewBox="0 0 16 16" width="14" height="14" fill="none" aria-hidden="true"><circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.3"/><path d="M8 4.5V8l2.5 2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
									<?php echo esc_html( date_i18n( 'H:i', strtotime( $alarmzeit ) ) ); ?>
								</span>
							<?php endif; ?>
							<?php if ( $einsatzort ) : ?>
								<span class="ffw-event-card__venue">
									<svg viewBox="0 0 16 16" width="14" height="14" fill="none" aria-hidden="true"><path d="M8 1.5C5.515 1.5 3.5 3.515 3.5 6c0 3.5 4.5 8.5 4.5 8.5s4.5-5 4.5-8.5c0-2.485-2.015-4.5-4.5-4.5Z" stroke="currentColor" stroke-width="1.3"/><circle cx="8" cy="6" r="1.5" stroke="currentColor" stroke-width="1.3"/></svg>
									<?php echo esc_html( $einsatzort ); ?>
								</span>
							<?php endif; ?>
						</div>
						<?php if ( ! empty( $fahrzeuge ) && ! is_wp_error( $fahrzeuge ) ) : ?>
							<div class="ffw-event-card__cats">
								<?php foreach ( array_slice( $fahrzeuge, 0, 3 ) as $fz ) : ?>
									<span class="tag"><?php echo esc_html( $fz->name ); ?></span>
								<?php endforeach; ?>
								<?php if ( count( $fahrzeuge ) > 3 ) : ?>
									<span class="tag">+<?php echo intval( count( $fahrzeuge ) - 3 ); ?></span>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
					<a href="<?php the_permalink(); ?>" class="ffw-event-card__link btn btn--outline btn--sm">
						<?php esc_html_e( 'Bericht lesen', 'ffw-theme' ); ?>
					</a>
				</article>
				<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>

			<div class="section-cta">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'einsatz' ) ); ?>" class="btn btn--outline">
					<?php esc_html_e( 'Alle Einsätze anzeigen', 'ffw-theme' ); ?>
				</a>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<!-- ===== LATEST NEWS ===== -->
	<?php
	$news_query = new WP_Query(
		array(
			'post_type'      => 'post',
			'posts_per_page' => 3,
			'post_status'    => 'publish',
		)
	);

	if ( $news_query->have_posts() ) :
	?>
	<section class="home-section home-section--news">
		<div class="container">
			<div class="section-heading">
				<div>
					<p class="section-label"><?php esc_html_e( 'Neuigkeiten', 'ffw-theme' ); ?></p>
					<h2><?php esc_html_e( 'Aktuelles', 'ffw-theme' ); ?></h2>
				</div>
			</div>

			<div class="posts-grid posts-grid--3">
				<?php
				while ( $news_query->have_posts() ) :
					$news_query->the_post();
					get_template_part( 'template-parts/content' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>

			<div class="section-cta">
				<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="btn btn--outline">
					<?php esc_html_e( 'Alle Neuigkeiten', 'ffw-theme' ); ?>
				</a>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<!-- ===== EVENTS CALENDAR (The Events Calendar PHP API) ===== -->
	<?php
	if ( function_exists( 'tribe_get_events' ) ) :
		$upcoming_events = tribe_get_events( array(
			'posts_per_page' => 4,
			'ends_after'     => 'now',
			'order'          => 'ASC',
		) );
	endif;

	if ( ! empty( $upcoming_events ) ) :
	?>
	<section class="home-section home-section--events">
		<div class="container">
			<div class="section-heading">
				<div>
					<p class="section-label"><?php esc_html_e( 'Termine', 'ffw-theme' ); ?></p>
					<h2><?php esc_html_e( 'Nächste Veranstaltungen', 'ffw-theme' ); ?></h2>
				</div>
			</div>

			<div class="ffw-events-list">
				<?php foreach ( $upcoming_events as $event ) :
					$event_date  = tribe_get_start_date( $event->ID, false, 'j. F Y' );
					$event_time  = tribe_get_start_date( $event->ID, false, 'H:i' );
					$event_end   = tribe_get_end_date( $event->ID, false, 'H:i' );
					$event_title = get_the_title( $event->ID );
					$event_link  = tribe_get_event_link( $event->ID );
					$event_venue = function_exists( 'tribe_get_venue' ) ? tribe_get_venue( $event->ID ) : '';
					$event_cats  = get_the_terms( $event->ID, 'tribe_events_cat' );
				?>
				<article class="ffw-event-card">
					<div class="ffw-event-card__date">
						<span class="ffw-event-card__day"><?php echo esc_html( tribe_get_start_date( $event->ID, false, 'j' ) ); ?></span>
						<span class="ffw-event-card__month"><?php echo esc_html( tribe_get_start_date( $event->ID, false, 'M Y' ) ); ?></span>
					</div>
					<div class="ffw-event-card__body">
						<h3 class="ffw-event-card__title">
							<a href="<?php echo esc_url( $event_link ); ?>"><?php echo esc_html( $event_title ); ?></a>
						</h3>
						<div class="ffw-event-card__meta">
							<span class="ffw-event-card__time">
								<svg viewBox="0 0 16 16" width="14" height="14" fill="none" aria-hidden="true"><circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.3"/><path d="M8 4.5V8l2.5 2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
								<?php echo esc_html( $event_time );
								if ( $event_end && $event_end !== $event_time ) echo ' – ' . esc_html( $event_end ); ?>
							</span>
							<?php if ( $event_venue ) : ?>
							<span class="ffw-event-card__venue">
								<svg viewBox="0 0 16 16" width="14" height="14" fill="none" aria-hidden="true"><path d="M8 1.5C5.515 1.5 3.5 3.515 3.5 6c0 3.5 4.5 8.5 4.5 8.5s4.5-5 4.5-8.5c0-2.485-2.015-4.5-4.5-4.5Z" stroke="currentColor" stroke-width="1.3"/><circle cx="8" cy="6" r="1.5" stroke="currentColor" stroke-width="1.3"/></svg>
								<?php echo esc_html( $event_venue ); ?>
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
				<?php endforeach; ?>
			</div>

			<div class="section-cta">
				<a href="<?php echo esc_url( tribe_get_events_link() ); ?>" class="btn btn--outline">
					<?php esc_html_e( 'Alle Termine anzeigen', 'ffw-theme' ); ?>
				</a>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<!-- ===== STATIC PAGE CONTENT (if assigned) ===== -->
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			if ( get_the_content() ) :
				?>
				<section class="home-section home-section--content">
					<div class="container">
						<div class="entry-content">
							<?php the_content(); ?>
						</div>
					</div>
				</section>
				<?php
			endif;
		endwhile;
	endif;
	?>

</main>

<style>
/* ===== Homepage Styles ===== */

/* Hero */
.hero-section {
	position: relative;
	min-height: 85vh;
	display: flex;
	align-items: center;
	background: var(--ffw-bg-deep);
	background-size: cover;
	background-position: center;
	overflow: hidden;
}

.hero-overlay {
	position: absolute;
	inset: 0;
	background: linear-gradient(
		135deg,
		rgba(0, 0, 0, 0.85) 0%,
		rgba(0, 0, 0, 0.5) 60%,
		rgba(227, 6, 19, 0.2) 100%
	);
}

.hero-content {
	position: relative;
	z-index: 1;
	padding-top: var(--ffw-spacing-xl);
	padding-bottom: var(--ffw-spacing-xl);
}

.hero-badge {
	display: inline-block;
	background: var(--ffw-color-primary);
	color: #fff;
	font-size: 0.75rem;
	font-weight: 700;
	letter-spacing: 0.15em;
	text-transform: uppercase;
	padding: 0.35rem 0.9rem;
	border-radius: 3px;
	margin-bottom: var(--ffw-spacing-md);
}

.hero-title {
	font-size: clamp(2.5rem, 6vw, 4.5rem);
	font-family: var(--ffw-font-heading);
	font-weight: 700;
	color: #fff;
	margin-bottom: var(--ffw-spacing-md);
	max-width: 700px;
	line-height: 1.05;
	text-shadow: 0 2px 20px rgba(0, 0, 0, 0.5);
}

.hero-subtitle {
	font-size: 1.2rem;
	color: rgba(255, 255, 255, 0.8);
	margin-bottom: var(--ffw-spacing-lg);
	max-width: 550px;
}

.hero-actions {
	display: flex;
	gap: var(--ffw-spacing-md);
	flex-wrap: wrap;
}

.hero-scroll-hint {
	position: absolute;
	bottom: 2rem;
	left: 50%;
	transform: translateX(-50%);
}

.hero-scroll-hint span {
	display: block;
	width: 2px;
	height: 40px;
	background: linear-gradient(to bottom, transparent, rgba(255,255,255,0.6));
	margin: 0 auto;
	animation: scrollHint 1.5s ease-in-out infinite;
}

@keyframes scrollHint {
	0%, 100% { opacity: 0; transform: scaleY(0); transform-origin: top; }
	50%       { opacity: 1; transform: scaleY(1); }
}

/* Stats Bar */
.stats-bar {
	background: var(--ffw-color-primary);
	padding: var(--ffw-spacing-lg) 0;
}

.stats-grid {
	display: grid;
	grid-template-columns: repeat(4, 1fr);
	gap: var(--ffw-spacing-md);
	text-align: center;
}

.stat-item { padding: var(--ffw-spacing-sm); }

.stat-number {
	display: block;
	font-family: var(--ffw-font-heading);
	font-size: 2rem;
	font-weight: 700;
	color: #fff;
	line-height: 1;
}

.stat-label {
	display: block;
	font-size: 0.8rem;
	color: rgba(255, 255, 255, 0.8);
	text-transform: uppercase;
	letter-spacing: 0.06em;
	margin-top: 0.3rem;
}

/* Home Sections */
.home-section {
	padding: var(--ffw-spacing-xl) 0;
}

.home-section--news {
	background: var(--ffw-bg-raised);
}

.home-section--events {
	background: var(--ffw-bg-deep);
}

.section-cta {
	text-align: center;
	margin-top: var(--ffw-spacing-lg);
}

/* Posts Grid */
.posts-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
	gap: var(--ffw-spacing-lg);
}

.posts-grid--3 {
	grid-template-columns: repeat(3, 1fr);
}

@media (max-width: 991px) {
	.posts-grid--3 { grid-template-columns: repeat(2, 1fr); }
	.stats-grid    { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 575px) {
	.posts-grid--3 { grid-template-columns: 1fr; }
	.stats-grid    { grid-template-columns: repeat(2, 1fr); }
	.hero-actions  { flex-direction: column; }
}

/* Event Cards */
.ffw-events-list {
	display: flex;
	flex-direction: column;
	gap: var(--ffw-spacing-md);
	margin-top: var(--ffw-spacing-lg);
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

/* Einsatz-Cards: dunklere Datumbox zur Unterscheidung von Event-Cards */
.ffw-event-card--einsatz .ffw-event-card__date {
	background: var(--ffw-text-secondary, #444);
}

.einsatz-tag--fehlalarm {
	font-size: 0.7rem;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.06em;
	background: #e0e0e0;
	color: #666;
	border-radius: 3px;
	padding: 0.1rem 0.45rem;
	vertical-align: middle;
	margin-right: 0.4rem;
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
endif;
get_footer();
