<?php
session_start();
$view = new stdClass();
$view->pageTitle = 'Homepage';
$view->pageHeading = '';
require_once('Models/Car.php');



$car = new Car();
$view->colors = $car->getColors();
$view->makes = $car->getMakes();
$view->engineSizes = $car->getEngineSizes();
$view->locations = $car->getLocations();
$view->types = $car->getTypes();

$cars = new Car();
$view->cars = $cars->getAllCars();


require_once('Views/index.phtml');

