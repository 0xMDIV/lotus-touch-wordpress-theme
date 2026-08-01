<?php
/**
 * Lotus Touch Theme Functions
 *
 * @package LotusTouch
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'LOTUS_TOUCH_VERSION', '2.0.0' );
define( 'LOTUS_TOUCH_DIR', get_template_directory() );
define( 'LOTUS_TOUCH_URI', get_template_directory_uri() );

/**
 * Theme Setup
 */
function lotus_touch_setup() {
    load_theme_textdomain( 'lotus-touch', LOTUS_TOUCH_DIR . '/languages' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );

    add_image_size( 'lotus-massage-card', 600, 400, true );
    add_image_size( 'lotus-massage-hero', 1200, 800, true );
    add_image_size( 'lotus-about', 800, 1000, true );

    register_nav_menus( array(
        'primary' => esc_html__( 'Hauptmenü', 'lotus-touch' ),
        'footer'  => esc_html__( 'Footer-Menü', 'lotus-touch' ),
    ) );

    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script',
    ) );

    add_theme_support( 'custom-logo', array(
        'height' => 100, 'width' => 100,
        'flex-height' => true, 'flex-width' => true,
    ) );

    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'custom-background', array(
        'default-color' => 'FFFCFA',
    ) );

    // Editor-Style (theme styles for Gutenberg blocks)
    add_editor_style( 'style.css' );
}
add_action( 'after_setup_theme', 'lotus_touch_setup' );

/**
 * Enqueue Scripts and Styles
 */
function lotus_touch_scripts() {
    // Google Fonts – dynamic from customizer
    $display_font = lotus_touch_opt( 'lotus_font_display', 'Playfair Display' );
    $body_font    = lotus_touch_opt( 'lotus_font_body', 'Inter' );
    $script_font  = 'Dancing Script';
    $fonts_url    = lotus_touch_build_google_fonts_url( array( $display_font, $body_font, $script_font ) );

    if ( $fonts_url ) {
        wp_enqueue_style( 'lotus-touch-fonts', $fonts_url, array(), null );
    }

    wp_enqueue_style( 'lotus-touch-style', get_stylesheet_uri(), array(), LOTUS_TOUCH_VERSION );
    wp_enqueue_style( 'lotus-touch-customizer', LOTUS_TOUCH_URI . '/assets/css/customizer.css', array(), LOTUS_TOUCH_VERSION );

    // Dynamic inline CSS for customizer colors
    $inline_css = lotus_touch_customizer_css();
    if ( $inline_css ) {
        wp_add_inline_style( 'lotus-touch-style', $inline_css );
    }

    wp_enqueue_script( 'gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js', array(), '3.12.5', true );
    wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js', array( 'gsap' ), '3.12.5', true );
    wp_enqueue_script( 'lotus-touch-admin-meta', LOTUS_TOUCH_URI . '/assets/js/admin-meta.js', array(), LOTUS_TOUCH_VERSION, true );

    wp_enqueue_script( 'lotus-touch-main', LOTUS_TOUCH_URI . '/assets/js/theme.js', array( 'gsap', 'gsap-scrolltrigger' ), LOTUS_TOUCH_VERSION, true );

    wp_localize_script( 'lotus-touch-main', 'lotusTouch', array(
        'homeUrl' => esc_url( home_url( '/' ) ),
        'searchUrl' => esc_url( home_url( '/suche/' ) ),
        'i18n'    => array(
            'noResults'    => esc_html__( 'Keine Ergebnisse gefunden', 'lotus-touch' ),
            'searching'    => esc_html__( 'Suche läuft…', 'lotus-touch' ),
            'regular'      => esc_html__( 'Regulär', 'lotus-touch' ),
            'reduced'      => esc_html__( 'Ermäßigt', 'lotus-touch' ),
        ),
    ) );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'lotus_touch_scripts' );

/**
 * Enqueue admin scripts (for meta box)
 */
function lotus_touch_admin_scripts( $hook ) {
    $screen = get_current_screen();
    if ( $screen && ( $screen->post_type === 'massage' || $screen->post_type === 'gutschein' ) ) {
        wp_enqueue_script( 'lotus-touch-admin-meta', LOTUS_TOUCH_URI . '/assets/js/admin-meta.js', array(), LOTUS_TOUCH_VERSION, true );
    }
}
add_action( 'admin_enqueue_scripts', 'lotus_touch_admin_scripts' );

/**
 * Helper: Build Google Fonts URL
 */
function lotus_touch_build_google_fonts_url( $fonts ) {
    if ( empty( $fonts ) ) return '';
    $families = array();
    foreach ( $fonts as $font ) {
        $font = trim( $font );
        if ( empty( $font ) ) continue;
        $families[] = str_replace( ' ', '+', $font ) . ':wght@400;500;600;700';
    }
    if ( empty( $families ) ) return '';
    return 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', $families ) . '&display=swap';
}

/**
 * Helper: Get Customizer value
 */
function lotus_touch_opt( $key, $default = '' ) {
    return get_theme_mod( $key, $default );
}

/**
 * Generate dynamic CSS from customizer values
 */
function lotus_touch_customizer_css() {
    $primary   = lotus_touch_opt( 'lotus_color_primary', '#8E5DA8' );
    $primary_l = lotus_touch_opt( 'lotus_color_primary_light', '#B084CC' );
    $primary_d = lotus_touch_opt( 'lotus_color_primary_dark', '#6B3D85' );
    $accent    = lotus_touch_opt( 'lotus_color_accent', '#E8B4D8' );
    $accent_w  = lotus_touch_opt( 'lotus_color_accent_warm', '#F5D4C3' );
    $text      = lotus_touch_opt( 'lotus_color_text', '#2D1B33' );
    $text_soft = lotus_touch_opt( 'lotus_color_text_soft', '#5C4A63' );
    $bg        = lotus_touch_opt( 'lotus_color_bg', '#FFFCFA' );
    $cream     = lotus_touch_opt( 'lotus_color_cream', '#FBF7F4' );

    $display = lotus_touch_opt( 'lotus_font_display', 'Playfair Display' );
    $body    = lotus_touch_opt( 'lotus_font_body', 'Inter' );
    $radius  = lotus_touch_opt( 'lotus_radius', 16 );

    $css = ":root {
        --color-primary: {$primary};
        --color-primary-light: {$primary_l};
        --color-primary-dark: {$primary_d};
        --color-accent: {$accent};
        --color-accent-warm: {$accent_w};
        --color-text: {$text};
        --color-text-soft: {$text_soft};
        --color-cream: {$cream};
        --color-warm-white: {$bg};
        --gradient-hero: linear-gradient(135deg, {$primary} 0%, {$primary_l} 50%, {$accent} 100%);
        --gradient-soft: linear-gradient(180deg, {$cream} 0%, {$accent_w} 100%);
        --gradient-lavender: linear-gradient(135deg, {$accent} 0%, {$accent_w} 100%);
        --font-display: '{$display}', Georgia, serif;
        --font-body: '{$body}', -apple-system, BlinkMacSystemFont, sans-serif;
        --radius-md: {$radius}px;
        --radius-lg: {$radius}px;
        --radius-xl: {$radius}px;
    }";
    return $css;
}

/**
 * Register Custom Post Type: Massage
 */
