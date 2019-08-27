<?php

/**
 * This class contains all the SQL statements
 */
class DBInteractionUtils {
    /**
     * Please use this attribute when u refer to the table in case we change the table-prefix
     * Sadly this is not possible in constants since concatenation is not allowed for static attributes
     */
    public static $_tablePrefix = 'wp_';
    public static $_clubsTableName = 'wp_club';
    public static $_userInClubTableName = 'wp_userinclub';

    /*
     * Select Statements
     */

    /*
     * Table: Liveticker
     */

    public static $_selectAllLivetickerEntries = '
    SELECT *
    FROM wp_liveticker l
    ORDER BY l.create_time DESC;';

    public static $_selectLivetickerEntries = '
    SELECT *
    FROM (
        SELECT * 
        FROM wp_liveticker l
        ORDER BY l.create_time
        DESC LIMIT 50
    ) l
    ORDER BY l.create_time DESC;';

    public static $_selectCountOfLivetickerEntries = '
    SELECT COUNT(*) AS count
    FROM wp_liveticker;';
    
    
    public static function selectLimitedLivetickerEntries($firstcount, $count) {
    return'
    SELECT *
    FROM wp_liveticker l
    ORDER BY l.create_time DESC 
    limit ' .$firstcount. ',' .$count. ';';
		
	}

    /*
     * Table: Club
     */

    public static $_selectCountOfClubs = '
    SELECT COUNT(*) AS count
    FROM wp_club;';

    public static $_selectClubNames = '
    SELECT c.name 
    FROM wp_club c;';

    public static $_selectClubIDsAndNames = '
    SELECT c.id, c.name
    FROM wp_club c;';

    public static $_selectClubIDsAndNamesWithAlias = '
    SELECT c.id, c.name
    FROM wp_club c;';

    public static function selectClubIDsAndNamesBasedOfUserID($id_user) {
        return '
        SELECT c.id, c.name
        FROM wp_club c
        INNER JOIN wp_userinclub u
        ON c.id = u.idclub
        WHERE u.iduser = ' . $id_user . '';
    }

    public static function selectClubNameBasedOfClubID($id_club) {
        return '
        SELECT c.name
        FROM wp_club c
        WHERE c.id = ' . $id_club . '';
    }

    public static function selectClubIDBasedOfClubName($name) {
        return '
        SELECT c.id
        FROM wp_club c
        WHERE c.name = "' . $name . '"';
    }

    /*
     * Table: User In Club
     */

    public static function selectUserIDBasedOfClubIDAndUserID($id_club, $id_user) {
        return '
        SELECT u.iduser 
        FROM wp_userinclub u 
        WHERE u.idclub =' . $id_club . ' 
        AND u.iduser = ' . $id_user . ';';
    }

    /**
     * Tabel: User In Event
     */

    public static function selectCountOfUserInEvent($id_event) {
        return '
        SELECT COUNT(*) AS count
        FROM wp_userinevent u
        WHERE u.idevent =' . $id_event . '
        ';
    }

    public static function selectUserIDBasedOfEventIDAndUserID($id_event, $id_user) {
        return '
        SELECT u.iduser 
        FROM wp_userinevent u 
        WHERE u.idevent =' . $id_event . ' 
        AND u.iduser = ' . $id_user . ';';
    }

    /**
     * Table: Event
     */
         
    public static $_selectEvents = '
    SELECT *
    FROM wp_event e;';

    public static function selectEventsBasedOfUserID($id_user) {
        return '
        SELECT * 
        FROM wp_event e
        WHERE e.iduser = ' . $id_user . '
        ORDER BY e.starttime ASC';
    }

    public static function selectEventsBasedOfClubID($id_club) {
        return '
        SELECT * 
        FROM wp_event e
        WHERE e.idclub = ' . $id_club . '
        ORDER BY e.starttime ASC';
    }

    public static function selectEventsBasedOfClubIDAndUserID($id_club, $id_user) {
        return '
        SELECT * 
        FROM wp_event e
        INNER JOIN wp_userinevent u
        ON e.id = u.idevent
        WHERE e.idclub = ' . $id_club . '
        AND u.iduser = ' . $id_user . '
        ORDER BY e.starttime ASC';
    }

    public static function selectAuthorOfEventID($id_event) {
        return '
        SELECT e.iduser
        FROM wp_event e
        WHERE e.id = ' . $id_event . '';
    }
    

    public static function selectEventEndTimeOfEventID($id_event) {
        return '
        SELECT e.endtime 
        FROM wp_event e
        WHERE e.id = ' . $id_event . '';
    }

    public static function selectMaxMemberNumberOfEventID($id_event) {
        return '
        SELECT e.maxmember 
        FROM wp_event e
        WHERE e.id = ' . $id_event . '';
    }
    
    public static function selectCountOfEventUser($id_user) {
        return '
        SELECT COUNT(*) AS count
        FROM wp_enent e
        WHERE u.ideuser =' . $id_user . '
        ';
    }

    /**
     * Table: Messages
     */

    public static function selectMessagesBasedOfClubID($id_club) {
        return '
        SELECT * 
        FROM (
            SELECT * 
            FROM wp_messages m
            WHERE m.idclub = ' . $id_club . '
            ORDER BY m.creationtime 
            DESC LIMIT 50
        ) m
        ORDER BY m.creationtime DESC;';
    }

    public static function selectMessageUnitBasedOfMessageID($id_message) {
        return '
        SELECT * 
        FROM wp_messages m 
        WHERE m.id = ' . $id_message . ';';
    }

    /**
     * Constructs a SQL-Statement
     * @var elementList the elementList formatted as String e.g. "club.name, club.id"
     * @var tableList the table list formatted as String, e.g. "wp_club, wp_user"
     * @var whereClause the where clause formatted as String without "where" and ";", e.g. "wp_user.id > 8"
     */
    public static function constructSelectStatement ($elementList, $tableList, $whereClause) {
        $sql = 'SELECT '.$elementList.' From '.$tableList.' where '.$whereClause.';';
        return $sql;
    }
    
}
?>