<?php
/**
 * Archive Massage Template
 * Zeigt alle Massagen an. Klick öffnet das Modal (keine separate Unterseite).
 *
 * @package LotusTouch
 */

get_header();
?>

<main id="main" class="site-main">

    <div class="page-header">
        <div class="container">
            <span class="section-eyebrow" style="color:var(--color-primary);"><?php esc_html_e( 'Unsere Behandlungen', 'lotus-touch' ); ?></span>
            <h1><?php post_type_archive_title(); ?></h1>
            <p class="subtitle"><?php esc_html_e( 'Klick auf eine Karte für alle Details.', 'lotus-touch' ); ?></p>
        </div>
    </div>

    <div class="page-content">
        <div class="container">
            <?php if ( have_posts() ) : ?>

                <div class="services-grid">
                    <?php while ( have_posts() ) : the_post();
                        $price    = get_post_meta( get_the_ID(), '_massage_price', true );
                        $unit     = get_post_meta( get_the_ID(), '_massage_unit', true ) ?: '€';
                        $duration = get_post_meta( get_the_ID(), '_massage_duration', true );
                        $featured = get_post_meta( get_the_ID(), '_massage_featured', true );
                        $thumb_id = get_post_thumbnail_id();
                        $thumb_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'lotus-massage-hero' ) : '';
                        $content_html = apply_filters( 'the_content', get_the_content() );
                    ?>
                        <article class="service-card fade-up" id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-search-item>
                            <button type="button" class="service-card-button" data-massage-modal
                                data-id="<?php echo esc_attr( get_the_ID() ); ?>"
                                data-title="<?php echo esc_attr( get_the_title() ); ?>"
                                data-excerpt="<?php echo esc_attr( wp_strip_all_tags( get_the_excerpt() ) ); ?>"
                                data-content="<?php echo esc_attr( wp_strip_all_tags( get_the_content() ) ); ?>"
                                data-content-html="<?php echo esc_attr( $content_html ); ?>"
                                data-price="<?php echo esc_attr( $price ); ?>"
                                data-unit="<?php echo esc_attr( $unit ); ?>"
                                data-duration="<?php echo esc_attr( $duration ); ?>"
                                data-image="<?php echo esc_url( $thumb_url ); ?>"
                                data-featured="<?php echo esc_attr( $featured ); ?>"
                                aria-haspopup="dialog"
                                aria-label="<?php echo esc_attr( sprintf( __( 'Details zu %s anzeigen', 'lotus-touch' ), get_the_title() ) ); ?>"></button>

                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="service-card-image">
                                    <?php the_post_thumbnail( 'lotus-massage-card' ); ?>
                                    <?php if ( $featured ) : ?>
                                        <span style="position:absolute;top:1rem;right:1rem;background:var(--gradient-hero);color:white;padding:0.375rem 0.875rem;border-radius:999px;font-size:0.75rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;z-index:2;">
                                            ✦ <?php esc_html_e( 'Beliebt', 'lotus-touch' ); ?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="service-card-zoom" aria-hidden="true">＋</span>
                                </div>
                            <?php endif; ?>

                            <div class="service-card-body">
                                <h3 class="service-card-title"><?php the_title(); ?></h3>
                                <p class="service-card-excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
                                <div class="service-card-meta">
                                    <div class="service-price">
                                        <?php if ( $price !== '' ) echo esc_html( $price . ' ' . $unit ); ?>
                                        <?php if ( $duration ) : ?><small><?php echo esc_html( $duration ); ?></small><?php endif; ?>
                                    </div>
                                </div>
                                <div class="service-card-cta">
                                    <span class="service-card-more"><?php esc_html_e( 'Mehr erfahren', 'lotus-touch' ); ?> →</span>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <?php
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => '←',
                    'next_text' => '→',
                ) );
                ?>

            <?php else : ?>
                <p class="text-center"><?php esc_html_e( 'Aktuell sind keine Behandlungen verfügbar.', 'lotus-touch' ); ?></p>
            <?php endif; ?>

            <div class="text-center" style="margin-top:3rem;">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-outline">
                    ← <?php esc_html_e( 'Zurück zur Startseite', 'lotus-touch' ); ?>
                </a>
            </div>
        </div>
    </div>

</main>

<?php get_footer(); ?>