<?php
/**
 * Project: Appoindar
 * Class: calSqlClass
 * Author: Muhammad Mahbubur Rahman
 * Email: mahbub.e.khuda@gmail.com
 * Description: This class is prepared to handle all DB operations regarding the project
 * Date: 2/04/2017
 */

include "databaseClass.php";
class calSqlClass extends DatabaseClass{
    public $con;


    public function con(){
        return mysqli_connect($this->hostname, $this->username, $this->password, $this->dbname, 3306);
    }

    protected function queryExecution($sql){
        $con = self::con();
        return mysqli_query($con,$sql);
    }

    public function checkConflict($courseid){
        //
        $sql = "SELECT conflict FROM `users` WHERE `id`='$courseid'";
        $opt = mysqli_fetch_assoc(self::queryExecution($sql));

        return $opt;
    }

    public function checkExistingRecord($courseid, $date, $period_from, $period_to){
        //
        $sql = "SELECT * FROM `timetable_entry` WHERE `courseid`='$courseid' AND `date`='$date' AND ((`period_from` <= $period_from AND `period_to` > $period_from) OR (`period_from` < $period_to AND `period_to` >= $period_to) OR (`period_from` >= $period_from AND `period_to` <= $period_to) OR (`period_from` = $period_to) OR (`period_to` = $period_from)) ";

        $result = self::queryExecution($sql);
        $rows = mysqli_num_rows($result);
        return $rows;
    }

    public function existingRecordData($courseid, $date, $period_from, $period_to){
        //
        $sql = "SELECT * FROM `timetable_entry` WHERE `courseid`='$courseid' AND `date`='$date' AND ((`period_from` <= $period_from AND `period_to` > $period_from) OR (`period_from` < $period_to AND `period_to` >= $period_to) OR (`period_from` >= $period_from AND `period_to` <= $period_to) OR (`period_from` = $period_to) OR (`period_to` = $period_from)) ";

        $result = self::queryExecution($sql);
        $event = mysqli_fetch_array($result);
        return $event;
    }

    public function weekendStatus($courseid, $period_from){
        //
        $sql = "SELECT * FROM `timetable_slot` WHERE courseid = '$courseid' AND period = '$period_from'";
        $resultSat = self::queryExecution($sql);
        $e = mysqli_fetch_array($resultSat);

        return $e;
    }

    public function eventEntry($courseid, $day, $period_from,  $period_to, $title, $description, $activitytype, $instructor){
        //
        $sql = "INSERT INTO `timetable_entry` (`courseid`, `date`, `period_from`, `period_to`, `title`, `description`, `type`, `instructor`) VALUES ('$courseid', '$day', '$period_from', '$period_to', '$title', '$description', '$activitytype', '$instructor');";
        //echo $sql;
        $result = self::queryExecution($sql);

        return $result;
    }

    public function eventUpdate($eventID, $date, $period_from,  $period_to, $title, $description, $activitytype, $instructor){
        //
        $title = addslashes($title);
        $description = addslashes($description);
        $sql = "UPDATE `timetable_entry` SET `date`='$date', `period_from`='$period_from', `period_to`='$period_to', `title`='$title', `description`='$description', `type`='$activitytype', `instructor`='$instructor' WHERE `id`='$eventID'";
        //echo $sql;
        $result = self::queryExecution($sql);


        return $result;
    }

    public function eventSelect($eventID){
        //
        $sql = "SELECT * FROM `timetable_entry` WHERE `id`='$eventID'";
        $eventResult = self::queryExecution($sql);
        $ev = mysqli_fetch_array($eventResult);

        return $ev;
    }

    public function eventRemove($eventID){
        //
        $sql = "DELETE FROM `timetable_entry` WHERE `id`='$eventID' ";

        $result = self::queryExecution($sql);


        return $result;
    }


    public function loadCourse($courseid){
        //
        $sql = "SELECT users.*, users.conflict FROM users WHERE users.id = '$courseid' ";

        $course_sql = self::queryExecution($sql);
        if(mysqli_num_rows($course_sql)<1){
            return 0;
        }
        $course = mysqli_fetch_assoc($course_sql);
        if(isset($course['coursetype']) && $course['coursetype'] == 'META'){
            $course['enddate'] = time()+(2*365*3600);
        }
        $this->course = $course;
        return $course;
    }

