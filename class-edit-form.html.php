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
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="text-align:left;">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h5 class="modal-title" id="editModalLabel">Edit Event</h5>
</div>
<div style="margin: 2px 20px 0px 4px; float: right;" id="remove-block">
    <button type="button" class="btn btn-danger btn-xs ladda-button" data-style="expand-left" data-event-id="" id="remove-link"><span class="ladda-label">Remove This Event</span></button>
</div>
<div style="clear: both"></div>
<form role="form" id="eventEditForm" class="form-horizontal">
<div class="modal-body" style="padding-top: 10px;padding-bottom:0px">
<fieldset>
<div class="panel panel-default">
    <div class="panel-body">

        <div class="form-group">
            <label for="title" class="col-sm-3 control-label">Title</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="edit-title" name="title" placeholder="Activity Name" value="English Grammar" />
            </div>
        </div>

        <div class="form-group">
            <label for="start-date" class="col-sm-3 control-label">Start</label>
            <div class="col-sm-6 date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="start" data-link-format="yyyy-mm-dd" >
                <input type="text" class="form-control" id="edit-start-date" name="start-date" placeholder="Start Date" readonly="readonly" value="2016-04-01" style="background-color: white; cursor: default;" />
<!--            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>-->
            </div>
<!--            <div class="col-sm-3">-->
<!--                <input name="start-time" id="edit-start-time" class="form-control" value="11:15 AM" style="background-color: white; cursor: default;"/>-->
<!--            </div>-->
        </div>


        <div class="form-group" id="end-group">
            <label for="end" class="col-sm-3 control-label">Period From</label>
            <!--            <div class="col-sm-6 form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="end" data-link-format="yyyy-mm-dd" >-->
            <!--                <input type="text" class="form-control" placeholder="End Date" name="end-date" id="end-date" readonly="readonly" style="background-color: white; cursor: default;" />-->
            <!--                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>-->
            <!--            </div>-->
            <div class="col-sm-3">
                <select name="period_from" id="edit_period_from" class="form-control">
                    <?php $i=1; foreach($periods as $period){?>
                        <option value="<?=$i?>"><?=$period['period']?></option>
                        <?php $i++; } ?>
                </select>
            </div>
        </div>
        <div class="form-group" id="end-group">
            <label for="end" class="col-sm-3 control-label">Period To</label>
            <!--            <div class="col-sm-6 form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="end" data-link-format="yyyy-mm-dd" >-->
            <!--                <input type="text" class="form-control" placeholder="End Date" name="end-date" id="end-date" readonly="readonly" style="background-color: white; cursor: default;" />-->
            <!--                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>-->
            <!--            </div>-->
            <div class="col-sm-3">
                <select name="period_to" id="edit_period_to" class="form-control">
                    <?php $i=1; foreach($periods as $period){?>
                        <option value="<?=$i?>"><?=$period['period']?></option>
                        <?php $i++; } ?>
                </select>
            </div>
        </div>
    </div>

</div>
<!--- Action Links -->

