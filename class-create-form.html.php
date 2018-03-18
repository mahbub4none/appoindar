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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="text-align:left;">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
    <button type="button" class="close myModalClose" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h5 class="modal-title" id="myModalLabel">Create Event</h5>
</div>
<div style="margin: 2px 20px 0px 4px; float: right; display: none" id="remove-block">
<!--    <button type="button" class="btn btn-danger btn-xs ladda-button" data-style="expand-left" data-event-id="" id="remove-link"><span class="ladda-label">Remove This Event</span></button>-->
</div>
<div style="clear: both"></div>
<form role="form" id="eventForm" class="form-horizontal">
<div class="modal-body" style="padding-top: 10px;padding-bottom:0px">
<fieldset>
<div class="panel panel-default">
    <div class="panel-body">

        <div class="form-group">
            <label for="title" class="col-sm-3 control-label">Title</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="title" name="title" placeholder="Activity Name" required="" />
            </div>
        </div>

        <div class="form-group">
            <label for="start-date" class="col-sm-3 control-label">Start</label>
            <div class="col-sm-6 date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="start" data-link-format="yyyy-mm-dd" >
                <input type="text" class="form-control" id="start-date" name="start-date" placeholder="Start Date" readonly="readonly" style="background-color: white; cursor: default;" required="required" />
<!--            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>-->
            </div>
        </div>

        <div class="form-group" id="end-group">
            <label for="end" class="col-sm-3 control-label">Period From</label>
<!--            <div class="col-sm-6 form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="end" data-link-format="yyyy-mm-dd" >-->
<!--                <input type="text" class="form-control" placeholder="End Date" name="end-date" id="end-date" readonly="readonly" style="background-color: white; cursor: default;" />-->
<!--                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>-->
<!--            </div>-->
            <div class="col-sm-3">
                <select name="period_from" id="period_from" class="form-control">
                    <?php $i=1; foreach($periods as $period){?>
                        <option value="<?=$i?>"><?=$period['period']?></option>
                    <?php $i++; } ?>
                </select>
            </div>
        </div>
        <div class="form-group" id="end-group">
            <label for="end" class="col-sm-3 control-label">Period To</label>
            <div class="col-sm-3">
                <select name="period_to" id="period_to" class="form-control">
                    <?php $i=1; foreach($periods as $period){?>
                        <option value="<?=$i?>"><?=$period['period']?></option>
                    <?php $i++; } ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="repeat_type" class="col-sm-3 control-label">Repeat</label>
            <div class="col-sm-3">
                <select class="form-control" name="repeat_type" id="repeat_type">
                    <option value="none">None</option>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>
        </div>

    </div>

</div>
<!--- Action Links -->

<div class="well1 well-sm1" style="margin-top: 10px">
<!--    <span class="basic">-->
<!--       <label for="allDay" style="padding-right: 5px; float: left;">-->
<!--           <input type="checkbox" name="allDay" id="allDay"> All Day-->
<!--       </label>-->
<!--       <label for="repeat" style="padding-right: 5px;">-->
<!--           <input type="checkbox" name="repeat" id="repeat" value="1"> Repeat -->
<!--       </label>-->
<!--    </span>-->

    <!-- Standard Settings -->
    <div class="standard col-sm-12" style="margin-top: 8px;">
        <div class="form-group">
            <label for="description" class="col-sm-3 control-label">Description</label>
            <div class="col-sm-9">
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>
        </div>

        <div class="form-group" style="display:block;">
            <label for="select-calendar" class="col-sm-3 control-label">Activity Type</label>
            <div class="col-sm-9">
                <!--<select class="selectpicker show-tick" data-selected-text-format="count" multiple>-->
                <select id="select-activity" class="form-control selectpicker show-tick col-lg-12 select-calendar-cls" name="activitytype">
                    <option value="" data-color="">Select Activity</option>
                    <?php foreach($activity_array as $activity){ ?>
                    <option value="<?=$activity['id']?>" data-color="<?=$activity['color']?>"><div class="color-box" style="background-color: <?=$activity['color']?>;">&nbsp;</div><?=$activity['name']?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="backgroundColor" class="col-sm-3 control-label">Event Color</label>
            <div class="col-sm-9">
                <div class="form-control" style="padding-bottom: 2px;white-space:nowrap">
                    <?php foreach($activity_array as $activity){ ?>
                    <span style="background-color: <?=$activity['color']?>" class="color-box" data-color="<?=$activity['color']?>" id="cid_<?=str_replace('#','',$activity['color'])?>">&nbsp;</span>
                    <?php } ?>
                </div>
                <input type="hidden" name="backgroundColor" id="backgroundColor" value="" />
            </div>
        </div>

        <div class="form-group" style="display:block">
            <label for="instructor" class="col-sm-3 control-label">Instructor</label>
            <div class="col-sm-9">
                <input id="instructor" class="form-control" type="text" name="instructor"/>
            </div>
        </div>
    </div>

    <!-- Standard Settings Ends -->
</div>



</fieldset>
</div>
<div class="modal-footer">

    <input type="hidden" value="-1" name="update-event" id="update-event" />
    <input type="hidden" value="<?=$course['id']?>" name="courseid" id="courseid" />
    <input type="hidden" value="<?=$course['startdate']?>" name="course_start" id="course_start" />
    <input type="hidden" value="<?=$course['enddate']?>" name="course_end" id="course_end" />
    <input type="hidden" value="CREATE_ACTIVITY" name="action" id="action" />
    <input type="hidden" value="" name="currentView" id="currentView" />
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="reset" class="btn btn-primary hide reset" id="reset">Reset</button>
    <button type="button" class="btn btn-primary create-event" id="create-event">Create Event</button>
</div>
</form>

</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->