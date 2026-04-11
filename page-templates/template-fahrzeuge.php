<?php
/**
 * Template Name: Fahrzeuge & Ausrüstung
 * Template Post Type: page
 *
 * Vehicle and equipment overview page.
 * Displays vehicles from the 'fahrzeug' taxonomy (Einsatzverwaltung plugin)
 * along with any page content added in the editor.
 */

get_header();
?>
<main id="primary" class="site-main fahrzeuge-page">
	<div class="container">

		<?php while ( have_posts() ) : the_post(); ?>
			<header class="page-header">
				<h1 class="page-title"><?php the_title(); ?></h1>
			</header>

			<?php if ( get_the_content() ) : ?>
				<div class="entry-content fahrzeuge-intro">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>
		<?php endwhile; ?>

		<?php
		// Display vehicles from the 'fahrzeug' taxonomy (registered by Einsatzverwaltung)
		$fahrzeuge = get_terms(
			array(
				'taxonomy'   => 'fahrzeug',
				'hide_empty' => false,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		if ( ! empty( $fahrzeuge ) && ! is_wp_error( $fahrzeuge ) ) :
		?>
		<section class="fahrzeuge-section" aria-label="<?php esc_attr_e( 'Unsere Fahrzeuge', 'ffw-theme' ); ?>">
			<div class="section-heading">
				<div>
					<p class="section-label"><?php esc_html_e( 'Fuhrpark', 'ffw-theme' ); ?></p>
					<h2><?php esc_html_e( 'Unsere Fahrzeuge', 'ffw-theme' ); ?></h2>
				</div>
			</div>

			<div class="fahrzeuge-grid">
				<?php foreach ( $fahrzeuge as $fahrzeug ) :
					// Count how many Einsätze use this vehicle
					$einsatz_count = $fahrzeug->count;
					// Attempt to get thumbnail from term meta (set via term meta plugin or ACF)
					$thumbnail_id  = get_term_meta( $fahrzeug->term_id, 'thumbnail_id', true );
				?>
				<article class="fahrzeug-card">
					<?php if ( $thumbnail_id ) : ?>
						<div class="fahrzeug-card__image">
							<?php echo wp_get_attachment_image( $thumbnail_id, 'ffw-vehicle' ); ?>
						</div>
					<?php else : ?>
						<div class="fahrzeug-card__image fahrzeug-card__image--placeholder" aria-hidden="true">
							<svg viewBox="0 0 80 50" width="80" height="50" fill="none" xmlns="http://www.w3.org/2000/svg">
								<rect width="80" height="50" fill="#2d2d2d"/>
								<path d="M10 30 L10 20 Q10 18 12 18 L50 18 L58 26 L68 26 Q70 26 70 28 L70 30 Z" fill="#E30613" opacity="0.6"/>
								<circle cx="20" cy="32" r="5" fill="#555" stroke="#888" stroke-width="1"/>
								<circle cx="58" cy="32" r="5" fill="#555" stroke="#888" stroke-width="1"/>
							</svg>
						</div>
					<?php endif; ?>

					<div class="fahrzeug-card__body">
						<h3 class="fahrzeug-card__name"><?php echo esc_html( $fahrzeug->name ); ?></h3>

						<?php if ( $fahrzeug->description ) : ?>
							<p class="fahrzeug-card__desc"><?php echo esc_html( $fahrzeug->description ); ?></p>
						<?php endif; ?>

						<?php if ( $einsatz_count ) : ?>
							<p class="fahrzeug-card__stats">
								<?php
								printf(
									/* translators: %d: number of operations */
									esc_html( _n( 'In %d Einsatz eingesetzt', 'In %d Einsätzen eingesetzt', $einsatz_count, 'ffw-theme' ) ),
									intval( $einsatz_count )
								);
								?>
							</p>
						<?php endif; ?>

						<a href="<?php echo esc_url( get_term_link( $fahrzeug ) ); ?>" class="btn btn--outline btn--sm">
							<?php esc_html_e( 'Einsätze ansehen', 'ffw-theme' ); ?>
						</a>
					</div>
				</article>
				<?php endforeach; ?>
			</div>
		</section>
		<?php elseif ( current_user_can( 'manage_options' ) ) : ?>
			<div class="admin-notice">
				<p>
					<?php esc_html_e( 'Hinweis für Admins: Fahrzeuge werden automatisch hier angezeigt, sobald das Plugin "Einsatzverwaltung" installiert ist und Fahrzeuge in den Einsätzen eingetragen sind.', 'ffw-theme' ); ?>
				</p>
			</div>
		<?php endif; ?>

	</div>
</main>

<style>
/* ===== Fahrzeuge Page Styles ===== */

.fahrzeuge-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
	gap: var(--ffw-spacing-lg);
	margin-top: var(--ffw-spacing-lg);
}

.fahrzeug-card {
	background: var(--ffw-bg-card);
	border: 1px solid var(--ffw-border-color);
	border-radius: var(--ffw-border-radius-lg);
	overflow: hidden;
	transition: box-shadow var(--ffw-transition), transform var(--ffw-transition);
}

.fahrzeug-card:hover {
	box-shadow: var(--ffw-shadow-hover);
	transform: translateY(-3px);
}

.fahrzeug-card__image img,
.fahrzeug-card__image svg {
	width: 100%;
	height: 200px;
	object-fit: cover;
	display: block;
}

.fahrzeug-card__image--placeholder {
	background: var(--ffw-bg-raised);
	display: flex;
	align-items: center;
	justify-content: center;
	height: 200px;
}

.fahrzeug-card__image--placeholder svg {
	width: auto;
	height: 80px;
}

.fahrzeug-card__body {
	padding: var(--ffw-spacing-lg);
}

.fahrzeug-card__name {
	font-family: var(--ffw-font-heading);
	font-size: 1.3rem;
	margin-bottom: var(--ffw-spacing-sm);
	color: var(--ffw-text-primary);
}

.fahrzeug-card__desc {
	font-size: 0.9rem;
	color: var(--ffw-text-muted);
	margin-bottom: var(--ffw-spacing-md);
}

.fahrzeug-card__stats {
	font-size: 0.8rem;
	color: var(--ffw-color-primary);
	font-weight: 600;
	margin-bottom: var(--ffw-spacing-md);
}

.admin-notice {
	background: rgba(227, 6, 19, 0.1);
	border: 1px solid rgba(227, 6, 19, 0.3);
	border-radius: var(--ffw-border-radius);
	padding: var(--ffw-spacing-md);
	color: var(--ffw-text-secondary);
	font-size: 0.9rem;
}

.fahrzeuge-intro {
	margin-bottom: var(--ffw-spacing-lg);
}
</style>

<?php
get_footer();
