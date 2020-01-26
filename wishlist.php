<?php
session_start();
$name = null;
if (isset($_SESSION['fullname'])){
    $name = $_SESSION['fullname'] . "'s Wishlist";
}
$view = new stdClass();
$view->pageTitle = 'Wishlist';
$view->pageHeading = "$name";
require_once('Models/User.php');
require_once('Models/Car.php');


if(!User::check()) {
    header("Location: login.php");
}

$cars = [];
$car = new Car();

if(isset($_COOKIE['wishlist'])) {

    foreach($_COOKIE['wishlist'] as $key => $value) {
        array_push($cars, $car->getCar($value));
    }
}
$view->cars = $cars;

$view->colors = $car->getColors();
$view->makes = $car->getMakes();
$view->engineSizes = $car->getEngineSizes();
$view->locations = $car->getLocations();
$view->types = $car->getTypes();


$url = strtok($_SERVER['HTTP_REFERER'], '?');

if(isset($_GET['add-to-wishlist'])) {
    $addedCar = $car->getCar($_GET['id']);
    $_SESSION['wishlist'][$_GET['id']] = $addedCar;
    header("Location: " . $url . "?noticeIndex=" . $addedCar['make_name'] . " added to Wishlist");

} else if(isset($_GET['remove-from-wishlist'])) {

    $addedCar = $car->getCar($_GET['id']);
    unset($_SESSION['wishlist'][$_GET['id']]);
    header("Location: " . $url . "?noticeIndex=" . $addedCar['make_name'] . " removed from Wishlist");
}

require_once('Views/wishlist.phtml');
