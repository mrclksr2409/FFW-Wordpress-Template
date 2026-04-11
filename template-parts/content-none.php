<?php
/**
 * FFW Theme — Template part for displaying no content found.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="no-results not-found">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Nichts gefunden', 'ffw-theme' ); ?></h1>
	</header>

	<div class="page-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
			<p>
				<?php
				printf(
					wp_kses(
						__( 'Bereit, den ersten Beitrag zu veröffentlichen? <a href="%1$s">Fang hier an</a>.', 'ffw-theme' ),
						array( 'a' => array( 'href' => array() ) )
					),
					esc_url( admin_url( 'post-new.php' ) )
				);
				?>
			</p>
		<?php elseif ( is_search() ) : ?>
			<p><?php esc_html_e( 'Es wurden leider keine Ergebnisse für deine Suchanfrage gefunden. Bitte versuche es mit anderen Suchbegriffen.', 'ffw-theme' ); ?></p>
			<?php get_search_form(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'Es scheint, als könnte der gesuchte Inhalt nicht gefunden werden. Möglicherweise hilft die Suche.', 'ffw-theme' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</section>
