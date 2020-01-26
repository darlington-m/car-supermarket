<?php

require_once "Models/Car.php";

if(isset($_GET['add-bid'])) {

    sleep(2);
    $car = new Car();
    $car->placeBid($_GET['carID'], $_GET['userID'], $_GET['ownerID'], $_GET['bid']);

} else {
    header("Location: index.php");
}

?>