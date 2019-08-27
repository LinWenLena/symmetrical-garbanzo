<?php
/*
Plugin Name: TP Club
Description: Plugin für die Clubverwaltung
Author: Fin Römer
Version: 1.0
 */

// Exit if accessed directly
defined('ABSPATH' || exit());

// Activates the output buffering
//ob_start();

// Include classes
include('tp-club-creation.php');
include('tp-club-view.php');

//add Init Hook
add_action('admin_menu','tp_clubs_admin');

function tp_clubs_admin() {
    add_menu_page('Sportarten', 'Sportarten', 'edit_posts', 'sportarten', 'tp_clubs_createView', '', 51);
}



/**
 * Creates the club View
 */
function tp_clubs_createView() {

    $tp_clubView = new tp_club_view();
    $tp_clubCreation = new tp_club_creation();

    echo('<h1>Sportarten</h1> ' . $tp_clubView->createClubsTableDiv() .
    $tp_clubCreation->getClubCreationDiv()) ;
}

?>