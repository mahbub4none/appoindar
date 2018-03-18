<?php 
	include "class_autoload.php";
    $setup = new setupClass();
    $setup->dbSetup();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Appoindar | Setup</title>
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
                    Database Setting
                </div>
                <div class="panel-body">
                    <form method="post" enctype="multipart/form-data">
                        HostName: <input class="form-control" type="text" name="hname"><br/>
                        UserName: <input class="form-control" type="text" name="uname"><br/>
                        Password: <input class="form-control" type="password" name="pass"><br/>
                        DatabaseName: <input class="form-control" type="text" name="dbname"><br/>
                        <input class="btn btn-default" type="submit" name="" value="Submit">
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>