    public function loadPeriods($courseid){
        //

        $sql = "SELECT * FROM `timetable_slot` WHERE courseid = '".$courseid."' ";
        $time_slots = self::queryExecution($sql);

        $periods = array();
        $i = 1;
        while($period = mysqli_fetch_array($time_slots)){
            $periods[$i]['id'] = $period['id'];
            $periods[$i]['start_t'] = $period['start_time'];
            $periods[$i]['end_t'] = $period['end_time'];
            $periods[$i]['period'] = $period['display'];
            $periods[$i]['start'] = substr_replace($period['start_time'], ':', -2, 0);
            $periods[$i]['end'] = substr_replace($period['end_time'], ':', -2, 0);

            $i++;
        }
        return $periods;
    }

    public  function loadEvents($courseid, $activity_type){
        //

        $course = self::loadCourse($courseid);

        $sql = "SELECT * FROM `timetable_entry` WHERE courseid = '".$courseid."' AND type IN ('$activity_type') ";
        $time_slots = self::queryExecution($sql);

        $events = array();
        $i = 0;
        while($entry = mysqli_fetch_array($time_slots)){
            $date_str = strtotime($entry['date']);
            if($date_str < $course['startdate'] || $date_str > $course['enddate'])continue;
            $events[$i]['id'] = $entry['id'];
            $events[$i]['title'] = ($entry['title']);
            $events[$i]['description'] = ($entry['description']);
            $events[$i]['start_date'] = $entry['date'];
            $events[$i]['end_date'] = $entry['date'];
            $events[$i]['period_start'] = $entry['period_from'];
            $events[$i]['period_end'] = $entry['period_to'];
            $events[$i]['activity_type'] = $entry['type'];
            $events[$i]['instructor'] = $entry['instructor'];
            $i++;
        }

        return $events;
    }


    protected function loadActivities($courseid){
        //

        $sql = "SELECT * FROM `timetable_type` WHERE courseid = '".$courseid."' ";
        $time_slots = self::queryExecution($sql);

        $activities = array();
        $i = 0;
        while($entry = mysqli_fetch_array($time_slots)){
            $activities[$i]['sl'] = $entry['id'];
            $activities[$i]['id'] = $entry['activitytype'];
            $activities[$i]['name'] = $entry['activitytype'];
            $activities[$i]['slug'] = str_replace(' ','_',$entry['activitytype']);
            $activities[$i]['color'] = $entry['activitycolor'];

            $i++;
        }

        return $activities;
    }

    public function courseSettings($courseid, $start, $end, $conflict, $saturday, $saturday_start, $saturday_end, $sunday, $sunday_start, $sunday_end){

        $courseUpdate = "UPDATE `users` SET startdate='$start' WHERE id = '$courseid'";
        $course2Update = "UPDATE `users` SET enddate='$end', conflict='$conflict' WHERE id = '$courseid'";
        $slotUpdate = "UPDATE `timetable_slot` SET saturday_working='$saturday', saturday_start_period='$saturday_start', saturday_end_period='$saturday_end',sunday_working='$sunday', sunday_start_period='$sunday_start', sunday_end_period='$sunday_end' WHERE courseid='$courseid' ";

        self::queryExecution($courseUpdate);
        self::queryExecution($course2Update);
        self::queryExecution($slotUpdate);
    }

    public function timetableSlots($courseid){
        $slotQuery = "SELECT * FROM `timetable_slot` WHERE courseid = '$courseid'";
        $slot = mysqli_fetch_array(self::queryExecution($slotQuery));

        return $slot;
    }

    public function activitySetting($type, $color, $activity_id){
        $sql = "UPDATE `timetable_type` SET activitytype='$type', activitycolor='$color' WHERE id='".$activity_id."'";
        self::queryExecution($sql);
    }

    public function periodSetting($start, $end, $period_id){
        $sql = "UPDATE `timetable_slot` SET start_time='$start', end_time='$end' WHERE id='".$period_id."'";
        self::queryExecution($sql);
    }
} 