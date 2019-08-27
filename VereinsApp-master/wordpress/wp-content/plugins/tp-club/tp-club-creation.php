<?php

include_once WP_CONTENT_DIR . '/DBService/DBInteractorService.php';
include_once WP_CONTENT_DIR . '/DBService/DBInteractionUtils.php';

/**
 * Class for the club creation.
 * @author: Fin Römer
 */
class tp_club_creation {

    /**
     * The Clubname
     */
    private $clubname;
    
    /**
     * The DB Interactor
     */
    private $_dbInteractorService;

    /**
     * constructor
     */
    public function __construct() {
        $this->init_process();
    }

    /**
     * Inits the necessary variables for this class including the "create new club"-handler
     */
    private function init_process() {
        $this->clubname = "";
        $this->_dbInteractorService = DBInteractorService::getInstance();

        if (isset($_POST['submitClubCreation'])) {
            $this->clubname = htmlspecialchars($_POST['clubname']);
            $this->createClub();
            $this->clearClubnameEntry();
            wp_redirect($_SERVER['HTTP_REFERER']);
        }
    }

    /**
     * Clears the club-name entry (so it does not show on page-refresh in the text-input)
     */
    private function clearClubnameEntry() {
        $this->clubname = "";
    }

    /**
     * Create the database entry for the club.
     */
    private function createClub() {
        $clubTableName = DBInteractionUtils::$_clubsTableName;
        $time = current_time( $type = 'mysql', $gmt = 0 );
        $clubDataSet = array('name' => $this->clubname,
                             'creationTime' => $time);
        $this->_dbInteractorService->executeInsertStatement($clubTableName, $clubDataSet, null);
    }

    /**
     * Creates the div for creating new Clubs
     */
    public function getClubCreationDiv() {
        return '
        <div>
            <h2>Neue Sportart erstellen</h2>
            <form name="form" action="' . $_SERVER['REQUEST_URI'] . '" method="post">
                <p class="row">
                    <label for="clubname">Sportart<br>
                        <input type="text" name="clubname" value="' . $this->clubname . '">
                    </label>
                </p>
                <p>
                    <button type="submit" name="submitClubCreation">Sportart erstellen</button>
                </p>  
            </form>
        </div>';
    }

}

?>