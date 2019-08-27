<?php

//include_once WP_CONTENT_DIR.'/DBService/DBInteractionClasses/InitiateDatabase.php';


/*
    This class organizes all the SQL-Database Interaction
    @Author: Fin Römer
*/

class DBInteractorService{

    /**
     * 
     *  (only) instance of this class
     * 
     */
    protected static $_dbInteractorService = null;
 
    /**
     * get instance
     *
     * returns the instance of this class, creates it, if there is no instance existing
     *
     * @return   DBInteractorService
     */
    public static function getInstance()
    {
        if (null === self::$_dbInteractorService)
        {
            self::$_dbInteractorService = new self;
        }
        return self::$_dbInteractorService;
    }
  
    /**
     * clone
     *
     * Prevent cloning of this class
     */
    protected function __clone() {}
    
    /**
     * constructor
     *
     * Prevent this class from being instanced by anything except itself
     */
    protected function __construct() {
     //   self::initiateDatabaseLandscape();
    }

    /**
     * Executes the given SQL-Select Statement.
     * 
     * For examples see at: https://codex.wordpress.org/Class_Reference/wpdb
     */
    public function executeSelectStatement( $selectSQL ) {
        global $wpdb;
        $myResultSet = $wpdb->get_results($selectSQL);
        return $myResultSet;
    }

    /**
     * Executes an Insert Statement
     * 
     * For examples see at: https://codex.wordpress.org/Class_Reference/wpdb
     * 
     * @tablename   The tablename of the table where the data should be inserted
     * @data        (array) Data to insert (in column => value pairs). Both $data columns and $data values 
     *                  should be "raw" (neither should be SQL escaped).
     * @format      (array|string) (optional) An array of formats to be mapped to each of the values in $data. 
     *                  If string, that format will be used for all of the values in $data.
     */
    public function executeInsertStatement( $tablename, $data, $format) {
        global $wpdb;
        if (is_null($format)) {
            $wpdb->insert($tablename, $data);
        } else {
            $wpdb->insert($tablename, $data, $format);
        }
    }
    
    /**
     * Executes an Update-Statement
     * 
     * For examples see at: https://codex.wordpress.org/Class_Reference/wpdb
     * 
     * @tablename   The name of the table
     * @data        (array) Data to update (in column => value pairs). Both $data columns and $data values should be "raw" 
     *                  (neither should be SQL escaped). This means that if you are using GET or POST data you may need to use 
     *                  stripslashes() to avoid slashes ending up in the database.
     * @where       (array) A named array of WHERE clauses (in column => value pairs). Multiple clauses will be joined with ANDs. 
     *                  Both $where columns and $where values should be "raw".
     */
    public function executeUpdateStatement( $table, $data, $where ) {
        global $wpdb;
        $wpdb->update(
            $table, 
            $data, 
            $where
        );
    }

    /**
     * Executes an Delete Statement
     * 
     * @tablename   The name of the table
     * @where       (array) (required) A named array of WHERE clauses (in column -> value pairs). 
     *                  Multiple clauses will be joined with ANDs. Both $where columns and $where values should be 'raw'. 
     * @where_format (string/array) (optional) An array of formats to be mapped to each of the values in $where. If a string,
     *                  that format will be used for all of the items in $where. A format is one of '%d', '%f', '%s' (integer, float, string). 
     *             
     * @returns     The number of rows deleted or false if an error occured.
     */
    public function deleteEntry( $tablename, $where, $where_format) {
        global $wpdb;
        if (is_null($where_format)) {
            return $wpdb->delete( $tablename, $where);
        }
        return $wpdb->delete( $tablename, $where, null);
    }

    /**
     * Executes the given SQL-Statement (no matter what type it is).
     * 
     * @sql         The SQL-Statement formatted as a String
     */
    public function executeGeneralStatement($sql) {
        global $wpdb;
        $wpdb->query($sql);
    }


}
?>