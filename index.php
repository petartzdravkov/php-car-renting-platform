<?php

session_start();
createStorageFiles();

/*****variables*****/
$users = json_decode(file_get_contents("storage/users.json"), true);
$cars = json_decode(file_get_contents("storage/cars.json"), true);
$log = file_get_contents("storage/log.txt");
$gas_station = json_decode(file_get_contents("storage/gas_station.json"), true);
$session_active = isset($_SESSION['uname']) ? true : false;



$page_query = htmlentities($_GET['page']);
require('views/header.php');

if($page_query === "login"){
    require('views/login.php');
}elseif($page_query === "register"){
    require('views/register.php');
}elseif($page_query === "logout"){

    //add to log file
    $date = date('j/m/Y');
    $time = date('H:i:s');
    $log .= "\n$time $date: " . $_SESSION['uname'] . " successfully logged out.";
    file_put_contents("storage/log.txt", $log);

    
    session_destroy();
    header("Location: index.php?page=login");
    die();
}elseif($page_query === "rent"){
    require('views/rent.php');
}elseif($page_query === "profile"){
    require('views/profile.php');
}elseif($page_query === "log"){
    require('views/log.php');
}elseif($page_query === "gas_station"){
    require('views/gas_station.php');
}elseif($page_query === "add"){
    require('views/add.php');
}elseif(empty($page_query)){
    header("Location: index.php?page=login");
    die();
}else{
    echo "<h2 class='text-center'> Page not found </h2>";
}






require('views/footer.php');










/**********FUNCTIONS*************/

function createStorageFiles(){
    //init users.json
    if(!file_exists("storage/users.json")){
	file_put_contents("storage/users.json", "");
    }

    
    //init cars.json
    if(!file_exists("storage/cars.json")){
	$cars = [
    "taxi" => [
        [
            "reg" => "CB1234AA",
            "brand" => "dacia",
            "model" => "duster",
            "avgFuelConsumption" => 8,    // liters per hour
            "maxFuel" => 46,              // liters
            "currentFuel" => 46
        ],
        [
            "reg" => "CB5678AA",
            "brand" => "mercedes",
            "model" => "G",
            "avgFuelConsumption" => 9,
            "maxFuel" => 42,
            "currentFuel" => 42
        ],
        [
            "reg" => "CB9101AA",
            "brand" => "vw",
            "model" => "golf",
            "avgFuelConsumption" => 7,
            "maxFuel" => 44,
            "currentFuel" => 44
        ],
        [
            "reg" => "CB1112AA",
            "brand" => "dacia",
            "model" => "logan",
            "avgFuelConsumption" => 8,
            "maxFuel" => 50,
            "currentFuel" => 50
        ],
        [
            "reg" => "CB1234AA",
            "brand" => "kia",
            "model" => "ceed",
            "avgFuelConsumption" => 8,
            "maxFuel" => 41,
            "currentFuel" => 41
        ]
    ],
    "truck" => [
        [
            "reg" => "CB0000AA",
            "brand" => "freightliner",
            "model" => "carolina",
            "avgFuelConsumption" => 15,
            "maxFuel" => 405,
            "currentFuel" => 405
        ],
        [
            "reg" => "CB1111AA",
            "brand" => "volvo",
            "model" => "vnl",
            "avgFuelConsumption" => 16,
            "maxFuel" => 400,
            "currentFuel" => 400
        ],
        [
            "reg" => "CB2222AA",
            "brand" => "kenworth",
            "model" => "t680",
            "avgFuelConsumption" => 14,
            "maxFuel" => 390,
            "currentFuel" => 390
        ],
        [
            "reg" => "CB3333AA",
            "brand" => "peterbilt",
            "model" => "579",
            "avgFuelConsumption" => 15,
            "maxFuel" => 418,
            "currentFuel" => 418
        ],
        [
            "reg" => "CB4444AA",
            "brand" => "navistar",
            "model" => "lt",
            "avgFuelConsumption" => 15,
            "maxFuel" => 415,
            "currentFuel" => 415
        ]
    ],
    "bus" => [
        [
            "reg" => "CB5555AA",
            "brand" => "mercedes",
            "model" => "citaro",
            "avgFuelConsumption" => 21,
            "maxFuel" => 205,
            "currentFuel" => 205
        ],
        [
            "reg" => "CB6666AA",
            "brand" => "volvo",
            "model" => "9700",
            "avgFuelConsumption" => 20,
            "maxFuel" => 202,
            "currentFuel" => 202
        ],
        [
            "reg" => "CB7777AA",
            "brand" => "setra",
            "model" => "s416",
            "avgFuelConsumption" => 19,
            "maxFuel" => 210,
            "currentFuel" => 210
        ],
        [
            "reg" => "CB8888AA",
            "brand" => "man",
            "model" => "lion's city",
            "avgFuelConsumption" => 20,
            "maxFuel" => 200,
            "currentFuel" => 200
        ],
        [
            "reg" => "CB9999AA",
            "brand" => "flyer",
            "model" => "xcelsior",
            "avgFuelConsumption" => 18,
            "maxFuel" => 195,
            "currentFuel" => 195
        ]
    ]
];
	    
	
	file_put_contents("storage/cars.json", json_encode($cars));
    }


    //init log
    if(!file_exists("storage/log.txt")){
	$init_message = "Log file created on " . date('j/m/Y') . " at " . date('H:i:s') . ".";
	file_put_contents("storage/log.txt", $init_message);
    }

    //init gas station
    if(!file_exists("storage/gas_station.json")){
	$gas_station = ["maxFuel" => 1000, "currentFuel" => 1000];
	file_put_contents("storage/gas_station.json", json_encode($gas_station));
    }
}


function adminExists(&$users){
    foreach($users as $user){
	if($user['isAdmin'] == 1){
	    return true;
	}
    }
    
    return false;
}