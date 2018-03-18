<?php
/*
 * This file working in ajax call
 * Getting data in post method
 * Sending calendar view with event data
 *
 * Don't change anything unless change DB fields name and ajax fields name
 * */
include("class_autoload.php");

$date = $_POST['date'];
$view = $_POST['view'];
$courseID = $_POST['courseID'];

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
