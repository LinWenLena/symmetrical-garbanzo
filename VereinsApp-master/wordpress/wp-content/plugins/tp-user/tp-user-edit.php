<?php

include_once WP_CONTENT_DIR . '/DBService/DTO/UserDTO.php';

/**
 * This class is handling the user profile
 * 
 * @author Fin Römer
 */
class tp_user_edit {

    private $user_firstname;
    private $user_lastname;
    private $user_oldPassword;
    private $user_newPassword;
    private $user_oldEmail;
    private $user_newEmail;
    private $message;
    private $success;

    public function __construct() {
        add_shortcode('edit-user-form', array($this, 'init_process'));
        $this->initUserData();
    }

    /**
     * Initializes the user-data to show them.
     */
    public function initUserData() {
        $userdata = $this->getCurrentUserdata();
        $this->user_firstname = $userdata->first_name;
        $this->user_oldEmail = $userdata->user_email; 
        $this->user_lastname = $userdata->last_name;
        
    }

     /**
     * Initialization of the register handling for the register form
     * @return string Custom register form
     */
    public function init_process() {
        
        // on password-change submit
        if (isset($_POST['submitPasswordChange'])) {
            $this->user_newPassword = htmlspecialchars($_POST['user_newPassword']);
            $this->user_oldPassword = htmlspecialchars($_POST['user_oldPassword']);
            if ($this->checkPassword()) {
                $this->sanitize_password();
                wp_update_user( array('ID' => $this->getCurrentUserdata()->ID, 'user_pass' => $this->user_newPassword));
                $this->clear_user_passwords();
            }
            
        }

        // on mail-change submit
        if (isset($_POST['submitMailChange'])) {
            $this->user_newEmail    = htmlspecialchars($_POST['user_newEmail']);
            $this->user_oldEmail = htmlspecialchars($_POST['user_oldEmail']);
            if ($this->check_email()) {
                $this->sanitizeEmail();
                wp_update_user( array('ID' => $this->getCurrentUserdata()->ID, 'user_email' => $this->user_newEmail));
                $this->user_oldEmail = $this->user_newEmail;
                $this->user_newEmail = '';
            }
        }
        
        // on username-change submit
        if (isset($_POST['submitUsernameChange'])) {
            
            $this->user_firstname = htmlspecialchars($_POST['user_firstname']);
            if ($this->check_username($this->user_firstname)) {
                $this->sanitize_user_firstname();
                wp_update_user( array('ID' => $this->getCurrentUserdata()->ID, 'first_name' => $this->user_firstname));
            }
            
            $this->user_lastname = htmlspecialchars($_POST['user_lastname']);
            if ($this->check_username($this->user_lastname)) {
                $this->sanitize_user_lastname();
                wp_update_user( array('ID' => $this->getCurrentUserdata()->ID, 'last_name' => $this->user_lastname));
            }
        }

        return $this->edit_user_form();
    }

    /**
     * Clears the password fields (and the class attributes)
     */
    private function clear_user_passwords() {
        $this->clear_user_newPassword();
        $this->clear_user_oldPassword();
    }

    private function clear_user_oldPassword() {
        $this->user_newPassword = '';
    }

    private function clear_user_newPassword() {
        $this->user_oldPassword = '';
    }

    /**
     * Returns the current data of the logged in user
     */
    private function getCurrentUserdata() {
        return get_userdata(get_current_user_id());
    }

    /**
     * Makes the user-name check
     */
    private function check_username($username) {
        $error = new WP_Error();

        // Check if the username is valid
        if (!validate_username($username)) {
            $error->add('username_invalid', 'Benutzername ist ungültig.');
        }

        return $this->evaluateErrorData($error);
    }

    /**
     * Checks the Email (currently only the new E-Mail String since that ain't even working out...)
     */
    private function check_email() {
        $error = new WP_Error();

        // Check if the old email is valid
        if (!strcmp($this->user_oldEmail, $this->getCurrentUserdata()->user_mail)) {
            $error->add('oldmail_invalid', 'Die zu ändernde E-Mail Adresse ist nicht korrekt.');
        }

        // Check if the email is valid
        if (!is_email($this->user_newEmail)) {
            $error->add('email_invalid', 'Die neue E-Mail-Adresse ist nicht gültig.');
        }

        // Check if the email is already registered
        if (email_exists($this->user_newEmail)) {
            $error->add('email', 'E-Mail-Adresse ist bereits vergeben.');
        }

        return $this->evaluateErrorData($error);
    }

