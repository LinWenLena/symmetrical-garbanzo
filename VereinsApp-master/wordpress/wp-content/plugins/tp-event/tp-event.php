<?php
/*
Plugin Name: TP Event
Description: Plugin für die Veranstaltungsverwaltung
Author: Mirco Baseniak
Version: 1.0
 */

// Exit if accessed directly
defined('ABSPATH' || exit());

// Activates the output buffering
ob_start();

// Include classes
include('tp-event-create.php');
include('tp-event-event.php');
include('tp-event-list.php');

// Create instances
$tp_list = new tp_list();
$tp_event = new tp_event($array);
$tp_create = new tp_create();
