<?php
//include('calendarClass.php');
$date = time();
$courseid = $_GET['id'];

$hello = new calendarClass($date, $courseid);

$con = $hello->con();

$activity_array = $hello->activity_array;
if(isset($_POST['action']) && $_POST['action'] == "ACTIVITY_SETTING"){
    foreach($activity_array as $activity){
        $type = $_POST['activity_type_'.$activity['sl']];
        $color = $_POST['activity_color_'.$activity['sl']];
        $activity_id = $activity['sl'];
        $hello->activitySetting($type, $color, $activity_id);
    }
}

$activity_array = $hello->activity_array;
?>
<style>
    .guest-view {
        border: 0px none;
        box-shadow: none;
        padding-left: 0px;
        padding-right: 0px;
        width: 90%;
        float: left;
    }

    .close_guest {
        background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
        border: medium none;
        height: 21px;
        vertical-align: middle;
        border-bottom: 1px dotted #d4d4d4;
    }

    .close_reminder {
        background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
        border: medium none;
        height: 32px;
        margin-left: 0;
        padding-left: 0;
        vertical-align: middle;
    }

    .standard {
        /*display: none;*/
    }

    .reminder {
        display: none;
    }

    .venue {
        display: none;
    }

    .repeat-box {
        display: none;
    }
    .well {
        background: transparent;
    }
    .event-form-break {
        margin-top: 10px;
    }
    .event-create-btn-input {
        background-image: none;
    }
    .color-box {
        display: inline-block;
        border: 0 solid;
        height: 18px;
        width: 18px;
        margin-right: 15px;
        cursor: pointer;
        border-radius: 10px;
        color: #ffffff;
        line-height: 22px;
    }
    .color-box:hover{
        border: 0 solid;
    }
    .color-box:active{
        border-radius: 0;
    }

    .color-box-selected {
        border-radius: 0;
    }

    .panel {
        margin: 0;
    }

    .col-sm-4, .col-xs-6, .col-lg-6, .col-xs-12, .col-lg-12 {
        padding-left: 0;
        padding-right: 0;
    }
    button .multiple-select-option-label {
        font-size: 9px;
        border: 1px solid darkgrey;
        border-radius: 5px;
        margin-top: 0;
        display: inline-block;
        padding-top: 4px;
        padding-bottom: 4px;
        padding-left: 2px;
        padding-right: 2px;
        background-color: #ffffff;
    }

    .time-panel {
        background: none repeat scroll 0 0 #FAFAFA;
        border: 1px solid #D4D4D4;
        height: 140px;
        overflow: auto;
        position: absolute;
        width: 100px;
        z-index: 99999;
        display: none;
    }

    .time-panel-ul {
        width: 100%;
    }
    .time-panel-ul li {
        border: 1px solid #F0F0F0;
        float: none;
        list-style: none outside none;
        margin:0;
        padding:0;
        text-align: left;
        width: 81px;
        border-right: 0;
        cursor: pointer;
        padding-left: 12px;
    }
    .time-panel-ul li:hover{
        background-color: #3A87AD;
        color: #ffffff;
    }
    #guest-list {
        margin-top: 5px;
        height: 100px;
        border: 1px solid #d9d9d9;
        border-radius: 4px;
        overflow:auto;
        padding-left: 4px;
        width: 345px;
        background-color: #F9F9F9;
    }
    #guest-list div {
        height: 22px;
        margin-top: 1px;
        margin-bottom: 1px;
    }
    #guest-list input {
        background-color: #F9F9F9;
        border-bottom: 1px dotted #D4D4D4;
        border-radius: 0;
        font-size: 13px;
        height: 22px;
        margin-bottom: 0;
        margin-top: 0;
        padding: 0;
    }
</style>
<!-- Modal -->
<div class="modal fade" id="editActivityModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="text-align:left;">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h5 class="modal-title" id="editModalLabel">Activity Setting</h5>
</div>
<div style="margin: 2px 20px 0px 4px; float: right; display: none;" id="remove-block">
<!--    <button type="button" class="btn btn-danger btn-xs ladda-button" data-style="expand-left" data-event-id="" id="remove-link"><span class="ladda-label">Remove This Event</span></button>-->
</div>
<div style="clear: both"></div>
<form action="" role="form" id="activityEditForm" class="form-horizontal" method="post">
<div class="modal-body" style="padding-top: 10px;padding-bottom:0px">
<fieldset>

<div class="panel panel-default">
    <div class="panel-body">

        <div class="form-group">
            <label for="title" class="col-sm-3 control-label">Title</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="edit-course" name="title" placeholder="Activity Name" value="<?=$course['fullname']?>" readonly />
            </div>
        </div>

        <?php foreach($activity_array as $activity){?>
            <div class="form-group">
                <label for="title" class="col-sm-3 control-label"><?=$activity['name']?></label>
                <div class="col-sm-9">
                    Activity Type: <input type="text" class="form-control" id="" name="activity_type_<?=$activity['sl']?>" value="<?=$activity['name']?>" />
                    Activity Color: <input type="color" class="form-control" id="" name="activity_color_<?=$activity['sl']?>" value="<?=$activity['color']?>" />
                </div>
            </div>
        <?php } ?>


    </div>

</div>


</fieldset>
</div>
<div class="modal-footer">

    <input type="hidden" value="-1" name="update-event" id="update-event" />
    <input type="hidden" value="ACTIVITY_SETTING" name="action" id="action" />
    <input type="hidden" value="<?=$course['id']?>" name="courseid" id="courseid" />
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" id="">Update</button>
</div>
</form>

</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->