<?php

//redirect to login if no page get query
if(!isset($_GET['page'])){
    header("Location: ../index.php?page=login");
    die();
}


//redirect to shop if session active
if($session_active){
    header("Location: " . $_SERVER['PHP_SELF'] . "?page=rent");
    die();
}

//check if login details are correct
if(isset($_POST['login_btn'])){
    //get from post
    $uname = htmlentities(trim($_POST['uname']));
    $pass = htmlentities(trim($_POST['pass']));

    if(empty($uname) || empty($pass)){
	$error = "All fields are required.";
    }else{
	if(isset($users[$uname]) && password_verify($pass, $users[$uname]['pass'])){
	    $_SESSION['uname'] = $uname;
	    // $_SESSION['email'] = $users[$uname]['email'];
	    // $_SESSION['age'] = $users[$uname]['age'];
	    // $_SESSION['gender'] = $users[$uname]['gender'];
	    // $_SESSION['xp'] = $users[$uname]['xp'];
	    // $_SESSION['description'] = $users[$uname]['description'];

	    //add to log file
	    $date = date('j/m/Y');
	    $time = date('H:i:s');
	    $log .= "\n$time $date: " . $_SESSION['uname'] . " successfully logged in.";
	    file_put_contents("storage/log.txt", $log);

	    //relocate to login
	    header("Location: " . $_SERVER['PHP_SELF'] . "?page=rent");
	    die();
	}

	$error = "Wrong username or password.";
	
    }
}






//successful registration message
if(isset($_GET['successReg'])){
    $success_msg = "Successful registration!";
}





?>







<div class="login_form m-auto">
    <h2 class="text-center"> Login </h2>
    <form method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>?page=login">

      <p class="m-2 text-center text-danger"> <?= isset($error) ? $error : '';?></p>
      <p class="m-2 text-center text-success"> <?= isset($success_msg) ? $success_msg : '';?></p>
      <input type="text" name="uname" placeholder="Username" class="m-2" value="<?=$uname;?>" autofocus><br>
      <input type="password" name="pass" placeholder="Password" class="m-2" value="<?=$pass;?>"><br>
      <div class="text-center">
	<input type="submit" name="login_btn" value="Login" class="m-2">
      </div>
    </form>
</div>
