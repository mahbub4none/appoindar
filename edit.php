<?php session_start(); ?>
<title>Appoindar | Edit Mode</title>
<?php
include('head.php');
include('class_autoload.php');
include('activity-settings.php');//  Activity Settings View
include('course-settings.php');//  Calendar Settings View
include('period-settings.php');//  Period Settings View

if(isset($_GET['id'])){
    $courseID = $_GET['id'];
    if($_SESSION['id'] != $courseID){
        echo "<script type='text/javascript'>window.location = 'login.php?error=2'</script>";
    }
}
else{
    echo "Course ID should be supplied!";
    die;
}

$date = time();
$hello = new calendarClass($date, $courseID);

$course = $hello->loadCourse($courseID);
$activity_array = $hello->activity_array;
$periods = $hello->periods;

?>
<!--Variables initiated to use in Custom JS file-->
<script>
    var columnWidth = "<?=$hello->columnWidth?>";
    var period_no = "<?=count($hello->periods)+1?>";
    var courseID = "<?=$courseID?>";
</script>
<!--Custom JS file called-->
<script src="js/custom/myCal.js"></script>
<style>
    .activity-type-list{
        margin-top: 10px !important;
        float: left;
        margin-right: 30px;
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
        min-width: 150px;
    }

    /*Activity button style*/
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

    /*Month view hide for edit mode*/
    .fc-month-button{display:none}
    .monthView{display: none}
</style>
<div class="container">
    <!--Activity Buttons-->
    <div class="col-md-12 col-xs-12">
        <div class="activity">
            <?php foreach($activity_array as $activity):?>
            <div class="activity-type-list checkbox checkbox-<?=$activity['slug']?>">
                <input class="activities" id="<?=$activity['slug']?>" type="checkbox" value="<?=$activity['name']?>"/><label for="<?=$activity['slug']?>"> <?=$activity['name']?></label>
            </div>
            <?php endforeach; ?>
        </div>
        <input type="hidden" id="currentView" value="weekView">
    </div>

    <div class="monthView" style=""></div>
    <div class="weekView"></div>
    <div class="dayView" style=""></div>
    <a href="view.php?id=<?=$courseID?>" class="btn btn-primary">View Mode</a>
    <button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#editActivityModal">
        Activity Setting
    </button>
    <button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#editPeriodModal">
        Period Setting
    </button>
    <button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#editCourseModal">
        Calendar Setting
    </button>
    <a class="btn btn-primary btn-md" href="login.php">
        Log Out
    </a>
    <?php include('class-create-form.html.php')?><!--  Class Create Form View  -->
    <?php include('class-edit-form.html.php')?><!--  Class Edit Form View  -->
    <?php include('event-hover-summery.html.php')?><!--  Class Summery Public View  -->

    <div id="wait" style="z-index:9999; display:none;border:0;position:absolute;top:50%;left:50%;padding:2px; text-align: center;"><img src='ajax-loader.gif' width="64" height="64" /><br>&nbsp;loading..</div>
</div>