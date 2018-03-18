<?php 
	include "class_autoload.php";
    $setup = new setupClass();
    $setup->login();
?>

<!DOCTYPE html>
<html>
<head>

	<title>Login</title>
    <?php include "head.php"?>
</head>
<body>
    <div class="container">
        <div class="row col-md-12 text-center">
            <h1>APPOINDAR</h1>
        </div>
        <div class="col-md-12 row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Login
                </div>
                <div class="panel-body">
                    <div class="row text-danger text-center">
                        <?php if(isset($_GET['error']) && $_GET['error'] == 1)echo "Username / Password is wrong!!"?>
                        <?php if(isset($_GET['error']) && $_GET['error'] == 2){
                            echo "Please login to enter the edit mode!";
                        }?>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <div class="row form-group">
                            <div class="col-md-6"><span class="col-md-4">User Name:</span> <input class="form-control col-md-8" type="text" name="uname"></div>
                            <div class="col-md-6"><span class="col-md-4">Password:</span> <input class="form-control col-md-8" type="password" name="pass"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"><input class="btn btn-default" type="submit" name="" value="Submit"></div>
                            <div class="col-md-6">Don't have an account? <a href="registration.php">Register</a></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>