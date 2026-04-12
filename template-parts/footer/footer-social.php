<?php
/**
 * FFW Theme — Footer Social Media Column
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$facebook  = get_theme_mod( 'ffw_social_facebook', '' );
$instagram = get_theme_mod( 'ffw_social_instagram', '' );
$youtube   = get_theme_mod( 'ffw_social_youtube', '' );
$twitter   = get_theme_mod( 'ffw_social_twitter', '' );
?>
<div class="footer-widget footer-widget--social">
	<h3 class="footer-widget-title"><?php esc_html_e( 'Folge uns', 'ffw-theme' ); ?></h3>

	<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
		<?php dynamic_sidebar( 'footer-3' ); ?>
	<?php endif; ?>

	<?php if ( $facebook || $instagram || $youtube || $twitter ) : ?>
		<ul class="social-links">
			<?php if ( $facebook ) : ?>
				<li>
					<a href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener noreferrer" class="social-link social-link--facebook">
						<svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
							<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
						</svg>
						<span>Facebook</span>
					</a>
				</li>
			<?php endif; ?>
			<?php if ( $instagram ) : ?>
				<li>
					<a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener noreferrer" class="social-link social-link--instagram">
						<svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
							<path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
							<line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
						</svg>
						<span>Instagram</span>
					</a>
				</li>
			<?php endif; ?>
			<?php if ( $youtube ) : ?>
				<li>
					<a href="<?php echo esc_url( $youtube ); ?>" target="_blank" rel="noopener noreferrer" class="social-link social-link--youtube">
						<svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
							<path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.96-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/>
							<polygon fill="#1a1a1a" points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/>
						</svg>
						<span>YouTube</span>
					</a>
				</li>
			<?php endif; ?>
			<?php if ( $twitter ) : ?>
				<li>
					<a href="<?php echo esc_url( $twitter ); ?>" target="_blank" rel="noopener noreferrer" class="social-link social-link--twitter">
						<svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
							<path d="M4 4l16 16M4 20L20 4" stroke="currentColor" stroke-width="2" fill="none"/>
							<path d="M3 5h4l14 14h-4L3 5z M3 19l7-7 M14 8l7-7"/>
						</svg>
						<span>X / Twitter</span>
					</a>
				</li>
			<?php endif; ?>
		</ul>
	<?php endif; ?>

	<div class="footer-emergency">
		<p class="footer-emergency__label"><?php esc_html_e( 'Im Notfall:', 'ffw-theme' ); ?></p>
		<a href="tel:112" class="footer-emergency__number">112</a>
	</div>
</div>
