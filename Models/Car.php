<?php


spl_autoload_register(function($class){
    require_once "Models/{$class}.php";
});

class Car
{
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }


    public function getCar($id) {

        $car = $this->db->getRow("SELECT * FROM sta698_cars c, sta698_model md, sta698_color cl, sta698_enginesize e, sta698_make mk, sta698_type t, sta698_location l
                                  WHERE c.id = ?
                                  AND c.model = md.model_id
                                  AND c.color = cl.color_id
                                  AND c.engineSize = e.engineSize_id
                                  AND c.make = mk.make_id
                                  AND c.carType = t.type_id
                                  AND c.location = l.location_id",
                                    array($id));
        return $car;
    }


    public function getAllAds($id) {

        $cars = $this->db->getRows("SELECT * FROM sta698_cars c, sta698_model md, sta698_color cl, sta698_enginesize e, sta698_make mk, sta698_type t, sta698_location l
                                  WHERE c.ownerId = ?
                                  AND c.model = md.model_id
                                  AND c.color = cl.color_id
                                  AND c.engineSize = e.engineSize_id
                                  AND c.make = mk.make_id
                                  AND c.carType = t.type_id
                                  AND c.location = l.location_id",
            array($id));
        return $cars;
    }

    public function getAllCars() {

        $cars = $this->db->getRows("SELECT * FROM sta698_cars c, sta698_model md, sta698_color cl, sta698_enginesize e, sta698_make mk, sta698_type t, sta698_location l
                                  WHERE c.model = md.model_id
                                  AND c.color = cl.color_id
                                  AND c.engineSize = e.engineSize_id
                                  AND c.make = mk.make_id
                                  AND c.carType = t.type_id
                                  AND c.location = l.location_id
                                  ");
        return $cars;
    }


    public function getAllHeaderSearchCars($text) {

        $cars = $this->db->getRows("SELECT * FROM sta698_cars c, sta698_model md, sta698_color cl, sta698_enginesize e, sta698_make mk, sta698_type t, sta698_location l
                                  WHERE c.model = md.model_id
                                  AND c.color = cl.color_id
                                  AND c.engineSize = e.engineSize_id
                                  AND c.make = mk.make_id
                                  AND c.carType = t.type_id
                                  AND c.location = l.location_id
                                  OR md.model_name LIKE '%{$text}%'
                                  OR c.description LIKE '%{$text}%'
                                  ");
        return $cars;
    }


    public function getSideSearchResults($location, $make, $model, $min, $max) {

        $values = [];
        $sql = "SELECT * FROM sta698_cars c, sta698_model md, sta698_color cl, sta698_enginesize e, sta698_make mk, sta698_type t, sta698_location l
                                  WHERE c.model = md.model_id
                                  AND c.color = cl.color_id
                                  AND c.engineSize = e.engineSize_id
                                  AND c.make = mk.make_id
                                  AND c.carType = t.type_id
                                  AND c.location = l.location_id";

        if (!empty($location)) {
            $sql .= " AND l.location_id = ?";
            array_push($values, $location);
        }
        if (!empty($make)) {
            $sql .= " AND mk.make_id = ?";
            array_push($values, $make);
        }
        if (!empty($model)) {
            $sql .= " AND md.model_id = ?";
            array_push($values, $model);
        }
        if (!empty($min)) {
            $sql .= " AND c.price < ?";
            array_push($values, $min);
        }
        if (!empty($max)) {
            $sql .= " AND c.price < ?";
            array_push($values, $max);
        }

        $cars = $this->db->getRows($sql, $values);

        if(empty($location) && empty($make) && empty($model) && empty($min) && empty($max)) {
            return [];
        } else {
            return $cars;
        }
    }


    public function getModels($id) {

        $data = $this->db->getRows("SELECT * FROM sta698_model WHERE car_make_id = ? ORDER BY model_name ASC", array($id));
        return $data;
    }


    public function getColors() {

        $data = $this->db->getRows("SELECT * FROM sta698_color ORDER BY color_name ASC");
        return $data;
    }


    public function getEngineSizes() {

        $data = $this->db->getRows("SELECT * FROM sta698_enginesize ORDER BY engine_size ASC");
        return $data;
    }


    public function getMakes() {

        $data = $this->db->getRows("SELECT * FROM sta698_make ORDER BY make_name ASC");
        return $data;
    }


    public function getTypes() {

        $data = $this->db->getRows("SELECT * FROM sta698_type ORDER BY type_name ASC");
        return $data;
    }


    public function getLocations() {

        $data = $this->db->getRows("SELECT * FROM sta698_location ORDER BY location_name ASC");
        return $data;
    }


    public function create($make, $model, $type, $color, $yearOfReg, $location, $doors, $transmission, $fuelType, $price, $mileage, $description, $imageName, $engineSize) {

        $userId = $_SESSION['userId'];

        $this->db->insertRow("INSERT INTO sta698_cars (make, model, carType, color, yearOfReg, image, location, doors, transmission, fuelType, price, mileage, description, ownerId, engineSize)
                                  VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                                    array($make, $model, $type, $color, $yearOfReg, $imageName, $location, $doors, $transmission, $fuelType, $price, $mileage, $description, $userId, $engineSize));
    }


    public function edit($make, $model, $type, $color, $yearOfReg, $location, $doors, $transmission, $fuelType, $price, $mileage, $description, $imageName, $engineSize, $id) {

        $this->db->updateRow("UPDATE sta698_cars SET make = ?, model = ?, carType = ?, color = ?, yearOfReg = ?, image = ?, location = ?, doors = ?, transmission = ?, fuelType = ?, price = ?, mileage = ?, description = ?, engineSize = ? WHERE id = ?",
            array($make, $model, $type, $color, $yearOfReg, $imageName, $location, $doors, $transmission, $fuelType, $price, $mileage, $description, $engineSize, $id));
    }


    public function editNoImage($make, $model, $type, $color, $yearOfReg, $location, $doors, $transmission, $fuelType, $price, $mileage, $description, $engineSize, $id) {

        $this->db->updateRow("UPDATE sta698_cars SET make = ?, model = ?, carType = ?, color = ?, yearOfReg = ?, location = ?, doors = ?, transmission = ?, fuelType = ?, price = ?, mileage = ?, description = ?, engineSize = ? WHERE id = ?",
            array($make, $model, $type, $color, $yearOfReg, $location, $doors, $transmission, $fuelType, $price, $mileage, $description, $engineSize, $id));
    }


    public function destroy($id) {

        $this->db->deleteRow("DELETE FROM sta698_cars WHERE id = ?", array($id));
    }

    public function placeBid($carID, $userID, $ownerID, $bid){
        $time = time();
        $this->db->insertRow("INSERT INTO sta698_bids (bid_car_id, bid_owner_id, bid_user_id, bid_amount, bid_time)
                              VALUES(?, ?, ?, ?, ?)",
                                array($carID, $ownerID, $userID, $bid, $time));
    }
}