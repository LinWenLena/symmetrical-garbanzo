<?php

include_once WP_CONTENT_DIR . '/DBService/DBInteractorService.php';
include_once WP_CONTENT_DIR . '/DBService/DBInteractionUtils.php';

/**
 * This class is handling the user profile
 * 
 * @author Fin Römer
 */
class tp_user_club_view {

    private $_dbInteractorService;
    private $_tableData;
    private $_tableHTML;

    public function __construct() {
        add_shortcode('user-clubview-form', array($this, 'init_process'));
        $this->initData();

    }

    /**
     * Initializes the user-data to show them.
     */
    public function initData() {
        $this->_dbInteractorService = DBInteractorService::getInstance();
    }

     /**
     * Initialization of the register handling for the register form
     * @return string Custom register form
     */
    public function init_process() {

        if (isset($_POST['leaveClub'])) {
            global $wpdb;
            $entryID = $_POST['leaveClub'];

            $tableName = DBInteractionUtils::$_userInClubTableName;
            $userID = $this->getCurrentUserID();
            $whereArray = array('idClub' => $entryID, 'idUser' => $userID);
            $wpdb->delete( $tableName, $whereArray);

            wp_redirect($_SERVER['HTTP_REFERER']);
        }


        return $this->edit_user_form();
    }

    /**
     * refreshes the table data
     */
    private function refreshTableData() {
        $selectUserClubs = DBInteractionUtils::selectClubIDsAndNamesBasedOfUserID($this->getCurrentUserID());
        $this->_tableData = $this->_dbInteractorService->executeSelectStatement($selectUserClubs);
    }

     /**
     * returns the current ID for the logged in User
     */
    private function getCurrentUserID() {
        if ( ! function_exists( 'wp_get_current_user' ) )
            return 0;
        $user = wp_get_current_user();
        return ( isset( $user->ID ) ? (int) $user->ID : 0 );
    }

    /**
     * This function returns the html-code for the form of the page
     */
    public function edit_user_form() {
        $selectUserClubs = DBInteractionUtils::selectClubIDsAndNamesBasedOfUserID($this->getCurrentUserID());
        global $wpdb;
        $tableData = $wpdb->get_results($selectUserClubs);

        $counter = 1;

        $tableLayout = '
        <form name="form" action="' . $_SERVER['REQUEST_URI'] . '" method="post" class="my-form-class">      
            <ul class="clubs">
                <li class="header">
                    <span class="name">Sportart</span>
                    <span class="action">Aktion</span>
                    <div class="clear"></div>
                </li>';
        $dataSet = $tableData;
        foreach ($dataSet as $tableSet) {
            $tableID = $tableSet->id;
            $tableLayout = $tableLayout . '
                <li>
                    <span class="name">
                        ' . $tableSet->name . '
                    </span>
                    <span class="action">
                        <button type="submit" name="leaveClub" value="'. $tableID .'">Sportart verlassen</button>
                    </span>
                    <div class="clear"></div>
                </li>';
            $counter = $counter + 1;
        }

        $tableEnd = '</ul>
            </form>';

        $tableLayout = $tableLayout . $tableEnd;
       // $this->_tableHTML = $tableLayout;
        return $tableLayout;
    }
}

?>