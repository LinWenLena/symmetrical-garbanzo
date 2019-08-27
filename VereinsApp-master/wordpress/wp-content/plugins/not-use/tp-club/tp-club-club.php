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
 * Class tp_club_entry
 * club entry
 */
class tp_club_entry {

    /**
     * @var int Should contain a club id
     */
    private $id_club;

    /**
     * @var string|null Should contain a name
     */
    private $name;

    /**
     * tp_club constructor.
     */
    public function __construct($club) {
        $this->id_club  = $club['idclub'];
        $this->name     = $club['name'];
    }

    /**
     * Generates the html for the club
     * @return string
     */
    public function get_club() {
        return '
        <li>
            <span class="name">' . $this->name . '</span><br>
        </li>
        ';
    }
}
