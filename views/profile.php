<?php

//redirect to login if no page get query
if(!isset($_GET['page'])){
    header("Location: ../index.php?page=login");
    die();
}


//redirect to login if session inactive
if(!$session_active){
    header("Location: " . $_SERVER['PHP_SELF'] . "?page=login");
    die();
}


//get uname from session
$uname = $_SESSION['uname'];


//if apply changes pressed
if(isset($_POST['profile_changes'])){
    
    //profile pic
    if(!empty($_FILES['profile_pic'])){
	$file_tmp = $_FILES['profile_pic']['tmp_name'];
	$file_name = $_FILES['profile_pic']['name'];

	if(is_uploaded_file($file_tmp)){
	    $upload_dir = "uploads/";
	    $new_file_name = $_SESSION['uname'] . "_" . time() . "_" . $file_name;

	    if(move_uploaded_file($file_tmp, $upload_dir . $new_file_name)){
		//delete old picture
		unlink($users[$_SESSION['uname']]['profile_pic']);
		
		//set new one
		$users = json_decode(file_get_contents("storage/users.json"), true);
		$users[$_SESSION['uname']]['profile_pic'] = $upload_dir . $new_file_name;
		file_put_contents("storage/users.json", json_encode($users));

		//success message
		$uploaded_file = "Successfully changed account picture";
	    } else{
		$error = "Something went wrong with the upload :/";
	    }
	}
    }


    //get $_POST params
    $pass_change_old = htmlentities(trim($_POST['pass_change_old']));
    $pass_change_new = htmlentities(trim($_POST['pass_change_new']));
    $email_change = htmlentities(trim($_POST['email_change']));
    $age_change = htmlentities(trim($_POST['age_change']));
    $gender_change = htmlentities(trim($_POST['gender_change']));
    $xp_change = htmlentities(trim($_POST['xp_change']));
    $description_change = htmlentities(trim($_POST['description_change']));


    //password
    if(!empty($pass_change_old) || !empty($pass_change_new)){
	if((empty($pass_change_old) && !empty($pass_change_new)) || (!empty($pass_change_old) && empty($pass_change_new))){
	    $pass_error = "Fill in both password fields please.";
	}else{
	    //check if old pass matches in db
	    if(!password_verify($pass_change_old, $users[$uname]['pass'])){
		$pass_error = "Old password doesn't match.";
	    } else{
		$users[$uname]['pass'] = password_hash($pass_change_new, PASSWORD_BCRYPT);
		$pass_success = "Password successfully changed";
	    }
	}
    }

    //email
    if(!empty($email_change)){
	$users[$uname]['email'] = $email_change;
	$email_success = "Email successfully changed";
    }

    //age
    if(!empty($age_change)){
	$users[$uname]['age'] = $age_change;
	$age_success = "Age successfully changed";
    }

    //gender
    if(!empty($gender_change)){
	if($users[$uname]['gender'] !== $gender_change){
	    $users[$uname]['gender'] = $gender_change;
	    $gender_success = "Gender successfully changed";
	}
    }

    //xp
    if(!empty($xp_change)){
	$users[$uname]['experience'] = $xp_change;
	$xp_success = "Experience successfully changed";
    }

    //description
    if(!empty($description_change)){
	$users[$uname]['description'] = $description_change;
	$description_success = "Description successfully changed";
    }

    //save updates to file
    file_put_contents("storage/users.json", json_encode($users));
}


//if delete account pressed
if(isset($_POST['delete_account'])){
    unset($users[$_SESSION['uname']]);
    file_put_contents("storage/users.json", json_encode($users));
    session_destroy();
    header("Location: index.php?page=register&delacc=1");
    die();
}



?>







<div class="container">
<h1 class="text-success"><?= $users[$_SESSION['uname']]['isAdmin'] == 1 ? "Admin Profile" : "";?></h1>
<h2> Hello, <?=$_SESSION['uname'];?> </h2>
<p class="m-2 text-center text-danger"> <?= isset($error) ? $error : '';?></p>
<p class="m-2 text-center text-success"> <?= isset($success_msg) ? $success_msg : '';?></p>
<form method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>?page=profile" enctype="multipart/form-data">

<img style="height: 300px; width: auto;" class="rounded-4" src="<?= $users[$_SESSION['uname']]['profile_pic']; ?>" alt="test">
<br><br>
<!-- <label for="profile_pic" class="py-2"> Upload Profile Pic: </label> -->
<h6> Change Account Picture </h6>
<input type="file" name="profile_pic" accept="image/*" id="profile_pic" class="py-2" />
<p class="text-success"> <?=$uploaded_file;?> </p>
<hr>

<h6> Change Password </h6>
<p class="m-2 text-center text-danger"> <?= isset($pass_error) ? $pass_error : '';?></p>
<p class="m-2 text-center text-success"> <?= isset($pass_success) ? $pass_success : '';?></p>
<label for="pass_change_old"> Old password: </label><br>
<input type="password" name="pass_change_old" id="pass_change_old" class="my-2" /><br>
<label for="pass_change_old"> New password: </label><br>
<input type="password" name="pass_change_new" id="pass_change_new" class="my-2"/>
<hr>

<h6> Change Email </h6>
<input type="email" name="email_change" placeholder="<?=$users[$uname]['email'];?>">
<p class="m-2 text-center text-success"> <?= isset($email_success) ? $email_success : '';?></p>
<hr>

<h6> Change Age </h6>
<input type="number" max="150" min="0" name="age_change" placeholder="<?=$users[$uname]['age'];?>">
<p class="m-2 text-center text-success"> <?= isset($age_success) ? $age_success : '';?></p>
<hr>

<h6> Change Gender </h6>
<input type="radio" name="gender_change" id="male" value="male" <?=$users[$uname]['gender'] === "male" ? "checked" : "";?>>
<label for="male"> Male </label>
<input type="radio" name="gender_change" id="female" value="female" <?=$users[$uname]['gender'] === "female" ? "checked" : "";?>>
<label for="male"> Female </label>
<p class="m-2 text-center text-success"> <?= isset($gender_success) ? $gender_success : '';?></p>
<hr>

<h6> Change Experience </h6>
<input type="number" max="70" min="0" name="xp_change" placeholder="<?=$users[$uname]['experience'];?>">
<p class="m-2 text-center text-success"> <?= isset($xp_success) ? $xp_success : '';?></p>
<hr>

<h6> Change Description </h6>
<textarea name="description_change" maxlength="100" rows="3" cols="60" placeholder="<?=$users[$uname]['description'];?>" col></textarea>
<p class="m-2 text-center text-success"> <?= isset($description_success) ? $description_success : '';?></p>
<hr>

<br><br>
<div>
<input type="submit" name="profile_changes" value="Apply Changes" class="my-2 btn btn-success">
<input type="submit" name="delete_account" value="Delete Account" class="m-2 btn btn-danger">
</div>
</form>
</div>
