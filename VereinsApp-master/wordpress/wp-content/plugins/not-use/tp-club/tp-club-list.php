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
 * Class tp_club_list
 * lists clubs
 */
class tp_club_list {

    /**
     * @var String|null Input error message
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
    }

    /**
     * Initialization of the club list handling
     * @return string entry list
     */
    public function init_process() {

        $error = new WP_Error();

        if ($this->club_has_entry()) {
            if (isset($_POST['submit'])) {
                $id_club = htmlspecialchars($_POST['hidden-idclub']);
                $this->delete_club($id_club);
                $this->success = 'Die Sportart wurde erfolgreich gelöscht.';
            }
        } else {
            $error->add('no_entry', 'Bisher sind noch keine Sportarten angelegt.');
        }
        return $this->club_list();
    }

    /**
     * Delete column from club
     * @param $id_entry
     */
    private function delete_club($id_club) {
        $where = array(
            'id' => $id_club,
        );
        DBInteractorService::getInstance()->deleteEntry('wp_club', $where, null);
    }

    /**
     * Checks if club has at least one entry
     * @return bool true if club has entry otherwise false
     */
    private function club_has_entry() {

        // select number of clubs
        $myResultSet = DBInteractorService::getInstance()->executeSelectStatement(
            DBInteractionUtils::$_selectCountOfClubs);

        if ($myResultSet[0]->count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Generates the html for the clubs
     * @return string
     */
    private function get_clubs() {

        // select all clubs
        $myResultSet = DBInteractorService::getInstance()->executeSelectStatement(
            DBInteractionUtils::$_selectClubIDsAndNames);

        $clubs = '<ul class="club-entity">';

        foreach ($myResultSet as $result) {
            $club = array (
                'idclub' => $result->id,
                'name'   => $result->name
            );
            $club = new tp_club_entry($club);
            $clubs .= $club->get_club();
            $clubs .= $this->delete_form($result->id);
        }

        $clubs .= '</ul>';

        return $clubs;
    }

    /**
     * Generates the html for the club list
     * @return string
     */
    private function club_list() {
        return '
        <div class="form-error">' . $this->message . '</div>
        <div class="form-success">' . $this->success . '</div>
        <div class="club-window">
            ' . $this->get_clubs() . '
        </div>';
    }

    /**
     * Generates the html for the form to delete a club
     * @param $id_club
     * @return string
     */
    private function delete_form($id_club) {
        return '
        <form name="form" action="' . $_SERVER['REQUEST_URI'] . '" method="post">
            <input type="hidden" name="hidden-idclub" value="' . $id_club . '">
            <p class="row">
                <button type="submit" name="submit">Sportart löschen</button>
            </p>
            <div class="clear"></div>
        </form>';
    }
}
