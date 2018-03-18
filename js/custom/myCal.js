/**
 * Created by Mahbub on 4/5/16.
 */

$(function(){
    $('body').on('click','.fc-month-button',function(){
        var cur_month = parseInt($('#current_month').val());
        var cur_time = parseInt($('#current_time').val());
        monthView(cur_month,cur_time);
        $('.weekView').hide();
        $('.dayView').hide();
        $('.monthView').show();

        $('#currentView').val('monthView');

        $('.fc-state-active').removeClass('fc-state-active');
        $(this).addClass('fc-state-active');
    });
    $('body').on('click','.fc-agendaWeek-button',function(){
        $('.dayView').hide();
        $('.monthView').hide();
        $('.weekView').show();
        $('#currentView').val('weekView');
    });
    $('body').on('click','.fc-agendaDay-button',function(){
        var cur_daytime = parseInt($('#current_daytime').val());
        changeView(cur_daytime, 'dayView');
        $('.weekView').hide();
        $('.monthView').hide();
        $('.dayView').show();
        $('#currentView').val('dayView');
    });

    function changeView(date, view){
        $('.fc-draggable').popover('hide');
        $('.popover').hide();
        $.ajax({
            type: 'post',
            data:{date:date, view:view, courseID:courseID},
            url:"events_array.php",
            success: function(e){
                $('div.'+view).html(e);
                //$('#wait').hide();
                if(view == 'weekView'){
                    weekView(date);
                }
                else if(view == 'dayView'){
                    dayView(date);
                }
            }
        });
    }

    function activityTypes(){
        var checkedActivity = $('.activities:checked').length;
        //alert(checkedActivity);
        var allVals = [];
        if(checkedActivity < 1){
            $('.activities').each(function() {
                allVals.push($(this).val());
            });
        }
        else{
            $('.activities:checked').each(function() {
                allVals.push($(this).val());
            });
        }

        return allVals;
    }

    function weekViewDragUpdate(date, id, period_from){
        $.ajax({
            type: 'post',
            data:{date:date, id:id, period_from:period_from, action:"EVENT_UPDATE_ON_DROP", courseid:courseID},
            url:"events_manager.php",
            success: function(e){
                if(e == 1){
                    $.bootstrapGrowl("Event Updated Successfully ", {
                        type: 'success',
                        width: 350
                    });
                }
                else if(e == 2){
                    //alert('Warning: There is a conflicted event!');
                    $.bootstrapGrowl(" There is a conflicted event! ", {
                        type: 'warning',
                        width: 350
                    });
                }
                else if(e == 3){
                    //alert('Warning: Selected time is not allowed!');
                    $.bootstrapGrowl(" Selected time is not allowed! ", {
                        type: 'warning',
                        width: 350
                    });
                }
                else{
                    //alert('Error: Event Update is failed!');
                    $.bootstrapGrowl(" Event Update is failed! ", {
                        type: 'danger',
                        width: 350
                    });
                }

                var view = $('#currentView').val();
                if(view == 'weekView'){
                    var cur_day = $('#week_day').val();
                    cur_day = parseInt(cur_day);
                    changeView(cur_day, 'weekView');
                }
                if(view == 'dayView'){
                    var cur_daytime = parseInt($('#current_daytime').val());
                    changeView(cur_daytime, 'dayView');
                }
            }
        });
    }

    function weekView(date){
        $('#wait').show();
        //alert(columnWidth);
        var checkedActivities = activityTypes();
        setTimeout(function(){

            $.ajax({
                type: 'post',
                data:{date:date, activities:checkedActivities, courseID:courseID},
                url:"events_array.php",
                success: function(e){
                    $('.fc-draggable').popover('hide');
                    $('.popover').hide();

                    if(e == null){
                        $('.training_events').html('');
                    }
                    $('.training_events').html('');
                    $('.training_events').append(e);

                    /*$( ".fc-draggable" ).resizable({
                     grid: [ columnWidthPx, 60 ],
                     containment: ".container",
                     minWidth: 60
                     });*/
                    var containerWidth = $('.container').width();
                    //alert(period_no);
                    var columnWidthPx = containerWidth/period_no;


                    $('.fc-draggable').draggable({
                        grid: [ columnWidthPx, 60 ],
                        containment:'.container',
                        scroll: false,
                        start: function(event, ui){
                            $(this).data('dragging', true);
                        },
                        stop: function(event, ui){
                            var currentView = $('#currentView').val();
                            var container = $('.training_events').css('width');
                            var columnWidth = container/period_no;
                            var t = $(this);
                            var left = t.css('left');
                            var top = t.css('top');
                            var period_from = parseInt(left)/parseInt(columnWidthPx);
                            var dayTop = '.day-'+parseInt(top);
                            var eDate = $(dayTop).data('date');
                            var eID = $(this).find('.id').val();
                            //console.log(eDate+" ID:"+eID+" PeriodFrom:"+period_from);
                            weekViewDragUpdate(eDate, eID, period_from);
                            setTimeout(function(){
                                $(event.target).data('dragging', false); // Set dragging false
                            }, 1);
                        }
                    });
                },
                error:function(){
                    $('.training_events').html('');
                }
            });
            $('#wait').hide();
        }, 1000);


    }

    //console.log(activityTypes());
    var d = new Date();
    var n = d.getTime();
    n = parseInt(n/1000)-1;
    changeView(n, 'dayView');
    setTimeout(function(){
        changeView(n, 'weekView');
    },1000);



    $('body').on('click','.next-week',function(){
        var cur_day = $('#week_day').val();
        var this_day = $('#this_day').val();
        cur_day = parseInt(cur_day) + (7*24*3600);
        changeView(cur_day, 'weekView');
        $('#week_day').val(cur_day);
        $('#this_day').val(this_day);
    });

    $('body').on('click','.prev-week',function(){
        var cur_day = $('#week_day').val();
        var this_day = $('#this_day').val();

        cur_day = parseInt(cur_day) - (7*24*3600);
        changeView(cur_day, 'weekView');
        $('#week_day').val(cur_day);
        $('#this_day').val(this_day);
    });


    function dayView(date){
        $('#wait').show();
        setTimeout(function(){
            var checkedActivities = activityTypes();

            $.ajax({
                type: 'post',
                data:{date:date, day:'day', activities:checkedActivities, courseID:courseID},
                url:"events_array.php",
                success: function(e){

                    $('.fc-draggable').popover('hide');
                    $('.popover').hide();

                    if(e == null){
                        $('.day-event-container').html('');
                    }
                    $('.day-event-container').html('');
                    $('.day-event-container').append(e);

                    /*$( ".fc-draggable" ).resizable({
                     grid: [ 300, 60 ],
                     containment: ".container"
                     });*/

                    $('.fc-draggable').draggable({
                        grid: [ 300, 60 ],
                        containment:'.container',
                        scroll: false,
                        start: function(event, ui){
                            $(this).data('dragging', true);
                        },
                        stop: function(event, ui){
                            var currentView = $('#currentView').val();
                            var t = $(this);
                            var top = t.css('top');
                            var period_from = (parseInt(top)+60)/60;
                            var eDate = t.data('date');
                            var eID = $(this).find('.id').val();
                            //console.log(eDate+" ID:"+eID+" PeriodFrom:"+period_from);
                            weekViewDragUpdate(eDate, eID, period_from);
                            setTimeout(function(){
                                $(event.target).data('dragging', false); // Set dragging false
                            }, 1);
                        }
                    });
                },
                error:function(){
                    $('.day-event-container').html('');
                }
            });

            $('#wait').hide();
        }, 1000);

    }

    $('body').on('click','.next-day',function(){
        var cur_daytime = parseInt($('#current_daytime').val());
        var this_daytime = parseInt($('#this_daytime').val());
        cur_daytime = parseInt(cur_daytime) + (24*3600);
        changeView(cur_daytime, 'dayView');
        $('#current_daytime').val(cur_daytime);
        $('#this_daytime').val(this_daytime);
    });

    $('body').on('click','.prev-day',function(){
        var cur_daytime = parseInt($('#current_daytime').val());
        var this_daytime = parseInt($('#this_daytime').val());
        cur_daytime = parseInt(cur_daytime) - (24*3600);
        changeView(cur_daytime, 'dayView');
        $('#current_daytime').val(cur_daytime);
        $('#this_daytime').val(this_daytime);
    });

    function monthView(month, dateTime){
        var checkedActivities = activityTypes();

        $.ajax({
            type: 'post',
            data: {month:month, activities:checkedActivities, courseID:courseID},
            url:"events_array.php",
            dataType:'JSON',
            success: function(e){

                $('.fc-draggable').popover('hide');
                $('.popover').hide();

                for(var i =1; i<=31; i++){
                    $('.event-catch-'+i).html('');
                }
                $.each(e, function(key, val){
                    var title = val.title;
                    if(title.length > 15) {
                        title = title.substring(0,14)+"...";
                    }

                    var event1 = '<a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end fc-draggable" style="background-color:'+val.bgcolor+'; ">' +
                        '<div class="fc-content">' +
                        '<span class="fc-time">'+val.start_time+'</span> ' +
                        '<span class="fc-title">'+title+'</span></div>' +
                        '<input type="hidden" class="for-pop-title" value="'+val.title+'"/>' +
                        '<input type="hidden" class="for-pop-start" value="'+val.start+'"/>' +
                        '<input type="hidden" class="for-pop-end" value="'+val.end+'"/>' +
                        '<input type="hidden" class="for-pop-desc" value="'+val.desc+'"/>' +
                        '<input type="hidden" class="for-pop-id id" id="event-id" value="'+val.id+'"/>' +
                        '<input type="hidden" class="for-pop-instructor" value="'+val.instructor+'"/>' +
                        '</a>';

                    $('.event-catch-'+val.start_date).append(event1); // Static event for MonthView

                });

                var cellWidth = $('.event-table').width();
                var cellHeight = parseInt($('.event-table').height());
                //console.log(cellHeight);
                $('.fc-draggable').draggable({
                    grid: [ cellWidth, cellHeight ],
                    start: function(event, ui){
                        $(this).data('dragging', true);
                    },
                    stop: function(event, ui){
                        setTimeout(function(){
                            $(event.target).data('dragging', false); // Set dragging false
                        }, 1);
                    }
                });

                //$('.month-event-drop').sortable();
                //}
            }
        });
    }

    $('body').on('click','.next-month',function(){
        var cur_month = $('#current_month').val();
        var this_month = $('#this_month').val();
        //alert(cur_month);
        cur_month++;
        if(cur_month > 12){
            cur_month=1;
            //todo: include year in this accounting
        }

        var cur_time = parseInt($('#current_time').val());
        var this_time = parseInt($('#this_time').val());
        var dateTime;

        switch(cur_month){
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                dateTime = cur_time + 31*24*3600;
                break;
            case 4:
            case 6:
            case 9:
            case 11:
                dateTime = cur_time +  30*24*3600;
                break;
            case 2:
                dateTime = cur_time +  28*24*3600;
                break;
        }

        monthView(cur_month,dateTime);

        $('#current_month').val(cur_month);
        $('#this_time').val(this_time);
        $('#this_month').val(this_month);
    });

    $('body').on('click','.prev-month',function(){
        var cur_month = $('#current_month').val();
        cur_month--;
        if(cur_month < 1){
            cur_month=12;
            //todo: include year in this accounting
        }

        var cur_time = parseInt($('#current_time').val());
        var dateTime;

        switch(cur_month){
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                dateTime = cur_time - 31*24*3600;
                break;
            case 4:
            case 6:
            case 9:
            case 11:
                dateTime = cur_time -  30*24*3600;
                break;
            case 2:
                dateTime = cur_time -  28*24*3600;
                break;
        }

        monthView(cur_month,dateTime);

        $('#current_month').val(cur_month);
    });

    $('.activities').click(function(){
        var currentView = $('#currentView').val();
        if(currentView == "weekView"){
            var cur_day = $('#week_day').val();
            cur_day = parseInt(cur_day);
            changeView(cur_day, 'weekView');
        }
        if(currentView == "monthView"){
            var cur_month = parseInt($('#current_month').val());
            var cur_time = parseInt($('#current_time').val());
            monthView(cur_month,cur_time);
        }
        if(currentView == "dayView"){
            var cur_daytime = parseInt($('#current_daytime').val());
            changeView(cur_daytime, 'dayView');
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

    $('#edit-start-course').datetimepicker({
        startDate: '<?php echo date("Y-m-d")?>',
        startView: 2,
        minView: 2,
        maxView: 2,
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });

    $('#edit-end-course').datetimepicker({
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
        var color = $(this).find(':selected').attr('data-color');
        var colorData = color.split('#');
        var cid = 'cid_' + colorData[1];
        //console.log(cid);
        $('#' + cid).click();
        if(colorData[1] == undefined){
            $('.color-box').html('&nbsp;');
            $('.color-box').removeClass('color-box-selected');
            $('#backgroundColor').val('');
        }
    });

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

    $('.container').on('click', '.fc-event-cell', function(){
        $('#myModal').modal({backdrop:'static',keyboard:false});
        var st_date = $(this).data('date');
        $('#start-date').val(st_date);
    });

    $('.container').on('click', '.fc-draggable', function(){
        if($(this).data('dragging')) return; // While Dragging is true don't act as click
        $('#editModal').modal({backdrop:'static',keyboard:false});

        var eventID = $(this).children('#event-id').val();
        $.ajax({
            url:'events_manager.php',
            type:'post',
            data:{eventID:eventID, action:'SELECT_AN_EVENT', courseid:courseID},
            dataType:'json',
            success:function(e){
                $('#remove-link').attr('data-event-id', e.id);
                $('#edit-title').val(e.title);
                $('#edit-start-date').val(e.date);
                $('#edit-description').val(e.description);
                $('#edit-instructor').val(e.instructor);
                $('#edit_period_from').val(e.period_from);
                $('#edit_period_to').val(e.period_to);
                $('#edit-select-activity').val(e.type);
                $('#eventID').val(e.id);
            }
        });
        $('#myModal').hide();
    });

    $('.fc-draggable').draggable({
        revert: "invalid",
        start: function(event, ui){
            $(this).data('dragging', true);
        },
        stop: function(event, ui){
            setTimeout(function(){
                $(event.target).data('dragging', false); // Set dragging false
            }, 1);
        }
    });

    $('.container').on('mouseenter', '.fc-draggable', function(e){
        //console.log('Hello');
        //$('.fc-draggable').popover('hide');
        //$('.popover').hide();

        var title = $(this).children('.for-pop-title').val();
        var start = $(this).children('.for-pop-start').val();
        var end = $(this).children('.for-pop-end').val();
        var desc = $(this).children('.for-pop-desc').val();
        if(desc.length > 25) {
            desc = desc.substring(0,24)+"...";
        }
        var instructor = $(this).children('.for-pop-instructor').val();
        //console.log(start);
        $(this).popover({
            html: true,
            placement: 'auto',
            title:function(){
                $(".pop-title").text(title);
                return $("#event-head").html();
            },
            content: function() {
                $(".pop-start").text(start);
                $(".pop-end").text(end);
                $(".pop-desc").text(desc);
                $(".pop-instructor").text(instructor);
                return $("#event-summery").html();
            },
            container:'body'
        });
        $(this).popover('show');
    });

//    $('.fc-h-event').qtip({
//        content: 'My content'
//    });

    $('.container').on('mouseleave', '.fc-draggable',function(){
        $(this).popover('hide');
    });

    $('.month-event-drop').droppable();

    function eventValidation(){
        var title = $('#title').val();
        var start_date = $('#start-date').val();
        var period_from = $('#period_from').val();
        var period_to = $('#period_to').val();
        var activity = $('#select-activity').val();
        var course_start = $('#course_start').val();
        var course_end = $('#course_end').val();
        var start_date_sec = Date.parse(start_date)/1000;
        //console.log(start_date_sec +" < "+ course_start+" || "+start_date_sec+" > "+course_end);

        var err = "";
        if(title == ''){
            err = "Title is required!";
        }
        else if(period_from > period_to){
            err = "Period To should be greater or equal Period From!";
        }
        else if(start_date == ''){
            err = "Date is required!";
        }
        else if(activity == ''){
            err = "Select Activity Type";
        }
        else if(start_date_sec < course_start || start_date_sec > course_end){
            err = "Activity should be within course duration!";
        }

        return err;
    }

    function eventEditValidation(){
        var title = $('#edit-title').val();
        var start_date = $('#edit-start-date').val();
        var period_from = $('#edit_period_from').val();
        var period_to = $('#edit_period_to').val();
        var activity = $('#edit-select-activity').val();
        var course_start = $('#edit_course_start').val();
        var course_end = $('#edit_course_end').val();
        var start_date_sec = Date.parse(start_date)/1000;
        //console.log(start_date_sec +" < "+ course_start+" || "+start_date_sec+" > "+course_end);

        var err = "";
        if(title == ''){
            err = "Title is required!";
        }
        else if(period_from > period_to){
            err = "Period To should be greater or equal Period From!";
        }
        else if(start_date == ''){
            err = "Date is required!";
        }
        else if(activity == ''){
            err = "Select Activity Type";
        }
        else if(start_date_sec < course_start || start_date_sec > course_end){
            err = "Activity should be within course duration!";
        }

        return err;
    }

    $('.create-event').click(function(){
        var formData = $('#eventForm').serialize();
        //alert(formData);
        var valid = eventValidation();
        if(valid != ""){
            alert(valid);
        }
        else{
            $.ajax({
                url:'events_manager.php',
                type: 'post',
                data: formData,
                success: function(e){
                    if(e == 1){
                        //alert('Success');
                        $.bootstrapGrowl(" Event is created Successfully! ", {
                            type: 'success',
                            width: 350
                        });
                        $("#reset").click();
                        $('#myModal').modal('hide');
                        var view = $('#currentView').val();
                        if(view == 'weekView'){
                            var cur_day = $('#week_day').val();
                            cur_day = parseInt(cur_day);
                            changeView(cur_day, 'weekView');
                        }
                        if(view == 'dayView'){
                            var cur_daytime = parseInt($('#current_daytime').val());
                            changeView(cur_daytime, 'dayView');
                        }
                    }
                    else if(e == 2){
                        //alert('Warning: There is a conflicted event!');
                        $.bootstrapGrowl(" There is a conflicted event! ", {
                            type: 'danger',
                            width: 350
                        });
                    }
                    else if(e == 3){
                        //alert('Warning: Selected time is not allowed!');
                        $.bootstrapGrowl(" Selected time is not allowed! ", {
                            type: 'danger',
                            width: 350
                        });
                    }
                    else{
                        //alert('Error: Event Creation is failed!');
                        $.bootstrapGrowl(" Event Update is failed! ", {
                            type: 'danger',
                            width: 350
                        });
                    }
                }
            });
        }

    });

    $('.update-event').click(function(){
        var formData = $('#eventEditForm').serialize();
        //alert(formData);
        var valid = eventEditValidation();
        if(valid != ""){
            alert(valid);
        }
        else{
            $.ajax({
                url:'events_manager.php',
                type: 'post',
                data: formData,
                success: function(e){
                    if(e == 1){
                        //alert('Success');
                        $.bootstrapGrowl(" Event is updated Successfully! ", {
                            type: 'success',
                            width: 350
                        });
                        $('#editModal').modal('hide');
//                        setTimeout(function () {
//                            location.reload();
//                        }, 1000);
                    }
                    else if(e == 2){
                        //alert('Warning: There is a conflicted event!');
                        $.bootstrapGrowl(" There is a conflicted event! ", {
                            type: 'warning',
                            width: 350
                        });
                    }
                    else if(e == 3){
                        //alert('Warning: Selected time is not allowed!');
                        $.bootstrapGrowl(" Selected time is not allowed! ", {
                            type: 'warning',
                            width: 350
                        });
                    }
                    else{
                        //alert('Error: Event update is failed!');
                        $.bootstrapGrowl(" Event update is failed! ", {
                            type: 'warning',
                            width: 350
                        });
                    }

                    var view = $('#currentView').val();
                    if(view == 'weekView'){
                        var cur_day = $('#week_day').val();
                        cur_day = parseInt(cur_day);
                        changeView(cur_day, 'weekView');
                    }
                    if(view == 'dayView'){
                        var cur_daytime = parseInt($('#current_daytime').val());
                        changeView(cur_daytime, 'dayView');
                    }

                }
            });
        }

    });

    $('#remove-link').click(function () {
        var eid = $(this).attr('data-event-id');

        $.post("events_manager.php",
            { eventID: eid, courseid:courseID, action: 'REMOVE_THIS_EVENT'},
            function (eventJSON) {
            })
            .always(function (e) {
                $('#editModal').modal('hide');
                if(e == 1){
                    //alert('Success');
                    $.bootstrapGrowl(" Event is removed Successfully! ", {
                        type: 'success',
                        width: 350
                    });
                    $("#reset").click();
                    var view = $('#currentView').val();
                    if(view == 'weekView'){
                        var cur_day = $('#week_day').val();
                        cur_day = parseInt(cur_day);
                        changeView(cur_day, 'weekView');
                    }
                    if(view == 'dayView'){
                        var cur_daytime = parseInt($('#current_daytime').val());
                        changeView(cur_daytime, 'dayView');
                    }
                }
                else{
                    //alert('Error: Event Delete is failed!');
                    $.bootstrapGrowl(" Event Delete is failed! ", {
                        type: 'danger',
                        width: 350
                    });
                }
            });
    });

    var view = $('#currentView').val();
    if(view == 'weekView'){
        var cur_day = $('#week_day').val();
        cur_day = parseInt(cur_day);
        changeView(cur_day, 'weekView');
    }
    if(view == 'dayView'){
        var cur_daytime = parseInt($('#current_daytime').val());
        changeView(cur_daytime, 'dayView');
    }

});