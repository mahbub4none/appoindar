<?php
include('class_autoload.php');

date_default_timezone_set('Asia/Dhaka');
$my_timezone = date_default_timezone_get();
$dateTimeZone = new DateTimeZone($my_timezone);
$dateTime = new DateTime("now", $dateTimeZone);

$timeOffset = $dateTimeZone->getOffset($dateTime);

$courseID = $_POST['courseID'];
$activity_types = $_POST['activities'];
$date = (isset($_POST['date']))?$_POST['date']:time();

//Load view
if (isset($_POST['view'])){
    $view = $_POST['view'];

    $loadCal = new calendarClass($date, $courseID);

    if($view == "weekView"){
        echo $loadCal->weekView();
    }
    if($view == "monthView"){
        echo $loadCal->monthView();
    }
    if($view == "dayView"){
        echo $loadCal->dayView();
    }
    exit();
}


$hello = new calendarClass($date, $courseID);
$course = $hello->loadCourse($courseID);
$course_start = intval($course['startdate']);
$course_end = intval($course['enddate']);

//$hello->setWeekStartDay();// Setting week day to Monday
$activity_array = $hello->activity_array;
$activities = (!empty($activity_types))?implode("','",$activity_types):'';
//Load Events
$events_array = $hello->loadEvents($courseID, $activities);
//var_dump($events_array);
$days = $hello->days;

//$times = array('12:00', '01:00', '01:30', '02:45', '04:00', '05:00', '06:30', '07:45', '09:00', '10:30', '11:45', '12:15', '13:00', '14:15', '13:45', '15:00', '16:15', '17:45', '18:15', '19:30', '20:15', '21:30', '22:15', '23:15');
$periods = $hello->periods;

$events_js = array();
$i=1;
$monthEvents = array();

