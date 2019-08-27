<?php

include_once WP_CONTENT_DIR . '/DBService/DBInteractorService.php';
include_once WP_CONTENT_DIR . '/DBService/DBInteractionUtils.php';

/**
 * This class shows the Clubs and give the administrator the possibility to delete Club-Entries
 * 
 * @author: Fin Römer
 */
class tp_club_view {

    /**
     * The table data
     */
    public $_tableData;

    /**
     * the DB Interactor
     */
    public $_dbInteractorService;
    
    /**
     * The table/div HTML
     */
    public $_tableHTML;

    /**
     * Constructor
     */
    public function __construct() {
        $this->_dbInteractorService = DBInteractorService::getInstance();
        $this->init_process();
    }

    /**
     * inits all necessary data of this class including the action handler for the DELETE-Buttons
     */
    private function init_process() {
        
        $this->refreshTableData();
        if (isset($_POST['dropEntry'])) {
            global $wpdb;
            $entryID = $_POST['dropEntry'];

            $tableName = DBInteractionUtils::$_userInClubTableName;
            $whereArray = array('idClub' => $entryID);
            $wpdb->delete( $tableName, $whereArray);
           
            $tableName = DBInteractionUtils::$_clubsTableName;
            $whereArray = array('id' => $entryID);
            $wpdb->delete( $tableName, $whereArray);

            wp_redirect($_SERVER['HTTP_REFERER']);
        }
    }

    /**
     * refreshes the table data
     */
    private function refreshTableData() {
        $selectClubNames = DBInteractionUtils::$_selectClubIDsAndNamesWithAlias;
        $this->_tableData = $this->_dbInteractorService->executeSelectStatement($selectClubNames);
        $this->refreshTableEntries();
    }

    /**
     * Creates the HTML-Div-Code with the table entries
     */
    private function refreshTableEntries() {
        $counter = 1;
        $tableLayout = '<div>
            <form name="form" action="' . $_SERVER['REQUEST_URI'] . '" method="post" >
                <table class="widefat myTable">
                    <thead>
                        <tr>
                            <th> Nummer </th>
                            <th> Sportart </th> 
                            <th> Aktion </th> 
                        </tr>
                    </thead>
                        ';
        $dataSet = $this->_tableData;
        foreach ($dataSet as $tableSet) {
            $tableID = $tableSet->id;
            $tableLayout = $tableLayout . '<tr>' . 
            
            '<td>' . $counter . '</td>' . '<td>' . $tableSet->name . '</td>' .
            '<td>' . 
            '<button type="submit" name="dropEntry" value="'. $tableID .'">Löschen</button>'.          
            '</td></tr>';

            $counter = $counter + 1;
        }

        $tableEnd = '</table>
            </form>
        </div>';

        $tableLayout = $tableLayout . $tableEnd;
        $this->_tableHTML = $tableLayout;
    }

    /**
     * Creates the Table Div displaying the Users
     */
    public function createClubsTableDiv() {
        return '<div value='. $this->_tableHTML;
    }

}


?>