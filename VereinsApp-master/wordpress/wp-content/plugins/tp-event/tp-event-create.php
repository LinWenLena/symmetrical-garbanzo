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
 * Class tp_create
 * create a new event
 */
class tp_create {

    /**
     * @var int Should contain a user id
     */
    private $id_user;

    /**
     * @var int Should contain a club id
     */
    private $id_club;

    /**
     * @var string|null Should contain a event name
     */
    private $name;

    /**
     * @var string|null Should contain a event description
     */
    private $description;

    /**
     * @var string|null Should contain a event place
     */
    private $place;

    /**
     * @var string|null Should contain a event date
     */
    private $date;

    /**
     * @var string|null Should contain a event start time
     */
    private $start_time;

    /**
     * @var string|null Should contain a event end time
     */
    private $end_time;

    /**
     * @var int|null Should contain a the max member number fpr event
     */
    private $max_member;

    /**
     * @var String|null Input error message or acknowledgment message
     */
    private $message;

    /**
     * @var String|null Sucess message
     */
    private $success;

    /**
     * tp_create constructor.
     */
    public function __construct() {
        // shortcut to integrate create event form in the frontend
        add_shortcode('new-event', array($this, 'init_process'));
    }

    /**
     * Initialization of the create event handling
     * @return string event form
     */
    public function init_process() {
        $this->id_user = get_current_user_id();
        if (isset($_POST['submit'])) {
            $this->id_club      = htmlspecialchars($_POST['option-idclub']);
            $this->name         = htmlspecialchars($_POST['event-name']);
            $this->description  = htmlspecialchars($_POST['description']);
            $this->place        = htmlspecialchars($_POST['place']);
            $this->date         = htmlspecialchars($_POST['date']);
            $this->start_time   = htmlspecialchars($_POST['start-time']);
            $this->end_time     = htmlspecialchars($_POST['end-time']);
            $this->max_member   = htmlspecialchars($_POST['max-member']);
            if ($this->check_data()) {
                $this->sanitize_data();
                $this->insert_event();
                $this->success = 'Die Veranstaltung wurde erfolgreich angelegt.';
            }
        }
        return $this->event_form();
    }

    /**
     * Checks the input data and creates error message
     * @return bool True if data is correct otherwise false
     */
    private function check_data() {
        $correct = true;
        $error = new WP_Error();

        // Check if club id, name, description, date, time and place is set
        if ($this->id_club == -1 || empty($this->name) || empty($this->description) ||
            empty($this->date) || empty($this->start_time) || empty($this->end_time) || empty($this->place)) {
            $error->add('field', 'Bitte alle Felder mit Sternchen ausfüllen.');
        }

        // Check if the current user has selected club id
        if (!$this->club_has_user()) {
            $error->add('club_has_not_user', 'Sie gehören der ausgewählten Sportart nicht an.');
        }

        // Check if the date is valid
        if (!$this->validate_date()) {
            $error->add('date_invalid', 'Datum ist ungültig.');
        }

        // Check if the time is valid
        if (!$this->validate_time()) {
            $error->add('time_invalid', 'Zeit ist ungültig.');
        }

        // Check if max member is a int
        if (!empty($this->max_member) && !$this->validate_max_member()) {
            $error->add('max_member_invalid', 'Maximale Teilnehmerzahl muss eine Ganzzahl sein.');
        }

        if (is_wp_error($error) && !empty($error->errors)) {
            $this->message = '<strong>FEHLER</strong>: ' . $error->get_error_message();
            $correct = false;
        }
        return $correct;
    }

    /**
     * Checks if club id has user id and
     * @return bool true if club has user otherwise false
     */
    private function club_has_user() {

        // select user id from user in club where user id = this user id and club id = this club id
        $myResultSet = DBInteractorService::getInstance()->executeSelectStatement(
            DBInteractionUtils::selectUserIDBasedOfClubIDAndUserID($this->id_club, $this->id_user));

        if ($myResultSet) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if date is valid
     * @param string $format default format
     * @return bool true if date is valid otherwise false
     */
    private function validate_date($format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $this->date);
        return $d && $d->format($format) == $this->date;
    }

    /**
     * Checks if time is valid
     * @param string $format default format
     * @return bool true if time is valid otherwise false
     */
    private function validate_time($format = 'H:i') {
        $d_start = DateTime::createFromFormat($format, $this->start_time);
        $d_end = DateTime::createFromFormat($format, $this->end_time);
        return $d_start && $d_start->format($format) == $this->start_time &&
            $d_end && $d_end->format($format) == $this->end_time;
    }

    /**
     * Checks if max member is a int
     * @return bool true if max member is a int otherwise false
     */
    function validate_max_member() {
        $number = filter_var($this->max_member, FILTER_VALIDATE_INT);
        return ($number !== FALSE);
    }

    /**
     * Sanitize the input data
     */
    private function sanitize_data() {
        if ($this->max_member == 0) {
            $this->max_member = null;
        }
    }

    /**
     * Insert new column to events
     */
    private function insert_event() {
        $data = array(
            'idclub'      => $this->id_club,
            'iduser'      => $this->id_user,
            'name'        => $this->name,
            'description' => $this->description,
            'place'       => $this->place,
            'startTime'   => $this->date . ' ' . $this->start_time,
            'endTime'     => $this->date . ' ' . $this->end_time,
            'maxMember'   => $this->max_member
        );
        DBInteractorService::getInstance()->executeInsertStatement('wp_event', $data, null);
    }

    /**
     * Generates the html for the options to choose a group
     * @return string
     */
    private function get_group_options() {

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

        return $options;
    }

    /**
     * Generates the html for the form to create a new event
     * @return string
     */
    private function event_form() {

        $options = $this->get_group_options();

        return '
        <div class="form-error">' . $this->message . '</div>
        <div class="form-success">' . $this->success . '</div>
        <form name="form" action="' . $_SERVER['REQUEST_URI'] . '" method="post">
            <h2 class="title">Beschreibung der Veranstaltung</h2>
             <p>
                <label for="name">Veranstaltungsname*<br>
                    <input type="text" name="event-name" value="' . $this->name . '">
                </label>
            </p>
             <p>
                <label for="description">Beschreibung*<br>
                    <input type="text" name="description" value="' . $this->description . '">
                </label>
            </p>
            <p>
                <label for="clubs">Zugehörige Sportart*<br>
                    <select name="option-idclub">
                        <option value="-1">Bitte auswählen</option>
                        ' . $options . '
                    </select>
                </label>
            </p>
            <h2 class="title">Informationen zur Veranstaltung</h2>
             <p class="row">
                <label for="date">Datum der Veranstaltung*<br>
                    <input type="date" name="date" value="' . $this->date . '">
                </label>
            </p>
            <p class="row">
                <label for="place">Ort der Veranstaltung*<br>
                    <input type="text" name="place" value="' . $this->place . '">
                </label>
            </p>
            <p class="row">
                <label for="time">Beginn der Veranstaltung*<br>
                    <input type="time" name="start-time" value="' . $this->start_time . '">
                </label>
            </p>
             <p class="row">
                <label for="time">Ende der Veranstaltung*<br>
                    <input type="time" name="end-time" value="' . $this->end_time . '">
                </label>
            </p>
            <p>
                <label for="max-member">Maximale Teilnehmeranzahl<br>
                    <input type="number" name="max-member" value="' . $this->max_member . '">
                </label>
            </p>
            <p>
                <button type="submit" name="submit">Veranstaltung anlegen</button>
            </p>
        </form>';
    }
}
