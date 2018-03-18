<?php 
	include "class_autoload.php";
    $setup = new setupClass();
    $setup->period_setup();
?>

<!DOCTYPE html>
<html>
<head>

	<title>Appoindar | Period Setup</title>
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
                    Periods Setup
                </div>
                <div class="panel-body">
                    <form method="post" enctype="multipart/form-data" class="period-form">
                        <input type="hidden" name="id" value="<?=$_GET['id']?>">
                        <div class="row">
                            <div class="col-md-4"><span class="">Period Heading</span></div>
                            <div class="col-md-4"><span class="">Start Time</span></div>
                            <div class="col-md-4"><span class="">End Time</span></div>
                        </div>
                        <div class="row row_1 form-group">
                            <div class="col-md-4"><input class="form-control" type="text" name="heading[]" placeholder="Ex: Period X" required="required"></div>
                            <div class="col-md-4"><input class="form-control" type="text" name="start[]" placeholder="Ex: 0930" required="required"></div>
                            <div class="col-md-4"><input class="form-control" type="text" name="end[]" placeholder="Ex: 1300" required="required"></div>
                        </div>
                    </form>
                    <button type="button" class="btn btn-primary plus">+ Row</button>
                    <button type="button" class="btn btn-danger minus">- Row</button>
                    <button type="submit" class="btn btn-success submit">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var i = 1;
        $(document).ready(function(){
            $('.plus').click(function(){
                i++;
                var row = "<div class='row form-group row_"+i+"'>"+
                    "<div class='col-md-4'><input class='form-control' type='text' name='heading[]' placeholder='Ex: Period X' required='required'></div>"+
                    "<div class='col-md-4'><input class='form-control' type='text' name='start[]' placeholder='Ex: 0930' required='required'></div>"+
                    "<div class='col-md-4'><input class='form-control' type='text' name='end[]' placeholder='Ex: 1300' required='required'></div>"+
                "</div>";
                $('.period-form').append(row);
            });

            $('.minus').click(function(){
                if(i > 1){
                    $('.row_'+i).hide();
                    i--;
                }
            });

            $('.submit').click(function(){
                $('.period-form').submit();
            });
        });
    </script>
</body>
</html>