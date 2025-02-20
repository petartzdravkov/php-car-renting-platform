<?php

//redirect to shop if session active
if(!$session_active){
    header("Location: ../index.php?page=login");
    die();
}

?>




<div class="container h-75 my-auto">
    
    <div class="p-4 bg-body-tertiary border rounded-3 overflow-y-scroll h-100">
        <h2>Log</h2>
	<p>
	    <?php
	    $log_resource = fopen("storage/log.txt", "r");
	    while(!feof($log_resource)){
		echo fgets($log_resource);
		echo "<br>";
	    }
	    fclose($log_resource);

	    ?>
	</p>

    </div>
    
</div>