<div class="" style="margin-top: 10px">
<!--    <span class="basic">-->
<!--       <label for="editAllDay" style="padding-right: 5px; float: left;">-->
<!--           <input type="checkbox" name="allDay" checked id="editAllDay"> All Day-->
<!--       </label>-->
<!--       <label for="editRepeat" style="padding-right: 5px;">-->
<!--           <input type="checkbox" name="repeat" id="editRepeat" value="1"> Repeat <!-- lite_disabled -->
<!--       </label>-->
<!--    </span>-->


    <!-- Repeat Box -->
    <div class="panel panel-info repeat-box col-sm-12" style="margin-top: 8px; margin-bottom: 8px;">
        <div class="panel-body">

            <div class="form-group">
                <label for="repeat_type" class="col-sm-3 control-label">Repeats</label>
                <div class="col-sm-9">
                    <select class="form-control" name="repeat_type" id="repeat_type">
                        <option value="daily">Daily</option>
                        <option value="everyWeekDay">Every Weekday (Monday to Friday)</option>
                        <option value="everyMWFDay">Every Monday, Wednesday, and Friday</option>
                        <option value="everyTTDay">Every Tuesday, and Thursday</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                        <option value="none">None</option>
                    </select>
                </div>
            </div>

            <div class="form-group" id="repeat_interval_group">
                <label for="repeat_interval" class="col-sm-3 control-label">Repeat Every</label>
                <div class="input-group col-sm-9">
                    <select class="form-control" name="repeat_interval" id="repeat_interval">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                    </select>
                    <span class="input-group-addon" id="repeat_interval_label">weeks</span>
                </div>
            </div>

            <div class="form-group" id="repeat_by_group">
                <label for="repeat_by_group" class="col-sm-3 control-label">Repeat by</label>
                <div class="input-group col-sm-9">
                    <label class="radio-inline">
                        <input class="repeat_by" type="radio" id="repeat_by_day_of_the_month" checked="checked" name="repeat_by" value="repeat_by_day_of_the_month" /> Day of the Month
                    </label>
                    <label class="radio-inline">
                        <input class="repeat_by" type="radio" id="repeat_by_day_of_the_week" name="repeat_by" value="repeat_by_day_of_the_week" /> Day of the Week
                    </label>
                </div>
            </div>

            <div class="form-group" id="repeat_on_group">
                <label for="repeat_on" class="col-sm-3 control-label">Repeat on</label>
                <div class="input-group col-sm-9">
                    <label class="checkbox-inline">
                        <input class="repeat_on_day" type="checkbox" id="repeat_on_sun" name="repeat_on_sun" value="1" /> S
                    </label>
                    <label class="checkbox-inline">
                        <input class="repeat_on_day" type="checkbox" id="repeat_on_mon" name="repeat_on_mon" value="1" /> M
                    </label>
                    <label class="checkbox-inline">
                        <input class="repeat_on_day" type="checkbox" id="repeat_on_tue" name="repeat_on_tue" value="1" /> T
                    </label>
                    <label class="checkbox-inline">
                        <input class="repeat_on_day" type="checkbox" id="repeat_on_wed" name="repeat_on_wed" value="1" /> W
                    </label>
                    <label class="checkbox-inline">
                        <input class="repeat_on_day" type="checkbox" id="repeat_on_thu" name="repeat_on_thu" value="1" /> T
                    </label>
                    <label class="checkbox-inline">
                        <input class="repeat_on_day" type="checkbox" id="repeat_on_fri" name="repeat_on_fri" value="1" /> F
                    </label>
                    <label class="checkbox-inline">
                        <input class="repeat_on_day" type="checkbox" id="repeat_on_sat" name="repeat_on_sat" value="1" /> S
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="repeat_start_date" class="col-sm-3 control-label">Starts on</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="repeat_start_date" name="repeat_start_date" value="0000-01-01" readonly style="background: transparent" />
                </div>
            </div>


            <div class="form-group">
                <label for="ends-db-val" class="col-sm-3 control-label">Ending Condition</label>
                <div class="col-sm-9">
                    <div class="input-group event-form-break">
                        <div class="input-group-btn dropup">
                            <button class="btn btn-default dropdown-toggle event-create-btn-input" type="button" data-toggle="dropdown">
                                <span id="ends-text">Ends <span id="ends-status">Never</span></span>&nbsp;<span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a id="ends-never" href="javascript:void(0);" data-value="Never" class="ends-params">Never</a></li>
                                <li><a id="ends-after" href="javascript:void(0);" data-value="After" class="ends-params">After</a></li>
                                <li><a id="ends-on" href="javascript:void(0);" data-value="On" class="ends-params">On</a></li>
                            </ul>
                            <input type="hidden" name="repeat_end_on" id="repeat_end_on" value="" />
                            <input type="hidden" name="repeat_end_after" id="repeat_end_after" value="" />
                            <input type="hidden" name="repeat_never" id="repeat_never" value="1" />
                        </div>
                        <input type="text" class="form-control" id="ends-db-val" readonly style="width: 130px" /> <span style="display: none; margin-left: 10px;" id="ends-after-label">occurrences</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Repeat Box Ends -->

    <!-- Standard Settings -->
    <div class="standard col-sm-12" style="margin-top: 8px;">
        <div class="form-group">
            <label for="description" class="col-sm-3 control-label">Description</label>
            <div class="col-sm-9">
                <textarea class="form-control" id="edit-description" name="description">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non sagittis arcu, eu consequat massa. Sed erat mauris, ultrices nec placerat a, feugiat ullamcorper sapien. Cras nisl felis, interdum luctus viverra nec, venenatis vel est. Nam lobortis id magna in tincidunt. Aenean faucibus turpis ut lectus congue, tristique scelerisque dui aliquet. Aenean et pretium ex, ut placerat libero. Sed eleifend ligula tellus, et posuere quam placerat sit amet. Quisque ac ex imperdiet, efficitur nisl vitae, elementum nisi. Quisque sit amet porta risus. Nulla rutrum posuere magna, ut feugiat nunc consectetur sit amet.
                </textarea>
            </div>
        </div>

        <div class="form-group" style="display:block;">
            <label for="select-calendar" class="col-sm-3 control-label">Activity Type</label>
            <div class="col-sm-9">
                <!--<select class="selectpicker show-tick" data-selected-text-format="count" multiple>-->
                <select id="edit-select-activity" class="form-control selectpicker show-tick col-lg-12 select-calendar-cls" name="activitytype">
                    <option value="" data-color="">Select Activity</option>
                    <?php foreach($activity_array as $activity){ ?>
                        <option value="<?=$activity['id']?>" data-color="<?=$activity['color']?>"><?=$activity['name']?></option>
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
                <input id="edit-instructor" class="form-control" type="text" name="instructor" value="Jhon Doe"/>
            </div>
        </div>
    </div>

    <!-- Standard Settings Ends -->
</div>



</fieldset>
</div>
<div class="modal-footer">

    <input type="hidden" value="-1" name="update-event" id="update-event" />
    <input type="hidden" value="EDIT_ACTIVITY" name="action" id="action" />
    <input type="hidden" value="<?=$course['id']?>" name="courseid" id="courseid" />
    <input type="hidden" value="<?=$course['startdate']?>" name="course_start" id="edit_course_start" />
    <input type="hidden" value="<?=$course['enddate']?>" name="course_end" id="edit_course_end" />
    <input type="hidden" value="" name="currentView" id="currentView" />
    <input type="hidden" value="" name="eventID" id="eventID" />
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary update-event" id="update-event">Update Event</button>
</div>
</form>

</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->