<?php
/**
 * Template Name: Sportart
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

                <?php if (is_user_logged_in()) : ?>
                    <?php echo do_shortcode('[club-form idclub="' . $fields['sportart'] . '"]'); ?>
                <?php endif; ?>

                <?php echo $fields['inhalt']; ?>
            <?php endwhile; else: ?>
                <p>Es ist leider kein Inhalt vorhanden.</p>
            <?php endif; ?>

        </div>
    </section>

<?php get_footer(); ?>