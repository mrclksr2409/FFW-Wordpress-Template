<?php
/**
 * FFW Theme — Einsatz Meta Fields Display
 * Reads post meta from the Einsatzverwaltung plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id        = get_the_ID();
$alarmzeit      = get_post_meta( $post_id, 'einsatz_alarmzeit',     true );
$einsatzende    = get_post_meta( $post_id, 'einsatz_einsatzende',   true );
$einsatzort     = get_post_meta( $post_id, 'einsatz_einsatzort',    true );
$einsatzleiter  = get_post_meta( $post_id, 'einsatz_einsatzleiter', true );
$mannschaft     = get_post_meta( $post_id, 'einsatz_mannschaft',    true );
$fehlalarm      = get_post_meta( $post_id, 'einsatz_fehlalarm',     true );
$lrn            = get_post_meta( $post_id, 'einsatz_lrn',           true );

// Taxonomies
$fahrzeuge    = get_the_terms( $post_id, 'fahrzeug' );
$einsatzarten = get_the_terms( $post_id, 'einsatzart' );
?>
<div class="einsatz-meta-wrapper">
	<?php if ( $fehlalarm ) : ?>
		<div class="einsatz-fehlalarm-badge">
			<?php esc_html_e( 'Fehlalarm', 'ffw-theme' ); ?>
		</div>
	<?php endif; ?>

	<dl class="einsatz-meta">
		<?php if ( $alarmzeit ) : ?>
		<div class="einsatz-meta__item">
			<dt>
				<svg class="einsatz-meta__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
				</svg>
				<?php esc_html_e( 'Alarmzeit', 'ffw-theme' ); ?>
			</dt>
			<dd><?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $alarmzeit ) ) ); ?></dd>
		</div>
		<?php endif; ?>

		<?php if ( $einsatzende ) : ?>
		<div class="einsatz-meta__item">
			<dt>
				<svg class="einsatz-meta__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
				</svg>
				<?php esc_html_e( 'Einsatzende', 'ffw-theme' ); ?>
			</dt>
			<dd><?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $einsatzende ) ) ); ?></dd>
		</div>
		<?php endif; ?>

		<?php if ( $einsatzort ) : ?>
		<div class="einsatz-meta__item">
			<dt>
				<svg class="einsatz-meta__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
				</svg>
				<?php esc_html_e( 'Einsatzort', 'ffw-theme' ); ?>
			</dt>
			<dd><?php echo esc_html( $einsatzort ); ?></dd>
		</div>
		<?php endif; ?>

		<?php if ( ! empty( $einsatzarten ) && ! is_wp_error( $einsatzarten ) ) : ?>
		<div class="einsatz-meta__item">
			<dt>
				<svg class="einsatz-meta__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M14.5 10c-.83 0-1.5-.67-1.5-1.5v-5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5v5c0 .83-.67 1.5-1.5 1.5z"/><path d="M20.5 10H19V8.5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
				</svg>
				<?php esc_html_e( 'Einsatzart', 'ffw-theme' ); ?>
			</dt>
			<dd><?php echo esc_html( implode( ', ', wp_list_pluck( $einsatzarten, 'name' ) ) ); ?></dd>
		</div>
		<?php endif; ?>

		<?php if ( $mannschaft ) : ?>
		<div class="einsatz-meta__item">
			<dt>
				<svg class="einsatz-meta__icon" aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
				</svg>
				<?php esc_html_e( 'Mannschaft', 'ffw-theme' ); ?>
			</dt>
			<dd><?php echo esc_html( $mannschaft ); ?></dd>
		</div>
		<?php endif; ?>

		<?php if ( $einsatzleiter ) : ?>
		<div class="einsatz-meta__item">
			<dt><?php esc_html_e( 'Einsatzleiter', 'ffw-theme' ); ?></dt>
			<dd><?php echo esc_html( $einsatzleiter ); ?></dd>
		</div>
		<?php endif; ?>

		<?php if ( $lrn ) : ?>
		<div class="einsatz-meta__item">
			<dt><?php esc_html_e( 'Lfd. Nr.', 'ffw-theme' ); ?></dt>
			<dd><?php echo esc_html( $lrn ); ?></dd>
		</div>
		<?php endif; ?>
	</dl>

	<?php if ( ! empty( $fahrzeuge ) && ! is_wp_error( $fahrzeuge ) ) : ?>
		<div class="einsatz-fahrzeuge">
			<p class="einsatz-fahrzeuge__label"><?php esc_html_e( 'Eingesetzte Fahrzeuge:', 'ffw-theme' ); ?></p>
			<div class="einsatz-tags">
				<?php foreach ( $fahrzeuge as $fahrzeug ) : ?>
					<a href="<?php echo esc_url( get_term_link( $fahrzeug ) ); ?>" class="einsatz-tag">
						<?php echo esc_html( $fahrzeug->name ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
</div>
