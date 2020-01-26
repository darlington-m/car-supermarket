<?php


spl_autoload_register(function($class){
    require_once "Models/{$class}.php";
});

class User
{
    private $db;
    private static $loggedIn = false;


    public function __construct()
    {
        $this->db = DB::getInstance();
    }


    public function getUser($email) {

        $user = $this->db->getRow("SELECT * FROM sta698_users WHERE email =?", array($email));
        return $user;
    }


    public function logIn($user)
    {
        $time = time();
        $userArr = array(
            'userId' => $user['id'],
            'email' => $user['email'],
            'fullname' => $user['fullname']
        );
        $_SESSION['user'] = $userArr;
        $_SESSION['wishlist'] = [];
        $this->db->insertRow("UPDATE sta698_users SET lastLoginTime  = ? WHERE email = ?", array($time, $user['email']));
        self::$loggedIn = true;
        header('Location: index.php');
    }


    public function register($name, $email, $phone, $address, $postcode, $pass1)
    {
        $encrPass = password_hash($pass1, PASSWORD_BCRYPT);
        $this->db->insertRow("INSERT INTO sta698_users (email, password, fullname, address, postcode, phoneNumber) VALUES(?, ?, ?, ?, ?, ?)", array($email, $encrPass, $name, $address, $postcode, $phone));
    }


    public static function logOut() {

        unset($_SESSION['user']);
        self::$loggedIn = false;
        header('Location: login.php?action=logout');
    }


    public static function check() {
        if(isset($_SESSION['user'])) {
            self::$loggedIn = true;
        }
        return self::$loggedIn;
    }


    public function show($id) {
        $user = $this->db->getRow("SELECT * FROM sta698_users WHERE id =?", array($id));
        return $user;
    }


    public function update($name, $email, $phone, $address, $postcode, $pass1, $id) {

        if (!empty($pass1)) {

            $encrPass = password_hash($pass1, PASSWORD_BCRYPT);
            $this->db->updateRow("UPDATE sta698_users SET email = ?, password = ?, fullname = ?, address = ?, postcode = ?, phoneNumber = ? WHERE id = ?", array($email, $encrPass, $name, $address, $postcode, $phone, $id));
        }
        else if (empty($pass1)) {

            $this->db->updateRow("UPDATE sta698_users SET email = ?, fullname = ?, address = ?, postcode = ?, phoneNumber = ? WHERE id = ?", array($email, $name, $address, $postcode, $phone, $id));
        }

    }


}
?>