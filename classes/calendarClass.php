<?php
/**
 * Project: Appoindar
 * Class: calendar
 * Author: Muhammad Mahbubur Rahman
 * Email: mahbub.e.khuda@gmail.com
 * Description: This is the base class of the project
 * Date: 2/04/2017
 */
include 'calSqlClass.php';

class calendarClass Extends calSqlClass {
    public $date;
    public $month;
    public $year;
    public $day;

    public $days = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');

    public $times;

    // Variable to set period data
    public $periods;

    // Variable to set activity data
    public $activity_array = array(
        array(
            "id" => 1,
            "name" => "Outdoor Activity",
            "slug" => "outdoor",
            "color" => "#000",
        ),
        array(
            "id" => 2,
            "name" => "Classroom Activity",
            "slug" => "classroom",
            "color" => "#39f",
        ),
    );

    //Variable to set events data
    public $events;

    //Here we generate the first day of the month
    public $first_day;

    //This gets us the month name
    public $title;

    //Here we find out what day of the week the first day of the month falls on
    public $day_of_week;

    public $blank;

    //Variable to store total days in a month
    public $days_in_month;

    //Variable to set the calendar column width
    public $columnWidth;

    //Variable to set calendar basic information
    public $course;

    //Variable to handle Full Name of the calendar
    public $heading;

    /**
     * calendarClass constructor.
     * @param $date
     * @param $courseID
     */
    function __construct($date, $courseID){
        //date_default_timezone_set('America/Los_Angeles');

        $course = self::loadCourse($courseID);

        if($course == 0){
            echo "Invalid course ID has been supplied!";die;
        }

        $start = intval($course['startdate']);
        $end = intval($course['enddate']);
//echo gettype($start);
        if($date >= $start && $date <= $end){
            $this->date = $date;
        }
        elseif($date < $start){
            $this->date = $start;
        }
        else $this->date = $end;

        //$this->date = $date;

        //This puts the day, month, and year in seperate variables
        $this->day = date('d', $this->date) ;
        $this->month = date('m', $this->date) ;
        $this->year = date('Y', $this->date) ;

        //Here we generate the first day of the month
        $this->first_day = mktime(0,0,0,$this->month, 1, $this->year) ;

        //This gets us the month name
        $this->title = date('F', $this->first_day) ;

        //Here we find out what day of the week the first day of the month falls on
        $dayWeek = date('D', $this->first_day) ;
        $this->day_of_week = date('w', $this->first_day) ;

        //We then determine how many days are in the current month
        $this->days_in_month = cal_days_in_month(0, $this->month, $this->year) ;

        $this->periods = $this->loadPeriods($courseID);

        $this->activity_array = $this->loadActivities($courseID);

        //Week view column width
        $countPeriod = count($this->periods)+1;
        $this->columnWidth = (100)/$countPeriod;

        $this->heading = $course["fullname"];
        //Week view set week start day
        $this->setWeekStartDay(1);
    }

    /*
     * Function: initiate
     * param: $date, $courseID
     * Setting different default value to initiate the calendar
     *
    function initiate($date, $courseID){
        date_default_timezone_set('America/Los_Angeles');
        $this->date = $date;

        //This puts the day, month, and year in seperate variables
        $this->day = date('d', $date) ;
        $this->month = date('m', $date) ;
        $this->year = date('Y', $date) ;

        //Here we generate the first day of the month
        $this->first_day = mktime(0,0,0,$this->month, 1, $this->year) ;

        //This gets us the month name
        $this->title = date('F', $this->first_day) ;

        //Here we find out what day of the week the first day of the month falls on
        $dayWeek = date('D', $this->first_day) ;
        $this->day_of_week = date('w', $this->first_day) ;

        //We then determine how many days are in the current month
        $this->days_in_month = cal_days_in_month(0, $this->month, $this->year) ;

        $this->periods = $this->loadPeriods($courseID);

        $this->activity_array = $this->loadActivities($courseID);

        //Week view column width
        $countPeriod = count($this->periods)+1;
        $this->columnWidth = (100)/$countPeriod;

        //Week view set week start day
        $this->setWeekStartDay(1);
    }

    /*public function dbConnect(){
        $host = "localhost";
        $user = "root";
        $pass = "mysql";
        $db = "training_cal";

        return mysqli_connect($host, $user, $pass, $db, 3306);
    }*/