    /**
     * Checks the new entered password
     */
    private function checkPassword() {
        $error = new WP_Error();
        $userdata = $this->getCurrentUserdata();
        
        if (!wp_check_password($this->user_oldPassword, $userdata->user_pass, $userdata->id)) {
            $error->add('user_oldPassword', 'Das bestehende Passwort ist falsch.');
        }

        // Check if the password character length not less than five
        if (5 > strlen($this->user_newPassword)) {
            $error->add('password_length', 'Passwort sollte mind. aus 5 Zeichen bestehen.');
        }

       /* if (strlen($this->user_oldPassword == 0 && $this->user_newPassword == 0 )) {
            $error = new WP_Error();
        }*/

        $this->clear_user_passwords();

        return $this->evaluateErrorData($error);
    }

    /**
     * Evaluates the error data and shows them.
     */
    private function evaluateErrorData($error) {
        if (is_wp_error($error) && !empty($error->errors)) {
            $this->message = '<strong>FEHLER</strong>: ' . $error->get_error_message();
            return false;
        }
        $this->success = "Änderungen erfolgreich vorgenommen.";
        return true;
    }

    /**
     * Sanitize the input data
     * 
     * @deprecated use the specified Sanitize-Functions instead
     */
    private function sanitize_data() {
        $this->user_firstname =   sanitize_user($this->user_firstname);
        $this->user_lastname = sanitize_user($this->user_lastname);
        $this->user_newEmail    =   sanitize_email($this->user_newEmail);
        $this->user_newPassword =   esc_attr($this->user_newPassword);
    }

    /**
     * Sanitize the user firstname
     */
    private function sanitize_user_firstname() {
        $this->user_firstname = sanitize_user($this->user_firstname);
    }

    /**
     * Sanitize the user lastname
     */
    private function sanitize_user_lastname() {
        $this->user_lastname = sanitize_user($this->user_lastname);
    }

    /**
     * Sanitize the user E-Mail
     */
    private function sanitizeEmail() {
        $this->user_newEmail = sanitize_email($this->user_newEmail);
        $this->user_oldEmail = sanitize_email($this->user_oldEmail);
    }

    /**
     * Sanitize the user password
     */
    private function sanitize_password() {
        $this->user_newPassword = esc_attr($this->user_newPassword);
        $this->user_oldPassword = esc_attr($this->user_oldPassword);
    }

    /**
     * This function returns the html-code for the form of the page
     */
    public function edit_user_form() {
        return '
        <div class="form-error">' . $this->message . '</div>
        <div class="form-success">' . $this->success . '</div>
        <form name="form" action="' . $_SERVER['REQUEST_URI'] . '" method="post" class="my-form-class">
            <h2 class="title">Namen anpassen</h2>
            <p class="row">
                <label for="user_firstname">Vorname<br>
                    <input type="text" name="user_firstname" value="' . $this->user_firstname . '">
                </label>
            </p>  
            <p class="row">
                <label for="user_lastname">Nachname<br>
                    <input type="text" name="user_lastname" value="' . $this->user_lastname . '">
                </label>
            </p>  
            <p>
                <button type="submit" name="submitUsernameChange">Anpassung bestätigen</button>
            </p>
            <h2 class="title">E-Mail-Adresse anpassen</h2>
            <p class="row"> 
                <label for="user_oldEmail">Alte E-Mail-Adresse<br>
                    <input type="text" name="user_oldEmail" value="' . $this->user_oldEmail . '">
                </label>
            </p>
            <p class="row">
                <label for="user_newEmail">Neue E-Mail-Adresse<br>
                    <input type="text" name="user_newEmail" value="' . $this->user_newEmail . '">
                </label>
            </p>
            <p>
                <button type="submit" name="submitMailChange">E-Mail Adresse ändern</button>
            </p>
            <h2 class="title">Passwort anpassen</h2>
            <p class="row">
                <label for="user_oldPassword">Altes Passwort<br>
                    <input type="password" name="user_oldPassword" value="' . $this->user_oldPassword . '">
                </label>
           </p>
           <p class="row">
                <label for="user_newPassword">Neues Passwort<br>
                    <input type="password" name="user_newPassword" value="' . $this->user_newPassword . '">
                </label>
            </p>
            <p>
                <button type="submit" name="submitPasswordChange">Passwort ändern</button>
            </p>
        </form>';
    }
}

?>