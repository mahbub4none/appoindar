<?php session_start();
class setupClass{
    public function dbSetup(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $hname = $_REQUEST["hname"];
            $uname = $_REQUEST["uname"];
            $pass = $_REQUEST["pass"];
            $db_name = $_REQUEST["dbname"];

            $myfile = fopen("databaseClass.php", "w");

            $txt = "<?php
	class Database {
		public \$hostname='".$hname."';
		public \$username='".$uname."';
		public \$password='".$pass."';
		public \$dbname='".$db_name."';
		public \$conn;

			function __construct(){
				try
 					{
 						\$this->conn = mysqli_connect(\$this->hostname, \$this->username, \$this->password, \$this->dbname, 3306);
 					}

 				catch(PDOException \$e)
 					{
 						echo \$e->getmessage();
 					}
			}


} ?> ";
            //var_dump($txt) ;die;
            fwrite($myfile,$txt);
            fclose($myfile);

            echo "<script type='text/javascript'>window.location = '../registration.php'</script>";
        }
    }

    public function login(){
        if(isset($_SESSION['id'])){
            session_destroy();
            echo "You have signed out. Login again.";
        }
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $username = $_POST['uname'];
            $password = $_POST['pass'];
            $password = md5($password);

            $selectQuery = "SELECT id FROM users WHERE username='$username' AND password='$password'";
            include "databaseClass.php";
            $dbcon = new databaseClass();
            $qry_result = mysqli_query($dbcon->conn, $selectQuery);
            $row = mysqli_num_rows($qry_result);
            if($row > 0){
                $user = mysqli_fetch_array($qry_result);
                $_SESSION['id'] = $user['id'];

                echo "<script type='text/javascript'>window.location = 'edit.php?id=".$user['id']."'</script>";
                exit();
            }
            else{
                echo "<script type='text/javascript'>window.location = 'login.php?error=1'</script>";
            }
        }
    }

    public function registration(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){

            $fullName = $_POST['app_name'];
            $shortName = $_POST['short_name'];
            $startDate = strtotime($_POST['startdate']);
            $endDate = strtotime($_POST['enddate']);
            $conflict = $_POST['conflict'];
            $username = $_POST['uname'];
            $password = $_POST['pass'];
            $password = md5($password);

            $insertQuery = "INSERT into `users` (username, password, fullname, shortname, startdate, enddate, conflict)
                            values ('$username', '$password', '$fullName', '$shortName', '$startDate', '$endDate', '$conflict')";
            //echo $insertQuery;
            include "databaseClass.php";
            $dbcon = new databaseClass();
            mysqli_query($dbcon->conn, $insertQuery);
            $last_id = mysqli_insert_id($dbcon->conn);
            echo "<script type='text/javascript'>window.location = 'period_setup.php?id=$last_id'</script>";
        }
    }

    public function period_setup(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $active = 1;
            $courseid = $_POST['id'];
            $period = 1;
            $values = array();
            $fields = "(courseid, period, active, start_time, end_time, display)";
            foreach($_POST['heading'] as $k=>$v){
                $heading = $v;
                $start = $_POST['start'][$k];
                $end = $_POST['end'][$k];
                $values[] = "('$courseid', '$period', '$active', '$start', '$end', '$heading')";
                $period++;
            }
            $value = implode(",", $values);

            $insertQuery = "INSERT into `timetable_slot` $fields values $value";
            //echo $insertQuery;die;
            include "databaseClass.php";
            $dbcon = new databaseClass();
            mysqli_query($dbcon->conn, $insertQuery);

            echo "<script type='text/javascript'>window.location = 'activity_setup.php?id=$courseid'</script>";
        }
    }

    public function activity_setup(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $courseid = $_POST['id'];
            $values = array();
            $fields = "(courseid, activitytype, activitycolor)";
            foreach($_POST['activity'] as $k=>$v){
                $activity = $v;
                $color = $_POST['color'][$k];
                $values[] = "('$courseid', '$activity', '$color')";
            }
            $value = implode(",", $values);

            $insertQuery = "INSERT into `timetable_type` $fields values $value";
            //echo $insertQuery;die;
            include "databaseClass.php";
            $dbcon = new databaseClass();
            mysqli_query($dbcon->conn, $insertQuery);

            echo "<script type='text/javascript'>window.location = 'login.php'</script>";
        }
    }
}
	




?>