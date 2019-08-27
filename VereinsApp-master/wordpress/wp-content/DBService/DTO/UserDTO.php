<?php

class UserDTO {

    private $user_firstname;
    private $user_lastname;
    private $email;
    private $displayName;
    private $phonenumer;
    private $password;

    private static $_currentUserDTO;

    public static function getCurrentUserInstance() {
        $currentUser = wp_get_current_user();
        self::$_currentUserDTO = new UserDTO ($currentUser->user_firstname, $currentUser->user_lastname, $currentUser->user_email, $currentUser->display_name);
        return self::$_currentUserDTO;
    }

    public function __construct ($user_firstname, $user_lastname, $email, $displayName) {
        $this->setUser_lastname($user_lastname);
        $this->setEmail($email);
        $this->setForumname($displayName);
        $this->setUser_firstname($user_firstname);
    }


    /**
     * Getters and Setters following
     */

    public function setUser_firstname ($user_firstname) {
        $this->user_firstname = $user_firstname;
    }

    public function setUser_lastname ($user_lastname) {
        $this->user_lastname = $user_lastname;
    }

    public function setEmail ($email) {
        $this->email = $email;
    }

    public function setDisplayName ($displayName) {
        $this->displayName = $displayName;
    }

    public function setPassword ($password) {
        $this->password = $password;
    }

    public function setPhonenumber ($phonenumber) {
        $this->phonenumber = $phonenumber;
    }

    public function getUser_firstname () {
        return $this->user_firstname;
    }

    public function getUser_lastname () {
        return $this->user_lastname;
    }

    public function getEmail () {
        return $this->username;
    }
    
    public function getDisplayName () {
        return $this->displayName;
    }

    public function getPhonenumber() {
        return $this->phonenumber;
    }
}



?>