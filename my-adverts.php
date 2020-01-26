<?php
session_start();
$view = new stdClass();
$view->pageTitle = 'My Adverts';
$view->pageHeading = $_SESSION['fullname'] . "'s Live Advertisements";
require_once('Models/Car.php');
require_once('Models/User.php');



$car = new Car();
$view->colors = $car->getColors();
$view->makes = $car->getMakes();
$view->engineSizes = $car->getEngineSizes();
$view->locations = $car->getLocations();
$view->types = $car->getTypes();

$cars = new Car();
$view->cars = $cars->getAllAds($_SESSION['userId']);


if (!User::check()){
    header('Location: login.php');
} else if(isset($_GET['add-to-wishlist'])) {
    $make = $_GET['make'];
    $cookie_value = $_GET['id'];
    setcookie("wishlist[$cookie_value]", $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
    header("Location: my-adverts.php?car-added=$make");
} else if(isset($_GET['remove-from-wishlist'])) {
    $make = $_GET['make'];
    $cookie_value = $_GET['id'];
    setcookie("wishlist[$cookie_value]", $cookie_value, time()-3600, "/"); // 86400 = 1 day
    header("Location: my-adverts.php?car-removed=$make");
} else if(isset($_GET['car-added'])) {
    $view->noticeIndex = $_GET['car-added'] . " added to your wishlist";
}else if(isset($_GET['car-removed'])) {
    $view->noticeIndex = $_GET['car-removed'] . " removed from your wishlist";
}


require_once('Views/my-adverts.phtml');