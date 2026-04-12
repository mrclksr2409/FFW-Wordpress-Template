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
			</div>
		</div>
		<div class="hero-scroll-hint" aria-hidden="true">
			<span></span>
		</div>
	</section>

	<!-- ===== STATS BAR ===== -->
	<?php
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
					<span class="stat-number"><?php echo esc_html( get_theme_mod( 'ffw_stat_vehicles', '5' ) ); ?></span>
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
					$einsatzarten    = get_the_terms( $e_id, 'einsatzart' );
					$ts              = $alarmzeit ? strtotime( $alarmzeit ) : get_the_date( 'U' );
					$excerpt         = get_the_excerpt();
					if ( ! $excerpt ) {
						$excerpt = wp_trim_words( get_the_content(), 20, '…' );
					}
					// Farbe der Einsatzart aus Term-Meta (Einsatzverwaltung: input name="typecolor")
					$keyword_color = '';
					if ( ! empty( $einsatzarten ) && ! is_wp_error( $einsatzarten ) ) {
						$keyword_color = get_term_meta( $einsatzarten[0]->term_id, 'typecolor', true );
					}
				?>
				<article class="ffw-event-card ffw-event-card--einsatz">
					<div class="ffw-event-card__date ffw-event-card__date--keyword"<?php if ( $keyword_color ) : ?> style="background-color:<?php echo esc_attr( $keyword_color ); ?>;"<?php endif; ?>>
						<?php if ( ! empty( $einsatzarten ) && ! is_wp_error( $einsatzarten ) ) : ?>
							<span class="ffw-event-card__keyword"><?php echo esc_html( $einsatzarten[0]->name ); ?></span>
						<?php else : ?>
							<span class="ffw-event-card__keyword">—</span>
						<?php endif; ?>
					</div>
					<div class="ffw-event-card__body">
						<h3 class="ffw-event-card__title">
							<?php if ( $fehlalarm ) : ?>
								<span class="einsatz-tag einsatz-tag--fehlalarm"><?php esc_html_e( 'Fehlalarm', 'ffw-theme' ); ?></span>
							<?php endif; ?>
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
						<div class="ffw-event-card__meta">
							<span class="ffw-event-card__time">
								<svg viewBox="0 0 16 16" width="14" height="14" fill="none" aria-hidden="true"><rect x="2.5" y="3.5" width="11" height="10" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M5.5 2v3M10.5 2v3M2.5 7.5h11" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
								<?php echo esc_html( date_i18n( 'd.m.Y', $ts ) ); ?>
								<?php if ( $alarmzeit ) : ?>
									&nbsp;<?php echo esc_html( date_i18n( 'H:i', $ts ) ); ?> Uhr
								<?php endif; ?>
							</span>
							<?php if ( $einsatzort ) : ?>
								<span class="ffw-event-card__venue">
									<svg viewBox="0 0 16 16" width="14" height="14" fill="none" aria-hidden="true"><path d="M8 1.5C5.515 1.5 3.5 3.515 3.5 6c0 3.5 4.5 8.5 4.5 8.5s4.5-5 4.5-8.5c0-2.485-2.015-4.5-4.5-4.5Z" stroke="currentColor" stroke-width="1.3"/><circle cx="8" cy="6" r="1.5" stroke="currentColor" stroke-width="1.3"/></svg>
									<?php echo esc_html( $einsatzort ); ?>
								</span>
							<?php endif; ?>
						</div>
						<?php if ( $excerpt ) : ?>
							<p class="ffw-event-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
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

			<?php $posts_page_id = (int) get_option( 'page_for_posts' );
			if ( $posts_page_id ) : ?>
			<div class="section-cta">
				<a href="<?php echo esc_url( get_permalink( $posts_page_id ) ); ?>" class="btn btn--outline">
					<?php esc_html_e( 'Alle Neuigkeiten', 'ffw-theme' ); ?>
				</a>
			</div>
			<?php endif; ?>
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

<?php
endif;
get_footer();
