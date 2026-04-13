<?php
/**
 * FFW Theme — Einsatz Archive template.
 * WordPress template hierarchy: archive-einsatz.php
 */

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) :
	$current_year = get_query_var( 'year' ) ? intval( get_query_var( 'year' ) ) : intval( date( 'Y' ) );
?>
<main id="primary" class="site-main einsatz-archive">

	<header class="page-header">
		<div class="container">
			<h1 class="page-title">
				<?php esc_html_e( 'Einsätze', 'ffw-theme' ); ?>
				<?php if ( get_query_var( 'year' ) ) : ?>
					<span class="page-title__year"><?php echo esc_html( $current_year ); ?></span>
				<?php endif; ?>
			</h1>
			<p class="page-description"><?php esc_html_e( 'Übersicht aller Feuerwehreinsätze', 'ffw-theme' ); ?></p>
		</div>
	</header>

	<div class="container">

		<?php
		// Jahr-Navigation — Einsatzverwaltung stellt [einsatzjahre] bereit
		if ( shortcode_exists( 'einsatzjahre' ) ) :
			echo '<div class="einsatz-year-nav">' . do_shortcode( '[einsatzjahre]' ) . '</div>';
		endif;
		?>

		<?php if ( have_posts() ) : ?>

			<div class="einsatz-archive__stats">
				<p class="einsatz-archive__count">
					<?php
					printf(
						/* translators: %d: number of operations */
						esc_html( _n( '%d Einsatz', '%d Einsätze', $wp_query->found_posts, 'ffw-theme' ) ),
						intval( $wp_query->found_posts )
					);
					if ( get_query_var( 'year' ) ) :
						echo ' ' . esc_html( sprintf( __( 'im Jahr %d', 'ffw-theme' ), $current_year ) );
					endif;
					?>
				</p>
			</div>

			<?php
			$active_month = null; // Aktuell geöffneter Monatsblock

			while ( have_posts() ) :
				the_post();
				$e_id         = get_the_ID();
				$alarmzeit    = get_post_meta( $e_id, 'einsatz_alarmzeit', true );
				$einsatzort   = get_post_meta( $e_id, 'einsatz_einsatzort', true );
				$fehlalarm    = get_post_meta( $e_id, 'einsatz_fehlalarm', true );
				$einsatzarten = get_the_terms( $e_id, 'einsatzart' );
				$ts           = $alarmzeit ? strtotime( $alarmzeit ) : get_the_date( 'U' );
				$excerpt      = get_the_excerpt();
				if ( ! $excerpt ) {
					$excerpt = wp_trim_words( get_the_content(), 20, '…' );
				}
				$keyword_color = '';
				if ( ! empty( $einsatzarten ) && ! is_wp_error( $einsatzarten ) ) {
					$keyword_color = get_term_meta( $einsatzarten[0]->term_id, 'typecolor', true );
				}

				// Monats-Gruppierung: neue Überschrift wenn sich der Monat ändert
				$month_key = date( 'Y-m', $ts );
				if ( $month_key !== $active_month ) {
					if ( null !== $active_month ) {
						echo '</div>'; // vorherigen Monatsblock schließen
					}
					$active_month = $month_key;
					echo '<h2 class="ffw-month-heading">' . esc_html( date_i18n( 'F Y', $ts ) ) . '</h2>';
					echo '<div class="ffw-events-list">';
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

			if ( null !== $active_month ) {
				echo '</div>'; // letzten Monatsblock schließen
			}
			?>

			<?php the_posts_pagination(); ?>

		<?php else : ?>
			<div class="einsatz-archive__empty">
				<p><?php esc_html_e( 'Für diesen Zeitraum wurden keine Einsätze gefunden.', 'ffw-theme' ); ?></p>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'einsatz' ) ); ?>" class="btn btn--outline">
					<?php esc_html_e( 'Alle Einsätze anzeigen', 'ffw-theme' ); ?>
				</a>
			</div>
		<?php endif; ?>

	</div>
</main>

<?php
endif;
get_footer();
