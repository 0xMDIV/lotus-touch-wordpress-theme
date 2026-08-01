<?php
/**
 * The Header
 *
 * @package LotusTouch
 */
?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta name="theme-color" content="#8E5DA8" />
    <link rel="profile" href="https://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

    <?php
    // SEO Meta
    $site_desc = get_bloginfo( 'description', 'display' );
    if ( is_front_page() && ! empty( $site_desc ) ) : ?>
        <meta name="description" content="<?php echo esc_attr( wp_strip_all_tags( $site_desc ) ); ?>" />
    <?php endif; ?>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Zum Inhalt springen', 'lotus-touch' ); ?></a>

<header class="site-header" id="siteHeader">
    <div class="container">
        <div class="header-inner">

            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-branding" rel="home">
                <?php if ( has_custom_logo() ) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <span class="site-logo-placeholder" aria-hidden="true" style="width:48px;height:48px;border-radius:50%;background:var(--gradient-hero);display:flex;align-items:center;justify-content:center;color:white;font-family:var(--font-display);font-size:1.25rem;font-weight:600;box-shadow:var(--shadow-sm);">LT</span>
                <?php endif; ?>

                <span class="site-branding-text">
                    <?php
                    $title = get_bloginfo( 'name' );
                    if ( $title ) : ?>
                        <span class="site-title"><?php echo esc_html( $title ); ?></span>
                    <?php endif;
                    $tagline = lotus_touch_opt( 'lotus_hero_tagline', get_bloginfo( 'description', 'display' ) );
                    if ( $tagline ) : ?>
                        <span class="site-tagline"><?php echo esc_html( $tagline ); ?></span>
                    <?php endif; ?>
                </span>
            </a>

            <div class="header-search" role="search">
                <label for="lotusSiteSearch" class="screen-reader-text"><?php esc_html_e( 'Suche', 'lotus-touch' ); ?></label>
                <input
                    type="search"
                    id="lotusSiteSearch"
                    placeholder="<?php esc_attr_e( 'Suche auf der Seite…', 'lotus-touch' ); ?>"
                    autocomplete="off"
                    aria-describedby="searchHelp"
                />
            </div>

            <nav class="main-navigation" id="primaryNav" aria-label="<?php esc_attr_e( 'Hauptnavigation', 'lotus-touch' ); ?>">
                <?php
                if ( has_nav_menu( 'primary' ) ) {
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'primary-menu',
                        'depth'          => 2,
                    ) );
                } else {
                    // Fallback menu if not configured yet
                    ?>
                    <ul class="primary-menu">
                        <li><a href="#hero"><?php esc_html_e( 'Start', 'lotus-touch' ); ?></a></li>
                        <li><a href="#ueber-uns"><?php esc_html_e( 'Über uns', 'lotus-touch' ); ?></a></li>
                        <li><a href="#angebot"><?php esc_html_e( 'Angebot', 'lotus-touch' ); ?></a></li>
                        <li><a href="#gutschein"><?php esc_html_e( 'Gutschein', 'lotus-touch' ); ?></a></li>
                        <li><a href="#kontakt"><?php esc_html_e( 'Kontakt', 'lotus-touch' ); ?></a></li>
                    </ul>
                    <?php
                }
                ?>
            </nav>

            <button class="menu-toggle" id="menuToggle" aria-label="<?php esc_attr_e( 'Menü öffnen', 'lotus-touch' ); ?>" aria-controls="primaryNav" aria-expanded="false">
                <span aria-hidden="true">☰</span>
            </button>

        </div>
    </div>
</header>

<div id="searchHelp" class="screen-reader-text">
    <?php esc_html_e( 'Tipp: Die Suche durchsucht Massagen und Seiteninhalte live, während Du tippst.', 'lotus-touch' ); ?>
</div>

<div class="search-results-message" id="searchMessage" role="status" aria-live="polite"></div>