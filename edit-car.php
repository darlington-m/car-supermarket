<?php
session_start();
$view = new stdClass();
$view->pageTitle = 'Edit Car';
$view->pageHeading = '';
require_once('Models/Car.php');

$car = new Car();


if(isset($_GET['edit-car']) && isset($_GET['car-id']) && !isset($_POST['submit-edit-car'])) {

    $view->colors = $car->getColors();
    $view->models =$car->getModels($_GET['make']);
    $view->makes = $car->getMakes();
    $view->engineSizes = $car->getEngineSizes();
    $view->locations = $car->getLocations();
    $view->types = $car->getTypes();
    $view->messages['userInput'] = $car->getCar($_GET['car-id']);
    if(isset($_GET['with'])) {
        $view->messages['errors']["emptyFields"] = "Ensure that no fields are empty";
    }

} else if (isset($_GET['delete-car']) && isset($_GET['car-id'])) {

    $car->destroy($_GET['car-id']);
    header("Location: my=adverts.php");
} else if(isset($_POST['submit-edit-car'])) {

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
        if (isset($_FILES['photo']['name'])) {

            move_uploaded_file($_FILES['photo']['tmp_name'], 'images/' . $_FILES['photo']['name']);
            $car->edit($_POST['make'], $_POST['model'], $_POST['type'], $_POST['color'], $_POST['yearOfReg'], $_POST['location'], $_POST['doors'], $_POST['transmission'], $_POST['fuelType'], $_POST['price'], $_POST['mileage'], $_POST['description'], $_FILES['photo']['name'], $_POST['engineSize']);
        }else{

            $car->editNoImage($_POST['make'], $_POST['model'], $_POST['type'], $_POST['color'], $_POST['yearOfReg'], $_POST['location'], $_POST['doors'], $_POST['transmission'], $_POST['fuelType'], $_POST['price'], $_POST['mileage'], $_POST['description'], $_POST['engineSize']);
        }
    }
    else {
        $view->messages = $messages;
        header("Location: edit-car.php?edit-car=action&with=errors&make=" . $_POST['make'] .  "&car-id=". $_POST['id']);
    }
}




    require_once('Views/edit-car.phtml');

