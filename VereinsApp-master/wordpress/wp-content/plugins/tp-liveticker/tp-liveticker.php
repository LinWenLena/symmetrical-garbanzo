<?php
/*
Plugin Name: TP Liveticker
Description: Plugin für die Livetickerverwaltung
Author: Mirco Baseniak
Version: 1.0
 */

// Exit if accessed directly
defined('ABSPATH' || exit());

// Activates the output buffering
ob_start();

// Include classes
include('tp-liveticker-create.php');
include('tp-liveticker-entry.php');
include('tp-liveticker-list.php');

// Create instances
$tp_create_entry = new tp_create_entry();
$tp_entry = new tp_entry($array);
$tp_liveticker_list = new tp_liveticker_list();

function liveticker_register() {
    add_menu_page('Liveticker', 'Liveticker', 'edit_posts', 'liveticker', 'liveticker_list', '', 50);
    add_submenu_page('liveticker', 'Alle Einträge', 'Alle Einträge', 'edit_posts', 'liveticker', 'liveticker_list');
    add_submenu_page('liveticker', 'Erstellen', 'Erstellen', 'manage_options', 'new-entry', 'new_entry');
}
add_action('admin_menu', 'liveticker_register');

function liveticker_list() {
    $tp_liveticker_list = new tp_liveticker_list();
    echo $tp_liveticker_list->init_process('', '', '');
}

function new_entry() {
    $tp_create_entry = new tp_create_entry();
    echo $tp_create_entry->init_process();
}
