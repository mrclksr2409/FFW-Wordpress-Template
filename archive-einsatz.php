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

	<header class="page-header einsatz-archive__header">
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
					echo '<h2 class="einsatz-month-heading">' . esc_html( date_i18n( 'F Y', $ts ) ) . '</h2>';
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

<style>
/* ===== Einsatz-Archive Monats-Gruppierung ===== */

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
	display: flex;
	align-items: center;
	gap: var(--ffw-spacing-md);
}

.einsatz-month-heading:first-of-type {
	margin-top: var(--ffw-spacing-lg);
}

/* ===== Event-Card Layout (geteilt mit Startseite) ===== */

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

.ffw-event-card__date--keyword {
	display: flex;
	align-items: center;
	justify-content: center;
}

.ffw-event-card__keyword {
	display: block;
	font-family: var(--ffw-font-heading);
	font-size: 1.25rem;
	font-weight: 700;
	color: #fff;
	line-height: 1.1;
	text-align: center;
	word-break: break-word;
}

.ffw-event-card--einsatz .ffw-event-card__date {
	background: var(--ffw-text-secondary, #444);
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

.ffw-event-card__excerpt {
	margin-top: var(--ffw-spacing-xs);
	font-size: 0.875rem;
	color: var(--ffw-text-muted);
	line-height: 1.5;
	display: -webkit-box;
	-webkit-line-clamp: 2;
	-webkit-box-orient: vertical;
	overflow: hidden;
}

.ffw-event-card__link {
	flex-shrink: 0;
	white-space: nowrap;
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
