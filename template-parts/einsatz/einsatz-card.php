<?php
/**
 * FFW Theme — Einsatz Card for archive/grid views.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id      = get_the_ID();
$alarmzeit    = get_post_meta( $post_id, 'einsatz_alarmzeit', true );
$einsatzort   = get_post_meta( $post_id, 'einsatz_einsatzort', true );
$fehlalarm    = get_post_meta( $post_id, 'einsatz_fehlalarm', true );
$einsatzarten = get_the_terms( $post_id, 'einsatzart' );
$fahrzeuge    = get_the_terms( $post_id, 'fahrzeug' );
?>
<article id="einsatz-<?php the_ID(); ?>" <?php post_class( 'einsatz-card' ); ?>>

	<?php if ( $fehlalarm ) : ?>
		<span class="einsatz-card__fehlalarm"><?php esc_html_e( 'Fehlalarm', 'ffw-theme' ); ?></span>
	<?php endif; ?>

	<div class="einsatz-card__header">
		<?php if ( $alarmzeit ) : ?>
			<time class="einsatz-card__date" datetime="<?php echo esc_attr( $alarmzeit ); ?>">
				<?php echo esc_html( date_i18n( 'd.m.Y', strtotime( $alarmzeit ) ) ); ?>
			</time>
		<?php endif; ?>

		<?php if ( ! empty( $einsatzarten ) && ! is_wp_error( $einsatzarten ) ) : ?>
			<span class="einsatz-card__type"><?php echo esc_html( $einsatzarten[0]->name ); ?></span>
		<?php endif; ?>
	</div>

	<h3 class="einsatz-card__title">
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</h3>

	<?php if ( $einsatzort ) : ?>
		<p class="einsatz-card__location">
			<svg aria-hidden="true" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
				<circle cx="12" cy="10" r="3"/>
			</svg>
			<?php echo esc_html( $einsatzort ); ?>
		</p>
	<?php endif; ?>

	<?php if ( ! empty( $fahrzeuge ) && ! is_wp_error( $fahrzeuge ) ) : ?>
		<div class="einsatz-card__vehicles">
			<?php foreach ( array_slice( $fahrzeuge, 0, 3 ) as $fahrzeug ) : ?>
				<span class="einsatz-tag einsatz-tag--sm"><?php echo esc_html( $fahrzeug->name ); ?></span>
			<?php endforeach; ?>
			<?php if ( count( $fahrzeuge ) > 3 ) : ?>
				<span class="einsatz-tag einsatz-tag--more">+<?php echo intval( count( $fahrzeuge ) - 3 ); ?></span>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<a href="<?php the_permalink(); ?>" class="einsatz-card__link" aria-label="<?php echo esc_attr( sprintf( __( 'Einsatz ansehen: %s', 'ffw-theme' ), get_the_title() ) ); ?>">
		<?php esc_html_e( 'Bericht lesen', 'ffw-theme' ); ?> &rarr;
	</a>
</article>
