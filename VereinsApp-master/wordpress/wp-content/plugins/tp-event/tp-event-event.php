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
 * Class tp_event
 * event unit
 */
class tp_event {

    /**
     * @var int Should contain a event id
     */
    private $id_event;

    /**
     * @var int Should contain a club id
     */
    private $id_club;

    /**
     * @var int Should contain a user id
     */
    private $id_user;

    /**
     * @var String Should contain a event name
     */
    private $name;

    /**
     * @var String Should contain a event description
     */
    private $description;

    /**
     * @var String Should contain a event place
     */
    private $place;

    /**
     * @var String Should contain the event start time
     */
    private $start_time;

    /**
     * @var String Should contain the event end time
     */
    private $end_time;

    /**
     * @var int Should contain number of max member of event
     */
    private $max_member;

    /**
     * tp_event constructor.
     */
    public function __construct($event_unit) {
        $this->id_event        = $event_unit['idevent'];
        $this->id_club         = $event_unit['idclub'];
        $this->id_user         = $event_unit['iduser'];
        $this->name            = $event_unit['name'];
        $this->description     = $event_unit['description'];
        $this->place           = $event_unit['place'];
        $this->start_time      = $event_unit['startTime'];
        $this->end_time        = $event_unit['endTime'];
        $this->max_member      = $event_unit['maxmember'];
    }

    /**
     * Insert new column to events
     */
    public function insert_event_unit() {
        $data = array(
            'idclub'      => $this->id_club,
            'iduser'      => $this->id_user,
            'name'        => $this->name,
            'description' => $this->description,
            'place'       => $this->place,
            'startTime'   => $this->start_time,
            'endTime'     => $this->end_time,
            'maxmember'   => $this->max_member
        );
        DBInteractorService::getInstance()->executeInsertStatement('wp_event', $data, null);
    }

    /**
     * Get name of club of event
     * @return mixed
     */
    private function club_name() {
        // select club name based of this club id
        $myResultSet = DBInteractorService::getInstance()->executeSelectStatement(
            DBInteractionUtils::selectClubNameBasedOfClubID($this->id_club));
        return $myResultSet[0]->name;
    }

    /**
     * Get count of user in event
     * @return mixed
     */
    private function member_count() {
        // select count of members in event with this event id
        $myResultSet = DBInteractorService::getInstance()->executeSelectStatement(
            DBInteractionUtils::selectCountOfUserInEvent($this->id_event));
        return $myResultSet[0]->count;
    }

    /**
     * Get count of free places of event
     * @return int|string count of places or Unbegrenzt
     */
    private function free_places() {
        if ($this->max_member != null) {
            return $this->max_member - $this->member_count();
        } else {
            return 'Unbegrenzt';
        }
    }

    /**
     * Generates the html for the event
     * @return string event
     */
    public function get_event() {
        return '
        <h2 class="name">' . $this->name . ' </h2>
        <p class="description">' . $this->description . ' </p>
        <ul class="infos">
            <li class="sport-club">
                <span class="name">Sportart</span>
                <span>' . $this->club_name() . '</span>
                <div class="clear"></div>
            </li>
            <li class="author-name">
                <span class="name">Autor</span>
                <span>' . get_userdata($this->id_user)->display_name . '</span>
                <div class="clear"></div>
            </li>
            <li class="date">
                <span class="name">Datum</span>
                <span>' . date('d.m.Y', strtotime($this->start_time)) . '</span>
                <div class="clear"></div>
            </li>
            <li class="time">
                <span class="name">Zeit</span>
                <span>von ' . date('H:i', strtotime($this->start_time)) . ' Uhr bis ' . date('H:i', strtotime($this->end_time)) . ' Uhr</span>
                <div class="clear"></div>
            </li>   
            <li class="place">
                <span class="name">Ort</span>
                <span>' . $this->place . '</span>
                <div class="clear"></div>
            </li> 
            <li class="join-member">
                <span class="name">Teilnehmerzahl</span>
                <span>' . $this->member_count() . '</span>
                <div class="clear"></div>
            </li>
            <li class="free-places">
                <span class="name">Freie Plätze</span>
                <span>' . $this->free_places() . '</span>
                <div class="clear"></div>
            </li>
            <div class="clear"></div>
        </ul>';
    }
}
