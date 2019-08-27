<?php
/**
 * The default template for displaying pages
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
                <?php echo $fields['inhalt']; ?>
            <?php endwhile; else: ?>
                <p>Es ist leider kein Inhalt vorhanden.</p>
            <?php endif; ?>

        </div>
    </section>

<?php get_footer(); ?>