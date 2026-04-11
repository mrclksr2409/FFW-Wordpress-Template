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
					<span class="stat-number"><?php echo esc_html( get_theme_mod( 'ffw_stat_operations', '100+' ) ); ?></span>
					<span class="stat-label"><?php esc_html_e( 'Einsätze/Jahr', 'ffw-theme' ); ?></span>
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
			'posts_per_page' => 3,
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

			<div class="einsatz-grid">
				<?php
				while ( $einsatz_query->have_posts() ) :
					$einsatz_query->the_post();
					get_template_part( 'template-parts/einsatz/einsatz-card' );
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

	<!-- ===== EVENTS CALENDAR (The Events Calendar shortcode) ===== -->
	<?php if ( function_exists( 'tribe_get_events' ) ) : ?>
	<section class="home-section home-section--events">
		<div class="container">
			<div class="section-heading">
				<div>
					<p class="section-label"><?php esc_html_e( 'Termine', 'ffw-theme' ); ?></p>
					<h2><?php esc_html_e( 'Nächste Veranstaltungen', 'ffw-theme' ); ?></h2>
				</div>
			</div>
			<?php echo do_shortcode( '[tribe_events view="list" limit="4"]' ); ?>
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
	background: linear-gradient(to bottom, transparent, rgba(255,255,255,0.4));
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
</style>

<?php
endif;
get_footer();
