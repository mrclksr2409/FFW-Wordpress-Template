<?php
/**
 * FFW Theme — Custom Search Form Template
 *
 * Wird von WordPress überall dort gerendert, wo get_search_form() aufgerufen
 * wird (Header, 404-Seite, Sidebar-Widget, content-none.php).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$unique_id = wp_unique_id( 'search-form-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $unique_id ); ?>">
		<?php esc_html_e( 'Suchen nach:', 'ffw-theme' ); ?>
	</label>
	<input
		type="search"
		id="<?php echo esc_attr( $unique_id ); ?>"
		class="search-form__input"
		name="s"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		placeholder="<?php esc_attr_e( 'Suchen …', 'ffw-theme' ); ?>"
		autocomplete="off"
	/>
	<button type="submit" class="search-form__submit btn btn--primary">
		<span class="screen-reader-text"><?php esc_html_e( 'Suchen', 'ffw-theme' ); ?></span>
		<svg class="search-form__icon" aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
			<circle cx="11" cy="11" r="7"></circle>
			<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
		</svg>
	</button>
</form>
