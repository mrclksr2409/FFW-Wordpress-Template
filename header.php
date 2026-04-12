<?php
/**
 * FFW Theme — header.php
 * Site header with Elementor Pro fallback support.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary">
		<?php esc_html_e( 'Zum Inhalt springen', 'ffw-theme' ); ?>
	</a>

	<?php
	// If Elementor Pro has a header template for this page, use it.
	// Otherwise, fall back to the theme's own header.
	if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) :
	?>
	<header id="masthead" class="site-header" role="banner">
		<div class="header-inner container">
			<?php get_template_part( 'template-parts/header/site-branding' ); ?>
			<?php get_template_part( 'template-parts/header/navigation' ); ?>
		</div>
	</header>
	<?php endif; ?>

	<div id="content" class="site-content">