    public function setWeekStartDay($day=0){
        $days = $this->days;
        for($i=0; $i<$day; $i++){
            //array_push($days, $days[0]);
            //array_shift($days);

            $keys = array_keys($days);
            $val = $days[$keys[0]];
            unset($days[$keys[0]]);
            $days[$keys[0]] = $val;
        }
        //var_dump($days);
        $this->days = $days;

    }

    public function monthView(){
        $keys = array_keys($this->days);
        $dayKey = $keys[0];
        $day_of_week = ($this->day_of_week == 0) ? 7 : $this->day_of_week;
        $this->blank = $day_of_week - $dayKey;

        $retval = ''?>
        <div class="fc fc-ltr fc-unthemed">
            <div class="fc-toolbar">
                <div class="fc-left">
                    <div class="fc-button-group">
                        <button class="fc-prev-button prev-month fc-button fc-state-default fc-corner-left" type="button">
                            <span class="fc-icon fc-icon-left-single-arrow"></span></button>
                        <button class="fc-next-button next-month fc-button fc-state-default fc-corner-right" type="button">
                            <span class="fc-icon fc-icon-right-single-arrow"></span></button>
                    </div>
                    <input type="hidden" id="current_month" value="<?=$this->month?>">
                    <input type="hidden" id="this_month" value="<?=$this->month?>">
                    <input type="hidden" id="current_time" value="<?=$this->date?>">
                    <input type="hidden" id="this_time" value="<?=$this->date?>">
                    <!--button class="fc-today-button fc-button fc-state-default fc-corner-left fc-corner-right fc-state-disabled" type="button" disabled="disabled">today</button-->
                </div>
                <div class="fc-right">
                    <div class="fc-button-group">
                        <button class="fc-agendaWeek-button fc-button fc-state-default fc-corner-left" type="button">week</button>
                        <button class="fc-month-button fc-button fc-state-active fc-state-default" type="button">month</button>
                        <button class="fc-agendaDay-button fc-button fc-state-default fc-corner-right" type="button">day</button>
                    </div>
                </div>
                <div class="fc-center"><h2><?=$this->heading?> - Month <?=self::countMonth()?> (<?=$this->title." ".$this->year?>)</h2></div>
                <div class="fc-clear"></div>
            </div>
            <div class="fc-view-container" style="">
                <div class="fc-view fc-month-view fc-basic-view" style="">
                    <table>
                        <thead class="fc-head">
                        <tr>
                            <td class="fc-head-container fc-widget-header" colspan="">
                                <div class="fc-row fc-widget-header">
                                    <table>
                                        <thead>
                                        <tr>
                                            <?php foreach($this->days as $day):?>
                                            <th class="fc-day-header fc-widget-header view-head"><?=$day?></th>
                                            <?php endforeach;?>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        </thead>
                        <tbody class="fc-body">
                        <?php
                        //This counts the days in the week, up to 7
                        $day_count = 1;
                        ?>
                        <tr>
                            <td class="fc-widget-content">
                                <div class="fc-day-grid-container" style="">
                                    <div class="fc-day-grid">
                                        <div class="fc-row fc-week fc-widget-content" style="height: 107px;">
                                            <div class="fc-bg">
                                                <table>
                                                    <tbody>
                                                    <tr>
                                                        <?php
                                                        for($i=0; $i<7; $i++){
                                                            echo '<td class="fc-day fc-widget-content"></td>';
                                                        }
                                                        ?>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="fc-content-skeleton">
                                                <table>
                                                    <thead>
                                                    <tr>
                                                        <?php
                                                        $blank = $this->blank;
                                                        //first we take care of those blank days
                                                        while ( $blank > 0 )
                                                        {
                                                            ?><td class="fc-day-number"></td><?php
                                                            $blank = $blank-1;
                                                            $day_count++;
                                                        }
                                                        //sets the first day of the month to 1
                                                        $day_num = 1;
                                                        //count up the days, untill we've done all of them in the month
                                                        while ( $day_num <= $this->days_in_month )
                                                        {
                                                        $zero = ($day_num<10)?'0':'';
                                                        ?>
                                                        <td>
                                                            <table class="event-table">
                                                                <tr>
                                                                    <td class='fc-day-number <?php $d = strtotime($this->year.'-'.$this->month.'-'.$day_num); echo (date('D',$d) == "Sat" || date('D',$d) == "Sun")? 'weekend' :'' ; ?>'><?=$day_num?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="height: 85px;" class="fc-event-cell month-event-drop event-catch-<?=$zero.$day_num?> <?php $d = strtotime($this->year.'-'.$this->month.'-'.$day_num); echo (date('D',$d) == "Sat" || date('D',$d) == "Sun")? 'weekend' :'' ; ?>"></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        <?php
                                                        $day_num++;
                                                        $day_count++;
                                                        //Make sure we start a new row every week
                                                        if ($day_count > 7)
                                                        {
                                                        ?>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="fc-row fc-week fc-widget-content" style="height: 107px;">
                                            <div class="fc-bg">
                                                <table>
                                                    <tbody>
                                                    <tr>
                                                        <?php
                                                        for($i=0; $i<7; $i++){
                                                            echo '<td data-date="" class="fc-event-cell"></td>';
                                                        }
                                                        ?>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="fc-content-skeleton">
                                                <table>
                                                    <thead>
                                                    <tr>
                                                        <?php
                                                        $day_count = 1;
                                                        }
                                                        }
                                                        //Finaly we finish out the table with some blank details if needed
                                                        while ( $day_count >1 && $day_count <=7 )
                                                        {
                                                            echo "<td> </td>";
                                                            $day_count++;
                                                        }
                                                        ?>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php
        return $retval;
    }

