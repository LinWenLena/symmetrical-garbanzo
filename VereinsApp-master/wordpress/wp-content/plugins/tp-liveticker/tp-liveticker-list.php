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
 * Class tp_liveticker_list
 * lists liveticker entrys
 */
class tp_liveticker_list {

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
        // shortcut to integrate liveticker list in the frontend
        add_shortcode('liveticker', array($this, 'init_process'));
    }

    /**
     * Initialization of the liveticker list handling
     * @return string entry list
     */
    public function init_process($atts, $content, $tag) {

        $error = new WP_Error();

        if ($this->liveticker_has_entry()) {
            if (isset($_POST['submit'])) {
                $id_entry = htmlspecialchars($_POST['submit']);
                $this->delete_entry($id_entry);
                $this->success = 'Der Eintrag wurde erfolgreich gelöscht.';
            }
        } else {
            $error->add('no_entry', 'Der Liveticker hat bisher noch keine Einträge.');
        }
        return $this->entry_list($tag);
    }

    /**
     * Delete column from liveticker
     * @param $id_entry
     */
    private function delete_entry($id_entry) {
        $where = array(
            'live_id' => $id_entry,
        );
        DBInteractorService::getInstance()->deleteEntry('wp_liveticker', $where, null);
    }

    /**
     * Checks if liveticker has at least one entry
     * @return bool true if liveticker has entry otherwise false
     */
    private function liveticker_has_entry() {

        // select number of liveticker entrys
        $myResultSet = DBInteractorService::getInstance()->executeSelectStatement(
            DBInteractionUtils::$_selectCountOfLivetickerEntries);

        if ($myResultSet[0]->count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Generates the html for the liveticker entrys
     * @param $type Type of list
     * @return string
     */
    private function get_entrys($type) {

        $myResultSet = '';

        switch ($type) {
            case 'liveticker':
                // select last 50 liveticker entries
                $myResultSet = DBInteractorService::getInstance()->executeSelectStatement(
                    DBInteractionUtils::$_selectLivetickerEntries);
                break;
            default:
                // select all liveticker entrys
                $myResultSet = DBInteractorService::getInstance()->executeSelectStatement(
                    DBInteractionUtils::$_selectAllLivetickerEntries);
                break;
        }

        $entrys = '<ul class="lt-entity">';

        foreach ($myResultSet as $result) {
            $entry = array (
                'identry'       => $result->live_id,
                'title'         => $result->title,
                'description'   => $result->content,
                'time'          => $result->create_time,
            );
            $entry = new tp_entry($entry);
            $entrys .= $entry->get_entry($type);
            switch ($type) {
                case 'liveticker':
                    break;
                default:
                    $entrys .= $this->delete_form($result->live_id);
                    break;
            }
        }

        $entrys .= '</ul>';

        return $entrys;
    }

    /**
     * Generates the html for the liveticker list
     * @param $type Type of list
     * @return string
     */
    private function entry_list($type) {
        return '
        <div class="form-error">' . $this->message . '</div>
        <div class="form-success">' . $this->success . '</div>
        <div class="entry-window">
            ' . $this->get_entrys($type) . '
        </div>';
    }

    /**
     * Generates the html for the form to delete a liveticker entry
     * @param $id_entry
     * @return string
     */
    private function delete_form($id_entry) {
        return '
        <form name="form" action="' . $_SERVER['REQUEST_URI'] . '" method="post">
            <p class="row">
                <button type="submit" name="submit" value="' . $id_entry . '">Eintrag löschen</button>
            </p>
            <div class="clear"></div>
        </form>';
    }
}
