/**
 * Created by Personal on 4/5/16.
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
    });
    $('body').on('click','.fc-agendaWeek-button',function(){
        $('.dayView').hide();
        $('.monthView').hide();
        $('.weekView').show();

        $('#currentView').val('weekView');
    });
    $('body').on('click','.fc-agendaDay-button',function(){
        var cur_daytime = parseInt($('#current_daytime').val());
        dayView(cur_daytime);
        $('.weekView').hide();
        $('.monthView').hide();
        $('.dayView').show();

        $('#currentView').val('dayView');
    });

    function changeView(date, view){

        $.ajax({
            type: 'post',
            data:{date:date, view:view, courseID:courseID},
            url:"change_view.php",
            success: function(e){
                $('div.'+view).html(e);
                //$('#wait').hide();

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

    function weekView(date){
        changeView(date, 'weekView');
        //alert(columnWidth);
        var containerWidth = $('.container').width();
        //alert(containerWidth);
        var columnWidthPx = containerWidth/period_no;
        var checkedActivities = activityTypes();

        $('#wait').show();
        setTimeout(function(){
            $.ajax({
                type: 'post',
                data:{date:date, activities:checkedActivities, courseID:courseID},
                url:"events_array.php",
                success: function(e){
                    if(e == null){
                        $('.training_events').html('');
                    }
                    $('.training_events').html('');
                    $('.training_events').append(e);

                    $('.fc-draggable2').draggable({
                        grid: [ columnWidthPx, 60 ],
                        containment:'.container',
                        scroll: false,
                        start: function(event, ui){
                            $(this).data('dragging', true);
                        },
                        stop: function(event, ui){
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

    var d = new Date();
    var n = d.getTime();
    n = parseInt(n/1000);
    weekView(n);
    dayView(n);


    $('body').on('click','.next-week',function(){
        var cur_day = $('#week_day').val();
        var this_day = $('#this_day').val();
        cur_day = parseInt(cur_day) + (7*24*3600);
        weekView(cur_day);
        $('#week_day').val(cur_day);
        $('#this_day').val(this_day);
    });

    $('body').on('click','.prev-week',function(){
        var cur_day = $('#week_day').val();
        var this_day = $('#this_day').val();

        cur_day = parseInt(cur_day) - (7*24*3600);
        weekView(cur_day);
        $('#week_day').val(cur_day);
        $('#this_day').val(this_day);
    });


    function dayView(date){
        $('#wait').show();
        changeView(date, 'dayView');
        //alert(columnWidth);
        setTimeout(function(){
            var checkedActivities = activityTypes();

            $.ajax({
                type: 'post',
                data:{date:date, day:'day', activities:checkedActivities, courseID:courseID},
                url:"events_array.php",
                success: function(e){
                    if(e == null){
                        $('.day-event-container').html('');
                    }
                    $('.day-event-container').html('');
                    $('.day-event-container').append(e);

                    $('.fc-draggable2').draggable({
                        grid: [ 300, 60 ],
                        containment:'.container',
                        scroll: false,
                        start: function(event, ui){
                            $(this).data('dragging', true);
                        },
                        stop: function(event, ui){
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
        dayView(cur_daytime);
        $('#current_daytime').val(cur_daytime);
        $('#this_daytime').val(this_daytime);
    });

    $('body').on('click','.prev-day',function(){
        var cur_daytime = parseInt($('#current_daytime').val());
        var this_daytime = parseInt($('#this_daytime').val());
        cur_daytime = parseInt(cur_daytime) - (24*3600);
        dayView(cur_daytime);
        $('#current_daytime').val(cur_daytime);
        $('#this_daytime').val(this_daytime);
    });

    function monthView(month, dateTime){
        changeView(dateTime, 'monthView');
        var checkedActivities = activityTypes();

        $('#wait').show();
        setTimeout(function(){
            $.ajax({
                type: 'post',
                data: {month:month, activities:checkedActivities, courseID:courseID},
                url:"events_array.php",
                dataType:'JSON',
                success: function(e){
                    for(var i =1; i<=31; i++){
                        $('.event-catch-'+i).html('');
                    }
                    $.each(e, function(key, val){
                        var title = val.title;
                        if(title > 15) {
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
                            '<input type="hidden" class="for-pop-instructor" value="'+val.instructor+'"/>' +
                            '</a>';

                        $('.event-catch-'+val.start_date).append(event1); // Static event for MonthView

                    });

                    var cellWidth = $('.event-table').width();
                    var cellHeight = parseInt($('.event-table').height());
                    //console.log(cellHeight);
                    $('.fc-draggable2').draggable({
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
            $('#wait').hide();
        }, 1000);

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
            weekView(cur_day);
        }
        if(currentView == "monthView"){
            var cur_month = parseInt($('#current_month').val());
            var cur_time = parseInt($('#current_time').val());
            monthView(cur_month,cur_time);
        }
        if(currentView == "dayView"){
            var cur_daytime = parseInt($('#current_daytime').val());
            dayView(cur_daytime);
        }
    });


    $('.fc-draggable2').draggable({
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

    $('.container').on('mouseover', '.fc-draggable', function(e){
        //console.log('Hello');
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
});