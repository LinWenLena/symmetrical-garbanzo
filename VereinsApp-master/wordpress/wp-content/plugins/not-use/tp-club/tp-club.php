<?php


// Exit if accessed directly
defined('ABSPATH' || exit());

// Activates the output buffering
ob_start();

// Include classes
include('tp-club-club.php');
include('tp-club-create.php');
include('tp-club-list.php');

function club_register() {
    add_menu_page('Sportarten', 'Sportarten', 'edit_posts', 'sportarten', 'club_list', '', 51);
    add_submenu_page('sportarten', 'Alle Einträge', 'Alle Einträge', 'edit_posts', 'sportarten', 'club_list');
    add_submenu_page('sportarten', 'Erstellen', 'Erstellen', 'manage_options', 'new-club', 'new_club');
}
add_action('admin_menu', 'club_register');

function club_list() {
    $tp_club_list = new tp_club_list();
    echo $tp_club_list->init_process();
}

function new_club() {
    $tp_create_club = new tp_create_club();
    echo $tp_create_club->init_process();
}
