<?php
/**
 * The Footer
 *
 * @package LotusTouch
 */
?>

<footer class="site-footer" id="kontakt">
    <div class="container">

        <div class="footer-grid">

            <div class="footer-brand">
                <h3><?php bloginfo( 'name' ); ?></h3>
                <?php
                $tagline = lotus_touch_opt( 'lotus_hero_tagline', '' );
                if ( $tagline ) : ?>
                    <p class="tagline"><?php echo esc_html( $tagline ); ?></p>
                <?php endif; ?>
                <p>
                    <?php
                    $about_excerpt = lotus_touch_opt( 'lotus_about_text', '' );
                    if ( $about_excerpt ) {
                        echo esc_html( wp_trim_words( $about_excerpt, 28, '…' ) );
                    } else {
                        esc_html_e( 'Deine Oase der Ruhe – mitten im Alltag.', 'lotus-touch' );
                    }
                    ?>
                </p>

                <?php
                // Social Media
                $socials = array(
                    'instagram' => array( 'label' => 'Instagram', 'icon' => '📷' ),
                    'facebook'  => array( 'label' => 'Facebook',  'icon' => 'f' ),
                    'pinterest' => array( 'label' => 'Pinterest', 'icon' => 'P' ),
                );
                $has_social = false;
                foreach ( $socials as $key => $info ) {
                    if ( lotus_touch_opt( "lotus_social_$key" ) ) { $has_social = true; break; }
                }
                if ( $has_social ) : ?>
                    <div class="footer-social" style="display:flex; gap:0.75rem; margin-top:1.5rem;">
                        <?php foreach ( $socials as $key => $info ) :
                            $url = lotus_touch_opt( "lotus_social_$key" );
                            if ( $url ) : ?>
                                <a href="<?php echo esc_url( $url ); ?>" aria-label="<?php echo esc_attr( $info['label'] ); ?>" target="_blank" rel="noopener" style="width:40px;height:40px;border-radius:50%;background:rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;transition:all 0.3s;">
                                    <?php echo esc_html( $info['icon'] ); ?>
                                </a>
                            <?php endif;
                        endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

                <div class="footer-column">
                    <h4><?php esc_html_e( 'Navigation', 'lotus-touch' ); ?></h4>
                    <?php
                    if ( has_nav_menu( 'footer' ) ) {
                        wp_nav_menu( array(
                            'theme_location' => 'footer',
                            'container'      => false,
                            'depth'          => 1,
                        ) );
                    } else { ?>
                        <ul>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>#hero"><?php esc_html_e( 'Start', 'lotus-touch' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>#ueber-uns"><?php esc_html_e( 'Über uns', 'lotus-touch' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>#angebot"><?php esc_html_e( 'Angebot', 'lotus-touch' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>#gutschein"><?php esc_html_e( 'Gutschein', 'lotus-touch' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>#kontakt-formular"><?php esc_html_e( 'Kontakt', 'lotus-touch' ); ?></a></li>
                        </ul>
                    <?php } ?>
                </div>

            <div class="footer-column">
                <h4><?php esc_html_e( 'Kontakt', 'lotus-touch' ); ?></h4>

                <?php
                $phone   = lotus_touch_opt( 'lotus_contact_phone' );
                $email   = lotus_touch_opt( 'lotus_contact_email' );
                $address = lotus_touch_opt( 'lotus_contact_address' );
                $hours   = lotus_touch_opt( 'lotus_contact_hours' );
                ?>

                <?php if ( $phone ) : ?>
                    <div class="footer-contact-item">
                        <span class="icon" aria-hidden="true">📞</span>
                        <div>
                            <strong><?php esc_html_e( 'Telefon', 'lotus-touch' ); ?></strong>
                            <a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ( $email ) : ?>
                    <div class="footer-contact-item">
                        <span class="icon" aria-hidden="true">✉</span>
                        <div>
                            <strong><?php esc_html_e( 'E-Mail', 'lotus-touch' ); ?></strong>
                            <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ( $address ) : ?>
                    <div class="footer-contact-item">
                        <span class="icon" aria-hidden="true">📍</span>
                        <div>
                            <strong><?php esc_html_e( 'Adresse', 'lotus-touch' ); ?></strong>
                            <span style="white-space:pre-line;opacity:0.9;"><?php echo esc_html( $address ); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ( $hours ) : ?>
                    <div class="footer-contact-item">
                        <span class="icon" aria-hidden="true">🕐</span>
                        <div>
                            <strong><?php esc_html_e( 'Öffnungszeiten', 'lotus-touch' ); ?></strong>
                            <span style="white-space:pre-line;opacity:0.9;"><?php echo esc_html( $hours ); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <?php
                // Widgets (z. B. zusätzlicher Inhalt)
                if ( is_active_sidebar( 'footer-3' ) ) {
                    echo '<div style="margin-top:1.5rem;">';
                    dynamic_sidebar( 'footer-3' );
                    echo '</div>';
                }
                ?>
            </div>

        </div>

        <div class="footer-bottom">
            <div>
                © <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>.
                <?php esc_html_e( 'Alle Rechte vorbehalten.', 'lotus-touch' ); ?>
            </div>

            <div class="footer-legal">
                <?php
                $impressum   = get_page_by_path( 'impressum' );
                $datenschutz = get_page_by_path( 'datenschutz' );
                ?>

                <?php if ( $impressum ) : ?>
                    <a href="<?php echo esc_url( get_permalink( $impressum ) ); ?>"><?php esc_html_e( 'Impressum', 'lotus-touch' ); ?></a>
                <?php endif; ?>

                <?php if ( $datenschutz ) : ?>
                    <a href="<?php echo esc_url( get_permalink( $datenschutz ) ); ?>"><?php esc_html_e( 'Datenschutz', 'lotus-touch' ); ?></a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</footer>

<?php
// Modal container - wird per JS befüllt
?>
<div class="massage-modal" id="massageModal" role="dialog" aria-modal="true" aria-labelledby="modalTitle" aria-hidden="true" hidden>
    <div class="massage-modal-backdrop" data-modal-close></div>
    <div class="massage-modal-dialog" role="document">
        <button type="button" class="massage-modal-close" data-modal-close aria-label="<?php esc_attr_e( 'Schließen', 'lotus-touch' ); ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                <path d="M6 6 L18 18 M18 6 L6 18"/>
            </svg>
        </button>

        <div class="massage-modal-media"></div>

        <div class="massage-modal-body">
            <span class="section-eyebrow massage-modal-eyebrow"></span>
            <h2 class="massage-modal-title" id="modalTitle"></h2>

            <div class="massage-modal-meta"></div>

            <div class="massage-modal-excerpt"></div>

            <div class="massage-modal-content"></div>

            <div class="massage-modal-actions" role="group" aria-label="<?php esc_attr_e( 'Aktionen', 'lotus-touch' ); ?>">
                <a href="#kontakt-formular" class="btn-icon massage-modal-cta" title="<?php esc_attr_e( 'Termin anfragen', 'lotus-touch' ); ?>" aria-label="<?php esc_attr_e( 'Termin anfragen', 'lotus-touch' ); ?>">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </a>
                <button type="button" class="btn-icon" data-modal-close title="<?php esc_attr_e( 'Schließen', 'lotus-touch' ); ?>" aria-label="<?php esc_attr_e( 'Schließen', 'lotus-touch' ); ?>">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                        <path d="M6 6 L18 18 M18 6 L6 18"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
