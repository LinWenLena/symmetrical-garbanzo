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
 * Class tp_list
 * lists and filter events
 */
class tp_list {

    /**
     * @var int Should contain a user id
     */
    private $id_user;

    /**
     * @var int Should contain a club id
     */
    private $id_club;

    /**
     * @var String|null Input error message or acknowledgment message
     */
    private $message;

    /**
     * @var String|null Success message
     */
    private $success;

    /**
     * tp_list constructor.
     */
    public function __construct() {
        // shortcuts to integrate event list in the frontend
        add_shortcode('event-list', array($this, 'init_process'));
        add_shortcode('my-event-list', array($this, 'init_process'));
    }

    /**
     * Initialization of the event list handling
     * @return string event list
     */
    public function init_process($atts, $content, $tag) {

        $error = new WP_Error();

        $this->id_user = get_current_user_id();
        if (isset($_POST['submit-club'])) {
            $this->id_club = htmlspecialchars($_POST['option-idclub']);
        }
        if (isset($_POST['submit-join'])) {
            $this->id_club = htmlspecialchars($_POST['hidden-idclub']);
            $id_event = htmlspecialchars($_POST['hidden-idevent']);
            if (!$this->event_is_over($id_event)) {
                if ($this->event_has_user($id_event)) {
                    $this->remove_user_from_event($id_event);
                    $this->success = 'Sie wurden erfolgreich aus der Veranstaltung entfernt.';
                } else {
                    if ($this->free_event_places($id_event)) {
                        $this->add_user_to_event($id_event);
                        $this->success = 'Sie wurden erfolgreich der Veranstaltung hinzugefügt.';
                    } else {
                        $error->add('event_full', 'Veranstaltung ist bereits belegt.');
                    }
                }
            } else {
                $error->add('event_over', 'Veranstaltung ist bereits vergangen.');
            }
        }
        if (isset($_POST['submit-delete'])) {
            $this->id_club = htmlspecialchars($_POST['hidden-idclub']);
            $id_event = htmlspecialchars($_POST['hidden-idevent']);
            if ($this->user_is_author_of_event($id_event)) {
                $this->delete_event($id_event);
                $this->success = 'Veranstaltung wurde erfolgreich entfernt.';
            } else {
                $error->add('user_not_author', 'Sie sind nicht berechtigt diese Veranstaltung zu löschen.');
            }
        }

        if (is_wp_error($error) && !empty($error->errors)) {
            $this->message = '<strong>FEHLER</strong>: ' . $error->get_error_message();
            $correct = false;
        }

        if ($this->current_user_has_club()) {
            if (isset($this->id_club) && $this->id_club != -1) {
                return $this->group_form()
                    . $this->event_list($tag);
            } else {
                return $this->group_form();
            }
        } else {
            return 'Sie gehören momentan noch keiner Sportart an.';
        }
    }

