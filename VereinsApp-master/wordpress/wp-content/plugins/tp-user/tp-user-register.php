<?php
/**
 * Created by PhpStorm.
 * User: mircobaseniak
 * Date: 30.03.18
 * Time: 12:25
 */

/**
 * Class tp_register
 * custom user register form
 */
class tp_register {

    /**
     * @var string|null Should contain a username
     */
    private $username;

    /**
     * @var string|null Should contain a email-address
     */
    private $email;

    /**
     * @var string|null Should contain a password
     */
    private $password;

    /**
     * @var String|null Input error message or acknowledgment message
     */
    private $message;

    /**
     * @var String|null Success message
     */
    private $success;

    /**
     * tp_register constructor.
     */
    public function __construct() {
        // shortcut to integrate register form in the frontend
        add_shortcode('register-form', array($this, 'init_process'));
    }

    /**
     * Initialization of the register handling for the register form
     * @return string Custom register form
     */
    public function init_process() {
        if (isset($_POST['submit'])) {
            $this->username = htmlspecialchars($_POST['username']);
            $this->email    = htmlspecialchars($_POST['email']);
            $this->password = htmlspecialchars($_POST['password']);
            if ($this->check_data()) {
                $this->sanitize_data();
                $this->register_user();
            }
        }
        return $this->register_form();
    }

    /**
     * Checks the input data and creates error message
     * @return bool True if data is correct otherwise false
     */
    private function check_data() {
        $correct = true;
        $error = new WP_Error();

        // Check if username, password and email is set
        if (empty($this->username) || empty($this->password) || empty($this->email)) {
            $error->add('field', 'Bitte alle Felder ausfüllen.');
        }

        // Check if the username character length not less than four
        if (4 > strlen($this->username)) {
            $error->add('username_length', 'Benutzername sollte mind. aus 4 Zeichen bestehen.');
        }

        // Check if the username is already registered
        if (username_exists($this->username)) {
            $error->add('user_name', 'Benutzername ist beriets vergeben.');
        }

        // Check if the username is valid
        if (!validate_username($this->username)) {
            $error->add('username_invalid', 'Benutzername ist ungültig.');
        }

        // Check if the email is valid
        if (!is_email($this->email)) {
            $error->add('email_invalid', 'E-Mail-Adresse ist ungültig.');
        }

        // Check if the email is already registered
        if (email_exists($this->email)) {
            $error->add('email', 'E-Mail-Adresse ist beriets vergeben.');
        }

        // Check if the password character length not less than five
        if (5 > strlen($this->password)) {
            $error->add('password', 'Passwort sollte mind. aus 5 Zeichen bestehen.');
        }

        if (is_wp_error($error) && !empty($error->errors)) {
            $this->message = '<strong>FEHLER</strong>: ' . $error->get_error_message();
            $correct = false;
        }
        return $correct;
    }

    /**
     * Sanitize the input data
     */
    private function sanitize_data() {
        $this->username =   sanitize_user($this->username);
        $this->email    =   sanitize_email($this->email);
        $this->password =   esc_attr($this->password);
    }

    /**
     * Registration of the user
     */
    private function register_user() {
        $user_data = array(
            'user_login'    =>  $this->username,
            'user_email'    =>  $this->email,
            'user_pass'     =>  $this->password
        );
        wp_insert_user($user_data);
        $this->success = 'Die Registrierung war erfolgreich. <a href="' . get_site_url() . '/login/">Login</a>';
    }

    /**
     * Generates the html for the register form
     * @return string Custom register form
     */
    public function register_form() {
        return '
        <div class="form-error">' . $this->message . '</div>
        <div class="form-success">' . $this->success . '</div>
        <form name="form" action="' . $_SERVER['REQUEST_URI'] . '" method="post">
             <p>
                <label for="username">Benutzername<br>
                    <input type="text" name="username" value="' . $this->username . '">
                </label>
            </p>
             <p>
                <label for="email">E-Mail-Adresse<br>
                    <input type="text" name="email" value="' . $this->email . '">
                </label>
            </p>
            <p>
                <label for="password">Passwort<br>
                    <input type="password" name="password">
                </label>
            </p>
            <p>
                <button type="submit" name="submit">Registrieren</button>
            </p>
        </form>';
    }
}
