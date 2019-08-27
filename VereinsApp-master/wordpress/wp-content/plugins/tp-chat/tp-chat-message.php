<?php
/**
 * Created by PhpStorm.
 * User: mircobaseniak
 * Date: 30.03.18
 * Time: 12:24
 */

include_once WP_CONTENT_DIR . '/DBService/DBInteractionUtils.php';
include_once WP_CONTENT_DIR . '/DBService/DBInteractorService.php';

/**
 * Class tp_message
 * chat message
 */
class tp_message {

    /**
     * @var int Should contain a message id
     */
    private $id_message;

    /**
     * @var int Should contain a club id
     */
    private $id_club;

    /**
     * @var int Should contain a user id
     */
    private $id_user;

    /**
     * @var String Should contain a message
     */
    private $message;

    /**
     * @var String Should contain the creation time
     */
    private $time;

    /**
     * tp_message constructor.
     */
    public function __construct($message_unit) {
        $this->id_club  = $message_unit['idclub'];
        $this->id_user  = $message_unit['iduser'];
        $this->message  = $message_unit['message'];
        $this->time     = $message_unit['time'];
    }

    /**
     * Insert new column to messages
     */
    public function insert_message_unit() {
        $data = array(
            'idclub'        => $this->id_club,
            'iduser'        => $this->id_user,
            'message'       => $this->message,
            'creationtime'  => $this->time
        );
        DBInteractorService::getInstance()->executeInsertStatement('wp_messages', $data, null);
    }

    /**
     * Check if this user id is current user id
     * @return string current-user if user id is current user id otherwise ''
     */
    private function user_id_is_current_user() {
        if ($this->id_user == get_current_user_id()) {
            return 'current-user';
        } else {
            return '';
        }
    }

    /**
     * Generates the html for the message
     * @return string message
     */
    public function get_message() {
        return '
        <div class="message-unit ' . $this->user_id_is_current_user() . '">
            <div class="author-name">' . get_userdata($this->id_user)->display_name . ' </div>
            <div class="message">' . $this->message . '</div>
            <div class="time">' . date('d.m H:i', strtotime($this->time)) . '</div>
        </div>
        <div class="clear"></div>';
    }
}
