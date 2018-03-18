<?php 
	include "setupClass.php";
    $setup = new setup();
    $setup->registration();
?>

<!DOCTYPE html>
<html>
<head>

	<title>Appoindar | Registration</title>
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
                    General Setting
                </div>
                <div class="panel-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="row form-group">
                            <div class="col-md-6"><span class="col-md-4">App Name:</span> <input class="form-control col-md-8" type="text" name="app_name" required="required"></div>
                            <div class="col-md-6"><span class="col-md-4">Short Name:</span> <input class="form-control col-md-8" type="text" name="short_name" required="required"></div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6"><span class="col-md-4">Start Date:</span> <input class="start form-control col-md-8" type="text" name="startdate" required="required"></div>
                            <div class="col-md-6"><span class="col-md-4">End Date:</span> <input class="end form-control col-md-8" type="text" name="enddate" required="required"></div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6"><span class="col-md-4">User Name:</span> <input class="form-control" type="text" name="uname" required="required"></div>
                            <div class="col-md-6"><span class="col-md-4">Password:</span> <input class="form-control" type="password" name="pass" required="required"></div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6"><input type="checkbox" name="conflict" class="" id="conflict">&nbsp;&nbsp;<label for="conflict"> Event overlap allowed?</label></div>
                            <div class="col-md-6"><input class="btn btn-default" type="submit" name="" value="Submit"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function(){
            $('.start').datepicker({
                startDate: '<?php echo date("Y-m-d")?>',
                startView: 2,
                minView: 2,
                maxView: 2,
                autoclose: true,
                todayHighlight: true,
                format: 'yyyy-mm-dd'
            });

            $('.end').datepicker({
                startDate: '<?php echo date("Y-m-d")?>',
                startView: 2,
                minView: 2,
                maxView: 2,
                autoclose: true,
                todayHighlight: true,
                format: 'yyyy-mm-dd'
            });
        })

    </script>
</body>
</html>