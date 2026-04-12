<?php
/**
 * FFW Theme — Site Branding (Logo + Name)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="site-branding">
	<?php if ( has_custom_logo() ) : ?>
		<div class="site-logo">
			<?php the_custom_logo(); ?>
		</div>
	<?php endif; ?>

	<div class="site-identity <?php echo has_custom_logo() ? 'has-logo' : ''; ?>">
		<?php if ( is_front_page() && is_home() ) : ?>
			<h1 class="site-title">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<?php bloginfo( 'name' ); ?>
				</a>
			</h1>
		<?php else : ?>
			<p class="site-title">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<?php bloginfo( 'name' ); ?>
				</a>
			</p>
		<?php endif; ?>

		<?php
		$description = get_bloginfo( 'description', 'display' );
		if ( $description || is_customize_preview() ) :
			?>
			<p class="site-description"><?php echo esc_html( $description ); ?></p>
		<?php endif; ?>
	</div>
</div>