    /**
     * Delete column from events
     * @param $id_event
     */
    private function delete_event($id_event) {
        $where = array(
            'id' => $id_event,
        );
        DBInteractorService::getInstance()->deleteEntry('wp_event', $where, null);
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
        <div class="form-error">' . $this->message . '</div>
        <div class="form-success">' . $this->success . '</div>
        <form name="form" action="' . $_SERVER['REQUEST_URI'] . '" method="post">
        <p>
            <label for="clubs">Veranstaltungen der Sportart<br>
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
     * Generates the html for the events
     * @param $type type of list
     * @return string
     */
    private function get_events($type) {

        $myResultSet = '';

        switch ($type) {
            case 'event-list':
                // select events of selected club
                $myResultSet = DBInteractorService::getInstance()->executeSelectStatement(
                    DBInteractionUtils::selectEventsBasedOfClubID($this->id_club));
                break;
            case 'my-event-list':
                // select events of selected club and user id as member
                $myResultSet = DBInteractorService::getInstance()->executeSelectStatement(
                    DBInteractionUtils::selectEventsBasedOfClubIDAndUserID($this->id_club, $this->id_user));
                break;
        }

        $events = '';

        foreach ($myResultSet as $result) {
            $event_unit = array (
                'idevent'     => $result->ID,
                'idclub'      => $result->idClub,
                'iduser'      => $result->idUser,
                'name'        => $result->NAME,
                'description' => $result->DESCRIPTION,
                'place'       => $result->PLACE,
                'date'        => $result->date,
                'startTime'   => $result->startTime,
                'endTime'     => $result->endTime,
                'maxmember'   => $result->maxMember
            );
            $event = new tp_event($event_unit);
            $events .= '<div class="event">';
            $events .= $event->get_event();
            $events .= $this->event_form($result->ID);
            $events .= '</div>';
        }

        return $events;
    }

    /**
     * Generates the html for the event list
     * @param $type type of list
     * @return string event list
     */
    private function event_list($type) {
        return '
        <div class="event-window">
            ' . $this->get_events($type) . '
        </div>';
    }

    /**
     * Checks if event id has user id
     * @param $id_event
     * @return bool true if event has user otherwise false
     */
    private function event_has_user($id_event) {

        // select user id from user in event where user id = this user id and event id = this event id
        $myResultSet = DBInteractorService::getInstance()->executeSelectStatement(
            DBInteractionUtils::selectUserIDBasedOfEventIDAndUserID($id_event, $this->id_user));

        if ($myResultSet) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if event is full or not
     * @param $id_event
     * @return bool true if event has at least on place otherwise false
     */
    private function free_event_places($id_event) {
        // select count of user in event with event id
        $myResultSet_user_count = DBInteractorService::getInstance()->executeSelectStatement(
            DBInteractionUtils::selectCountOfUserInEvent($id_event));

        // select max member number of event with event id
        $myResultSet_max_member = DBInteractorService::getInstance()->executeSelectStatement(
            DBInteractionUtils::selectMaxMemberNumberOfEventID($id_event));

        if ($myResultSet_max_member[0]->maxmember != null) {
            return (($myResultSet_max_member[0]->maxmember - $myResultSet_user_count[0]->count) > 0);
        } else {
            return true;
        }
    }

    /**
     * Check if event is over
     * @param $id_event
     * @return bool true if event is over otherwise false
     */
    private function event_is_over($id_event) {
        // select date of event with event id
        $myResultSet = DBInteractorService::getInstance()->executeSelectStatement(
            DBInteractionUtils::selectEventEndTimeOfEventID($id_event));

        return strtotime($myResultSet[0]->endtime) < time();
    }

    /**
     * Check if current user is author of event
     * @param $id_event
     * @return bool true if current user is author of event otherwise false
     */
    private function user_is_author_of_event($id_event) {
        // select author id of event with event id
        $myResultSet = DBInteractorService::getInstance()->executeSelectStatement(
            DBInteractionUtils::selectAuthorOfEventID($id_event));

        return $myResultSet[0]->iduser == $this->id_user;
    }

    /**
     * Insert new column to userinevent
     * @param $id_event
     */
    private function add_user_to_event($id_event) {
        $data = array(
            'idevent'      => $id_event,
            'iduser'       => $this->id_user,
            'entereddate'  => date('Y-m-d H:i:s')
        );
        DBInteractorService::getInstance()->executeInsertStatement('wp_userinevent', $data, null);
    }

    /**
     * Delete column from userinevent
     * @param $id_event
     */
    private function remove_user_from_event($id_event) {
        $where = array(
            'idevent' => $id_event,
            'iduser'  => $this->id_user
        );
        DBInteractorService::getInstance()->deleteEntry('wp_userinevent', $where, null);
    }

    /**
     * Generates the html for the form to enter an leave a event
     * @param $id_event
     * @return string
     */
    private function event_form($id_event) {
        if ($this->event_is_over($id_event)) {
            $text = 'Veranstaltung ist vorbei';
        } else {
            if ($this->event_has_user($id_event)) {
                $text = 'Veranstaltung verlassen';
            } else {
                if ($this->free_event_places($id_event)) {
                    $text = 'Veranstaltung beitreten';
                } else {
                    $text = 'Veranstaltung ist belegt';
                }
            }
        }

        if ($this->user_is_author_of_event($id_event)) {
            $delete_event = '
            <p class="row">
                <button class="delete" type="submit" name="submit-delete">Veranstaltung löschen</button>
            </p>
            ';
        } else {
            $delete_event = '';
        }

        return '
        <form name="form" action="' . $_SERVER['REQUEST_URI'] . '" method="post">
            <input type="hidden" name="hidden-idclub" value="' . $this->id_club . '">
            <input type="hidden" name="hidden-idevent" value="' . $id_event . '">
            <p class="row">
                <button type="submit" name="submit-join">' . $text . '</button>
            </p>
            ' . $delete_event . '
            <div class="clear"></div>
        </form>';
    }
}
