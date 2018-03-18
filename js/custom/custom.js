$(document).ready(function() {
    /*
    * Function to set UI changes regarding repeat option
    * */
    function setRepeatOptionsForDays(stDate) {
        var stdUnix = new Date(stDate);
        var weekDay = stdUnix.getDay(stdUnix);

        repeat_week_days_checked = false;

        //==== reset the checkboxes
        if (repeat_on_sun == 1) $('#repeat_on_sun').click(function () {
            this.checked
        });
        if (repeat_on_mon == 1) $('#repeat_on_mon').click(function () {
            this.checked
        });
        if (repeat_on_tue == 1) $('#repeat_on_tue').click(function () {
            this.checked
        });
        if (repeat_on_wed == 1) $('#repeat_on_wed').click(function () {
            this.checked
        });
        if (repeat_on_thu == 1) $('#repeat_on_thu').click(function () {
            this.checked
        });
        if (repeat_on_fri == 1) $('#repeat_on_fri').click(function () {
            this.checked
        });
        if (repeat_on_sat == 1) $('#repeat_on_sat').click(function () {
            this.checked
        });

        repeat_on_sun = 0;
        repeat_on_mon = 0;
        repeat_on_tue = 0;
        repeat_on_wed = 0;
        repeat_on_thu = 0;
        repeat_on_fri = 0;
        repeat_on_sat = 0;

        //==== set repeat day checkboxes based on start date
        switch (weekDay) {
            case 0:
                $('#repeat_on_sun').click();
                repeat_on_sun = 1;
                repeat_week_days_checked = true;
                break;
            case 1:
                $('#repeat_on_mon').click();
                repeat_on_mon = 1;
                repeat_week_days_checked = true;
                break;
            case 2:
                $('#repeat_on_tue').click();
                repeat_on_tue = 1;
                repeat_week_days_checked = true;
                break;
            case 3:
                $('#repeat_on_wed').click();
                repeat_on_wed = 1;
                repeat_week_days_checked = true;
                break;
            case 4:
                $('#repeat_on_thu').click();
                repeat_on_thu = 1;
                repeat_week_days_checked = true;
                break;
            case 5:
                $('#repeat_on_fri').click();
                repeat_on_fri = 1;
                repeat_week_days_checked = true;
                break;
            case 6:
                $('#repeat_on_sat').click();
                repeat_on_sat = 1;
                repeat_week_days_checked = true;
                break;
        }

    }

    /*
    * function to format date string
    * */
    function formatTimeStr(dateStr) {
        var timeData = dateStr.split(':');

        var hh = parseInt(timeData[0]);
        var m = parseInt(timeData[1]);
        var dd = "AM";
        var h = hh;
        if (h >= 12) {
            h = hh - 12;
            dd = "PM";
        }
        if (h == 0) {
            h = 12;
        }
        m = m < 10 ? "0" + m : m;

        h = h < 10 ? "0" + h : h;

        var replacement = h + ":" + m;
        replacement += " " + dd;

        return replacement;
    }

        var activity = [];
        var event = $.getJSON( "events.json", function( data ) {
            var activity_type = data.activity_type;

                var events;
                var myEvent;
                $.each( activity_type, function( key, val ) {
                    var color = val.color;
                    events = val.events;

                    $.each(events, function(key, val){
                        myEvent = val;
                        myEvent.backgroundColor = color;
                        activity.push(myEvent);
                    });
                });
            //console.log(activity);
            $('#calendar').fullCalendar('addEventSource', activity);
        });


    //var event = [{"title":"Hello", "start": "2016-04-01T12:00:00", "end": "2016-04-02T14:45:00", "description":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non sagittis arcu, eu consequat massa..."}];
    //var event = fetchJSON();
    //console.log(event);

    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        firstDay:1,
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        //events: 'events_array.php',
        dayClick: function(date, allDay, jsEvent, view) {
            //==== show this panel if it is hidden
            $('#end-group').show();
            $('#remove-block').hide();
            $('#repeat_by_group').hide();


            //===Selecting Multiple Calendar

            $('#eventForm fieldset').removeAttr('disabled');

            var dt = new Date();

            var hours   = dt.getHours();
            var minutes = dt.getMinutes();
            if(minutes > 30) minutes = 30;
            else minutes = 0;
            var ehours;
            if(hours > 0) ehours = hours+1;
            if(hours == 0) ehours = hours;
            if(hours == 23) ehours = hours;

            var eminutes;
            if(ehours >= 24) ehours = '0';
            if(hours > 0) eminutes = minutes;
            if(hours == 0) eminutes = '59';
            if(hours == 23) eminutes = '59';

            var mm = date.format('M');
            var dd = date.format('D');
            var yyyy = date.format('YYYY');

            if(parseInt(mm) <= 9) mm = '0'+(parseInt(mm)+0);
            if(parseInt(dd) <= 9) dd = '0'+dd;
            if(parseInt(hours) <= 9) hours = '0'+hours;
            if(parseInt(minutes) <= 9) minutes = '0'+minutes;
            if(parseInt(ehours) <= 9) ehours = '0'+ehours;
            if(parseInt(eminutes) <= 9) eminutes = '0'+eminutes;

            var curDate = yyyy+'-'+mm+'-'+dd+' '+hours+':'+minutes;
            var curDateInput = yyyy+'-'+mm+'-'+dd;

            var shortdateFormat = '$calendarProperties[shortdate_format]';
            var longdateFormat = '$calendarProperties[longdate_format]';
            var title = $.fullCalendar.moment(date).format(longdateFormat+' hh:mm A');

            $('#myModal').modal({backdrop:'static',keyboard:false});
            //$('#myModalLabel').html(title);
            $('#myTab a:first').tab('show');
            $('#create-event').html('Create Event');
            $('#update-event').val('');

            //==== resetting fields
            document.getElementById('eventForm').reset();
            $('.checkbox-inline input, #allDay').removeAttr('checked');
            $('.repeat-box').hide();
            //$('#hide-standard-settings').click(); //<!-- lite_disabled: always expanding this section for lite version only -->
            $('#show-standard-settings').click(); //lite_disabled
            //$('.color-box').removeClass('color-box-selected');
            $('#backgroundColor').val('#3a87ad');
            $('#repeat_end_on').val('');
            $('#repeat_end_after').val('');
            $('#repeat_never').val('1');
            $('#ends-db-val').datetimepicker('remove');
            $('#ends-db-val').attr('readonly','readonly');
            $('#files').children('div').remove();
            $('#imageName').val('');

            //====For Agenda Week & Agenda Day
            if(hours > 0 || minutes > 0){

            }

            //====Setting Date Fields
            $('#start-date').val(curDateInput);
            $('#end-date').val(curDateInput);
            $('#repeat_start_date').val(curDateInput);

            //===convert 24 hours to 12 hours format
            var startTime12Format = formatTimeStr(hours+':'+minutes);
            var endTime12Format = formatTimeStr(ehours+':'+eminutes);

            $('#start-time').val(startTime12Format);
            $('#end-time').val(endTime12Format);

        },
        eventClick: function(event, jsEvent, view) {
            $('#editModal').modal({backdrop:'static',keyboard:false});
        },
        eventMouseover:function(event, jsEvent, view){
            var desc = event.description;
            if(desc.length > 25) {
                desc = desc.substring(0,24)+"...";
            }

            $(this).popover({
                html: true,
                placement: 'top',
                title:function(){
                    $(".pop-title").text(event.title);
                    return $("#event-head").html();
                },
                content: function() {
                    $(".pop-start").text(moment(event.start).format('DD-MM-YYYY hh:mm A'));
                    $(".pop-end").text(moment(event.end).format('DD-MM-YYYY hh:mm A'));
                    $(".pop-desc").text(desc);
                    return $("#event-summery").html();
                },
                container:'body'
            });
            $(this).popover('show');
        },
        eventMouseout:function(event, jsEvent, view){
            $(this).popover('hide');
        }
    });

    $('#repeat').click(function(){
        $('.repeat-box').toggle();
    });

    $('#start-date').datetimepicker({
        startDate: '<?php echo date("Y-m-d")?>',
        startView: 2,
        minView: 2,
        maxView: 2,
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });

    $('#edit-start-date').datetimepicker({
        startDate: '<?php echo date("Y-m-d")?>',
        startView: 2,
        minView: 2,
        maxView: 2,
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });

    $('#repeat_start_date').datetimepicker({
        startDate: '<?php echo date("Y-m-d")?>',
        startView: 2,
        minView: 2,
        maxView: 2,
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });

    $('#end-date').datetimepicker({
        startDate: '<?php echo date("Y-m-d")?>',
        startView: 2,
        minView: 2,
        maxView: 2,
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });

    $('#edit-end-date').datetimepicker({
        startDate: '<?php echo date("Y-m-d")?>',
        startView: 2,
        minView: 2,
        maxView: 2,
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });


    $('.color-box').click(function () {
        $('.color-box').html('&nbsp;');
        $('.color-box').removeClass('color-box-selected');
        $(this).addClass('color-box-selected');
        $(this).html('&nbsp;✔');
        var cVal = $(this).attr('data-color');
        $('#backgroundColor').val(cVal);
    });

    $('.cal-color-box').click(function () {
        $('.cal-color-box').html('&nbsp;');
        $('.cal-color-box').removeClass('color-box-selected');
        $(this).addClass('color-box-selected');
        $(this).html('&nbsp;✔');
        var cVal = $(this).attr('data-color');
        $('#cal-color').val(cVal);
    });

    $('#select-activity').change(function () {
        //alert($(this).val());
        var color = $(this).find(':selected').attr('data-color')
        var colorData = color.split('#');
        var cid = 'cid_' + colorData[1];
        $('#' + cid).click();
    });

    $('#repeat_type').change(function () {
        var repeatType = $(this).val();
        var intervalLabel = 'weeks';
        $('#repeat_interval_group').show();
        $('#repeat_on_group').show();
        $('#repeat_by_group').hide();
        $('.repeat_by').removeAttr('checked');
        //$('#repeat_on_wed').removeAttr('checked');

        switch (repeatType) {
            case 'daily':
                $('#repeat_on_group').hide();
                intervalLabel = 'Days';
                break;
            case 'everyWeekDay':
                intervalLabel = '';
                $('#repeat_interval_group').hide();
                $('#repeat_on_group').hide();
                break;
            case 'everyMWFDay':
                intervalLabel = '';
                $('#repeat_interval_group').hide();
                $('#repeat_on_group').hide();
                break;
            case 'everyTTDay':
                intervalLabel = '';
                $('#repeat_interval_group').hide();
                $('#repeat_on_group').hide();
                break;
            case 'weekly':
                intervalLabel = 'Weeks';
                //$('#repeat_on_wed').attr('checked','checked');
                setRepeatOptionsForDays($('#start-date').val());
                break;
            case 'monthly':
                $('#repeat_on_group').hide();
                $('#repeat_by_group').show();
                $('#repeat_by_day_of_the_month').click();
                intervalLabel = 'Months';
                break;
            case 'yearly':
                intervalLabel = 'Years';
                $('#repeat_on_group').hide();
                break;
            case 'none':
            default :
                $('#repeat_on_group').hide();
                intervalLabel = 'Days';
                break;
        }
        $('#repeat_interval_label').html(intervalLabel);
    });

    $('.activities').click(function(){
        var cid = new Array();
        var i = 0;
        $('.activities').each(function () {
            if ($(this).prop('checked') == true) {
                cid[i] = $(this).val();
                i = i + 1;
            }
        });

        $.post("events_array.php",
            { activities: cid},
            function (eventJSON) {
            }, "json")
            .always(function (eventJSON) {

                $('#calendar').fullCalendar('removeEvents');
                $('#calendar').fullCalendar('addEventSource', eventJSON);

            }, "json");

    });

});