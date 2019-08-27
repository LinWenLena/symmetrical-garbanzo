<?php
/**
 * Created by PhpStorm.
 * User: mircobaseniak
 * Date: 30.03.18
 * Time: 12:24
 */

/**
 * Class tp_login
 * custom user login form
 */
class tp_login {

    /**
     * @var string|null Should contain a username
     */
    private $username;

    /**
     * @var string|null Should contain a password
     */
    private $password;

    /**
     * @var boolean|null To remember user
     */
    private $remember;

    /**
     * @var String|null Input error message
     */
    private $error;

    /**
     * tp_login constructor.
     */
    public function __construct() {
        // shortcut to integrate login form in the frontend
        add_shortcode('login-form', array($this, 'init_process'));
    }

    /**
     * Initialization of the login handling for the login form
     * @return string Custom login form
     */
    public function init_process() {
        if (isset($_POST['submit'])) {
            $this->username = htmlspecialchars($_POST['username']);
            $this->password = htmlspecialchars($_POST['password']);
            $this->remember = true;
            if ($this->check_data()) {
                $this->login_user();
            }
        }
        return $this->login_form();
    }

    /**
     * Checks the input data and creates error message
     * @return bool True data is correct otherwise false
     */
    private function check_data() {
        $correct = true;
        $user_data = array(
            'user_login'    =>  $this->username,
            'user_password' =>  $this->password,
            'remember'      =>  $this->remember
        );
        $error = wp_signon($user_data, false);
        if (is_wp_error($error)) {
            $this->error = $error->get_error_message();
            $correct = false;
        }
        return $correct;
    }

    /**
     * Login of the user
     * Detour to dashboard if user role is administrator otherwise to front-page
     */
    private function login_user() {
        $user = get_user_by('login', $this->username);
        if ($user->roles[0] === 'administrator') {
            wp_redirect(home_url('wp-admin'));
        } else {
            wp_redirect(get_home_url());
        }
    }

    /**
     * Generates the html for the login form
     * @return string Custom login form
     */
    public function login_form() {
        return '
        <div class="form-error">' . $this->error . '</div>
        <form name="form" action="' . $_SERVER['REQUEST_URI'] . '" method="post">
            <p>
                <label for="username">Benutzername oder E-Mail-Adresse<br>
                    <input type="text" name="username" value="' . $this->username . '">
                </label>
            </p>
            <p>
                <label for="password">Passwort<br>
                    <input type="password" name="password">
                </label>
            </p>
            <p>
                <button type="submit" name="submit">Anmelden</button>
            </p>
        </form>';
    }
}