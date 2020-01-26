<?php
session_start();
$view = new stdClass();
$view->pageTitle = 'Register';
$view->pageHeading = 'Register';

spl_autoload_register(function($class){
    require_once "Models/{$class}.php";
});


$car = new Car();
$view->colors = $car->getColors();
$view->makes = $car->getMakes();
$view->engineSizes = $car->getEngineSizes();
$view->locations = $car->getLocations();
$view->types = $car->getTypes();

if (isset($_POST['submit-register'])) {

    $reg = new User();
    $user = $reg->getUser($_POST['email']);
    $messages = array( "userInput" => $_POST, "errors" => []);

    if ($user != null) {
        $messages["errors"]["exists"] = "Email already taken";
        $view->messages = $messages;
    }else {
        if (empty($_POST['name'])) {
            $messages["errors"]["name"] = "Name Field Empty";
        }
        if (empty($_POST['email'])) {
            $messages['errors']["email"] = "Email Field Empty";
        }
        if (empty($_POST['phone-number'])) {
            $messages["errors"]["phone"] = "Phone Field Empty";
        }
        if (empty($_POST['address'])) {
            $messages["errors"]["address"] = "Address Field Empty";
        }
        if (empty($_POST['postcode'])) {
            $messages["errors"]["postcode"] = "Address Field Empty";
        }
        if (empty($_POST['password'])) {
            $messages["errors"]["pass1"] = "Password Field Empty";
        }
        if (empty($_POST['password1'])) {
            $messages["errors"]["pass2"] = "Password Field Empty";
        }
        if ($_POST['password'] != $_POST['password1']) {
            $messages["errors"]["passnotmatch"] = "Passwords Do Not Match";
        }
        if (strlen($_POST['password']) < 8 || strlen($_POST['password1']) < 8) {
            $messages["errors"]["passwordshort"] = "Passwords Less Than 8 Characters";
        }
    }
    if ($messages['errors'] == null) {
        $reg->register($_POST['name'], $_POST['email'], $_POST['phone-number'], $_POST['address'], $_POST['postcode'], $_POST['password']);
        header('Location: login.php?action=registered');
    }
    else {
        $view->messages = $messages;
    }
    }

require_once('Views/register.phtml');