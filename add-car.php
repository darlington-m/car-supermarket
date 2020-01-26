<?php
session_start();
$view = new stdClass();
$view->pageTitle = 'Add Car';
$view->pageHeading = '';
require_once('Models/User.php');
require_once('Models/Car.php');

$car = new Car();
$view->colors = $car->getColors();
$view->makes = $car->getMakes();
$view->engineSizes = $car->getEngineSizes();
$view->locations = $car->getLocations();
$view->types = $car->getTypes();


if (!User::check()){
    header('Location: login.php');
}else if(isset($_POST['make']) && !isset($_POST['submit-finished']) ) {
    $view->models = $car->getModels($_POST['make']);
    $messages = array( "userInput" => []);
    $messages['userInput']['make'] = $_POST['make'];
    $view->messages = $messages;
} else if(isset($_POST['submit-finished'])) {

    $messages = array( "userInput" => $_POST, "errors" => []);

    if (empty($_POST['make'])) {
        $messages['errors']["make"] = "Make Field Empty";
    }
    if (empty($_POST['model'])) {
        $messages['errors']["model"] = "Model Field Empty";
    }
    if (empty($_POST['type'])) {
        $messages['errors']["type"] = "Type Field Empty";
    }
    if (empty($_POST['color'])) {
        $messages['errors']["color"] = "Color Field Empty";
    }
    if (empty($_POST['yearOfReg'])) {
        $messages['errors']["yearOfReg"] = "Year of Registration Field Empty";
    }
    if (empty($_POST['location'])) {
        $messages['errors']["location"] = "Location Field Empty";
    }
    if (empty($_POST['doors'])) {
        $messages['errors']["doors"] = "Doors Field Empty";
    }
    if (empty($_POST['transmission'])) {
        $messages['errors']["transmission"] = "Transmission Field Empty";
    }
    if (empty($_POST['fuelType'])) {
        $messages['errors']["fuelType"] = "Fuel Type Field Empty";
    }
    if (empty($_POST['price'])) {
        $messages['errors']["price"] = "Price Field Empty";
    }
    if (empty($_POST['mileage'])) {
        $messages['errors']["mileage"] = "Mileage Field Empty";
    }
    if (empty($_POST['description'])) {
        $messages['errors']["description"] = "Description Field Empty";
    }
    if (empty($_FILES['photo']['name'])) {
        $messages['errors']["image"] = "Image Field Empty";
    }
    if (empty($_POST['engineSize'])) {
        $messages['errors']["engineSize"] = "Engine Size Field Empty";
    }

    if ($messages['errors'] == null) {
        move_uploaded_file($_FILES['photo']['tmp_name'], 'images/' . $_FILES['photo']['name']);
        $car->create($_POST['make'], $_POST['model'], $_POST['type'], $_POST['color'], $_POST['yearOfReg'], $_POST['location'], $_POST['doors'], $_POST['transmission'], $_POST['fuelType'], $_POST['price'], $_POST['mileage'], $_POST['description'], $_FILES['photo']['name'], $_POST['engineSize']);
    }
    else {
        $view->messages = $messages;
    }
}


require_once('Views/add-car.phtml');