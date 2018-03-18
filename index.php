<?php

	$file = "databaseClass.php";
	if(file_exists($file)){
        echo "<script type='text/javascript'>window.location = 'login.php'</script>";
	}
	else{
		echo "<script type='text/javascript'>window.location = 'setup.php'</script>";
	}

?>
