<?php
session_start();
$view = new stdClass();
$view->pageTitle = 'Login';
$view->pageHeading = 'Login';
require_once('Models/User.php');


$car = new Car();
$view->colors = $car->getColors();
$view->makes = $car->getMakes();
$view->engineSizes = $car->getEngineSizes();
$view->locations = $car->getLocations();
$view->types = $car->getTypes();

if (User::check()){
    header('Location: index.php');
} else if(isset($_POST['submit-login'])) {

    $password = $_POST['password'];
    $email = $_POST['email'];

    $messages = array("error" => null,
        "email" => $email,
        "password" => $password
    );

    $login = new User();
    $user = $login->getUser($email);
    $passCheck = password_verify($password, $user['password']);

    if(empty($email) && empty($password) )
    {
        $messages["error"] = "Email and Password Fields Empty";
        $view->messages = $messages;
    }
    else if(empty($email))
    {
        $messages["error"] = "Email Field Empty";
        $view->messages = $messages;
    }
    else if(empty($password))
    {
        $messages["error"] = "Password Field Empty";
        $view->messages = $messages;
    }
    else if($user == null)
    {
        $messages["error"] = "User Does Not Exist";
        $view->messages = $messages;
    }
    else
    {
        if($passCheck)
        {
            $login->logIn($user);
        }
        else
        {
            $messages["error"] = "Incorrect Password";
            $view->messages = $messages;
        }
    }
}else if(isset($_GET['action']) && $_GET['action'] == "logout") {

    $view->notice = 'You have been logged out.';
}else if(isset($_GET['action']) && $_GET['action'] == "registered") {

    $view->notice = 'Registration successful. Please login';
}




require_once('Views/login.phtml');