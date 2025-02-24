<?php

//redirect to login if no page get query
if(!isset($_GET['page'])){
    header("Location: ../index.php?page=login");
    die();
}


//redirect to shop if session active
if(!$session_active){
    header("Location: ../index.php?page=login");
    die();
}

//success message for adding a card
if(isset($_GET['addcar'])){
    $success_msg = "Successfully added a new car!";
}








//**renting logic**//

if(!empty($_POST)){
    //get key and index from submitted name
    $selected_car = key($_POST);
    $selected_car_array = explode("_", $selected_car);
    /*     var_dump($selected_car_array); */

    $date = date('j/m/Y');
    $time = date('H:i:s');


    
    //if renting
    if($selected_car_array[2] == "rent"){
	//if is rented is not set or is 0, change it to 1, store renter and write cars to disc
	if(!isset($cars[$selected_car_array[0]][$selected_car_array[1]]['isRented']) || $cars[$selected_car_array[0]][$selected_car_array[1]]['isRented'] == 0){
	    $cars[$selected_car_array[0]][$selected_car_array[1]]['isRented'] = 1;
	    $cars[$selected_car_array[0]][$selected_car_array[1]]['renter'] = $_SESSION['uname'];
	    $cars[$selected_car_array[0]][$selected_car_array[1]]['startRentTime'] = time();

	    //add to users array
	    $users[$_SESSION['uname']]['isRenting'] = 1;
	    
	    //success msg and log
	    $success_msg = "Successfully rented car!";
	    $log .= "\n$time $date: " . $_SESSION['uname'] . " successfully rented a " . $selected_car_array[0] . " " . $cars[$selected_car_array[0]][$selected_car_array[1]]['brand'] . " " . $cars[$selected_car_array[0]][$selected_car_array[1]]['model'] . "." ;
	}else{
	    $error = "Sorry, vehicle is currently rented by " . $cars[$selected_car_array[0]][$selected_car_array[1]]['renter'] . ".";
	    $log .= "\n$time $date: " . $_SESSION['uname'] . " tried to hack us by renting the already rented " . $selected_car_array[0] . " " . $cars[$selected_car_array[0]][$selected_car_array[1]]['brand'] . " " . $cars[$selected_car_array[0]][$selected_car_array[1]]['model'] . ".";
	}
    }

    //if releasing
    elseif($selected_car_array[2] == "release"){
	$cars[$selected_car_array[0]][$selected_car_array[1]]['isRented'] = 0;
	unset($cars[$selected_car_array[0]][$selected_car_array[1]]['renter']);
	unset($users[$_SESSION['uname']]['isRenting']);

	//calc burned fuel
	$time_rented = (time() - $cars[$selected_car_array[0]][$selected_car_array[1]]['startRentTime']) / 60;// / 60;
	$burned_fuel = round($time_rented * $cars[$selected_car_array[0]][$selected_car_array[1]]['avgFuelConsumption'], 1);
	if($cars[$selected_car_array[0]][$selected_car_array[1]]['currentFuel'] - $burned_fuel <= 0){
	    $cars[$selected_car_array[0]][$selected_car_array[1]]['currentFuel'] = 0;
	}else{
	    $cars[$selected_car_array[0]][$selected_car_array[1]]['currentFuel'] -= $burned_fuel;
	}
	unset($cars[$selected_car_array[0]][$selected_car_array[1]]['startRentTime']);

	//success message and log
	$success_msg = "Successfully released car!";
	$log .= "\n$time $date: " . $_SESSION['uname'] . " successfully released a " . $selected_car_array[0] . " " . $cars[$selected_car_array[0]][$selected_car_array[1]]['brand'] . " " . $cars[$selected_car_array[0]][$selected_car_array[1]]['model'] . "." ;
    }

    //if deleting a car from Admin profile
    elseif($selected_car_array[2] == "delete"){ 
	$success_msg = "Successfully deleted " . $selected_car_array[0] . " " . $cars[$selected_car_array[0]][$selected_car_array[1]]['brand'] . " " . $cars[$selected_car_array[0]][$selected_car_array[1]]['model'] . ".";
	unset($cars[$selected_car_array[0]][$selected_car_array[1]]);
    }


    
    //if filling up + subtract from gas station
    else{ 
	$amount_filled = $cars[$selected_car_array[0]][$selected_car_array[1]]['maxFuel'] - $cars[$selected_car_array[0]][$selected_car_array[1]]['currentFuel'];

	//if not enough in gas station
	if($gas_station['currentFuel'] - $amount_filled < 0){
	    $cars[$selected_car_array[0]][$selected_car_array[1]]['currentFuel'] += $gas_station['currentFuel'];
	    
	    $error = "Not enough gas in gas station. Please contact administrator. " . $gas_station['currentFuel'] . "L filled up.";
	    $cars[$selected_car_array[0]][$selected_car_array[1]]['startRentTime'] = time();
	    $gas_station['currentFuel'] = 0;
	    
	    $log .= "\n$time $date: " . $_SESSION['uname'] . " filled up with " . $gas_station['currentFuel'] . "L " . $selected_car_array[0] . " " . $cars[$selected_car_array[0]][$selected_car_array[1]]['brand'] . " " . $cars[$selected_car_array[0]][$selected_car_array[1]]['model'] . " and the gas station became empty." ;
	}else{
	    //if enough gas in gas station
	    $cars[$selected_car_array[0]][$selected_car_array[1]]['currentFuel'] = $cars[$selected_car_array[0]][$selected_car_array[1]]['maxFuel'];
	    
	    $gas_station['currentFuel'] -= $amount_filled;
	    $cars[$selected_car_array[0]][$selected_car_array[1]]['startRentTime'] = time();
	    $success_msg = "Successfully filled up car with $amount_filled L!";
	    $log .= "\n$time $date: " . $_SESSION['uname'] . " successfully filled up " . $selected_car_array[0] . " " . $cars[$selected_car_array[0]][$selected_car_array[1]]['brand'] . " " . $cars[$selected_car_array[0]][$selected_car_array[1]]['model'] . "." ;
	}
    }

    //save to disc
    file_put_contents("storage/cars.json", json_encode($cars));
    file_put_contents("storage/log.txt", $log);
    file_put_contents("storage/users.json", json_encode($users));
    file_put_contents("storage/gas_station.json", json_encode($gas_station));
}


