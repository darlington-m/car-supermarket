<?php
session_start();
$view = new stdClass();
$view->pageTitle = 'Search';
$view->pageHeading = '';
require_once('Models/Car.php');


$car = new Car();
$view->colors = $car->getColors();
$view->makes = $car->getMakes();
$view->engineSizes = $car->getEngineSizes();
$view->locations = $car->getLocations();
$view->types = $car->getTypes();

if(isset($_POST['make']) && !isset($_POST['sidebar-search-submit'])) {
    if (isset($_POST['make'])) {

        $view->cars = $car->getAllCars();

        $view->models = $car->getModels($_POST['make']);
        $searchMessages = array( "userInput" => []);
        $searchMessages['userInput']['location'] = $_POST['location'];
        $searchMessages['userInput']['make'] = $_POST['make'];
        $view->searchMessages = $searchMessages;
    }
}if(isset($_POST['make-refine']) && !isset($_POST['submit-refine-search'])) {
    if (isset($_POST['make-refine'])) {

        $view->cars = $car->getAllCars();

        $view->models = $car->getModels($_POST['make-refine']);
        $searchMessages = array( "userInput" => []);
        $searchMessages['userInput']['location'] = $_POST['location'];
        $searchMessages['userInput']['make-refine'] = $_POST['make-refine'];
        $view->searchMessages = $searchMessages;
    }
}else if(isset($_GET['header-search-text'])) {

    $view->cars = $car->getAllHeaderSearchCars($_GET['header-search-text']);
    $view->noticeIndex = " Results for your search ";
    $view->showRefine = true;
}else if(isset($_POST['sidebar-search-submit'])) {

    $view->cars = $car->getSideSearchResults($_POST['location'], $_POST['make'], $_POST['model'], $_POST['min-price'], $_POST['max-price']);
    $view->noticeIndex = " Your search returned " . count($view->cars) . " cars.";
    $view->showRefine = true;

} else {
    $view->cars = $car->getAllCars();
    $view->showRefine = true;
}


require_once('Views/search.phtml');

