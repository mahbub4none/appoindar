<?php
//include('calendarClass.php');
include('class_autoload.php');
//$date = time();
//$hello = new appoindar\calendar();

$hello = new calSqlClass();

$courseid = $_POST['courseid'];
//$hello->initiate($date,$courseid);
$con = $hello->con();

if(isset($_POST['action']) && $_POST['action'] == 'CREATE_ACTIVITY'){
    $title = addslashes($_POST['title']);
    $date = $_POST['start-date'];
    $period_from = $_POST['period_from'];
    $period_to = $_POST['period_to'];
    $repeat_type = $_POST['repeat_type'];
    $description = addslashes($_POST['description']);
    $activitytype = $_POST['activitytype'];
    $instructor = $_POST['instructor'];
    $courseid = $_POST['courseid'];

    $opt = $hello->checkConflict($courseid);

    $rows = 0;
    if($opt['conflict'] == 1){
        $rows = $hello->checkExistingRecord($courseid, $date, $period_from, $period_to);
    }

    if($rows > 0){
        echo 2;
    }
    else{
        $e = $hello->weekendStatus($courseid, $period_from);
        $sat_working = $e['saturday_working'];
        $sat_start = $e['saturday_start_period'];
        $sat_end = $e['saturday_end_period'];

        $sun_working = $e['sunday_working'];
        $sun_start = $e['sunday_start_period'];
        $sun_end = $e['sunday_end_period'];

        $course = $hello->loadCourse($courseid);
        $end = $course['enddate'];
        $date_str = strtotime($date);

        if($repeat_type == 'daily'){
            for($i=$date_str; $i<=$end; $i=strtotime('+1 day',$i)){
                $day = date('Y-m-d',$i);
                $day_str = date("D",$i);
                if(($sat_working == 0 && $day_str == "Sat")||($sun_working == 0 && $day_str == "Sun")){continue;}
                elseif(($sat_working == 1 && $day_str == "Sat") && ($period_from < $sat_start || $period_to > $sat_end)){continue;}
                elseif(($sun_working == 1 && $day_str == "Sun") && ($period_from < $sun_start || $period_to > $sun_end)){continue;}
                else{
                    $result = $hello->eventEntry($courseid, $day, $period_from,  $period_to, $title, $description, $activitytype, $instructor);
                }

            }
            echo 1;
        }
        elseif($repeat_type == 'weekly'){
            for($i=$date_str; $i<=$end; $i=strtotime('+1 week',$i)){
                $day = date('Y-m-d',$i);
                $day_str = date("D",$i);
                if(($sat_working == 0 && $day_str == "Sat")||($sun_working == 0 && $day_str == "Sun")){continue;}
                elseif(($sat_working == 1 && $day_str == "Sat") && ($period_from < $sat_start || $period_to > $sat_end)){continue;}
                elseif(($sun_working == 1 && $day_str == "Sun") && ($period_from < $sun_start || $period_to > $sun_end)){continue;}
                else{
                    $result = $hello->eventEntry($courseid, $day, $period_from,  $period_to, $title, $description, $activitytype, $instructor);
                }
            }
            echo 1;
        }
        elseif($repeat_type == 'monthly'){
            for($i=$date_str; $i<=$end; $i=strtotime('+1 month',$i)){
                $day = date('Y-m-d',$i);
                $day_str = date("D",$i);
                if(($sat_working == 0 && $day_str == "Sat")||($sun_working == 0 && $day_str == "Sun")){continue;}
                elseif(($sat_working == 1 && $day_str == "Sat") && ($period_from < $sat_start || $period_to > $sat_end)){continue;}
                elseif(($sun_working == 1 && $day_str == "Sun") && ($period_from < $sun_start || $period_to > $sun_end)){continue;}
                else{
                    $result = $hello->eventEntry($courseid, $day, $period_from,  $period_to, $title, $description, $activitytype, $instructor);
                }
            }
            echo 1;
        }
        else{
            $day_str = date("D",$date_str);
            $day = date('Y-m-d',$date_str);
            if(($sat_working == 0 && $day_str == "Sat")||($sun_working == 0 && $day_str == "Sun")){echo 3;exit;}
            elseif(($sat_working == 1 && $day_str == "Sat") && ($period_from < $sat_start || $period_to > $sat_end)){echo 3;exit;}
            elseif(($sun_working == 1 && $day_str == "Sun") && ($period_from < $sun_start || $period_to > $sun_end)){echo 3;exit;}
            else{
                $result = $hello->eventEntry($courseid, $day, $period_from,  $period_to, $title, $description, $activitytype, $instructor);
            }
            echo 1;
        }

    }
}

if(isset($_POST['action']) && $_POST['action'] == 'REMOVE_THIS_EVENT'){
    $eventID = $_POST['eventID'];
    $result = $hello->eventRemove($eventID);
    if($result)
        echo 1;
    else
        echo "Error!";
}