    public function dayView(){
        $retval = ''?>
        <div id="calendar" class="fc fc-ltr fc-unthemed">
            <div class="fc-toolbar">
                <div class="fc-left">
                    <div class="fc-button-group">
                        <button class="fc-prev-button prev-day fc-button fc-state-default fc-corner-left" type="button"><span
                                class="fc-icon fc-icon-left-single-arrow"></span></button>
                        <button class="fc-next-button next-day fc-button fc-state-default fc-corner-right" type="button"><span
                                class="fc-icon fc-icon-right-single-arrow"></span></button>
                    </div>
                    <input type="hidden" id="current_day" value="<?=$this->day?>">
                    <input type="hidden" id="this_day" value="<?=$this->day?>">
                    <input type="hidden" id="current_daytime" value="<?=$this->date?>">
                    <input type="hidden" id="this_daytime" value="<?=$this->date?>">
                    <!--button class="fc-today-button fc-button fc-state-default fc-corner-left fc-corner-right fc-state-disabled" type="button" disabled="disabled">today</button-->
                </div>
                <div class="fc-right">
                    <div class="fc-button-group">
                        <button class="fc-agendaWeek-button fc-button fc-state-default fc-corner-left" type="button">week</button>
                        <button class="fc-month-button fc-button fc-state-default" type="button">month</button>
                        <button class="fc-agendaDay-button fc-button fc-state-default fc-state-active fc-corner-right" type="button">day</button>
                    </div>
                </div>
                <div class="fc-center"><h2><?=$this->heading?> - Day <?=self::countDay()?> - <?=date('F d, Y',$this->date)?></h2></div>
                <div class="fc-clear"></div>
            </div>
            <div class="fc-view-container" style="">
                <div class="fc-view fc-agendaDay-view fc-agenda-view" style="">
                    <table>
                        <thead class="fc-head">
                        <tr>
                            <td class="fc-head-container fc-widget-header">
                                <div class="fc-row fc-widget-header" style="border-right-width: 1px; margin-right: 16px;">
                                    <table>
                                        <thead>
                                        <tr>
                                            <th style="width:85px" class="fc-axis fc-widget-header view-day"></th>
                                            <th data-date="" class="fc-day-header fc-widget-header fc-thu view-day"><?=date('l',$this->date)?>
                                            </th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        </thead>
                        <tbody class="fc-body <?php echo (date('D',$this->date) == "Sat" || date('D',$this->date) == "Sun")? 'weekend' :'' ; ?>">
                        <tr>
                            <td class="fc-widget-content">
                                <div class="fc-day-grid" style="display: none">
                                    <div class="fc-row fc-week fc-widget-content"
                                         style="border-right-width: 1px; margin-right: 16px;">
                                        <div class="fc-bg">
                                            <table>
                                                <tbody>
                                                <tr>
                                                    <td style="width:43px" class="fc-axis fc-widget-content"><span>all-day</span></td>
                                                    <td data-date="2016-03-31"
                                                        class="fc-day fc-widget-content fc-thu fc-past"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="fc-content-skeleton">
                                            <table>
                                                <tbody>
                                                <tr>
                                                    <td style="width:43px" class="fc-axis"></td>
                                                    <td></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr class="fc-divider fc-widget-header">
                                <div class="fc-time-grid-container fc-scroller" style="height: <?php echo count($this->periods)*60?>px;">
                                    <div class="fc-time-grid">
                                        <div class="fc-bg">
                                            <table>
                                                <tbody>
                                                <tr>
                                                    <td style="width:85px" class="fc-axis fc-widget-content"></td>
                                                    <td data-date=""
                                                        class="fc-day fc-widget-content fc-thu fc-past"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="fc-slats">
                                            <table>
                                                <tbody>
                                                <?php foreach($this->periods as $k=>$v):?>
                                                    <tr data-time="">
                                                        <td style="width: 85px; height: 60px;" class="fc-axis fc-time fc-widget-content view-head"><span><?php echo (is_int($v['period']))?'Period ':''?><?=$v['period']?><br><?=$v['start']?>-<?=$v['end']?></span></td>
                                                        <td class="fc-widget-content fc-event-cell fc-dropable" data-date="<?=date('Y-m-d',$this->date)?>"></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="fc-content-skeleton">
                                            <table>
                                                <tbody>
                                                <tr>
                                                    <td style="width:85px" class="fc-axis"></td>
                                                    <td>
                                                        <div class="fc-content-col">
                                                            <div class="fc-event-container fc-helper-container"></div>
                                                            <div class="day-event-container">

                                                            </div>
                                                            <div class="fc-highlight-container"></div>
                                                            <div class="fc-bgevent-container"></div>
                                                            <div class="fc-business-container"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <hr class="fc-divider fc-widget-header" style="display: none;">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
        return $retval;
    }