function lotus_touch_register_massage_cpt() {
    $labels = array(
        'name'                  => _x( 'Massagen', 'Post type general name', 'lotus-touch' ),
        'singular_name'         => _x( 'Massage', 'Post type singular name', 'lotus-touch' ),
        'menu_name'             => _x( 'Massagen', 'Admin Menu text', 'lotus-touch' ),
        'add_new'               => __( 'Neue hinzufügen', 'lotus-touch' ),
        'add_new_item'          => __( 'Neue Massage hinzufügen', 'lotus-touch' ),
        'new_item'              => __( 'Neue Massage', 'lotus-touch' ),
        'edit_item'             => __( 'Massage bearbeiten', 'lotus-touch' ),
        'view_item'             => __( 'Massage ansehen', 'lotus-touch' ),
        'all_items'             => __( 'Alle Massagen', 'lotus-touch' ),
        'search_items'          => __( 'Massagen durchsuchen', 'lotus-touch' ),
        'not_found'             => __( 'Keine Massagen gefunden.', 'lotus-touch' ),
        'featured_image'        => __( 'Massage-Bild', 'lotus-touch' ),
        'set_featured_image'    => __( 'Massage-Bild festlegen', 'lotus-touch' ),
        'remove_featured_image' => __( 'Massage-Bild entfernen', 'lotus-touch' ),
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_rest'        => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => 'massage', 'with_front' => false ),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-heart',
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions', 'page-attributes' ),
        'orderby'             => 'menu_order',
        'order'               => 'ASC',
    );

    register_post_type( 'massage', $args );
}
add_action( 'init', 'lotus_touch_register_massage_cpt' );

/**
 * Register Custom Post Type: Gutschein
 */
function lotus_touch_register_voucher_cpt() {
    $labels = array(
        'name'               => _x( 'Gutscheine', 'Post type general name', 'lotus-touch' ),
        'singular_name'      => _x( 'Gutschein', 'Post type singular name', 'lotus-touch' ),
        'menu_name'          => _x( 'Gutscheine', 'Admin Menu text', 'lotus-touch' ),
        'add_new_item'       => __( 'Neuen Gutschein hinzufügen', 'lotus-touch' ),
        'edit_item'          => __( 'Gutschein bearbeiten', 'lotus-touch' ),
        'all_items'          => __( 'Alle Gutscheine', 'lotus-touch' ),
    );
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'gutschein', 'with_front' => false ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-tickets-alt',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
    );
    register_post_type( 'gutschein', $args );
}
add_action( 'init', 'lotus_touch_register_voucher_cpt' );

/**
 * Massage Meta Box (Bild, kurzer Text, langer Text, mehrere Preise)
 */
