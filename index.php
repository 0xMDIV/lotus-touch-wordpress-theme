<?php
/**
 * Main Index Template (Fallback)
 *
 * @package LotusTouch
 */

get_header();
?>

<main id="main" class="site-main">
    <div class="page-header">
        <div class="container">
            <?php if ( is_home() && ! is_front_page() ) : ?>
                <h1><?php single_post_title(); ?></h1>
            <?php else : ?>
                <h1><?php esc_html_e( 'Beiträge', 'lotus-touch' ); ?></h1>
                <p class="subtitle"><?php esc_html_e( 'Aktuelles aus unserem Studio', 'lotus-touch' ); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="page-content">
        <div class="container">
            <?php if ( have_posts() ) : ?>

                <div class="services-grid">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class( 'service-card' ); ?>>
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="service-card-image">
                                    <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                        <?php the_post_thumbnail( 'lotus-massage-card' ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <div class="service-card-body">
                                <h3 class="service-card-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <p class="service-card-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24, '…' ) ); ?></p>
                                <div class="service-card-meta">
                                    <span style="font-size:0.875rem;color:var(--color-text-soft);">
                                        <?php echo esc_html( get_the_date() ); ?>
                                    </span>
                                    <a href="<?php the_permalink(); ?>" class="btn btn-outline" style="padding:0.5rem 1.25rem;font-size:0.875rem;">
                                        <?php esc_html_e( 'Lesen', 'lotus-touch' ); ?> →
                                    </a>
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
                <p class="text-center"><?php esc_html_e( 'Keine Beiträge gefunden.', 'lotus-touch' ); ?></p>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
get_footer();