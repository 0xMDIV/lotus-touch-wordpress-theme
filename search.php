<?php
/**
 * Search Results Page
 * Eigene Suchseite mit AJAX-Live-Suche und Direktlinks
 *
 * @package LotusTouch
 */

get_header();
?>

<main id="main" class="site-main">

    <div class="page-header search-page-header">
        <div class="container-narrow">
            <span class="section-eyebrow" style="color:var(--color-primary);">🔍 <?php esc_html_e( 'Suche', 'lotus-touch' ); ?></span>
            <h1><?php esc_html_e( 'Wonach suchst Du?', 'lotus-touch' ); ?></h1>
            <p class="subtitle"><?php esc_html_e( 'Durchsuche unsere Massagen, Seiten und Sektionen.', 'lotus-touch' ); ?></p>

            <form role="search" method="get" id="lotusSearchForm" class="search-page-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <input
                    type="search"
                    id="lotusSearchInput"
                    name="s"
                    placeholder="<?php esc_attr_e( 'z. B. Hot Stone, Gutschein, Massage…', 'lotus-touch' ); ?>"
                    value="<?php echo esc_attr( get_search_query() ); ?>"
                    autocomplete="off"
                    aria-label="<?php esc_attr_e( 'Suchbegriff', 'lotus-touch' ); ?>"
                />
                <button type="submit" class="btn btn-primary">
                    <?php esc_html_e( 'Suchen', 'lotus-touch' ); ?>
                </button>
            </form>
        </div>
    </div>

    <div class="page-content search-page-content">
        <div class="container-narrow">

            <div id="searchResults" class="search-results" aria-live="polite">
                <p class="search-hint">
                    <?php esc_html_e( 'Tipp: Gib mindestens 2 Zeichen ein. Die Suche zeigt alle passenden Behandlungen, Seiten und Sektionen mit Direktlinks.', 'lotus-touch' ); ?>
                </p>
            </div>

        </div>
    </div>

</main>

<?php get_footer(); ?>