function lotus_touch_add_massage_meta_boxes() {
    add_meta_box(
        'lotus_massage_details',
        __( '💰 Massage-Preise (mehrere Optionen möglich)', 'lotus-touch' ),
        'lotus_touch_massage_meta_box_callback',
        'massage',
        'normal',
        'high'
    );

    add_meta_box(
        'lotus_massage_help',
        __( 'ℹ️ Welche Felder wo?', 'lotus-touch' ),
        'lotus_touch_massage_short_text_callback',
        'massage',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'lotus_touch_add_massage_meta_boxes' );

function lotus_touch_massage_meta_box_callback( $post ) {
    wp_nonce_field( 'lotus_massage_save_meta', 'lotus_massage_meta_nonce' );

    $featured = get_post_meta( $post->ID, '_massage_featured', true );
    $prices   = get_post_meta( $post->ID, '_massage_prices', true );
    if ( ! is_array( $prices ) ) $prices = array();
    if ( empty( $prices ) ) {
        $prices = array( array( 'duration' => '60 Minuten', 'price' => '', 'unit' => '€' ) );
    }
    ?>
    <div class="lotus-meta-box">
        <p>
            <label style="display:flex;align-items:center;gap:8px;font-weight:600;">
                <input type="checkbox" name="lotus_massage_featured" value="1" <?php checked( $featured, '1' ); ?> />
                <?php esc_html_e( 'Als beliebt markieren (mit Stern-Badge)', 'lotus-touch' ); ?>
            </label>
        </p>

        <hr style="margin:1.25rem 0;border:none;border-top:1px solid #e5e5e5;" />

        <h3 style="margin:0 0 0.5rem;font-size:1.1rem;"><?php esc_html_e( 'Preise (mehrere möglich)', 'lotus-touch' ); ?></h3>
        <p class="description" style="margin-bottom:1rem;"><?php esc_html_e( 'Füge beliebig viele Preis-Optionen hinzu (z. B. 30 Min / 60 Min / 90 Min).', 'lotus-touch' ); ?></p>

        <div id="lotus-prices-list">
            <?php foreach ( $prices as $i => $row ) :
                $row_type = isset( $row['type'] ) ? $row['type'] : 'regular';
                $row_original = isset( $row['original'] ) ? $row['original'] : '';
            ?>
                <div class="lotus-price-row lotus-price-row-<?php echo esc_attr( $row_type ); ?>" style="display:flex;gap:8px;margin-bottom:8px;align-items:center;flex-wrap:wrap;">
                    <select name="lotus_massage_prices[<?php echo $i; ?>][type]" class="lotus-price-type" style="width:auto;min-width:130px;" title="<?php esc_attr_e( 'Preistyp', 'lotus-touch' ); ?>">
                        <option value="regular" <?php selected( $row_type, 'regular' ); ?>>🏷️ <?php esc_html_e( 'Regulär', 'lotus-touch' ); ?></option>
                        <option value="reduced" <?php selected( $row_type, 'reduced' ); ?>>💚 <?php esc_html_e( 'Ermäßigt', 'lotus-touch' ); ?></option>
                    </select>
                    <input type="text" name="lotus_massage_prices[<?php echo $i; ?>][duration]"
                        value="<?php echo esc_attr( $row['duration'] ?? '' ); ?>"
                        placeholder="<?php esc_attr_e( 'Dauer (z. B. 60 Min.)', 'lotus-touch' ); ?>"
                        style="flex:1;min-width:140px;" />
                    <input type="text" name="lotus_massage_prices[<?php echo $i; ?>][original]"
                        value="<?php echo esc_attr( $row_original ); ?>"
                        placeholder="<?php esc_attr_e( 'Vorher (z. B. 75)', 'lotus-touch' ); ?>"
                        class="lotus-price-original"
                        style="width:90px;display:<?php echo $row_type === 'reduced' ? '' : 'none'; ?>;"
                        title="<?php esc_attr_e( 'Optional: Originalpreis für „statt X€"', 'lotus-touch' ); ?>" />
                    <input type="text" name="lotus_massage_prices[<?php echo $i; ?>][price]"
                        value="<?php echo esc_attr( $row['price'] ?? '' ); ?>"
                        placeholder="<?php esc_attr_e( 'Preis (z. B. 65)', 'lotus-touch' ); ?>"
                        style="width:100px;" />
                    <select name="lotus_massage_prices[<?php echo $i; ?>][unit]" style="width:80px;">
                        <option value="€" <?php selected( $row['unit'] ?? '€', '€' ); ?>>€</option>
                        <option value="CHF" <?php selected( $row['unit'] ?? '', 'CHF' ); ?>>CHF</option>
                        <option value="$" <?php selected( $row['unit'] ?? '', '$' ); ?>>$</option>
                    </select>
                    <button type="button" class="button lotus-price-remove" aria-label="<?php esc_attr_e( 'Entfernen', 'lotus-touch' ); ?>">✕</button>
                </div>
            <?php endforeach; ?>
        </div>

        <p>
            <button type="button" class="button button-secondary" id="lotus-price-add">
                + <?php esc_html_e( 'Preis-Option hinzufügen', 'lotus-touch' ); ?>
            </button>
        </p>

        <p class="description" style="margin-top:0.5rem;">
            <?php esc_html_e( '💚 „Ermäßigt" mit optionalem Vorher-Preis zeigt z. B. „statt 75€ → 65€". Regulär ist Standard.', 'lotus-touch' ); ?>
        </p>

        <!-- Template for new rows -->
        <template id="lotus-price-template">
            <div class="lotus-price-row lotus-price-row-regular" style="display:flex;gap:8px;margin-bottom:8px;align-items:center;flex-wrap:wrap;">
                <select name="lotus_massage_prices[__INDEX__][type]" class="lotus-price-type" style="width:auto;min-width:130px;" title="Preistyp">
                    <option value="regular">🏷️ Regulär</option>
                    <option value="reduced">💚 Ermäßigt</option>
                </select>
                <input type="text" name="lotus_massage_prices[__INDEX__][duration]"
                    placeholder="Dauer (z. B. 60 Min.)"
                    style="flex:1;min-width:140px;" />
                <input type="text" name="lotus_massage_prices[__INDEX__][original]"
                    placeholder="Vorher (optional)"
                    class="lotus-price-original"
                    style="width:90px;display:none;"
                    title="Optionaler Originalpreis" />
                <input type="text" name="lotus_massage_prices[__INDEX__][price]"
                    placeholder="Preis (z. B. 65)"
                    style="width:100px;" />
                <select name="lotus_massage_prices[__INDEX__][unit]" style="width:80px;">
                    <option value="€">€</option>
                    <option value="CHF">CHF</option>
                    <option value="$">$</option>
                </select>
                <button type="button" class="button lotus-price-remove" aria-label="Entfernen">✕</button>
            </div>
        </template>
    </div>
    <?php
}

function lotus_touch_massage_short_text_callback( $post ) {
    // The native "excerpt" field is the short text.
    // The native "content" editor is the long text.
    // This meta box just gives a hint.
    ?>
    <div class="lotus-meta-box">
        <p>
            <strong><?php esc_html_e( 'Kurz:', 'lotus-touch' ); ?></strong>
            <?php esc_html_e( 'Das ist der "Auszug" (rechts oben im Editor). Wird in der Card-Vorschau angezeigt (max. ~150 Zeichen).', 'lotus-touch' ); ?>
        </p>
        <p>
            <strong><?php esc_html_e( 'Lang:', 'lotus-touch' ); ?></strong>
            <?php esc_html_e( 'Das ist der Standard-Editor unter diesem Kasten. Wird im Modal als ausführlicher Beschreibungstext angezeigt.', 'lotus-touch' ); ?>
        </p>
        <p>
            <strong><?php esc_html_e( 'Bild:', 'lotus-touch' ); ?></strong>
            <?php esc_html_e( 'Das "Beitragsbild" rechts (Sidebar). Wird in Card und Modal angezeigt.', 'lotus-touch' ); ?>
        </p>
    </div>
    <?php
}

function lotus_touch_save_massage_meta( $post_id ) {
    if ( ! isset( $_POST['lotus_massage_meta_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['lotus_massage_meta_nonce'], 'lotus_massage_save_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    // Featured
    if ( isset( $_POST['lotus_massage_featured'] ) ) {
        update_post_meta( $post_id, '_massage_featured', '1' );
    } else {
        delete_post_meta( $post_id, '_massage_featured' );
    }

    // Prices
    if ( isset( $_POST['lotus_massage_prices'] ) && is_array( $_POST['lotus_massage_prices'] ) ) {
        $clean = array();
        foreach ( $_POST['lotus_massage_prices'] as $row ) {
            $duration = sanitize_text_field( wp_unslash( $row['duration'] ?? '' ) );
            $price    = sanitize_text_field( wp_unslash( $row['price'] ?? '' ) );
            $unit     = sanitize_text_field( wp_unslash( $row['unit'] ?? '€' ) );
            $type     = sanitize_text_field( wp_unslash( $row['type'] ?? 'regular' ) );
            $original = sanitize_text_field( wp_unslash( $row['original'] ?? '' ) );
            if ( ! in_array( $type, array( 'regular', 'reduced' ), true ) ) {
                $type = 'regular';
            }
            if ( $duration || $price ) {
                $clean[] = array(
                    'duration' => $duration,
                    'price'    => preg_replace( '/[^0-9.,]/', '', $price ),
                    'unit'     => $unit,
                    'type'     => $type,
                    'original' => $original ? preg_replace( '/[^0-9.,]/', '', $original ) : '',
                );
            }
        }
        if ( ! empty( $clean ) ) {
            update_post_meta( $post_id, '_massage_prices', $clean );
        } else {
            delete_post_meta( $post_id, '_massage_prices' );
        }
    } else {
        delete_post_meta( $post_id, '_massage_prices' );
    }

    // Migration: legacy single price fields
    $legacy_price = get_post_meta( $post_id, '_massage_price', true );
    $legacy_dur   = get_post_meta( $post_id, '_massage_duration', true );
    $legacy_unit  = get_post_meta( $post_id, '_massage_unit', true );
    if ( $legacy_price && ! get_post_meta( $post_id, '_massage_prices', true ) ) {
        update_post_meta( $post_id, '_massage_prices', array( array(
            'duration' => $legacy_dur ?: '',
            'price'    => $legacy_price,
            'unit'     => $legacy_unit ?: '€',
            'type'     => 'regular',
            'original' => '',
        ) ) );
    }
}
add_action( 'save_post_massage', 'lotus_touch_save_massage_meta' );

/**
 * Helper: Get massage prices (back-compat with old single price)
 */
function lotus_touch_get_prices( $post_id ) {
    $prices = get_post_meta( $post_id, '_massage_prices', true );
    if ( is_array( $prices ) && ! empty( $prices ) ) {
        return $prices;
    }
    // Fallback: legacy
    $p = get_post_meta( $post_id, '_massage_price', true );
    if ( $p ) {
        return array( array(
            'duration' => get_post_meta( $post_id, '_massage_duration', true ),
            'price'    => $p,
            'unit'     => get_post_meta( $post_id, '_massage_unit', true ) ?: '€',
        ) );
    }
    return array();
}

/**
 * Voucher meta box
 */
function lotus_touch_add_voucher_meta_boxes() {
    add_meta_box(
        'lotus_voucher_details',
        __( 'Gutschein-Details', 'lotus-touch' ),
        'lotus_touch_voucher_meta_box_callback',
        'gutschein',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'lotus_touch_add_voucher_meta_boxes' );

function lotus_touch_voucher_meta_box_callback( $post ) {
    wp_nonce_field( 'lotus_voucher_save_meta', 'lotus_voucher_meta_nonce' );
    $value = get_post_meta( $post->ID, '_voucher_value', true );
    ?>
    <p>
        <label for="lotus_voucher_value" style="display:block;font-weight:600;margin-bottom:4px;">
            <?php esc_html_e( 'Gutscheinwert', 'lotus-touch' ); ?>
        </label>
        <input type="text" id="lotus_voucher_value" name="lotus_voucher_value" value="<?php echo esc_attr( $value ); ?>" style="width:100%" />
    </p>
    <?php
}

function lotus_touch_save_voucher_meta( $post_id ) {
    if ( ! isset( $_POST['lotus_voucher_meta_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['lotus_voucher_meta_nonce'], 'lotus_voucher_save_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
    if ( isset( $_POST['lotus_voucher_value'] ) ) {
        update_post_meta( $post_id, '_voucher_value', sanitize_text_field( wp_unslash( $_POST['lotus_voucher_value'] ) ) );
    }
}
add_action( 'save_post_gutschein', 'lotus_touch_save_voucher_meta' );

/**
 * WordPress Customizer
 */
function lotus_touch_customize_register( $wp_customize ) {

    // ============ Farben ============
    $wp_customize->add_section( 'lotus_colors', array(
        'title'    => __( '🎨 Farben', 'lotus-touch' ),
        'priority' => 29,
    ) );

    $colors = array(
        'lotus_color_primary'        => array( 'label' => 'Primärfarbe (Lila)',         'default' => '#8E5DA8' ),
        'lotus_color_primary_light'  => array( 'label' => 'Primärfarbe (Hell)',         'default' => '#B084CC' ),
        'lotus_color_primary_dark'   => array( 'label' => 'Primärfarbe (Dunkel)',       'default' => '#6B3D85' ),
        'lotus_color_accent'         => array( 'label' => 'Akzent (Rosa)',              'default' => '#E8B4D8' ),
        'lotus_color_accent_warm'    => array( 'label' => 'Akzent Warm (Pfirsich)',     'default' => '#F5D4C3' ),
        'lotus_color_text'           => array( 'label' => 'Text',                       'default' => '#2D1B33' ),
        'lotus_color_text_soft'      => array( 'label' => 'Text (weich)',               'default' => '#5C4A63' ),
        'lotus_color_cream'          => array( 'label' => 'Hintergrund (Creme)',        'default' => '#FBF7F4' ),
        'lotus_color_bg'             => array( 'label' => 'Haupt-Hintergrund',          'default' => '#FFFCFA' ),
    );
    foreach ( $colors as $key => $data ) {
        $wp_customize->add_setting( $key, array(
            'default'           => $data['default'],
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key, array(
            'label'   => $data['label'],
            'section' => 'lotus_colors',
        ) ) );
    }

    // ============ Schriften ============
    $wp_customize->add_section( 'lotus_fonts', array(
        'title'    => __( '🔤 Schriften', 'lotus-touch' ),
        'priority' => 30,
    ) );

    $font_choices = array(
        'Inter'                => 'Inter',
        'Roboto'               => 'Roboto',
        'Open Sans'            => 'Open Sans',
        'Lato'                 => 'Lato',
        'Poppins'              => 'Poppins',
        'Montserrat'           => 'Montserrat',
        'Source Sans Pro'      => 'Source Sans Pro',
        'Nunito'               => 'Nunito',
        'Work Sans'            => 'Work Sans',
        'Playfair Display'     => 'Playfair Display',
        'Merriweather'         => 'Merriweather',
        'Lora'                 => 'Lora',
        'Cormorant Garamond'   => 'Cormorant Garamond',
        'Libre Baskerville'    => 'Libre Baskerville',
        'DM Serif Display'     => 'DM Serif Display',
        'Bitter'               => 'Bitter',
    );

    $wp_customize->add_setting( 'lotus_font_display', array(
        'default' => 'Playfair Display', 'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'lotus_font_display', array(
        'label'   => 'Display / Headlines', 'section' => 'lotus_fonts',
        'type'    => 'select', 'choices' => $font_choices,
    ) );

    $wp_customize->add_setting( 'lotus_font_body', array(
        'default' => 'Inter', 'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'lotus_font_body', array(
        'label'   => 'Body / Fließtext', 'section' => 'lotus_fonts',
        'type'    => 'select', 'choices' => $font_choices,
    ) );

    $wp_customize->add_setting( 'lotus_font_size_base', array(
        'default' => 17, 'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'lotus_font_size_base', array(
        'label'       => 'Basis-Schriftgröße (px)', 'section' => 'lotus_fonts',
        'type'        => 'number', 'input_attrs' => array( 'min' => 14, 'max' => 22 ),
    ) );

    $wp_customize->add_setting( 'lotus_radius', array(
        'default' => 16, 'sanitize_callback' => 'absint', 'transport' => 'refresh',
    ) );
    $wp_customize->add_control( 'lotus_radius', array(
        'label'       => 'Border-Radius (px)', 'section' => 'lotus_fonts',
        'type'        => 'range', 'input_attrs' => array( 'min' => 0, 'max' => 40, 'step' => 2 ),
    ) );

    // ============ Hero ============
    $wp_customize->add_section( 'lotus_hero_section', array(
        'title'    => __( '✨ Hero-Bereich', 'lotus-touch' ),
        'priority' => 31,
    ) );

    $wp_customize->add_setting( 'lotus_hero_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'lotus_hero_image', array(
        'label'       => __( 'Hero-Hintergrundbild', 'lotus-touch' ),
        'description' => __( 'Wird hinter dem Gradient angezeigt. Lila-Gradient bleibt als Overlay darüber (für Lesbarkeit).', 'lotus-touch' ),
        'section'     => 'lotus_hero_section',
    ) ) );

    // Hero-Bild: Anzeige-Optionen
    $wp_customize->add_setting( 'lotus_hero_image_size', array(
        'default' => 'cover', 'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'lotus_hero_image_size', array(
        'label'   => __( 'Bild-Anpassung', 'lotus-touch' ),
        'section' => 'lotus_hero_section',
        'type'    => 'select',
        'choices' => array(
            'cover'   => __( 'Füllend (Cover) – Bild bedeckt komplett, ggf. beschnitten', 'lotus-touch' ),
            'contain' => __( 'Einpassend (Contain) – ganzes Bild sichtbar, ggf. Ränder', 'lotus-touch' ),
        ),
    ) );

    $wp_customize->add_setting( 'lotus_hero_image_position', array(
        'default' => 'center center', 'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'lotus_hero_image_position', array(
        'label'   => __( 'Bild-Position', 'lotus-touch' ),
        'section' => 'lotus_hero_section',
        'type'    => 'select',
        'choices' => array(
            'left top'      => __( 'Oben links', 'lotus-touch' ),
            'center top'    => __( 'Oben mittig', 'lotus-touch' ),
            'right top'     => __( 'Oben rechts', 'lotus-touch' ),
            'left center'   => __( 'Mitte links', 'lotus-touch' ),
            'center center' => __( 'Mitte (Standard)', 'lotus-touch' ),
            'right center'  => __( 'Mitte rechts', 'lotus-touch' ),
            'left bottom'   => __( 'Unten links', 'lotus-touch' ),
            'center bottom' => __( 'Unten mittig', 'lotus-touch' ),
            'right bottom'  => __( 'Unten rechts', 'lotus-touch' ),
        ),
    ) );

    $wp_customize->add_setting( 'lotus_hero_overlay_opacity', array(
        'default' => 60, 'sanitize_callback' => 'absint', 'transport' => 'refresh',
    ) );
    $wp_customize->add_control( 'lotus_hero_overlay_opacity', array(
        'label'       => __( 'Lila-Overlay-Stärke (%)', 'lotus-touch' ),
        'description' => __( 'Höher = mehr Lila-Tönung, niedriger = Bild mehr sichtbar', 'lotus-touch' ),
        'section'     => 'lotus_hero_section',
        'type'        => 'range', 'input_attrs' => array( 'min' => 0, 'max' => 100, 'step' => 5 ),
    ) );

    // Hero Video (MP4)
    $wp_customize->add_setting( 'lotus_hero_video', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'lotus_hero_video', array(
        'label'       => __( 'Hero-Live-Video (MP4/WebM)', 'lotus-touch' ),
        'description' => __( 'Hintergrund-Video statt Bild. Empfohlen: kurze, optimierte Videos (< 5MB). Hat Vorrang vor dem Bild.', 'lotus-touch' ),
        'section'     => 'lotus_hero_section',
        'mime_type'   => 'video',
    ) ) );

    $wp_customize->add_setting( 'lotus_hero_video_poster', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'lotus_hero_video_poster', array(
        'label'       => __( 'Video-Poster-Bild (Fallback)', 'lotus-touch' ),
        'description' => __( 'Wird angezeigt solange das Video lädt oder auf Geräten, die Video nicht abspielen können.', 'lotus-touch' ),
        'section'     => 'lotus_hero_section',
    ) ) );

    // Studio-Icon / Bild (neben Studio-Name)
    $wp_customize->add_setting( 'lotus_studio_icon', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'lotus_studio_icon', array(
        'label'       => __( 'Studio-Icon / Logo (klein)', 'lotus-touch' ),
        'description' => __( 'Wird im Hero über dem Studio-Namen angezeigt. Quadratisches Format empfohlen, z. B. Logo oder Symbol.', 'lotus-touch' ),
        'section'     => 'lotus_hero_section',
    ) ) );

    $wp_customize->add_setting( 'lotus_hero_title', array(
        'default' => 'Lotus Touch', 'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'lotus_hero_title', array(
        'label' => 'Studio-Name', 'section' => 'lotus_hero_section', 'type' => 'text',
    ) );

    $wp_customize->add_setting( 'lotus_hero_tagline', array(
        'default' => 'Zeit für Dich', 'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'lotus_hero_tagline', array(
        'label' => 'Tagline', 'section' => 'lotus_hero_section', 'type' => 'text',
    ) );

    $wp_customize->add_setting( 'lotus_hero_description', array(
        'default' => 'Eine Oase der Ruhe – wo Dein Körper neue Kraft schöpft und Deine Seele zur Ruhe kommt.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'lotus_hero_description', array(
        'label' => 'Beschreibung', 'section' => 'lotus_hero_section', 'type' => 'textarea',
    ) );

    $wp_customize->add_setting( 'lotus_hero_cta_text', array(
        'default' => 'Termin vereinbaren', 'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'lotus_hero_cta_text', array(
        'label' => 'Button-Text (primär)', 'section' => 'lotus_hero_section', 'type' => 'text',
    ) );
    $wp_customize->add_setting( 'lotus_hero_cta_link', array(
        'default' => '#kontakt-formular', 'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'lotus_hero_cta_link', array(
        'label' => 'Button-Link (primär)', 'section' => 'lotus_hero_section', 'type' => 'url',
    ) );
    $wp_customize->add_setting( 'lotus_hero_cta2_text', array(
        'default' => 'Angebot entdecken', 'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'lotus_hero_cta2_text', array(
        'label' => 'Button-Text (sekundär)', 'section' => 'lotus_hero_section', 'type' => 'text',
    ) );
    $wp_customize->add_setting( 'lotus_hero_cta2_link', array(
        'default' => '#angebot', 'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'lotus_hero_cta2_link', array(
        'label' => 'Button-Link (sekundär)', 'section' => 'lotus_hero_section', 'type' => 'url',
    ) );

    // ============ Über uns ============
    $wp_customize->add_section( 'lotus_about_section', array(
        'title' => __( '📖 Über uns', 'lotus-touch' ), 'priority' => 32,
    ) );

    $wp_customize->add_setting( 'lotus_about_eyebrow', array(
        'default' => 'Unsere Geschichte', 'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'lotus_about_eyebrow', array(
        'label' => 'Eyebrow', 'section' => 'lotus_about_section', 'type' => 'text',
    ) );
    $wp_customize->add_setting( 'lotus_about_title', array(
        'default' => 'Wo Körper und Seele zur Ruhe kommen', 'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'lotus_about_title', array(
        'label' => 'Titel', 'section' => 'lotus_about_section', 'type' => 'text',
    ) );
    $wp_customize->add_setting( 'lotus_about_text', array(
        'default' => "In unserem Studio verbinden wir jahrhundertealte Massagetechniken mit modernem Wissen.\n\nJede Behandlung ist so individuell wie Du – wir nehmen uns Zeit für Deine Bedürfnisse und schaffen einen Raum, in dem Du einfach nur sein darfst.",
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'lotus_about_text', array(
        'label' => 'Text (Absätze mit Leerzeile trennen)', 'section' => 'lotus_about_section', 'type' => 'textarea',
    ) );
    $wp_customize->add_setting( 'lotus_about_image', array(
        'default' => '', 'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'lotus_about_image', array(
        'label' => 'Bild', 'section' => 'lotus_about_section',
    ) ) );
    for ( $i = 1; $i <= 4; $i++ ) {
        $defaults = array( 'Zertifizierte Therapeutinnen', 'Hochwertige Öle & Düfte', 'Ruhige, private Atmosphäre', 'Individuelle Behandlungen' );
        $wp_customize->add_setting( "lotus_about_feature_$i", array(
            'default' => $defaults[$i-1], 'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( "lotus_about_feature_$i", array(
            'label' => "Stichpunkt $i", 'section' => 'lotus_about_section', 'type' => 'text',
        ) );
    }

    // ============ Angebot ============
    $wp_customize->add_section( 'lotus_services_section', array(
        'title' => __( '💆 Angebot-Sektion', 'lotus-touch' ), 'priority' => 33,
    ) );
    $wp_customize->add_setting( 'lotus_services_eyebrow', array(
        'default' => 'Unser Angebot', 'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'lotus_services_eyebrow', array(
        'label' => 'Eyebrow', 'section' => 'lotus_services_section', 'type' => 'text',
    ) );
    $wp_customize->add_setting( 'lotus_services_title', array(
        'default' => 'Massagen, die berühren', 'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'lotus_services_title', array(
        'label' => 'Titel', 'section' => 'lotus_services_section', 'type' => 'text',
    ) );
    $wp_customize->add_setting( 'lotus_services_subtitle', array(
        'default' => 'Wähle aus unseren Behandlungen – jede mit eigener Wirkung und individuellem Charme.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'lotus_services_subtitle', array(
        'label' => 'Untertitel', 'section' => 'lotus_services_section', 'type' => 'textarea',
    ) );

    // ============ Gutschein ============
    $wp_customize->add_section( 'lotus_voucher_section', array(
        'title' => __( '🎁 Gutschein-Sektion', 'lotus-touch' ), 'priority' => 34,
    ) );
    $wp_customize->add_setting( 'lotus_voucher_eyebrow', array(
        'default' => 'Verschenke Wohlbefinden', 'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'lotus_voucher_eyebrow', array(
        'label' => 'Eyebrow', 'section' => 'lotus_voucher_section', 'type' => 'text',
    ) );
    $wp_customize->add_setting( 'lotus_voucher_title', array(
        'default' => 'Gutscheine', 'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'lotus_voucher_title', array(
        'label' => 'Titel', 'section' => 'lotus_voucher_section', 'type' => 'text',
    ) );
    $wp_customize->add_setting( 'lotus_voucher_text', array(
        'default' => 'Unsere Gutscheine sind das perfekte Geschenk – für Geburtstage, Jubiläen, als kleine Aufmerksamkeit oder einfach so.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'lotus_voucher_text', array(
        'label' => 'Text', 'section' => 'lotus_voucher_section', 'type' => 'textarea',
    ) );
    $wp_customize->add_setting( 'lotus_voucher_button_text', array(
        'default' => 'Gutschein anfragen', 'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'lotus_voucher_button_text', array(
        'label' => 'Button-Text', 'section' => 'lotus_voucher_section', 'type' => 'text',
    ) );
    $wp_customize->add_setting( 'lotus_voucher_button_link', array(
        'default' => '#kontakt-formular', 'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'lotus_voucher_button_link', array(
        'label' => 'Button-Link', 'section' => 'lotus_voucher_section', 'type' => 'url',
    ) );

    // ============ Kontakt ============
    $wp_customize->add_section( 'lotus_contact_section', array(
        'title' => __( '📞 Kontakt & Footer', 'lotus-touch' ), 'priority' => 35,
    ) );
    $fields = array(
        'lotus_contact_phone'   => array( 'label' => 'Telefon',  'default' => '+49 123 4567890', 'sanitize' => 'sanitize_text_field' ),
        'lotus_contact_email'   => array( 'label' => 'E-Mail',   'default' => 'hallo@lotus-touch.de', 'sanitize' => 'sanitize_email' ),
        'lotus_contact_address' => array( 'label' => 'Adresse',  'default' => "Musterstraße 12\n12345 Musterstadt", 'sanitize' => 'sanitize_textarea_field' ),
        'lotus_contact_hours'   => array( 'label' => 'Öffnungszeiten', 'default' => "Mo – Fr: 10:00 – 19:00\nSa: 10:00 – 16:00\nSo: geschlossen", 'sanitize' => 'sanitize_textarea_field' ),
    );
    foreach ( $fields as $key => $data ) {
        $wp_customize->add_setting( $key, array(
            'default' => $data['default'], 'sanitize_callback' => $data['sanitize'],
        ) );
        $type = ( $key === 'lotus_contact_email' || $key === 'lotus_contact_phone' ) ? 'text' : 'textarea';
        $wp_customize->add_control( $key, array(
            'label' => $data['label'], 'section' => 'lotus_contact_section', 'type' => $type,
        ) );
    }

    // ============ Social Media ============
    $wp_customize->add_section( 'lotus_social_section', array(
        'title' => __( '📱 Social Media', 'lotus-touch' ), 'priority' => 36,
    ) );
    foreach ( array( 'instagram' => 'Instagram', 'facebook' => 'Facebook', 'pinterest' => 'Pinterest' ) as $key => $label ) {
        $wp_customize->add_setting( "lotus_social_$key", array(
            'default' => '', 'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( "lotus_social_$key", array(
            'label' => $label . ' URL', 'section' => 'lotus_social_section', 'type' => 'url',
        ) );
    }
}
add_action( 'customize_register', 'lotus_touch_customize_register' );

/**
 * Apply customizer font size to body
 */
function lotus_touch_customizer_body_fontsize() {
    $size = lotus_touch_opt( 'lotus_font_size_base', 17 );
    echo '<style>body { font-size: ' . intval( $size ) . 'px; }</style>';
}
add_action( 'wp_head', 'lotus_touch_customizer_body_fontsize', 99 );

/**
 * Widget areas
 */
function lotus_touch_widgets_init() {
    register_sidebar( array(
        'name' => esc_html__( 'Footer Spalte 1', 'lotus-touch' ), 'id' => 'footer-1',
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">', 'after_widget' => '</div>',
        'before_title' => '<h4>', 'after_title' => '</h4>',
    ) );
    register_sidebar( array(
        'name' => esc_html__( 'Footer Spalte 2', 'lotus-touch' ), 'id' => 'footer-2',
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">', 'after_widget' => '</div>',
        'before_title' => '<h4>', 'after_title' => '</h4>',
    ) );
    register_sidebar( array(
        'name' => esc_html__( 'Footer Spalte 3', 'lotus-touch' ), 'id' => 'footer-3',
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">', 'after_widget' => '</div>',
        'before_title' => '<h4>', 'after_title' => '</h4>',
    ) );
}
add_action( 'widgets_init', 'lotus_touch_widgets_init' );

/**
 * Excerpt
 */
function lotus_touch_excerpt_length( $length ) { return 24; }
add_filter( 'excerpt_length', 'lotus_touch_excerpt_length' );
function lotus_touch_excerpt_more( $more ) { return '…'; }
add_filter( 'excerpt_more', 'lotus_touch_excerpt_more' );

/**
 * Body classes
 */
function lotus_touch_body_classes( $classes ) {
    if ( ! is_singular() ) $classes[] = 'hfeed';
    if ( is_front_page() ) $classes[] = 'is-frontpage';
    return $classes;
}
add_filter( 'body_class', 'lotus_touch_body_classes' );

/**
 * /suche/ route: use search.php for /suche/ URL
 */
function lotus_touch_search_rewrite() {
    add_rewrite_rule( '^suche/?$', 'index.php?s=', 'top' );
    add_rewrite_rule( '^suche/page/([0-9]+)/?$', 'index.php?s=&paged=$matches[1]', 'top' );
}
add_action( 'init', 'lotus_touch_search_rewrite' );

/**
 * Force search.php template for /suche/
 */
function lotus_touch_search_template( $template ) {
    if ( is_search() ) {
        $search_template = locate_template( 'search.php' );
        if ( $search_template ) return $search_template;
    }
    return $template;
}
add_filter( 'template_include', 'lotus_touch_search_template', 99 );

/**
 * Flush rewrite rules on theme activation
 */
function lotus_touch_activate() {
    lotus_touch_register_massage_cpt();
    lotus_touch_register_voucher_cpt();
    lotus_touch_search_rewrite();
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'lotus_touch_activate' );

/**
 * Flush rewrite on init if not yet
 */
function lotus_touch_maybe_flush_rules() {
    if ( get_option( 'lotus_touch_rewrite_flushed' ) !== '1' ) {
        flush_rewrite_rules();
        update_option( 'lotus_touch_rewrite_flushed', '1' );
    }
}
add_action( 'init', 'lotus_touch_maybe_flush_rules', 99 );

/**
 * AJAX Search: Search through massages + pages + sections
 * Returns JSON with results and direct links
 */
function lotus_touch_search() {
    $query = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
    if ( strlen( $query ) < 2 ) {
        wp_send_json_error( array( 'message' => __( 'Bitte mindestens 2 Zeichen eingeben.', 'lotus-touch' ) ) );
    }

    $results = array();

    // Search massages
    $massages = new WP_Query( array(
        'post_type'      => 'massage',
        'posts_per_page' => 20,
        's'              => $query,
    ) );
    if ( $massages->have_posts() ) {
        while ( $massages->have_posts() ) {
            $massages->the_post();
            $prices = lotus_touch_get_prices( get_the_ID() );
            $first_price = ! empty( $prices ) ? $prices[0] : null;
            $results[] = array(
                'type'     => 'massage',
                'id'       => get_the_ID(),
                'title'    => get_the_title(),
                'excerpt'  => wp_strip_all_tags( get_the_excerpt() ),
                'url'      => home_url( '/#massage-' . get_the_ID() ),
                'anchor'   => 'massage-' . get_the_ID(),
                'meta'     => $first_price ? $first_price['price'] . ' ' . $first_price['unit'] : '',
                'thumb'    => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ),
            );
        }
        wp_reset_postdata();
    }

    // Search pages
    $pages = new WP_Query( array(
        'post_type'      => 'page',
        'posts_per_page' => 10,
        's'              => $query,
    ) );
    if ( $pages->have_posts() ) {
        while ( $pages->have_posts() ) {
            $pages->the_post();
            $results[] = array(
                'type'    => 'page',
                'id'      => get_the_ID(),
                'title'   => get_the_title(),
                'excerpt' => wp_strip_all_tags( get_the_excerpt() ),
                'url'     => get_permalink(),
                'anchor'  => '',
            );
        }
        wp_reset_postdata();
    }

    // Search static sections (customizer content)
    $sections = array(
        array( 'id' => 'hero',          'title' => 'Start',           'fields' => array( 'lotus_hero_title', 'lotus_hero_tagline', 'lotus_hero_description' ) ),
        array( 'id' => 'ueber-uns',     'title' => 'Über uns',        'fields' => array( 'lotus_about_title', 'lotus_about_text' ) ),
        array( 'id' => 'gutschein',     'title' => 'Gutscheine',      'fields' => array( 'lotus_voucher_title', 'lotus_voucher_text' ) ),
        array( 'id' => 'kontakt',       'title' => 'Kontakt',         'fields' => array( 'lotus_contact_phone', 'lotus_contact_email', 'lotus_contact_address', 'lotus_contact_hours' ) ),
    );
    foreach ( $sections as $section ) {
        $hit = false; $hit_field = '';
        foreach ( $section['fields'] as $field ) {
            $val = lotus_touch_opt( $field, '' );
            if ( $val && stripos( $val, $query ) !== false ) {
                $hit = true;
                $hit_field = $field;
                break;
            }
        }
        if ( $hit ) {
            $results[] = array(
                'type'    => 'section',
                'title'   => $section['title'],
                'excerpt' => wp_trim_words( lotus_touch_opt( $hit_field, '' ), 24, '…' ),
                'url'     => home_url( '/#' . $section['id'] ),
                'anchor'  => $section['id'],
            );
        }
    }

    wp_send_json_success( array(
        'query'   => $query,
        'count'   => count( $results ),
        'results' => $results,
    ) );
}
add_action( 'wp_ajax_lotus_search', 'lotus_touch_search' );
add_action( 'wp_ajax_nopriv_lotus_search', 'lotus_touch_search' );

/**
 * Search script data
 */
function lotus_touch_search_data() {
    wp_localize_script( 'lotus-touch-main', 'lotusTouchSearch', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'lotus_touch_search_data', 20 );

/**
 * Contact form handler
 */
function lotus_touch_handle_contact_form() {
    check_ajax_referer( 'lotus_contact_nonce', 'nonce' );
    $name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
    $email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
    $phone   = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
    $subject = sanitize_text_field( wp_unslash( $_POST['subject'] ?? '' ) );
    $message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );
    if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
        wp_send_json_error( array( 'message' => __( 'Bitte fülle alle Pflichtfelder aus.', 'lotus-touch' ) ) );
    }
    if ( ! is_email( $email ) ) {
        wp_send_json_error( array( 'message' => __( 'Bitte gib eine gültige E-Mail-Adresse ein.', 'lotus-touch' ) ) );
    }
    $to      = lotus_touch_opt( 'lotus_contact_email', get_option( 'admin_email' ) );
    $site    = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
    $subj    = sprintf( '[%s] %s', $site, $subject ?: __( 'Neue Kontaktanfrage', 'lotus-touch' ) );
    $body    = sprintf( "Name: %s\nE-Mail: %s\nTelefon: %s\nBetreff: %s\n\nNachricht:\n%s", $name, $email, $phone, $subject, $message );
    $headers = array( 'Reply-To: ' . $name . ' <' . $email . '>', 'Content-Type: text/plain; charset=UTF-8' );
    $sent    = wp_mail( $to, $subj, $body, $headers );
    if ( $sent ) {
        wp_send_json_success( array( 'message' => __( 'Vielen Dank! Deine Nachricht wurde gesendet.', 'lotus-touch' ) ) );
    } else {
        wp_send_json_error( array( 'message' => __( 'Es gab ein Problem beim Senden.', 'lotus-touch' ) ) );
    }
}
add_action( 'wp_ajax_lotus_contact', 'lotus_touch_handle_contact_form' );
add_action( 'wp_ajax_nopriv_lotus_contact', 'lotus_touch_handle_contact_form' );

function lotus_touch_localize_contact() {
    wp_localize_script( 'lotus-touch-main', 'lotusTouchContact', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'lotus_contact_nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'lotus_touch_localize_contact', 20 );

/**
 * Demo content on theme activation (LEGACY – for first install only)
 * New installs use the manual button in admin notices.
 */
function lotus_touch_demo_content() {
    $existing = get_posts( array( 'post_type' => 'massage', 'posts_per_page' => 1 ) );
    if ( ! empty( $existing ) ) return;

    $demo = array(
        array( 'Klassische Massage',         'Wohltuende Ganzkörper-Massage, die Verspannungen löst und neue Energie schenkt.', array( array( '30 Minuten', '45' ), array( '60 Minuten', '65' ), array( '90 Minuten', '85' ) ) ),
        array( 'Aromatherapie Massage',      'Sanfte Streichungen kombiniert mit ätherischen Ölen – ein Fest für die Sinne.', array( array( '60 Minuten', '75' ), array( '90 Minuten', '95' ) ) ),
        array( 'Hot Stone Massage',          'Warme Basaltsteine lösen tiefe Verspannungen und schenken wohlige Wärme.', array( array( '60 Minuten', '85' ), array( '90 Minuten', '110' ) ) ),
        array( 'Schwangerschafts-Massage',   'Speziell auf die Bedürfnisse werdender Mütter abgestimmt – sanft und sicher.', array( array( '45 Minuten', '70' ), array( '60 Minuten', '85' ) ) ),
        array( 'Rücken & Nacken Intensiv',   'Gezielte Behandlung für alle, die viel am Schreibtisch sitzen – schnell und effektiv.', array( array( '30 Minuten', '45' ) ) ),
        array( 'Paar-Massage',               'Gemeinsam entspannen – nebeneinander, in ruhiger Atmosphäre, mit feinen Ölen.', array( array( '60 Minuten', '180' ), array( '90 Minuten', '240' ) ) ),
    );

    foreach ( $demo as $i => $m ) {
        list( $title, $excerpt, $price_rows ) = $m;
        $post_id = wp_insert_post( array(
            'post_title'   => $title,
            'post_excerpt' => $excerpt,
            'post_content' => $excerpt . "\n\nDiese Behandlung ist Teil unseres Verwöhnprogramms. Wir verwenden ausschließlich hochwertige, natürliche Öle und passen den Druck individuell an Deine Wünsche an.\n\nWirkung: tiefe Entspannung, Stressabbau, Lösung von Verspannungen.\n\nAblauf: Vorgespräch → Vorbereitung → Massage → Nachruhe.",
            'post_type'    => 'massage',
            'post_status'  => 'publish',
            'menu_order'   => $i,
        ) );
        if ( $post_id ) {
            $prices = array();
            foreach ( $price_rows as $row ) {
                $prices[] = array( 'duration' => $row[0], 'price' => $row[1], 'unit' => '€' );
            }
            update_post_meta( $post_id, '_massage_prices', $prices );
            if ( $i < 2 ) update_post_meta( $post_id, '_massage_featured', '1' );
        }
    }

    // Pages
    $pages = array(
        'impressum'   => 'Impressum',
        'datenschutz' => 'Datenschutzerklärung',
        'kontakt'     => 'Kontakt',
    );
    foreach ( $pages as $slug => $title ) {
        if ( ! get_page_by_path( $slug ) ) {
            wp_insert_post( array(
                'post_title'   => $title, 'post_name' => $slug,
                'post_content' => '<!-- Inhalt im WP-Admin unter "Seiten" bearbeiten. -->',
                'post_type'    => 'page', 'post_status' => 'publish',
            ) );
        }
    }
}
add_action( 'after_switch_theme', 'lotus_touch_demo_content' );

/**
 * ===============================================================
 *  DEMO-DATEN: Manueller Button im Admin
 * ===============================================================
 * User kann jederzeit auf "Beispieldaten einfügen" klicken,
 * um 6 Demo-Massagen + die rechtlichen Seiten zu erstellen.
 */
function lotus_touch_demo_nonce_action() { return 'lotus_touch_demo_install'; }
function lotus_touch_admin_notice_demo() {
    $screen = get_current_screen();
    if ( ! $screen || ! in_array( $screen->id, array( 'dashboard', 'themes', 'appearance_page_customizer' ), true ) ) {
        return;
    }
    $has_demo = get_posts( array( 'post_type' => 'massage', 'posts_per_page' => 1 ) );
    if ( ! empty( $has_demo ) ) return;
    $url = wp_nonce_url( add_query_arg( 'lotus_demo', '1' ), lotus_touch_demo_nonce_action() );
    ?>
    <div class="notice notice-info is-dismissible" style="border-left-color:#8E5DA8;">
        <p>
            <strong>🌸 Lotus Touch Theme:</strong>
            <?php esc_html_e( 'Möchtest du Beispieldaten (6 Demo-Massagen + rechtliche Seiten) einfügen, um den vollen Funktionsumfang zu sehen?', 'lotus-touch' ); ?>
        </p>
        <p>
            <a href="<?php echo esc_url( $url ); ?>" class="button button-primary">
                ✨ <?php esc_html_e( 'Beispieldaten einfügen', 'lotus-touch' ); ?>
            </a>
            <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button">
                <?php esc_html_e( 'Theme leeren und selbst füllen', 'lotus-touch' ); ?>
            </a>
        </p>
    </div>
    <?php
}
add_action( 'admin_notices', 'lotus_touch_admin_notice_demo' );

/**
 * Handle demo-install click
 */
function lotus_touch_handle_demo_install() {
    if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Keine Berechtigung.', 'lotus-touch' ) );
    }
    if ( ! isset( $_GET['lotus_demo'] ) || $_GET['lotus_demo'] !== '1' ) {
        return;
    }
    check_admin_referer( lotus_touch_demo_nonce_action() );

    // Empty existing massages first (only if user explicitly chose to install demo)
    $existing = get_posts( array( 'post_type' => 'massage', 'posts_per_page' => -1, 'post_status' => 'any' ) );
    foreach ( $existing as $p ) {
        wp_delete_post( $p->ID, true );
    }

    // Now install demo
    lotus_touch_install_demo_data();

    wp_safe_redirect( admin_url( 'edit.php?post_type=massage&lotus_demo=installed' ) );
    exit;
}
add_action( 'admin_init', 'lotus_touch_handle_demo_install' );

/**
 * Install demo data (separated function so it can be called multiple times)
 */
function lotus_touch_install_demo_data() {
    // Pages
    $pages = array(
        'impressum'   => array( 'Impressum', "Angaben gemäß § 5 TMG\n\nMax Mustermann\nMusterstraße 12\n12345 Musterstadt\n\nKontakt:\nTelefon: +49 123 4567890\nE-Mail: hallo@lotus-touch.de\n\nUmsatzsteuer-ID: DE123456789" ),
        'datenschutz' => array( 'Datenschutzerklärung', "Diese Website nutzt Daten ausschließlich zur Kontaktaufnahme und Terminanfrage. Es werden keine Daten an Dritte weitergegeben.\n\nDeine Rechte: Du kannst jederzeit Auskunft über gespeicherte Daten verlangen sowie deren Löschung beantragen." ),
    );
    foreach ( $pages as $slug => $data ) {
        if ( ! get_page_by_path( $slug ) ) {
            wp_insert_post( array(
                'post_title'   => $data[0],
                'post_name'    => $slug,
                'post_content' => $data[1],
                'post_type'    => 'page',
                'post_status'  => 'publish',
            ) );
        }
    }

    // 6 Demo-Massagen mit mehreren Preisen (inkl. Reduced)
    $demo = array(
        array(
            'Klassische Massage',
            'Wohltuende Ganzkörper-Massage, die Verspannungen löst und neue Energie schenkt.',
            "Diese Behandlung ist Teil unseres Verwöhnprogramms. Wir verwenden ausschließlich hochwertige, natürliche Öle und passen den Druck individuell an Deine Wünsche an.\n\nWirkung: tiefe Entspannung, Stressabbau, Lösung von Verspannungen.\n\nAblauf: Vorgespräch → Vorbereitung → Massage → Nachruhe.",
            array(
                array( 'duration' => '30 Minuten', 'price' => '45', 'unit' => '€', 'type' => 'regular' ),
                array( 'duration' => '60 Minuten', 'price' => '65', 'unit' => '€', 'type' => 'regular' ),
                array( 'duration' => '90 Minuten', 'price' => '85', 'unit' => '€', 'type' => 'regular' ),
            ),
            true,
        ),
        array(
            'Aromatherapie Massage',
            'Sanfte Streichungen kombiniert mit ätherischen Ölen – ein Fest für die Sinne.',
            "Sanfte, fließende Bewegungen in Kombination mit hochwertigen ätherischen Ölen wie Lavendel, Ylang-Ylang oder Rosengeranie. Wirkt besonders ausgleichend bei Stress und Schlafproblemen.\n\nDauer: 60–90 Minuten\nWirkung: tiefe Entspannung, bessere Stimmung, ausgeglichener Schlaf.",
            array(
                array( 'duration' => '60 Minuten', 'price' => '75', 'unit' => '€', 'type' => 'regular' ),
                array( 'duration' => '90 Minuten', 'price' => '95', 'unit' => '€', 'type' => 'regular' ),
            ),
            true,
        ),
        array(
            'Hot Stone Massage',
            'Warme Basaltsteine lösen tiefe Verspannungen und schenken wohlige Wärme.',
            "Glattgeschliffene Basaltsteine werden auf circa 50°C erhitzt und entlang der Energielinien des Körpers aufgelegt. Die Wärme dringt tief in die Muskulatur und löst selbst hartnäckige Verspannungen.\n\nIdeal bei: Rückenschmerzen, Muskelverspannungen, Stress.",
            array(
                array( 'duration' => '60 Minuten', 'price' => '85', 'unit' => '€', 'type' => 'regular' ),
                array( 'duration' => '90 Minuten', 'price' => '110', 'unit' => '€', 'type' => 'regular' ),
            ),
            false,
        ),
        array(
            'Schwangerschafts-Massage',
            'Speziell auf die Bedürfnisse werdender Mütter abgestimmt – sanft und sicher.',
            "Speziell ausgebildete Therapeutinnen begleiten Dich mit besonders sanften Griffen durch diese besondere Zeit. Spezielle Lagerungskissen sorgen für Komfort in jeder Position.\n\nEmpfohlen ab dem 2. Trimester.\nWirkung: Entlastung des Rückens, bessere Durchblutung, weniger Wassereinlagerungen.",
            array(
                array( 'duration' => '45 Minuten', 'price' => '60', 'unit' => '€', 'type' => 'reduced', 'original' => '70' ),
                array( 'duration' => '60 Minuten', 'price' => '75', 'unit' => '€', 'type' => 'reduced', 'original' => '85' ),
            ),
            false,
        ),
        array(
            'Rücken & Nacken Intensiv',
            'Gezielte Behandlung für alle, die viel am Schreibtisch sitzen – schnell und effektiv.',
            "Konzentrierte Tiefenarbeit an Schulter, Nacken und oberem Rücken. Perfekt für den Büroalltag oder nach dem Sport. Auf Wunsch mit Triggerpunkt-Behandlung.\n\nSchnelle Hilfe bei:\n• Nackenverspannungen\n• Kopfschmerzen (Spannungstyp)\n• Schultersteifigkeit",
            array(
                array( 'duration' => '30 Minuten', 'price' => '45', 'unit' => '€', 'type' => 'regular' ),
            ),
            false,
        ),
        array(
            'Paar-Massage',
            'Gemeinsam entspannen – nebeneinander, in ruhiger Atmosphäre, mit feinen Ölen.',
            "Gönnt Euch gemeinsame Auszeit zu zweit. Zwei Massageliegen, zwei Therapeutinnen, ein wunderbares Erlebnis. Ideal als Geschenk, Jahrestag oder einfach als bewusste Pause vom Alltag.\n\nInklusive: Tee & Ruhezone danach.",
            array(
                array( 'duration' => '60 Minuten', 'price' => '180', 'unit' => '€', 'type' => 'regular' ),
                array( 'duration' => '90 Minuten', 'price' => '240', 'unit' => '€', 'type' => 'regular' ),
            ),
            false,
        ),
    );

    $i = 0;
    foreach ( $demo as $m ) {
        list( $title, $excerpt, $content, $prices, $featured ) = $m;
        $post_id = wp_insert_post( array(
            'post_title'   => $title,
            'post_excerpt' => $excerpt,
            'post_content' => $content,
            'post_type'    => 'massage',
            'post_status'  => 'publish',
            'menu_order'   => $i,
        ) );
        if ( $post_id ) {
            update_post_meta( $post_id, '_massage_prices', $prices );
            if ( $featured ) {
                update_post_meta( $post_id, '_massage_featured', '1' );
            }
        }
        $i++;
    }

    // Set default customizer values for demo
    set_theme_mod( 'lotus_hero_title', 'Lotus Touch' );
    set_theme_mod( 'lotus_hero_tagline', 'Zeit für Dich' );
    set_theme_mod( 'lotus_hero_description', 'Eine Oase der Ruhe – wo Dein Körper neue Kraft schöpft und Deine Seele zur Ruhe kommt.' );
    set_theme_mod( 'lotus_about_title', 'Wo Körper und Seele zur Ruhe kommen' );
    set_theme_mod( 'lotus_contact_phone', '+49 123 4567890' );
    set_theme_mod( 'lotus_contact_email', 'hallo@lotus-touch.de' );
    set_theme_mod( 'lotus_contact_address', "Musterstraße 12\n12345 Musterstadt" );
    set_theme_mod( 'lotus_contact_hours', "Mo – Fr: 10:00 – 19:00\nSa: 10:00 – 16:00\nSo: geschlossen" );
}

/**
 * After-install: notify user
 */
function lotus_touch_admin_notice_installed() {
    if ( ! isset( $_GET['lotus_demo'] ) || $_GET['lotus_demo'] !== 'installed' ) return;
    ?>
    <div class="notice notice-success is-dismissible">
        <p>
            <strong>✅ <?php esc_html_e( 'Beispieldaten erfolgreich eingefügt!', 'lotus-touch' ); ?></strong>
            <?php esc_html_e( 'Du findest sie unter Massagen → Alle Massagen. Bilder kannst du im Editor hinzufügen.', 'lotus-touch' ); ?>
        </p>
    </div>
    <?php
}
add_action( 'admin_notices', 'lotus_touch_admin_notice_installed' );
