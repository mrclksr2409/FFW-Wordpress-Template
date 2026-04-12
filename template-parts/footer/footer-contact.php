<?php
/**
 * FFW Theme — Footer Contact Column
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$contact_address = get_theme_mod( 'ffw_contact_address', '' );
$contact_phone   = get_theme_mod( 'ffw_contact_phone', '' );
$contact_email   = get_theme_mod( 'ffw_contact_email', '' );
?>
<div class="footer-widget footer-widget--contact">
	<?php if ( has_custom_logo() ) : ?>
		<div class="footer-logo"><?php the_custom_logo(); ?></div>
	<?php endif; ?>

	<h3 class="footer-widget-title">
		<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
	</h3>

	<?php if ( $contact_address ) : ?>
		<address class="footer-address">
			<?php echo nl2br( esc_html( $contact_address ) ); ?>
		</address>
	<?php endif; ?>

	<?php if ( $contact_phone && $contact_phone !== '112' ) : ?>
		<p class="footer-phone">
			<span class="footer-contact-label"><?php esc_html_e( 'Telefon:', 'ffw-theme' ); ?></span>
			<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $contact_phone ) ); ?>">
				<?php echo esc_html( $contact_phone ); ?>
			</a>
		</p>
	<?php endif; ?>

	<?php if ( $contact_email ) : ?>
		<p class="footer-email">
			<a href="mailto:<?php echo esc_attr( $contact_email ); ?>">
				<?php echo esc_html( $contact_email ); ?>
			</a>
		</p>
	<?php endif; ?>

	<?php
	// Fallback to sidebar widget if set
	if ( is_active_sidebar( 'footer-1' ) ) {
		dynamic_sidebar( 'footer-1' );
	}
	?>
</div>
