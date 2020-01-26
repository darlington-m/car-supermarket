<?php
session_start();
$view = new stdClass();
$view->pageTitle = 'Edit Profile';
$view->pageHeading = 'Edit My Profile';
require_once('Models/User.php');

$car = new Car();
$view->colors = $car->getColors();
$view->makes = $car->getMakes();
$view->engineSizes = $car->getEngineSizes();
$view->locations = $car->getLocations();
$view->types = $car->getTypes();

if (!User::check()){
    header('Location: login.php');
} else if(!isset($_POST['submit-update-user'])) {

    $messages = array("userInput" => []);
    $user = new User();
    $foundUser = $user->show($_SESSION['userId']);
    $messages["userInput"]["name"] = $foundUser['fullname'];
    $messages["userInput"]["email"] = $foundUser['email'];
    $messages["userInput"]["phone-number"] = $foundUser['phoneNumber'];
    $messages["userInput"]["address"] = $foundUser['address'];
    $messages["userInput"]["postcode"] = $foundUser['postcode'];
    $messages["userInput"]["password"] = "";
    $messages["userInput"]["password1"] = "";
    $view->messages = $messages;
    if(isset($_GET['action'])) {
        $view->notice = 'updated';
    }
} else if(isset($_POST['submit-update-user'])) {

    $messages = array( "userInput" => $_POST, "errors" => []);

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
    if (!empty($_POST['password'])) {
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

        $user = new User();
        $user->update($_POST['name'], $_POST['email'], $_POST['phone-number'], $_POST['address'], $_POST['postcode'], $_POST['password'], $_SESSION['userId']);
        header('Location: edit-profile.php?action=updated');
    }
    else{

        $view->messages = $messages;
    }
}

require_once('Views/edit-profile.phtml');