<?php
/**
 * FFW Theme — Single Einsatz (Incident Report) template.
 * WordPress template hierarchy: single-einsatz.php
 */

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) :

	while ( have_posts() ) :
		the_post();

		// Collect data for page-header subtitle
		$post_id      = get_the_ID();
		$alarmzeit    = get_post_meta( $post_id, 'einsatz_alarmzeit', true );
		$einsatzarten = get_the_terms( $post_id, 'einsatzart' );
		$fehlalarm    = get_post_meta( $post_id, 'einsatz_fehlalarm', true );

		$header_date = $alarmzeit
			? date_i18n( get_option( 'date_format' ), strtotime( $alarmzeit ) )
			: get_the_date();

		$einsatzart_name = ( ! empty( $einsatzarten ) && ! is_wp_error( $einsatzarten ) )
			? esc_html( $einsatzarten[0]->name )
			: '';
?>
<main id="primary" class="site-main einsatz-single">

	<header class="page-header">
		<div class="container">
			<p class="page-description">
				<span class="badge"><?php esc_html_e( 'Einsatzbericht', 'ffw-theme' ); ?></span>
				<?php if ( $fehlalarm ) : ?>
					<span class="badge badge--muted"><?php esc_html_e( 'Fehlalarm', 'ffw-theme' ); ?></span>
				<?php endif; ?>
				<?php if ( $einsatzart_name ) : ?>
					<span class="page-header__sep">&mdash;</span>
					<span><?php echo $einsatzart_name; ?></span>
				<?php endif; ?>
			</p>
			<h1 class="page-title"><?php the_title(); ?></h1>
			<p class="page-description"><?php echo esc_html( $header_date ); ?></p>
		</div>
	</header>

	<div class="container">

		<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'ffw-theme' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Start', 'ffw-theme' ); ?></a>
			<span aria-hidden="true">&rsaquo;</span>
			<a href="<?php echo esc_url( get_post_type_archive_link( 'einsatz' ) ); ?>"><?php esc_html_e( 'Einsätze', 'ffw-theme' ); ?></a>
			<span aria-hidden="true">&rsaquo;</span>
			<span aria-current="page"><?php the_title(); ?></span>
		</nav>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'einsatz-report' ); ?>>

			<?php get_template_part( 'template-parts/einsatz/einsatz-meta' ); ?>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="einsatz-report__image">
					<?php the_post_thumbnail( 'ffw-hero' ); ?>
				</div>
			<?php endif; ?>

			<?php if ( get_the_content() ) : ?>
				<div class="einsatz-report__content entry-content">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>

			<footer class="einsatz-report__footer">
				<?php
				the_post_navigation(
					array(
						'prev_text'    => '<span class="nav-subtitle">' . esc_html__( 'Vorheriger Einsatz', 'ffw-theme' ) . '</span> <span class="nav-title">%title</span>',
						'next_text'    => '<span class="nav-subtitle">' . esc_html__( 'Nächster Einsatz', 'ffw-theme' ) . '</span> <span class="nav-title">%title</span>',
						'in_same_term' => false,
						'taxonomy'     => 'einsatzart',
					)
				);
				?>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'einsatz' ) ); ?>" class="btn btn--ghost">
					&larr; <?php esc_html_e( 'Alle Einsätze', 'ffw-theme' ); ?>
				</a>
			</footer>
		</article>

	</div>
</main>
<?php
	endwhile;

endif;
get_footer();
