<?php
/**
 * Front Page Template (Landingpage mit allen Sektionen)
 *
 * @package LotusTouch
 */

get_header();
?>

<main id="main" class="site-main">

    <?php
    // HERO
    $hero_title       = lotus_touch_opt( 'lotus_hero_title', get_bloginfo( 'name' ) );
    $hero_tagline     = lotus_touch_opt( 'lotus_hero_tagline' );
    $hero_description = lotus_touch_opt( 'lotus_hero_description' );
    $hero_cta_text    = lotus_touch_opt( 'lotus_hero_cta_text', __( 'Termin vereinbaren', 'lotus-touch' ) );
    $hero_cta_link    = lotus_touch_opt( 'lotus_hero_cta_link', '#kontakt-formular' );
    $hero_cta2_text   = lotus_touch_opt( 'lotus_hero_cta2_text', __( 'Angebot entdecken', 'lotus-touch' ) );
    $hero_cta2_link   = lotus_touch_opt( 'lotus_hero_cta2_link', '#angebot' );
    $hero_image       = lotus_touch_opt( 'lotus_hero_image', '' );
    $hero_video       = lotus_touch_opt( 'lotus_hero_video', '' );
    $hero_video_poster= lotus_touch_opt( 'lotus_hero_video_poster', '' );
    $hero_size        = lotus_touch_opt( 'lotus_hero_image_size', 'cover' );
    $hero_position    = lotus_touch_opt( 'lotus_hero_image_position', 'center center' );
    $hero_overlay     = absint( lotus_touch_opt( 'lotus_hero_overlay_opacity', 60 ) );
    $studio_icon      = lotus_touch_opt( 'lotus_studio_icon', '' );

    // Build classes & style
    $hero_classes = array( 'hero' );
    $hero_inline  = '';
    $overlay_opacity = $hero_overlay / 100;

    if ( $hero_video ) {
        $hero_classes[] = 'has-bg-video';
    } elseif ( $hero_image ) {
        $hero_classes[] = 'has-bg-image';
        $hero_inline = ' style="background-image: url(' . esc_url( $hero_image ) . '); background-size: ' . esc_attr( $hero_size ) . '; background-position: ' . esc_attr( $hero_position ) . ';"';
    }
    if ( $hero_overlay < 100 ) {
        $hero_classes[] = 'has-overlay';
    }
    $hero_classes[] = 'overlay-opacity-' . $hero_overlay;
    ?>

    <section class="<?php echo esc_attr( implode( ' ', $hero_classes ) ); ?>" id="hero" data-searchable<?php echo $hero_inline; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
        <?php if ( $hero_video ) : ?>
            <video class="hero-video" autoplay muted loop playsinline poster="<?php echo esc_url( $hero_video_poster ); ?>" preload="metadata">
                <source src="<?php echo esc_url( $hero_video ); ?>" type="video/mp4">
            </video>
        <?php endif; ?>

        <div class="hero-content">
            <?php if ( $studio_icon ) : ?>
                <div class="hero-studio-icon fade-in">
                    <img src="<?php echo esc_url( $studio_icon ); ?>" alt="<?php echo esc_attr( $hero_title ); ?>" />
                </div>
            <?php elseif ( has_custom_logo() ) : ?>
                <div class="hero-logo"><?php the_custom_logo(); ?></div>
            <?php endif; ?>

            <?php if ( $hero_tagline ) : ?>
                <p class="hero-tagline fade-in"><?php echo esc_html( $hero_tagline ); ?></p>
            <?php endif; ?>

            <h1 class="hero-title fade-up"><?php echo esc_html( $hero_title ); ?></h1>

            <?php if ( $hero_description ) : ?>
                <p class="hero-description fade-up"><?php echo esc_html( $hero_description ); ?></p>
            <?php endif; ?>

            <div class="hero-cta fade-up">
                <?php if ( $hero_cta_text ) : ?>
                    <a href="<?php echo esc_url( $hero_cta_link ); ?>" class="btn btn-light">
                        <?php echo esc_html( $hero_cta_text ); ?>
                        <span aria-hidden="true">→</span>
                    </a>
                <?php endif; ?>
                <?php if ( $hero_cta2_text ) : ?>
                    <a href="<?php echo esc_url( $hero_cta2_link ); ?>" class="btn btn-outline" style="color:white;border-color:rgba(255,255,255,0.6);">
                        <?php echo esc_html( $hero_cta2_text ); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <a href="#ueber-uns" class="scroll-indicator" aria-label="<?php esc_attr_e( 'Nach unten scrollen', 'lotus-touch' ); ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M7 13l5 5 5-5M7 6l5 5 5-5"/>
            </svg>
        </a>
    </section>

    <?php
    // ÜBER UNS
    $about_eyebrow = lotus_touch_opt( 'lotus_about_eyebrow', __( 'Unsere Geschichte', 'lotus-touch' ) );
    $about_title   = lotus_touch_opt( 'lotus_about_title' );
    $about_text    = lotus_touch_opt( 'lotus_about_text' );
    $about_image   = lotus_touch_opt( 'lotus_about_image' );
    ?>

    <section class="section about-section" id="ueber-uns" data-searchable>
        <div class="container">
            <div class="about-grid">

                <div class="about-image-wrap fade-in">
                    <?php if ( $about_image ) : ?>
                        <div class="about-image">
                            <img src="<?php echo esc_url( $about_image ); ?>" alt="<?php echo esc_attr( $about_title ); ?>" loading="lazy" />
                        </div>
                    <?php else : ?>
                        <div class="about-image" style="background:var(--gradient-soft);display:flex;align-items:center;justify-content:center;color:var(--color-primary);">
                            <svg width="120" height="120" viewBox="0 0 100 100" fill="currentColor" opacity="0.4">
                                <path d="M50 10 C 30 30, 20 50, 50 90 C 80 50, 70 30, 50 10 Z" />
                                <path d="M50 30 C 40 45, 35 55, 50 75 C 65 55, 60 45, 50 30 Z" fill="var(--color-accent)" />
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="about-content fade-up">
                    <?php if ( $about_eyebrow ) : ?>
                        <span class="section-eyebrow"><?php echo esc_html( $about_eyebrow ); ?></span>
                    <?php endif; ?>

                    <?php if ( $about_title ) : ?>
                        <h2 class="section-title"><?php echo esc_html( $about_title ); ?></h2>
                    <?php endif; ?>

                    <?php if ( $about_text ) : ?>
                        <?php
                        $paragraphs = explode( "\n\n", $about_text );
                        foreach ( $paragraphs as $p ) :
                            $p = trim( $p );
                            if ( $p ) echo '<p>' . esc_html( $p ) . '</p>';
                        endforeach;
                        ?>
                    <?php endif; ?>

                    <?php
                    $features = array();
                    for ( $i = 1; $i <= 4; $i++ ) {
                        $f = lotus_touch_opt( "lotus_about_feature_$i" );
                        if ( $f ) $features[] = $f;
                    }
                    if ( ! empty( $features ) ) : ?>
                        <ul class="about-features">
                            <?php foreach ( $features as $f ) : ?>
                                <li><?php echo esc_html( $f ); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>

    <?php
    // ANGEBOT / MASSAGEN
    $services_eyebrow  = lotus_touch_opt( 'lotus_services_eyebrow', __( 'Unser Angebot', 'lotus-touch' ) );
    $services_title    = lotus_touch_opt( 'lotus_services_title' );
    $services_subtitle = lotus_touch_opt( 'lotus_services_subtitle' );

    $massages = new WP_Query( array(
        'post_type'      => 'massage',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ) );
    ?>

    <section class="section services-section" id="angebot" data-searchable>
        <div class="container">
            <div class="text-center">
                <?php if ( $services_eyebrow ) : ?>
                    <span class="section-eyebrow fade-in"><?php echo esc_html( $services_eyebrow ); ?></span>
                <?php endif; ?>

                <?php if ( $services_title ) : ?>
                    <h2 class="section-title fade-up"><?php echo esc_html( $services_title ); ?></h2>
                <?php endif; ?>

                <?php if ( $services_subtitle ) : ?>
                    <p class="section-subtitle fade-up"><?php echo esc_html( $services_subtitle ); ?></p>
                <?php endif; ?>
            </div>

            <?php if ( $massages->have_posts() ) : ?>

                <div class="services-grid">
                    <?php
                    while ( $massages->have_posts() ) : $massages->the_post();
                        $prices    = lotus_touch_get_prices( get_the_ID() );
                        $featured  = get_post_meta( get_the_ID(), '_massage_featured', true );
                        $thumb_id  = get_post_thumbnail_id();
                        $thumb_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'lotus-massage-hero' ) : '';
                        $excerpt   = has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 24, '…' );
                        $content_html = apply_filters( 'the_content', get_the_content() );

                        // Build prices JSON for modal
                        $prices_json = array();
                        foreach ( $prices as $p ) {
                            $prices_json[] = array(
                                'duration' => $p['duration'] ?? '',
                                'price'    => $p['price'] ?? '',
                                'unit'     => $p['unit'] ?? '€',
                                'type'     => isset( $p['type'] ) ? $p['type'] : 'regular',
                                'original' => isset( $p['original'] ) ? $p['original'] : '',
                            );
                        }

                        // First price for card preview
                        $first_price = ! empty( $prices_json ) ? $prices_json[0] : null;
                        $first_is_reduced = $first_price && ( $first_price['type'] === 'reduced' );
                    ?>
                        <article class="service-card fade-up" id="massage-<?php the_ID(); ?>"
                            data-search-item
                            data-search-text="<?php echo esc_attr( strtolower( get_the_title() . ' ' . wp_strip_all_tags( get_the_content() ) . ' ' . wp_strip_all_tags( $excerpt ) ) ); ?>">

                            <button type="button" class="service-card-button" data-massage-modal
                                data-id="<?php echo esc_attr( get_the_ID() ); ?>"
                                data-title="<?php echo esc_attr( get_the_title() ); ?>"
                                data-excerpt="<?php echo esc_attr( $excerpt ); ?>"
                                data-content-html="<?php echo esc_attr( $content_html ); ?>"
                                data-prices='<?php echo esc_attr( json_encode( $prices_json ) ); ?>'
                                data-image="<?php echo esc_url( $thumb_url ); ?>"
                                data-featured="<?php echo esc_attr( $featured ); ?>"
                                aria-haspopup="dialog"
                                aria-label="<?php echo esc_attr( sprintf( __( 'Details zu %s anzeigen', 'lotus-touch' ), get_the_title() ) ); ?>"></button>

                            <div class="service-card-image">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'lotus-massage-card', array( 'loading' => 'lazy' ) ); ?>
                                <?php endif; ?>
                                <?php if ( $featured ) : ?>
                                    <span class="service-card-badge">✦ <?php esc_html_e( 'Beliebt', 'lotus-touch' ); ?></span>
                                <?php endif; ?>
                                <span class="service-card-zoom" aria-hidden="true">＋</span>
                            </div>

                            <div class="service-card-body">
                                <h3 class="service-card-title"><?php the_title(); ?></h3>

                                <?php if ( $excerpt ) : ?>
                                    <p class="service-card-excerpt"><?php echo esc_html( $excerpt ); ?></p>
                                <?php endif; ?>

                                <?php if ( $first_price ) : ?>
                                    <div class="service-card-price-block">
                                        <span class="service-card-price-from"><?php esc_html_e( 'ab', 'lotus-touch' ); ?></span>
                                        <span class="service-card-price-value">
                                            <?php echo esc_html( $first_price['price'] . ' ' . $first_price['unit'] ); ?>
                                        </span>
                                        <?php if ( ! empty( $first_price['duration'] ) ) : ?>
                                            <span class="service-card-price-duration"><?php echo esc_html( $first_price['duration'] ); ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="service-card-cta">
                                    <span class="service-card-more"><?php esc_html_e( 'Details ansehen', 'lotus-touch' ); ?> →</span>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>

            <?php else : ?>
                <p class="text-center" style="color:var(--color-text-soft);">
                    <?php esc_html_e( 'Aktuell werden die Angebote vorbereitet. Schau bald wieder vorbei!', 'lotus-touch' ); ?>
                </p>
            <?php endif; ?>

        </div>
    </section>

    <?php
    // GUTSCHEIN
    $voucher_eyebrow     = lotus_touch_opt( 'lotus_voucher_eyebrow', __( 'Verschenke Wohlbefinden', 'lotus-touch' ) );
    $voucher_title       = lotus_touch_opt( 'lotus_voucher_title', __( 'Gutscheine', 'lotus-touch' ) );
    $voucher_text        = lotus_touch_opt( 'lotus_voucher_text' );
    $voucher_button_text = lotus_touch_opt( 'lotus_voucher_button_text', __( 'Gutschein anfragen', 'lotus-touch' ) );
    $voucher_button_link = lotus_touch_opt( 'lotus_voucher_button_link', '#kontakt-formular' );
    ?>

    <section class="section voucher-section" id="gutschein" data-searchable>
        <div class="container">
            <div class="voucher-card scale-in">
                <div class="voucher-card-image">
                    <div class="voucher-card-image-content">
                        <span class="voucher-icon" aria-hidden="true">🎁</span>
                        <h3><?php esc_html_e( 'Geschenk', 'lotus-touch' ); ?></h3>
                        <p style="opacity:0.9;"><?php esc_html_e( 'für Dich & Deine Liebsten', 'lotus-touch' ); ?></p>
                    </div>
                </div>

                <div class="voucher-card-body">
                    <?php if ( $voucher_eyebrow ) : ?>
                        <span class="section-eyebrow"><?php echo esc_html( $voucher_eyebrow ); ?></span>
                    <?php endif; ?>

                    <?php if ( $voucher_title ) : ?>
                        <h2 class="section-title"><?php echo esc_html( $voucher_title ); ?></h2>
                    <?php endif; ?>

                    <?php if ( $voucher_text ) : ?>
                        <p><?php echo esc_html( $voucher_text ); ?></p>
                    <?php endif; ?>

                    <ul class="voucher-features">
                        <li><?php esc_html_e( 'Wert frei wählbar oder für eine bestimmte Behandlung', 'lotus-touch' ); ?></li>
                        <li><?php esc_html_e( 'Persönlich gestaltet und auf Wunsch per Post', 'lotus-touch' ); ?></li>
                        <li><?php esc_html_e( '3 Jahre gültig', 'lotus-touch' ); ?></li>
                        <li><?php esc_html_e( 'Auch kurzfristig als Last-Minute-Geschenk', 'lotus-touch' ); ?></li>
                    </ul>

                    <?php if ( $voucher_button_text ) : ?>
                        <a href="<?php echo esc_url( $voucher_button_link ); ?>" class="btn btn-primary">
                            <?php echo esc_html( $voucher_button_text ); ?>
                            <span aria-hidden="true">→</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php
    // KONTAKT
    $phone   = lotus_touch_opt( 'lotus_contact_phone' );
    $email   = lotus_touch_opt( 'lotus_contact_email' );
    $address = lotus_touch_opt( 'lotus_contact_address' );
    $hours   = lotus_touch_opt( 'lotus_contact_hours' );
    ?>

    <section class="section contact-section" id="kontakt-formular" data-searchable>
        <div class="container">
            <div class="text-center">
                <span class="section-eyebrow fade-in"><?php esc_html_e( 'Schreib uns', 'lotus-touch' ); ?></span>
                <h2 class="section-title fade-up"><?php esc_html_e( 'Kontakt aufnehmen', 'lotus-touch' ); ?></h2>
                <p class="section-subtitle fade-up"><?php esc_html_e( 'Wir melden uns innerhalb von 24 Stunden bei Dir. Versprochen.', 'lotus-touch' ); ?></p>
            </div>

            <div class="contact-grid">

                <div class="contact-info fade-in">
                    <h2><?php esc_html_e( 'So erreichst Du uns', 'lotus-touch' ); ?></h2>
                    <p><?php esc_html_e( 'Ob telefonisch, per E-Mail oder persönlich – wir freuen uns auf Dich.', 'lotus-touch' ); ?></p>

                    <ul class="contact-details">
                        <?php if ( $phone ) : ?>
                            <li>
                                <span class="icon" aria-hidden="true">📞</span>
                                <div>
                                    <strong><?php esc_html_e( 'Telefon', 'lotus-touch' ); ?></strong>
                                    <a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
                                </div>
                            </li>
                        <?php endif; ?>

                        <?php if ( $email ) : ?>
                            <li>
                                <span class="icon" aria-hidden="true">✉</span>
                                <div>
                                    <strong><?php esc_html_e( 'E-Mail', 'lotus-touch' ); ?></strong>
                                    <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                                </div>
                            </li>
                        <?php endif; ?>

                        <?php if ( $address ) : ?>
                            <li>
                                <span class="icon" aria-hidden="true">📍</span>
                                <div>
                                    <strong><?php esc_html_e( 'Adresse', 'lotus-touch' ); ?></strong>
                                    <span style="opacity:0.95;display:block;white-space:pre-line;"><?php echo esc_html( $address ); ?></span>
                                </div>
                            </li>
                        <?php endif; ?>

                        <?php if ( $hours ) : ?>
                            <li>
                                <span class="icon" aria-hidden="true">🕐</span>
                                <div>
                                    <strong><?php esc_html_e( 'Öffnungszeiten', 'lotus-touch' ); ?></strong>
                                    <span style="opacity:0.95;display:block;white-space:pre-line;"><?php echo esc_html( $hours ); ?></span>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="contact-form fade-up">
                    <h3><?php esc_html_e( 'Nachricht senden', 'lotus-touch' ); ?></h3>

                    <form id="lotusContactForm" novalidate>
                        <div class="form-grid">
                            <div class="form-row">
                                <label for="cf-name"><?php esc_html_e( 'Name *', 'lotus-touch' ); ?></label>
                                <input type="text" id="cf-name" name="name" required autocomplete="name" />
                            </div>
                            <div class="form-row">
                                <label for="cf-email"><?php esc_html_e( 'E-Mail *', 'lotus-touch' ); ?></label>
                                <input type="email" id="cf-email" name="email" required autocomplete="email" />
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-row">
                                <label for="cf-phone"><?php esc_html_e( 'Telefon', 'lotus-touch' ); ?></label>
                                <input type="tel" id="cf-phone" name="phone" autocomplete="tel" />
                            </div>
                            <div class="form-row">
                                <label for="cf-subject"><?php esc_html_e( 'Betreff', 'lotus-touch' ); ?></label>
                                <input type="text" id="cf-subject" name="subject" />
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="cf-message"><?php esc_html_e( 'Nachricht *', 'lotus-touch' ); ?></label>
                            <textarea id="cf-message" name="message" rows="5" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width:100%;">
                            <span class="btn-text"><?php esc_html_e( 'Nachricht senden', 'lotus-touch' ); ?></span>
                        </button>

                        <div class="form-status" id="formStatus" role="status" aria-live="polite" style="margin-top:1rem;display:none;"></div>
                    </form>
                </div>

            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>