<?php
session_start();

$users = json_decode(file_get_contents("../storage/users.json"), true);
$cars = json_decode(file_get_contents("../storage/cars.json"), true);

if(isset($users[$_SESSION['uname']]['isRenting'])){
    foreach($cars as $key_car_type => $car_type){
	foreach($car_type as $key_car_config => $car_config){
	    if($car_config['isRented']){
		//calc burned fuel
		$time_rented = (time() - $car_config['startRentTime']) / 60;// / 60;
		$burned_fuel = round($time_rented * $car_config['avgFuelConsumption'], 1);
		if($burned_fuel > $car_config['maxFuel']){
		    $burned_fuel = $car_config['maxFuel'];
		}
		echo $car_config['currentFuel']-$burned_fuel . "L/" . $car_config['maxFuel'] . "L";

		break 2;
	    }
	}
    }
}