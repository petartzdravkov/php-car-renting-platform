<?php

if(!isset($_GET['page'])){
    header("Location: ../index.php?page=login");
    die();
}

?>

<!doctype html>
<html lang="en" data-bs-theme="dark">
    <head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ShoferBG</title>
	<!-- bootrstrap css -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    </head>
    <body class="d-flex flex-column vh-100">
	<nav class="navbar navbar-expand-md">
	    <div class="container">
		<a href="#" class="navbar-brand"> ShoferBG </a>

		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
		    <span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navmenu">
		    <ul class="navbar-nav ms-auto">
			<?php if(!isset($_SESSION['uname'])){?>
			    <li class="nav-item">
				<a href="index.php?page=login" class="nav-link <?=$page_query=="login" ? "active" : "";?>"> Login </a>
			    </li>
			    <li class="nav-item">
				<a href="index.php?page=register" class="nav-link <?=$page_query=="register" ? "active" : "";?>"> Register </a>
			    </li>
			<?php }else{ ?>
			    <li class="nav-item">
				<a href="index.php?page=logout" class="nav-link <?=$page_query=="logout" ? "active" : "";?>"> Logout </a>
			    </li>
			    <li class="nav-item">
				<a href="index.php?page=rent" class="nav-link <?=$page_query=="rent" ? "active" : "";?>"> Rent </a>
			    </li>
			    <?php if($users[$_SESSION['uname']]['isAdmin'] == 1){ ?>
				<li class="nav-item">
				    <a href="index.php?page=add" class="nav-link <?=$page_query=="add" ? "active" : "";?>"> Add Vehicles </a>
				</li>	
				<li class="nav-item">
				    <a href="index.php?page=log" class="nav-link <?=$page_query=="log" ? "active" : "";?>"> Log </a>
				</li>
				<li class="nav-item">
				    <a href="index.php?page=gas_station" class="nav-link <?=$page_query=="gas_station" ? "active" : "";?>"> Gas Station </a>
				</li>
			    <?php } ?>
			    <li class="nav-item">
				<a href="index.php?page=profile" class="nav-link <?=$page_query=="profile" ? "active" : "";?>"> Profile </a>
			    </li>
			<?php } ?>
		    </ul>
		</div>
	    </div>
	</nav>