    public function weekView(){
        $countPeriod = count($this->periods)+1;
        $columnWidth = (100)/$countPeriod;
        $dayColumnWidth = $columnWidth;
        $retval = ''?>
        <div id="calendar" class="fc fc-ltr fc-unthemed">
            <div class="fc-toolbar">
                <div class="fc-left">
                    <div class="fc-button-group">
                        <button class="fc-prev-button prev-week fc-button fc-state-default fc-corner-left" type="button"><span
                                class="fc-icon fc-icon-left-single-arrow"></span></button>
                        <button class="fc-next-button next-week fc-button fc-state-default fc-corner-right" type="button"><span
                                class="fc-icon fc-icon-right-single-arrow"></span></button>
                    </div>
                    <input type="hidden" id="week_day" value="<?=$this->date?>">
                    <input type="hidden" id="this_day" value="<?=$this->date?>">
                    <!--button class="fc-today-button fc-button fc-state-default fc-corner-left fc-corner-right fc-state-disabled" type="button" disabled="disabled">today</button-->
                </div>
                <div class="fc-right">
                    <div class="fc-button-group">
                        <button class="fc-agendaWeek-button fc-button fc-state-default fc-state-active fc-corner-left" type="button">week</button>
                        <button class="fc-month-button fc-button fc-state-default" type="button">month</button>
                        <button class="fc-agendaDay-button fc-button fc-state-default fc-corner-right" type="button">day</button>
                    </div>
                </div>
                <div class="fc-center"><h2><?=$this->heading?> - Week <?=self::countWeek()?> (<?php echo self::rangeWeek($this->date)?>)</h2></div>
                <div class="fc-clear"></div>
            </div>
            <div class="fc-view-container" style="">
                <table>
                    <thead>
                        <tr>
                            <td>
                                <div>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="view-head" style="width: <?=$dayColumnWidth?>%; height: 60px;"></th>
                                                <?php foreach($this->periods as $k=>$v):?>
                                                    <th class="view-head" style="width: <?=$dayColumnWidth?>%; height: 60px;"><span class="period-no"><?php echo (is_int($v['period']))?'Period ':''?><?=$v['period']?></span><br><span class="period-time"><?=$v['start']?>- <?=$v['end']?></span></th>
                                                <?php endforeach; ?>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="event-cell" style="position: relative;">
                                <div>
                                    <table>
                                        <tbody>
                                        <?php
                                        $dayToDate = self::weekDates($this->date);
                                        $row = 0;
                                        foreach($dayToDate as $k=>$v):
                                            ?>
                                            <tr>
                                                <th class="view-day <?=strtolower($k)?> day-<?=$row*60?>" data-date="<?=date('Y-m-d',$v)?>" style="width: <?=$dayColumnWidth?>%; height: 60px;">
                                                    <span><?=$k?></span><br>
                                                    <span><?=date('d/m',$v)?></span>
                                                </th>
                                                <?php foreach($this->periods as $time):?>
                                                    <td style="width: <?=$dayColumnWidth?>%; height: 60px;" class="fc-event-cell <?php echo ($k == "Sat" || $k == "Sun")? 'weekend' :'' ; ?>" data-date="<?=date('Y-m-d',$v)?>"></td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php $row++; endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="training_events"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
        return $retval;
    }
    /*
     * function rangeWeek is used to show the week range for given date
     * param: $datestr, int, unix time
     * return: string, week day with date
     * */
    public function rangeWeek($datestr) {
        //date_default_timezone_set(date_default_timezone_get());
        //$dt = strtotime($datestr);
        $dt = $datestr;

        $days = $this->days;
        $keys = array_keys($days);

        $firstDayKey = $keys[0];
        $firstDayVal = strtolower($days[$firstDayKey]);

        $lastDayKey = $keys[6];
        $lastDayVal = strtolower($days[$lastDayKey]);

        switch($firstDayVal){
            case 'tue':
                $firstDayVal = 'tues';break;
            case 'wed':
                $firstDayVal = 'wednes';break;
            case 'thu':
                $firstDayVal = 'thurs';break;
            case 'sat':
                $firstDayVal = 'satur';break;
        }

        switch($lastDayVal){
            case 'tue':
                $lastDayVal = 'tues';break;
            case 'wed':
                $lastDayVal = 'wednes';break;
            case 'thu':
                $lastDayVal = 'thurs';break;
            case 'sat':
                $lastDayVal = 'satur';break;
        }

        $res['start'] = date('w', $dt)==$firstDayKey ? date('j M', $dt) : date('j M', strtotime('last '.$firstDayVal.'day', $dt));
        $res['end'] = date('w', $dt)==$lastDayKey ? date('j M, Y', $dt) : date('j M, Y', strtotime('next '.$lastDayVal.'day', $dt));
        return $res['start']." &mdash; ".$res['end'];
    }

