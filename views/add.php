<?php

if(!isset($_GET['page'])){
    header("Location: ../index.php?page=login");
    die();
}

if(isset($_POST['add_vehicle'])){

    //get post params
    $car_type = htmlentities(trim($_POST['car_type']));
    $reg = htmlentities(trim($_POST['reg']));
    $brand = htmlentities(trim($_POST['brand']));
    $model = htmlentities(trim($_POST['model']));
    $avgFuel = htmlentities(trim($_POST['avgFuel']));
    $maxFuel = htmlentities(trim($_POST['maxFuel']));

    if(empty($car_type) || empty($reg) || empty($brand) || empty($model) || empty($avgFuel) || empty($maxFuel)){
	$error = "All fields are required.";
    }else{
	$cars[$car_type][] = ["reg" => $reg,
			      "brand" => $brand,
			      "model" => $model,
			      "avgFuelConsumption" => $avgFuel,
			      "maxFuel" => $maxFuel,
			      "currentFuel" => $maxFuel];

	file_put_contents("storage/cars.json", json_encode($cars));
	header("Location: index.php?page=rent&addcar=1");
	die();
    }
}



?>




<div class="m-auto w-25">
    
    <div class="p-4 bg-body-tertiary border rounded-3">
	
	<h2>Add Vehicles</h2>

	<p class="m-2 text-center text-danger"> <?= isset($error) ? $error : '';?></p>
	<form method="POST" action="index.php?page=add">
	    <!-- TODO all fields required backend -->
	    <!-- TODO create vehicle entry -->

	    <label for="car_type"> Choose a vehicle type: </label><br>
	    <select name="car_type" id="car_type">
		<option value="taxi" <?=$car_type=="taxi" ? "selected" : "";?>> Taxi </option>
		<option value="truck" <?=$car_type=="truck" ? "selected" : "";?>> Truck </option>
		<option value="bus" <?=$car_type=="bus" ? "selected" : "";?>> Bus </option>
	    </select><br><br>
	    
	    <label for="reg">Registration Number:</label><br>
	    <input type="text" id="reg" name="reg" placeholder="CB0000AA" value="<?=$reg;?>"><br><br>

	    <label for="brand">Brand:</label><br>
	    <input type="text" id="brand" name="brand" placeholder="mercedes" value="<?=$brand;?>"><br><br>

	    <label for="model">Model:</label><br>
	    <input type="text" id="model" name="model" placeholder="GLS" value="<?=$model;?>"><br><br>

	    <label for="avgFuel">Average Fuel Consumption (L):</label><br>
	    <input type="number" id="avgFuel" name="avgFuel" placeholder="15" step="0.1"  value="<?=$avgFuel;?>"><br><br>

	    <label for="maxFuel">Max Fuel Capacity (L):</label><br>
	    <input type="number" id="maxFuel" name="maxFuel" placeholder="405" value="<?=$maxFuel;?>"><br><br>

	    
	    <button class="btn btn-outline-success mt-3" type="input" name="add_vehicle">Add Vehicle</button>
	</form>
    </div>
    
</div>