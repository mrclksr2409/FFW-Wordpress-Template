<?php
/**
 * FFW Theme — footer.php
 * 3-column footer with Elementor Pro fallback support.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	</div><!-- #content .site-content -->

	<?php
	// If Elementor Pro has a footer template, use it.
	// Otherwise, fall back to the theme's own footer.
	if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) :
	?>
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="footer-widgets">
			<div class="container">
				<div class="footer-grid">
					<div class="footer-col footer-col--brand">
						<?php get_template_part( 'template-parts/footer/footer-contact' ); ?>
					</div>
					<div class="footer-col footer-col--links">
						<?php get_template_part( 'template-parts/footer/footer-links' ); ?>
					</div>
					<div class="footer-col footer-col--social">
						<?php get_template_part( 'template-parts/footer/footer-social' ); ?>
					</div>
				</div>
			</div>
		</div>

		<div class="footer-bottom">
			<div class="container">
				<p class="footer-copy">
					&copy; <?php echo esc_html( current_time( 'Y' ) ); ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
					&mdash; <?php esc_html_e( 'Freiwillige Feuerwehr', 'ffw-theme' ); ?>
				</p>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer-1',
						'menu_class'     => 'footer-nav',
						'container'      => 'nav',
						'container_class'=> 'footer-nav-container',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
				?>
			</div>
		</div>
	</footer>
	<?php endif; ?>

</div><!-- #page .site -->

<?php wp_footer(); ?>
</body>
</html>