    public function weekDates($datestr) {
        //date_default_timezone_set(date_default_timezone_get());
        //$dt = strtotime($datestr);
        $dt = $datestr;

        $days = $this->days;
        $keys = array_keys($days);

        $firstDayKey = $keys[0];
        $firstDayVal = strtolower($days[$firstDayKey]);

        $lastDayKey = $keys[6];
        $lastDayVal = strtolower($days[$lastDayKey]);

        switch($firstDayVal){
            case 'tue':
                $firstDayVal = 'tues';break;
            case 'wed':
                $firstDayVal = 'wednes';break;
            case 'thu':
                $firstDayVal = 'thurs';break;
            case 'sat':
                $firstDayVal = 'satur';break;
        }

        switch($lastDayVal){
            case 'tue':
                $lastDayVal = 'tues';break;
            case 'wed':
                $lastDayVal = 'wednes';break;
            case 'thu':
                $lastDayVal = 'thurs';break;
            case 'sat':
                $lastDayVal = 'satur';break;
        }

        $start = date('w', $dt)==$firstDayKey ? $dt : strtotime('last '.$firstDayVal.'day', $dt);
        $end = date('w', $dt)==$lastDayKey ? $dt : strtotime('next '.$lastDayVal.'day', $dt);

        //$start_date = date('d',$start);
        //$end_date = date('d',$end);

        $dayToDate = array();
        while($start <= $end){
            $day = date('D',$start);
            //$date = date('d/m', $start);
            //$dayToDate[$day] = $date;
            $dayToDate[$day] = $start;

            $start = $start + (3600*24);
            //$start_date++;
        }

        if(count($dayToDate) < 7){
            $day = date('D',$end);
            //$date = date('d/m', $end);
            //$dayToDate[$day] = $date;
            $dayToDate[$day] = $end;
        }
        return $dayToDate;
    }