// Load Month view events
if(isset($_POST['month'])){
    //echo $_POST['month'];
    $month = $_POST['month'];
    //echo "$month > ".date('m',$course_end);
    $c_m_end = intval(date('m',$course_end));// Month of course end
    $c_m_start = intval(date('m',$course_start));// Month of course start
    if($month > $c_m_end){$month = $c_m_end;}
    elseif($month < $c_m_start){$month = $c_m_start;}
    //echo $month;
    foreach($events_array as $events){
        $count_periods = count($periods);


        $period_start_time = ($periods[$events['period_start']]['start']);
        $period_end_time = ($periods[$events['period_end']]['end']);

        $events_start = $events['start_date']."T".$period_start_time.":00Z";
        $events_end = $events['end_date']."T".$period_end_time.":00Z";

        $start = strtotime($events_start)-($timeOffset);
        $start_day = date('D', $start);
        $count = 0;
        $start_day_index = '';
        foreach($days as $day){
            if($day == $start_day){
                $start_day_index = $count;
                break;
            }
            $count++;
        }

        $start_time = date('H:i', $start);
        $count = 1;
        $start_time_index = '';
        foreach($periods as $period){
            if($period['start'] == $start_time){
                $start_time_index = $count;
                break;
            }
            $count++;
        }

        $end = strtotime($events_end)-($timeOffset);
        $end_day = date('D', $end);
        $count = 0;
        $end_day_index = '';
        foreach($days as $day){
            if($day == $end_day){
                $end_day_index = $count;
                break;
            }
            $count++;
        }

        $end_time = date('H:i', $end);
        $count = 1;
        $end_time_index = '';
        foreach($periods as $period){
            if($period['end'] == $end_time){
                $end_time_index = $count;
                break;
            }
            $count++;
        }

        $start_date = date('d',$start);
        $start_month = date('m', $start);
        $id = $events['id'];
        if($month == $start_month){
            $monthEvents[$id]['start_date'] = $start_date;
            $monthEvents[$id]['title'] = $events['title'];
            $monthEvents[$id]['start_time'] = $start_time;
            $monthEvents[$id]['end_time'] = $end_time;

            $monthEvents[$id]['start'] = date('d-m-Y H:i A', $start);
            $monthEvents[$id]['end'] = date('d-m-Y H:i A', $end);
            $monthEvents[$id]['desc'] = $events['description'];
            $monthEvents[$id]['id'] = $events['id'];
            $monthEvents[$id]['instructor'] = $events['instructor'];

            $bgcolor = "#999";
            foreach($activity_array as $activity){
                if($activity['id'] == $events['activity_type']){
                    $bgcolor = $activity['color'];
                }
            }

            $monthEvents[$id]['bgcolor'] = $bgcolor;

        }
    }
    echo json_encode($monthEvents);
}
elseif(isset($_POST['day'])){
    //echo 123;
    $date = $_POST['date'];//echo '<br>';
    //$weekAllDays = $hello->weekFullDates($date);
    //var_dump($weekAllDays);
    //$weekFirstDate = $weekAllDays[0];
    //$weeklastDate = $weekAllDays[6];
    //echo $date." ";
    if($date > $course_end){$date = $course_end;}
    elseif($date < $course_start){$date = $course_start;}
    //echo $date;
    foreach($events_array as $events){
        $count_periods = count($periods);


        $period_start_time = ($periods[$events['period_start']]['start']);
        $period_end_time = ($periods[$events['period_end']]['end']);

        $events_start = $events['start_date']."T".$period_start_time.":00Z";
        $events_end = $events['end_date']."T".$period_end_time.":00Z";

        $start = strtotime($events_start)-($timeOffset);
        $end = strtotime($events_end)-($timeOffset);

        $today_start = strtotime(date('Y-m-d',$date)."T00:00:00Z");
        $today_end = strtotime(date('Y-m-d',$date)."T23:59:59Z");

        if($end < $today_start || $start > $today_end){
            continue;
        }
        //        $start_day = date('D', $start);
        //        $count = 0;
        //        $start_day_index = '';
        //        foreach($days as $day){
        //            if($day == $start_day){
        //                $start_day_index = $count;
        //                break;
        //            }
        //            $count++;
        //        }

        $start_time = date('H:i', $start);
        $count = 0;
        $start_time_index = '';
        foreach($periods as $period){
            if($period['start'] == $start_time){
                $start_time_index = $count;
                break;
            }
            $count++;
        }


        //        $end = strtotime($events_end)-($timeOffset);
        //        $end_day = date('D', $end);
        //        $count = 0;
        //        $end_day_index = '';
        //        foreach($days as $day){
        //            if($day == $end_day){
        //                $end_day_index = $count;
        //                break;
        //            }
        //            $count++;
        //        }

        $end_time = date('H:i', $end);
        $count = 0;
        $end_time_index = '';
        foreach($periods as $period){
            if($period['end'] == $end_time){
                $end_time_index = $count;
                break;
            }
            $count++;
        }
        //
        //        $columnWidth = (100)/($count_periods+1); //10
        //
        //        $left = ((((100)-$columnWidth)/$count_periods) * $start_time_index);
        //        $right = ((((100)-$columnWidth)/$count_periods) * ($count_periods - $end_time_index));
        //        $width = ($end_time_index - $start_time_index+1)*$columnWidth;
        $top = 60 * $start_time_index;
        $height = $width = ($end_time_index - $start_time_index+1)*60;;
        //$bottom = -($top+60);

        $bgcolor = "#999";
        foreach($activity_array as $activity){
            if($activity['id'] == $events['activity_type']){
                $bgcolor = $activity['color'];
            }
        }

        $ret = ''?>
        <a class="fc-time-grid-event fc-v-event fc-event fc-start fc-end fc-draggable fc-resizable"
           style="background-color: <?=$bgcolor?>; top: <?=$top?>px; height: <?=$height?>px; z-index: 1; width: 100%;" data-date="<?=$events['start_date']?>">
            <div class="fc-content">
                <div data-full="10:30 AM - 11:30 PM" data-start="10:30" class="fc-time"><span><?=$start_time?> - <?=$end_time?></span></div>
                <div class="fc-title"><?=$events['title']?></div>
            </div>
            <div class="fc-bg"></div>
            <div class="fc-resizer fc-end-resizer"></div>
            <input type="hidden" class="for-pop-title" value="<?=$events['title']?>"/>
            <input type="hidden" class="for-pop-start" value="<?=date('d-m-Y H:i A', $start)?>"/>
            <input type="hidden" class="for-pop-end" value="<?=date('d-m-Y H:i A', $end)?>"/>
            <input type="hidden" class="for-pop-desc" value="<?=$events['description']?>"/>
            <input type="hidden" class="for-pop-id id" id="event-id" value="<?=$events['id']?>"/>
            <input type="hidden" class="for-pop-instructor" value="<?=$events['instructor']?>"/>
        </a>
        <?php
        echo $ret;

    }

}
else{
    $now = $_POST['date'];//echo '<br>';
    if($now > $course_end){$now = $course_end;}
    elseif($now < $course_start){$now = $course_start;}
    $now_date = date('Y-m-d 00:00:00',$now);
    $date = strtotime($now_date);
    $weekAllDays = $hello->weekFullDates($date, $days);
    //var_dump($weekAllDays);
    $weekFirstDate = $weekAllDays[0];
    $weeklastDate = $weekAllDays[6] + 86399; // 86399 seconds added for 23:59 that is last limit of the day
    //echo date('Y-m-d H:i',$weeklastDate);
    foreach($events_array as $events){
        //count periods
        $count_periods = count($periods);
        //event start from
        $period_from = $events['period_start'];
        //event end to
        $period_to = $events['period_end'];

        //event start time
        $period_start_time = ($periods[$period_from]['start']);
        //event end time
        $period_end_time = ($periods[$period_to]['end']);


        $events_start = $events['start_date']."T".$period_start_time.":00Z";
        $events_end = $events['end_date']."T".$period_end_time.":00Z";

        $start = strtotime($events_start)-($timeOffset);
        $end = strtotime($events_end)-($timeOffset);

        if($start < $weekFirstDate || $end > $weeklastDate){
            //echo $events['title'].date('Y-m-d H:i D',$start)."<br>";
            continue;
        }

        $start_day = date('D', $start);
        $start_time = date('H:i', $start);
        //echo $start_day;die;
        //var_dump($days);die;
        $count = 0;
        $start_day_index = 0;
        foreach($days as $day){
            if($day == $start_day){
                $start_day_index = $count;
                break;
            }
            $count++;
        }


        $count = 1;
        $start_time_index = '';
        foreach($periods as $period){
            if($period['start'] == $start_time){
                $start_time_index = $count;
                break;
            }
            $count++;
        }


        $end = strtotime($events_end)-($timeOffset);
        $end_day = date('D', $end);
        $count = 0;
        $end_day_index = 0;
        foreach($days as $day){
            if($day == $end_day){
                $end_day_index = $count;
                break;
            }
            $count++;
        }

        $end_time = date('H:i', $end);
        $count = 1;
        $end_time_index = 0;
        foreach($periods as $period){
            if($period['end'] == $end_time){
                $end_time_index = $count;
                break;
            }
            $count++;
        }

        $columnWidth = (100)/($count_periods+1); //10

        $left = ((((100)-$columnWidth)/$count_periods) * $period_from);
        $right = ((((100)-$columnWidth)/$count_periods) * ($count_periods - $period_to));

        $width = ($end_time_index - $period_from+1)*$columnWidth;

        $top = 60 * $start_day_index;
        $bottom = -($top+60);

        $bgcolor = "#999";
        foreach($activity_array as $activity){
            if($activity['id'] == $events['activity_type']){
                $bgcolor = $activity['color'];
            }
        }
        $title = $events['title'];
        if(strlen($title) > 15) {
            $title = substr($title,0,14)."...";
        }

        $ret = ''?>
        <div style="background-color: <?=$bgcolor?>; top: <?=$top?>; height: 60px; z-index: 1; left: <?=$left?>%; width: <?=$width?>%; position: inherit"
             class="fc-time-grid-event fc-v-event fc-event fc-start fc-end fc-draggable fc-resizable"
             data-original-title="" title="">
            <div class="fc-content">
                <div data-full="" data-start=""
                     class="fc-time"><span><?=$start_time." - ".$end_time?></span></div>
                <div class="fc-title"><?=$title?></div>
            </div>
            <input type="hidden" class="for-pop-title" value="<?=$events['title']?>"/>
            <input type="hidden" class="for-pop-start" value="<?=date('d-m-Y H:i A', $start)?>"/>
            <input type="hidden" class="for-pop-end" value="<?=date('d-m-Y H:i A', $end)?>"/>
            <input type="hidden" class="for-pop-desc" value="<?=$events['description']?>"/>
            <input type="hidden" class="for-pop-id id" id="event-id" value="<?=$events['id']?>"/>
            <input type="hidden" class="for-pop-instructor" value="<?=$events['instructor']?>"/>
        </div>
        <?php
        echo $ret;

    }

}