if(isset($_POST['action']) && $_POST['action'] == 'EDIT_ACTIVITY'){
    $title = ($_POST['title']);
    $date = $_POST['start-date'];
    $period_from = $_POST['period_from'];
    $period_to = $_POST['period_to'];
    $description = ($_POST['description']);
    $activitytype = $_POST['activitytype'];
    $instructor = $_POST['instructor'];
    $courseid = $_POST['courseid'];
    $eventID = $_POST['eventID'];

    $e = $hello->weekendStatus($courseid, $period_from);
    $sat_working = $e['saturday_working'];
    $sat_start = $e['saturday_start_period'];
    $sat_end = $e['saturday_end_period'];

    $sun_working = $e['sunday_working'];
    $sun_start = $e['sunday_start_period'];
    $sun_end = $e['sunday_end_period'];

    $date_str = strtotime($date);
    $day_str = date("D",$date_str);


    $opt = $hello->checkConflict($courseid);

    $rows = 0;
    if($opt['conflict'] == 1){
        $rows = $hello->checkExistingRecord($courseid, $date, $period_from, $period_to);
    }

    if($rows > 1){
        echo 2;
    }
    elseif($rows == 1){
        $event = $hello->existingRecordData($courseid, $date, $period_from, $period_to);
        if($event['id'] == $eventID){
            if(($sat_working == 0 && $day_str == "Sat")||($sun_working == 0 && $day_str == "Sun")){echo 3;exit;}
            elseif(($sat_working == 1 && $day_str == "Sat") && ($period_from < $sat_start || $period_to > $sat_end)){echo 3;exit;}
            elseif(($sun_working == 1 && $day_str == "Sun") && ($period_from < $sun_start || $period_to > $sun_end)){echo 3;exit;}
            else{
                $result = $hello->eventUpdate($eventID, $date, $period_from,  $period_to, $title, $description, $activitytype, $instructor);
                echo 1;
            }
        }
        else{
            echo 2;
        }
    }
    else{
        if(($sat_working == 0 && $day_str == "Sat")||($sun_working == 0 && $day_str == "Sun")){echo 3;exit;}
        elseif(($sat_working == 1 && $day_str == "Sat") && ($period_from < $sat_start || $period_to > $sat_end)){echo 3;exit;}
        elseif(($sun_working == 1 && $day_str == "Sun") && ($period_from < $sun_start || $period_to > $sun_end)){echo 3;exit;}
        else{
            $result = $hello->eventUpdate($eventID, $date, $period_from,  $period_to, $title, $description, $activitytype, $instructor);
            echo 1;
        }
    }
}

if(isset($_POST['action']) && $_POST['action'] == 'EVENT_UPDATE_ON_DROP'){
    $date = $_POST['date'];
    $period_from = $_POST['period_from'];
    $eventID = $_POST['id'];

    if(empty($date)){
        echo "date is empty";
        die;
    }

    $ev = $hello->eventSelect($eventID);
    $courseid = $ev['courseid'];
    $period_to_old = $ev['period_to'];
    $period_from_old = $ev['period_from'];
    $eW = $period_to_old - $period_from_old;
    $period_to = $period_from + $eW;

    $title = $ev['title'];
    $description = $ev['description'];
    $activitytype = $ev['type'];
    $instructor = $ev['instructor'];

    $e = $hello->weekendStatus($courseid, $period_from);
    $sat_working = $e['saturday_working'];
    $sat_start = $e['saturday_start_period'];
    $sat_end = $e['saturday_end_period'];

    $sun_working = $e['sunday_working'];
    $sun_start = $e['sunday_start_period'];
    $sun_end = $e['sunday_end_period'];

    $date_str = strtotime($date);
    $day_str = date("D",$date_str);


    $opt = $hello->checkConflict($courseid);
    $rows = 0;
    if($opt['conflict'] == 1){
        $rows = $hello->checkExistingRecord($courseid, $date, $period_from, $period_to);
    }

    if($rows > 1){
        echo 2;
    }
    elseif($rows == 1){
        $event = $hello->existingRecordData($courseid, $date, $period_from, $period_to);
        if($event['id'] == $eventID){
            if(($sat_working == 0 && $day_str == "Sat")||($sun_working == 0 && $day_str == "Sun")){echo 3;exit;}
            elseif(($sat_working == 1 && $day_str == "Sat") && ($period_from < $sat_start || $period_to > $sat_end)){echo 3;exit;}
            elseif(($sun_working == 1 && $day_str == "Sun") && ($period_from < $sun_start || $period_to > $sun_end)){echo 3;exit;}
            else{
                $result = $hello->eventUpdate($eventID, $date, $period_from,  $period_to, $title, $description, $activitytype, $instructor);
                echo 1;
            }
        }
        else{
            echo 2;
        }
    }
    else{
        if(($sat_working == 0 && $day_str == "Sat")||($sun_working == 0 && $day_str == "Sun")){echo 3;exit;}
        elseif(($sat_working == 1 && $day_str == "Sat") && ($period_from < $sat_start || $period_to > $sat_end)){echo 3;exit;}
        elseif(($sun_working == 1 && $day_str == "Sun") && ($period_from < $sun_start || $period_to > $sun_end)){echo 3;exit;}
        else{
            $result = $hello->eventUpdate($eventID, $date, $period_from,  $period_to, $title, $description, $activitytype, $instructor);
            echo 1;
        }
    }
}

if(isset($_POST['action']) && $_POST['action'] == 'SELECT_AN_EVENT'){
    $eventID = $_POST['eventID'];
    $event = $hello->eventSelect($eventID);
    echo json_encode($event);
}