    public function weekFullDates($datestr, $days) {
        //date_default_timezone_set(date_default_timezone_get());
        //$dt = strtotime($datestr);
        $dt = $datestr;

        //$days = $this->days;

        $keys = array_keys($days);

        $firstDayKey = $keys[0];
        $firstDayVal = strtolower($days[$firstDayKey]);

        $lastDayKey = $keys[6];
        $lastDayVal = strtolower($days[$lastDayKey]);

        switch($firstDayVal){
            case 'tue':
                $firstDayVal = 'tues';break;
            case 'wed':
                $firstDayVal = 'wednes';break;
            case 'thu':
                $firstDayVal = 'thurs';break;
            case 'sat':
                $firstDayVal = 'satur';break;
        }

        switch($lastDayVal){
            case 'tue':
                $lastDayVal = 'tues';break;
            case 'wed':
                $lastDayVal = 'wednes';break;
            case 'thu':
                $lastDayVal = 'thurs';break;
            case 'sat':
                $lastDayVal = 'satur';break;
        }

        $start = date('w', $dt)==$firstDayKey ? $dt : strtotime('last '.$firstDayVal.'day', $dt);
        $end = date('w', $dt)==$lastDayKey ? $dt : strtotime('next '.$lastDayVal.'day', $dt);

        //$start_date = date('d',$start);
        //$end_date = date('d',$end);

        $dayToDate = array();
        while($start <= $end){
            //echo date('d',$start);
            array_push($dayToDate, $start);
            $start = $start + (3600*24);
            //$start_date++;
        }
        if(count($dayToDate) < 7){
            array_push($dayToDate, $end);
        }
        return $dayToDate;
    }

    public function countDay(){
        $course = $this->course;
        $start = $course['startdate'];
        $today = $this->date;

        $countDay = ($today - $start)/(24*3600);
        $countDay = ($countDay <= 0) ? 1: round($countDay);
        return $countDay;
    }

    public function countMonth(){
        $course = $this->course;
        $start = $course['startdate'];
        $today = $this->date;

        $startMonth = date('m',$start);
        $thisMonth = date('m',$today);

        $countMonth = ($thisMonth - $startMonth)+1;
        return $countMonth;
    }

    public function countWeek(){
        $course = $this->course;
        $start = $course['startdate'];
        $today = $this->date;

        $today_str = strtolower(date('l',$today));
        $startDay_str = strtolower(date('l',$start));

        $days = $this->days;
        $start_day = date('D', $start);
        $count = 0;
        $start_day_index = '';

        foreach($days as $day){
            if($day == $start_day){
                $start_day_index = $count; // Getting Course start day week index
                break;
            }
            $count++;
        }

        $to_day = date('D', $today);
        $count = 0;
        $to_day_index = '';
        foreach($days as $day){
            if($day == $to_day){
                $to_day_index = $count; // Getting today week index
                break;
            }
            $count++;
        }

        if($to_day_index < $start_day_index){
            $countDay = strtotime('next '.$startDay_str, $today);
        }
        elseif($to_day_index > $start_day_index){
            $countDay = strtotime('previous '.$startDay_str, $today);
        }
        else{
            $countDay = $today;
        }

        $week = 0;
        for($i = $countDay; $i >= $start; $i = $prevToToday ){
            $prevToToday = strtotime('previous '.$today_str, $i);
            $week++;
        }

        return $week;
    }
}
?>