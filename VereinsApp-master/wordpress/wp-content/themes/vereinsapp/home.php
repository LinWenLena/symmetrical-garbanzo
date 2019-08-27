<?php
/**
 * The template for displaying the blog posts
 *
 * @package WordPress
 * @subpackage VereinsApp
 * @since vereinsapp 1.0
 */

/**
 * @var array Contain fields of the page
 */
$fields = get_fields();

get_header(); ?>

    <section class="main">
        <div class="content">

            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                <div class="post">
                    <h2><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
                    <div class="entry">
                        <?php //the_field('inhalt') ?>
                        <?php echo custom_field_excerpt(get_field('inhalt')) ?>
                        <p class="postmetadata">
                            <?php echo get_the_date('d. F Y') ?>
                            <?php //the_category(','); ?>
                            <br />
                        </p>
                    </div>
                </div>

            <?php endwhile; else: ?>
                <p>Es gibt leider keine Neuhigkeiten.</p>
            <?php endif; ?>

        </div>
    </section>

<?php get_footer(); ?>