<?php
/*
Author: Javed Ur Rehman
Website: http://www.allphptricks.com/
*/


$con = new mysqli("localhost","root","","register");
// $con = new PDO("mysql:host=localhost;dbname=register", "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$con->set_charset("utf8mb4");
//Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
?>