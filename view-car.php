<?php
session_start();
$view = new stdClass();
$view->pageHeading = '';
require_once('Models/Car.php');


$car = new Car();
$view->colors = $car->getColors();
$view->makes = $car->getMakes();
$view->engineSizes = $car->getEngineSizes();
$view->locations = $car->getLocations();
$view->types = $car->getTypes();

if(isset($_GET['car-id'])) {

        $view->car = $car->getCar($_GET['car-id']);
        $view->pageTitle = $view->car['make_name'] . ' ' . $view->car['model_name'];
}


require_once('Views/view-car.phtml');
