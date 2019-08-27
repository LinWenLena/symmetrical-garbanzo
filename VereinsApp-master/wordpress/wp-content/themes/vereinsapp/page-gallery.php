<?php
/**
 * Template Name: Galerie
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

        <?php

        foreach ($fields['gruppen'] as $gruppe) {
            echo '<h2>' . $gruppe['gruppe']['name'] . '</h2>';
            echo '<div class="images">';
            foreach ($gruppe['gruppe']['bilder'] as $bild) {
                echo '<img src=' . $bild['bild'] . '>';
            }
            echo '<div class="clear"></div>';
            echo '</div>';
        }

        ?>

    </div>
</section>

<?php get_footer(); ?>
