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
 * Class tp_control
 * custom user chat form
 */
class tp_control {

    /**
     * @var int Should contain a user id
     */
    private $id_user;

    /**
     * @var int Should contain a club id
     */
    private $id_club;

    /**
     * @var string|null Should contain a message
     */
    private $message;

    /**
     * tp_control constructor.
     */
    public function __construct() {
        // shortcut to integrate chat form in the frontend
        add_shortcode('chat-form', array($this, 'init_process'));
    }

    /**
     * Initialization of the chat handling for the chat form
     * @return string chat form
     */
    public function init_process() {
        $this->id_user = get_current_user_id();
        if (isset($_POST['submit-club'])) {
            $this->id_club = htmlspecialchars($_POST['option-idclub']);
        }
        if (isset($_POST['submit-message'])) {
            $this->id_club = htmlspecialchars($_POST['hidden-idclub']);
            $this->message = htmlspecialchars($_POST['message']);
            if ($this->club_has_current_user() && $this->message_is_not_empty()) {
                $this->add_new_message();
            }
        }
        if ($this->current_user_has_club()) {
            if (isset($this->id_club) && $this->id_club != -1) {
                return $this->group_form()
                    . $this->chat_window()
                    . $this->message_form();
            } else {
                return $this->group_form();
            }
        } else {
            return 'Sie gehören momentan noch keiner Sportart an.';
        }
    }

    /**
     * Add a new message to database
     */
    private function add_new_message() {
        $new_message_unit = array (
            'idclub'  => $this->id_club,
            'iduser'  => $this->id_user,
            'message' => $this->message,
            'time'    => date('Y-m-d H:i:s')
        );
        $new_message = new tp_message($new_message_unit);
        $new_message->insert_message_unit();
    }

    /**
     * Checks if message is not empty
     * @return bool true if message is not empty otherwise false
     */
    private function message_is_not_empty() {
        return (isset($this->message) && $this->message != '');
    }

    /**
     * Checks if current user has at least one club
     * @return bool true if current user has club otherwise false
     */
    private function current_user_has_club() {

        // select sport clubs of current user
        $myResultSet = DBInteractorService::getInstance()->executeSelectStatement(
            DBInteractionUtils::selectClubIDsAndNamesBasedOfUserID($this->id_user));

        if ($myResultSet) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if club id has current user id
     * @return bool true if club has user otherwise false
     */
    private function club_has_current_user() {

        // select user id from user in club where user id = current user id id and club id = this club id
        $myResultSet = DBInteractorService::getInstance()->executeSelectStatement(
            DBInteractionUtils::selectUserIDBasedOfClubIDAndUserID($this->id_club, $this->id_user));

        if ($myResultSet) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Generates the html for the form to choose a group
     * @return string
     */
    private function group_form() {

        // select sport clubs of current user
        $myResultSet = DBInteractorService::getInstance()->executeSelectStatement(
            DBInteractionUtils::selectClubIDsAndNamesBasedOfUserID($this->id_user));

        // clubs of current user
        $options = '';

        foreach ($myResultSet as $result) {
            if ($this->id_club == $result->id) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $options .= '<option value="' . $result->id . '" ' . $selected . '>' . $result->name . '</option>';
        }

        return '
        <form name="form" action="' . $_SERVER['REQUEST_URI'] . '" method="post">
        <p>
            <label for="clubs">Chat der Sportart<br>
                <select name="option-idclub">
                    <option value="-1">Bitte auswählen</option>
                    ' . $options . '
                </select>
            </label>
        </p>
        <p>
            <button type="submit" name="submit-club">Auswählen</button>
        </p>
      </form>';
    }

    /**
     * Generates the html for the chat window with messages
     * @return string chat window
     */
    private function chat_window() {
        return '
        <div class="chat-window">
            ' . $this->get_messages() . '
        </div>';
    }

    /**
     * Gemerates the html for the messages
     * @return string last 50 messages
     */
    private function get_messages() {

        // select messages of selected club
        $myResultSet = DBInteractorService::getInstance()->executeSelectStatement(
            DBInteractionUtils::selectMessagesBasedOfClubID($this->id_club));

        // last 50 messages
        $messages = '';

        foreach ($myResultSet as $result) {
            $message_unit = array (
                'idclub'   => $result->idClub,
                'iduser'   => $result->idUser,
                'message'  => $result->MESSAGE,
                'time'     => $result->creationTime,
            );
            $message = new tp_message($message_unit);
            $messages .= $message->get_message();
        }

        return $messages;
    }

    /**
     * Generates the tml for the form to create a new message
     * @return string
     */
    private function message_form() {
        return '
        <form name="form" action="' . $_SERVER['REQUEST_URI'] . '" method="post">
            <label for="message"><br>
                <input type="text" name="message" value="" placeholder="Ihre Nachricht">
            </label>
        <input type="hidden" name="hidden-idclub" value="' . $this->id_club . '">
        <p>
            <button type="submit" name="submit-message">Senden</button>
        </p>
      </form>';
    }
}
