<?php
/*
Plugin Name: TP User
Description: Plugin für die Userverwaltung
Author: Mirco Baseniak
Version: 1.0
 */

// Exit if accessed directly
defined('ABSPATH' || exit());

// Activates the output buffering
//ob_start();

// Include classes
include('tp-user-login.php');
include('tp-user-register.php');
include('tp-user-club.php');
include('tp-user-edit.php');
include('tp-user-club-view.php');

// Create instances
$tp_login = new tp_login();
$tp_register = new tp_register();
$tp_club = new tp_club();

add_action('init', 'initUserEditMenu');

function initUserEditMenu() {
    $tp_user_edit = new tp_user_edit();    
    $tp_user_clubview = new tp_user_club_view();
}
?>