<?php
/*
Plugin Name: TP Chat
Description: Plugin für den Chatbereich
Author: Mirco Baseniak
Version: 1.0
 */

// Exit if accessed directly
defined('ABSPATH' || exit());

// Activates the output buffering
ob_start();

// Include classes
include('tp-chat-message.php');
include('tp-chat-control.php');

// Create instances
$tp_control = new tp_control();
$tp_message = new tp_message($array);
