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
 * Class tp_create_entry
 * create a new liveticker entry
 */
class tp_create_entry {

    /**
     * @var string|null Should contain a title
     */
    private $title;

    /**
     * @var string|null Should contain a description
     */
    private $description;

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
     * Initialization of the create liveticker handling
     * @return string
     */
    public function init_process() {
        if (isset($_POST['submit'])) {
            $this->title        = htmlspecialchars($_POST['title']);
            $this->description  = htmlspecialchars($_POST['description']);
            if ($this->check_data()) {
                $this->insert_entry();
                $this->success = 'Der Eintrag wurde erfolgreich angelegt.';
                $this->title = "";
                $this->description = "";
            }
        }
        return $this->liveticker_form();
    }

    /**
     * Checks the input data and creates error message
     * @return bool True if data is correct otherwise false
     */
    private function check_data() {
        $correct = true;
        $error = new WP_Error();

        // Check if title and content is set
        if (empty($this->title) || empty($this->description)) {
            $error->add('field', 'Bitte alle Felder ausfüllen.');
        }

        if (is_wp_error($error) && !empty($error->errors)) {
            $this->message = '<strong>FEHLER</strong>: ' . $error->get_error_message();
            $correct = false;
        }
        return $correct;
    }

    /**
     * Insert new column to liveticker
     */
    private function insert_entry() {
        $data = array(
            'title'         => $this->title,
            'content'       => $this->description,
            'create_time'   => date('Y-m-d H:i:s')
        );
        DBInteractorService::getInstance()->executeInsertStatement('wp_liveticker', $data, null);
    }

    /**
     * Generates the html for the form to create a new liveticker entry
     * @return string
     */
    private function liveticker_form() {
        return '
        <div class="form-error">' . $this->message . '</div>
        <div class="form-success">' . $this->success . '</div>
        <form name="form" action="' . $_SERVER['REQUEST_URI'] . '" method="post">
            <h2 class="title">Liveticker-Eintrag erstellen</h2>
             <p>
                <label for="title">Titel<br>
                    <input type="text" name="title" value="' . $this->title . '">
                </label>
            </p>
             <p>
                <label for="description">Beschreibung<br>
                    <input type="text" name="description" value="' . $this->description . '">
                </label>
            </p>
            <p>
                <button type="submit" name="submit">Eintrag erstellen</button>
            </p>
        </form>';
    }
}
