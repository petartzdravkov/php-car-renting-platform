<?php

if(!isset($_GET['page'])){
    header("Location: ../index.php?page=login");
    die();
}




if(isset($_POST['fill_gas_station'])){
    $gas_station['currentFuel'] = $gas_station['maxFuel'];
    file_put_contents("storage/gas_station.json", json_encode($gas_station));
}




?>




<div class="m-auto w-25 text-center">

<div class="p-4 bg-body-tertiary border rounded-3">
<h2>Gas Station</h2>
<h4><?php echo $gas_station['currentFuel'] . "/" . $gas_station['maxFuel'] . "L"; ?></h4>

<form method="POST" action="index.php?page=gas_station">
<button class="btn btn-outline-success mt-3" type="input" name="fill_gas_station">Fill Gas Station</button>
</form>
</div>

</div>