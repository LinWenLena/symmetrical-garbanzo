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
 * Class tp_entry
 * liveticker entry
 */
class tp_entry {

    /**
     * @var int Should contain a liveticker id
     */
    private $id_entry;

    /**
     * @var string|null Should contain a title
     */
    private $title;

    /**
     * @var string|null Should contain a description
     */
    private $description;

    /**
     * @var String Should contain the event time
     */
    private $time;

    /**
     * tp_entry constructor.
     */
    public function __construct($entry) {
        $this->id_entry = $entry['identry'];
        $this->title = $entry['title'];
        $this->description = $entry['description'];
        $this->time = $entry['time'];
    }

    /**
     * Generates the html for the liveticker entry
     * @param $type Type of list
     * @return string
     */
    public function get_entry($type) {
        switch ($type) {
            case 'liveticker':
                return '
                <li>
                    <span class="time">
                        ' . date('d.m.Y', strtotime($this->time)) . '<br>
                        ' . date('H:i', strtotime($this->time)) . ' Uhr
                    </span>
                    <span class="title">' . $this->title . '</span>
                    <span>' . $this->description . '</span>
                    <div class="clear"></div>
                </li>';
                break;
            default:
                return '
                <li>
                    <span class="time">' . date('d.m.Y H:i', strtotime($this->time)) . '</span><br>
                    <span class="title"><b>' . $this->title . '</b></span><br>
                    <span>' . $this->description . '</span><br>
                </li>';
                break;
        }
    }
}
