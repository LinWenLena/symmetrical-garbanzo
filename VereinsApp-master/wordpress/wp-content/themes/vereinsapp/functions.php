<?php
/**
 * VereinsApp functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags.
 *
 * @package WordPress
 * @subpackage VereinsApp
 * @since vereinsapp 1.0
 */

 
 // Load Favicons
/*
function load_favicons() {
  	$favicon_url = get_template_directory_uri() . '/images/favicons';

    echo '<link rel="apple-touch-icon" sizes="180x180" href="' . $favicon_url . '/apple-touch-icon.png">' ."\n";
    echo '<link rel="icon" type="image/png" sizes="32x32" href="' . $favicon_url . '/favicon-32x32.png">' ."\n";
    echo '<link rel="icon" type="image/png" sizes="16x16" href="' . $favicon_url . '/favicon-16x16.png">' ."\n";
    echo '<link rel="manifest" href="' . $favicon_url . '/site.webmanifest">' ."\n";
    echo '<link rel="mask-icon" href="' . $favicon_url . '/safari-pinned-tab.svg" color="#df780d">' ."\n";
    echo '<meta name="msapplication-TileColor" content="#df780d">' ."\n";
    echo '<meta name="theme-color" content="#ffffff">' ."\n";
}
*/
  
// Add Favicons
//add_action('wp_head', 'load_favicons');
//add_action('login_head', 'load_favicons');
//add_action('admin_head', 'load_favicons');

 
// Load Login-Logo
/*
function namespace_login_style() {
    echo '<style>.login h1 a { width: 160px !important; background-size: 100%; background-position: center; background-image: url( ';
    echo get_template_directory_uri() . '/images/logo-s.svg';
    echo ' ) !important;}</style>';
}
*/

// Add Login-Logo
//add_action('login_head', 'namespace_login_style');


// Load Login-Logo-URL
function namespace_login_headerurl($url) {
    $url = home_url( '/' );
    return $url;
}

// Add Login-Logo-URL
add_filter('login_headerurl', 'namespace_login_headerurl');


// Remove Navigation-Points from Adminmenu
/*
function remove_menus () {
	global $menu;
		$restricted = array(
            __('Beiträge'),
			__('Kommentare'),
			__('Werkzeuge'));
		end ($menu);
		while (prev($menu)){
			$value = explode(' ',$menu[key($menu)][0]);
			if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
		}
}
*/

// Add Remove Navigation-Points from Adminmenu
//add_action('admin_menu', 'remove_menus');


// Remove Navigation-Points from Adminbar
/*
function my_admin_bar_render() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
    $wp_admin_bar->remove_node('new-post');
}
*/

// Add Remove Navigation-Points from Adminbar
//add_action('wp_before_admin_bar_render', 'my_admin_bar_render');


// Load Theme Stylesheet
function load_parent_style() {
    wp_register_style('parent-theme', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('parent-theme');
}

// Add Theme Stylesheet
add_action('wp_head', 'load_parent_style', 0);


// Load Google Font
/*
function load_google_font() {
    wp_enqueue_style('google-font', 'https://fonts.googleapis.com/css?family=Roboto|Roboto+Mono', false);
}
*/

// Add Google Font
//add_action('wp_enqueue_scripts', 'load_google_font');


// Load JQuery
function load_jquery() {
    wp_deregister_script('jquery');
    wp_register_script('jquery', "https://code.jquery.com/jquery-3.3.1.min.js", false, null);
    wp_enqueue_script('jquery');
}

// Add JQuery
add_action('wp_enqueue_scripts', 'load_jquery');


// Load Theme Java Script
function load_theme_script() {
    wp_enqueue_script( 'script', get_template_directory_uri() . '/js/script.js', array ('jquery'), 1.1, true);
    wp_enqueue_script( 'comment-reply');
}

// Add Theme Java Script
add_action('wp_enqueue_scripts', 'load_theme_script');


// Main Navigation
function main_nav() {
	wp_nav_menu(
	array(
        'theme_location'  => '',
        'menu'            => 'main-menu',
        'container'       => '',
        'container_class' => '',
        'container_id'    => '',
        'menu_class'      => '',
        'menu_id'         => '',
        'echo'            => true,
        'fallback_cb'     => '',
        'before'          => '',
        'after'           => '',
        'link_before'     => '',
        'link_after'      => '',
        'items_wrap'      => '<ul>%3$s</ul>',
        'depth'           => 0,
        'walker'          => ''
		)
	);
}

// Footer Navigation
function footer_nav() {
	wp_nav_menu(
	array(
        'theme_location'  => '',
        'menu'            => 'footer-menu',
        'container'       => 'div',
        'container_class' => 'nav',
        'container_id'    => '',
        'menu_class'      => '',
        'menu_id'         => '',
        'echo'            => true,
        'fallback_cb'     => '',
        'before'          => '',
        'after'           => '',
        'link_before'     => '',
        'link_after'      => '',
        'items_wrap'      => '<ul>%3$s</ul>',
        'depth'           => 0,
        'walker'          => ''
		)
	);
}

// Register Navigation
function register_menu() {
    register_nav_menus(array(
        'main-menu' => __('Main Menu', ''), // Main Navigation
		'footer-menu' => __('Footer Menu', ''), // Footer Navigation
    ));
}

// Add Navigation
add_action('init', 'register_menu');


// Disable Admin Bar for All Users Except for Administrators
function remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}

// Add Disable Admin Bar for All Users Except for Administrators
add_action('after_setup_theme', 'remove_admin_bar');

/*
 * Other Functions
 */

/**
 * Child Page Menu
 * Lists the link of the parent page and its child pages
 */
function list_child_pages() {
    global $post;
    if (is_page() && $post->post_parent) { // post is a child page
        $page = $post->post_parent;
    } else { // post is a parent page
        $page = $post->ID;
    }
    $args_parent = array(
        'title_li'    => '',
        'include'     => array($page),
        'echo'        => 0
    );
    $args_childs = array(
        'sort_column' => 'menu_order',
        'title_li'    => '',
        'child_of'    => $page,
        'echo'        => 0
    );
    $parentpage = wp_list_pages($args_parent);
    $childpages = wp_list_pages($args_childs);
    if ($parentpage && $childpages) {
        echo '
        <ul>
            ' . $parentpage . $childpages . '
            <div class="clear"></div>
        </ul>';
    }
}

/**
 * Gets a text and cut it to the place of the set more tag
 * @param $text - text to cut short
 * @return bool|string - excerpt
 */
function custom_field_excerpt($text) {
    global $post;
    if ( '' != $text ) {
        if (strpos($text, '<!--more-->') > 0) {
            $text = substr($text, 0, strpos($text, '<!--more-->'));
            $permalink = '<a href="' . get_permalink($post->ID) .'">Weiterlesen</a>';
            $text = $text . $permalink;
        }
    }
    return $text;
}

?>