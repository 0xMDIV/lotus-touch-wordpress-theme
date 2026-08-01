<?php
/**
 * Page Template (Standard-Seiten)
 *
 * @package LotusTouch
 */

get_header();
?>

<main id="main" class="site-main">

    <?php while ( have_posts() ) : the_post(); ?>

        <div class="page-header">
            <div class="container">
                <h1><?php the_title(); ?></h1>
                <?php if ( get_the_excerpt() && has_excerpt() ) : ?>
                    <p class="subtitle"><?php echo esc_html( get_the_excerpt() ); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="page-content">
            <div class="container-narrow">
                <div class="page-content-inner">
                    <?php
                    if ( has_post_thumbnail() ) : ?>
                        <div class="page-featured-image" style="margin-bottom:2.5rem;border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-md);">
                            <?php the_post_thumbnail( 'large', array( 'style' => 'width:100%;height:auto;' ) ); ?>
                        </div>
                    <?php endif; ?>

                    <?php
                    the_content();

                    wp_link_pages( array(
                        'before' => '<div class="page-links">' . esc_html__( 'Seiten:', 'lotus-touch' ),
                        'after'  => '</div>',
                    ) );
                    ?>
                </div>
            </div>
        </div>

        <?php
        // Kommentare für Seiten (optional)
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;
        ?>

    <?php endwhile; ?>

</main>

<?php
get_footer();