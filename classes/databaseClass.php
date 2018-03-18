<?php
	class databaseClass {
		public $hostname='localhost';
		public $username='root';
		public $password='';
		public $dbname='appoindar';
		public $conn;

			function __construct(){
				try
 					{
 						$this->conn = mysqli_connect($this->hostname, $this->username, $this->password, $this->dbname, 3306);
 					}

 				catch(PDOException $e)
 					{
 						echo $e->getmessage();
 					}
			}


} ?> 