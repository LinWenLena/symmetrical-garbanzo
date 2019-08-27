<?php

/**
 * 
 * The DTO for the wp_userinclub table
 * author: Fin Römer
 * 
 */
class UserInClubDTO {
    /**
     * The Database-entries
     */
    private $userID;
    private $clubID;
    private $username;
    private $clubname;
    private $role;

    /**
     * Creates a new UserInClubDTO
     */
    public function __construct($idUser, $nameUser, $idClub, $nameclub, $currentRole, $currentStatus) {
        $this->userID = $idUser;
        $this->clubID = $idClub;
        $this->username = $nameUser;
        $this->clubname = $nameClub;
        $this->role = $currentRole;
        $this->status = $currentStatus;
    }

    /**
     * getters
     */
    public function __get($property) {
        if (property_exists($this, $property)) {
          return $this->property;
        }
    }
}

?>