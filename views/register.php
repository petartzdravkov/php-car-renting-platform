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


if(isset($_POST['register_btn'])){
    //get from post
    $uname = htmlentities(trim($_POST['uname']));
    $pass = htmlentities(trim($_POST['pass']));
    $email = htmlentities(trim($_POST['email']));
    $age = htmlentities(trim($_POST['age']));
    $gender = htmlentities(trim($_POST['gender']));
    $xp = htmlentities(trim($_POST['xp']));
    $description = htmlentities(trim($_POST['description']));
    $is_admin = htmlentities(trim($_POST['is_admin']));
    //get from files
    $file_tmp = $_FILES['profile_pic']['tmp_name'];
    $file_name = $_FILES['profile_pic']['name'];    
    

    
    if(empty($uname) || empty($pass) || empty($email) || empty($age) || empty($gender) || empty($xp) || empty($description) || empty($file_tmp)){
	$error = "All fields are required.";
    }else{
	if(isset($users[$uname])){
	    $error = "Username exists.";
	}else{
	    //handle picture saving logic to be able to include path in users array afterwards
	    $upload_dir = "uploads/";
	    $new_file_name = $uname . "_" . time() . "_" . $file_name;
	    //add input data to users array and write it to disc if pic upload was ok
	    if(is_uploaded_file($file_tmp) && move_uploaded_file($file_tmp, $upload_dir . $new_file_name)){
		$users[$uname] = ["pass" => password_hash($pass, PASSWORD_BCRYPT), "email" => $email, "age" => $age, "gender" => $gender, "experience" => $xp, "description" => $description, "profile_pic" => $upload_dir . $new_file_name];

		//add isAdmin
		if(!empty($is_admin)){
		    $users[$uname]["isAdmin"] = 1;
		} else{
		    $users[$uname]["isAdmin"] = 0;
		}
		file_put_contents("storage/users.json", json_encode($users));


		//add to log file
		$date = date('j/m/Y');
		$time = date('H:i:s');
		$log .= "\n$time $date: " . $uname . " successfully registered.";
		file_put_contents("storage/log.txt", $log);

		//relocate to login
		header("Location: " . $_SERVER['PHP_SELF'] . "?page=login&successReg=1");
		die();
		
	    }else{
		$error = "Something went wrong with the upload.";
	    }
	    


	}
    }

}


?>





<div class="register_form m-auto">
<h2 class="text-center"> Register </h2>
<form method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>?page=register" enctype="multipart/form-data" class="text-center">

<p class="m-2 text-center text-danger"> <?= isset($error) ? $error : '';?></p>
<input type="text" name="uname" placeholder="Username" class="m-2" value="<?=$uname;?>" autofocus><br>
<input type="password" name="pass" placeholder="Password" class="m-2" value="<?=$pass;?>"><br>
<input type="email" name="email" placeholder="Email" class="m-2" value="<?=$email;?>"><br>
<input type="number" name="age" placeholder="Age" min="0" max="150" class="m-2" value="<?=$age;?>"><br>
<input type="radio" id="gender" name="gender" class="m-2" value="male" <?= isset($gender) && $gender === "male" ? "checked" : "";?>>
<label for="gender"> Male </label>
<input type="radio" id="gender" name="gender" class="m-2" value="female" <?= isset($gender) && $gender === "female" ? "checked" : "";?>>
<label for="gender"> Female </label><br>
<input type="number" name="xp" placeholder="Experience" class="m-2" min="0" max="70" value="<?=$xp;?>"><br>
<textarea name="description" maxlength="100" placeholder="Write a short decription about yourself." class="m-2"> <?=$description;?>
</textarea><br>
<?php if(!adminExists($users)){?>
    <input type="checkbox" name="is_admin" id="is_admin">
    <label for="is_admin"> I am registering as Admin. </label><br>
    <?php }?>
<input type="file" name="profile_pic" accept="image/*" class="m-2" />

<div class="text-center">
<input type="submit" name="register_btn" value="Register" class="m-2">
</div>
</form>
</div>
