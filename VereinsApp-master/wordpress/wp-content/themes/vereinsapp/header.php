<?php
/**
 * The template for displaying the header
 *
 * @package WordPress
 * @subpackage VereinsApp
 * @since vereinsapp 1.0
 */

/**
 * @var array Contain fields of the page
 */
$fields = get_fields();

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<meta name="description" content="<?php bloginfo('description'); ?>">
    <title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' |'; } ?> <?php bloginfo('name'); ?></title>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <?php get_sidebar(); ?>

    <div class="container">
        <header>
            <div class="area">
                <div class="bg-image">
                    <?php if ( isset($fields['header']['hintergrundbild']) &&  $fields['header']['hintergrundbild'] != '') : ?>
                        <span class="overlay"></span>
                        <img src="<?php echo $fields['header']['hintergrundbild']; ?>">
                    <?php endif; ?>
                </div>
                <div class="content">
                    <h1><?php single_post_title(); ?></h1>
                </div>
            </div>
            <div class="bar">
                <div class="content">
                    <?php list_child_pages(); ?>
                </div>
            </div>
        </header>