?>

<p class="m-2 text-center text-danger"> <?= isset($error) ? $error : '';?></p>
<p class="m-2 text-center text-success"> <?= isset($success_msg) ? $success_msg : '';?></p>

<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 m-0">

<?php
$xp = $users[$_SESSION['uname']]['experience'];
foreach($cars as $key_car_type => $car_type){
    //continue depending on experience
    if($xp<10 && $key_car_type != "taxi"){
	continue;
    }elseif($xp<15 && $key_car_type == "bus"){
	continue;
    }


    
    
    foreach($car_type as $key_car_config => $car_config){
	//continue if I have already rented a car
	if(isset($users[$_SESSION['uname']]['isRenting']) && ($_SESSION['uname'] !== $car_config['renter'])){
	    continue;
	}
	?>
	<div class="col">
	<div class="card m-2">
	<div class="card-body">
	<h5 class="card-title"> <?= ucfirst($key_car_type); ?></h5>
	<h6 class="card-subtitle mb-2 text-body-secondary"><?= ucfirst($car_config['brand']); ?></h6>
	<br>
	<p class="card-text">Reg. number: <?=$car_config['reg'];?></p>
	  <p class="card-text">Model: <?=$car_config['model'];?></p>
<?php if(!isset($users[$_SESSION['uname']]['isRenting'])){ ?>
	  <p class="card-text">Fuel: <?=$car_config['currentFuel'] . "L/" . $car_config['maxFuel'] . "L";?></p>
<?php } else{ ?>
	  <p class="card-text" id="burned_fuel"></p>
<?php } ?>
	<p class="card-text">Fuel Consumption: <?=$car_config['avgFuelConsumption'] . "L/h";?></p>
	<form method="POST" action="<?= $_SERVER['PHP_SELF'] . '?page=rent'; ?>">
	<?php if($cars[$key_car_type][$key_car_config]["isRented"] == 0 || !isset($cars[$key_car_type][$key_car_config]["isRented"])){ //if car not rented?>
	    <input type="submit" name="<?=$key_car_type . "_" . $key_car_config?>_rent_btn" value="Rent Vehicle" class="btn btn-success"/>
	    <?php if($users[$_SESSION['uname']]['isAdmin'] == 1){ //if Admin?> 
		<input type="submit" name="<?=$key_car_type . "_" . $key_car_config?>_delete_btn" value="Delete Vehicle" class="btn btn-danger"/>
		<?php } ?>
	    <?php }elseif($cars[$key_car_type][$key_car_config]["renter"] === $_SESSION['uname']){ //if car rented by logged in user?>
		<input type="submit" name="<?=$key_car_type . "_" . $key_car_config?>_release_btn" value="Release Vehicle" class="btn btn-danger"/>
		<input type="submit" name="<?=$key_car_type . "_" . $key_car_config?>_gasfill_btn" value="Fill Up Gas" class="btn btn-warning mx-3"/>
		<?php }else{ //if car rented by someone else?>
		    <p class="text-danger"> Currently rented by <?=$cars[$key_car_type][$key_car_config]["renter"];?></p>
		    <?php } ?>
	</form>
	</div>
	</div>
	</div>
	<?php }
}
//}
?>


</div>



<script>

  function getBurnedFuel(){
      var request = new XMLHttpRequest();
      request.open("get", "ajax_requests/current_fuel.php");
      request.onreadystatechange = function(e){
	  if(this.readyState==4 && this.status==200){
	      var burned_fuel_div = document.getElementById('burned_fuel');
	      if(burned_fuel_div){
		  burned_fuel_div.innerHTML = this.responseText;
	      }
	  }
      }
      request.send();

  }

  setInterval(getBurnedFuel, 1000);
</script>