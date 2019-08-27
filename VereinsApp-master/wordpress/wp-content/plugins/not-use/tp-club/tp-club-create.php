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
 * Class tp_create_club
 * create a new club
 */
class tp_create_club {

    /**
     * @var string|null Should contain a name
     */
    private $name;

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
    }

    /**
     * Initialization of the create club handling
     * @return string
     */
    public function init_process() {
        if (isset($_POST['submit'])) {
            $this->name = htmlspecialchars($_POST['name']);
            if ($this->check_data()) {
                $this->insert_club();
                $this->success = 'Die Sportart wurde erfolgreich angelegt.';
            }
        }
        return $this->club_form();
    }

    /**
     * Checks the input data and creates error message
     * @return bool True if data is correct otherwise false
     */
    private function check_data() {
        $correct = true;
        $error = new WP_Error();

        // Check if name is set
        if (empty($this->name)) {
            $error->add('field', 'Bitte einen Namen angeben.');
        }

        // select club id of club with input name
        $myResultSet = DBInteractorService::getInstance()->executeSelectStatement(
            DBInteractionUtils::selectClubIDBasedOfClubName($this->name));

        if ($myResultSet) {
            $error->add('club_exist', 'Eine Sportart mit diesem Namen existiert bereits.');
        }

        if (is_wp_error($error) && !empty($error->errors)) {
            $this->message = '<strong>FEHLER</strong>: ' . $error->get_error_message();
            $correct = false;
        }
        return $correct;
    }

    /**
     * Insert new column to club
     */
    private function insert_club() {
        $data = array(
            'name'         => $this->name,
            'creationTime' => date('Y-m-d H:i:s')
        );
        DBInteractorService::getInstance()->executeInsertStatement('wp_club', $data, null);
    }

    /**
     * Generates the html for the form to create a new club
     * @return string
     */
    private function club_form() {
        return '
        <div class="form-error">' . $this->message . '</div>
        <div class="form-success">' . $this->success . '</div>
        <form name="form" action="' . $_SERVER['REQUEST_URI'] . '" method="post">
            <h2 class="title">Sportart erstellen</h2>
             <p>
                <label for="title">Name<br>
                    <input type="text" name="name" value="' . $this->name . '">
                </label>
            </p>
            <p>
                <button type="submit" name="submit">Eintrag erstellen</button>
            </p>
        </form>';
    }
}
