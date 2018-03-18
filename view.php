<?php
include('class_autoload.php');

$date = time();
if(isset($_GET['id'])){
    $courseID= $_GET['id'];
}
else{
    echo "Course ID should be supplied!";
    die;
}

$hello = new calendarClass($date, $courseID);

$activity_array = $hello->activity_array;
$periods = $hello->periods;
?>


<link href="css/fullcalendar.css" rel="stylesheet">
<link href="plugins/bootstrap-3.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="plugins/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<link href="plugins/jquery-ui-1.11.2/jquery-ui.css" rel="stylesheet">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
<link href="css/awesome-bootstrap-checkbox.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">

<script src="js/jquery.js"></script>
<script src="plugins/bootstrap-3.3.5/dist/js/bootstrap.js"></script>
<script src="plugins/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js"></script>
<script src="plugins/jquery-ui-1.11.2/jquery-ui.min.js"></script>
<script>
    var columnWidth = "<?=$hello->columnWidth?>";
    var period_no = "<?=count($hello->periods)+1?>";
    var courseID = "<?=$courseID?>";
</script>
<script>
    /*$(document).ready(function(){
        $(document).ajaxStart(function(){
            $("#wait").css("display", "block");
        });
        $(document).ajaxComplete(function(){
            $("#wait").css("display", "none");
        });
    });*/
</script>
<script src="js/custom/myCal-public.js"></script>
<style>
    .activity-type-list{
        margin-top: 10px !important;
        float: left;
        margin-right: 30px;
    }
    .activity-type-list .activities{
    }
    .popover div{
        padding-left: 10px;
    }

    .fade{
        opacity:0 ;
        -webkit-transition:opacity 0s linear;
        -moz-transition:opacity 0s linear;
        -ms-transition:opacity 0s linear;
        -o-transition:opacity 0s linear;
        transition:opacity 0s linear;
    }
    .activity{
        margin: 0 auto;
        min-width: 500px;
    }

    <?php foreach($activity_array as $activity):?>
    .checkbox-<?=$activity['slug']?> input[type="checkbox"] + label::before{
        background-color: <?=$activity['color']?>;
        border-color: <?=$activity['color']?>;
    }

    .checkbox-<?=$activity['slug']?> input[type="checkbox"]:checked + label::before{
        background-color: <?=$activity['color']?>;
        border-color: <?=$activity['color']?>;
    }
    .checkbox-<?=$activity['slug']?> input[type="checkbox"]:checked + label::after{
        color: #fff;
    }
    <?php endforeach; ?>


</style>
<div class="container">
    <div class="col-md-12">
        <div class="activity">
            <?php foreach($activity_array as $activity):?>
                <div class="activity-type-list checkbox checkbox-<?=$activity['slug']?>">
                    <input class="activities" id="<?=$activity['slug']?>" type="checkbox" value="<?=$activity['name']?>"/><label for="<?=$activity['slug']?>"> <?=$activity['name']?></label>
                </div>
            <?php endforeach; ?>
        </div>
        <input type="hidden" id="currentView" value="weekView">
    </div>

    <div class="monthView" style=""><?=$hello->monthView()?></div>
    <div class="weekView"><?=$hello->weekView()?></div>
    <div class="dayView" style=""><?=$hello->dayView()?></div>
    <a href="edit.php?id=<?=$courseID?>" class="btn btn-primary">Edit Mode</a>
    <?php include('class-create-form.html.php')?><!--  Class Create Form View  -->
    <?php include('class-edit-form.html.php')?><!--  Class Edit Form View  -->
    <?php include('event-hover-summery.html.php')?><!--  Class Summery Public View  -->

    <div id="wait" style="z-index:9999; display:none;border:0;position:absolute;top:50%;left:50%;padding:2px;"><img src='ajax-loader.gif' width="64" height="64" /><br>Loading..</div>
</div>
