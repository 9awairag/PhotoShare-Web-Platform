<?php
/*
Author: Javed Ur Rehman
Website: http://www.allphptricks.com/
*/
?>

<?php
session_start();
if(!isset($_SESSION["username"])){
echo $_SESSION["username"];
header("Location: login.php");
exit(); }
?>
