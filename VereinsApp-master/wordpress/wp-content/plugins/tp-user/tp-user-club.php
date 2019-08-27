<?php
/**
 * Created by PhpStorm.
 * User: mircobaseniak
 * Date: 17.04.18
 * Time: 13:25
 */

include_once WP_CONTENT_DIR . '/DBService/DBInteractionUtils.php';
include_once WP_CONTENT_DIR . '/DBService/DBInteractorService.php';

/*
 *  Customize ACF Plugin
 */

/**
 * Dynamic generation of selection fields for fields with the name sportart
 * @param $field - the field to be filled
 * @return mixed - the filled field
 */
function acf_load_sport_field_choices($field) {

    $field['choices'] = array();

    // select sport clubs
    $myResultSet = DBInteractorService::getInstance()->executeSelectStatement(
        DBInteractionUtils::$_selectClubIDsAndNames);

    // create selection options
    foreach ($myResultSet as $result) {
        $field['choices'][$result->id] = $result->name;
    }

    return $field;
}

add_filter('acf/load_field/name=sportart', 'acf_load_sport_field_choices');

/**
 * Class tp_club
 * add or remove a user from a club
 */
class tp_club {

    /**
     * @var int Should contain a user id
     */
    private $id_user;

    /**
     * @var int Should contain a sport id
     */
    private $id_club;

    /**
     * tp_club constructor.
     */
    public function __construct() {
        // shortcut to integrate club form in the frontend
        add_shortcode('club-form', array($this, 'init_process'));
    }

    /**
     * Initialization of the club handling for the club form
     * @return string Custom club form
     */
    public function init_process($atts) {
        $this->id_user = get_current_user_id();
        $this->id_club = htmlspecialchars($atts['idclub']);
        if (isset($_POST['submit'])) {
            if ($this->club_has_user()) {
                $this->remove_user_from_club();
            } else {
                $this->add_user_to_club();
            }
        }
        return $this->club_form();
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
     * Insert new column to userinclub
     */
    private function add_user_to_club() {
        $data = array(
            'idclub'       => $this->id_club,
            'iduser'       => $this->id_user,
            'role'         => 'Mitglied',
            'entereddate'  => date('Y-m-d H:i:s')
        );
        DBInteractorService::getInstance()->executeInsertStatement('wp_userinclub', $data, null);
    }

    /**
     * Delete column from userinclub
     */
    private function remove_user_from_club() {
        $where = array(
            'idclub'    => $this->id_club,
            'iduser'    => $this->id_user
        );
        DBInteractorService::getInstance()->deleteEntry('wp_userinclub', $where, null);
    }

    /**
     * Generates the html for the club form
     * @return string Custom club form
     */
    public function club_form() {
        if ($this->club_has_user()) {
            $text = 'Sportart verlassen';
        } else {
            $text = 'Sportart beitreten';
        }
        return '
        <form name="form" action="' . $_SERVER['REQUEST_URI'] . '" method="post">
            <p>
                <button type="submit" name="submit">' . $text . '</button>
            </p>
        </form>';
    }
}
