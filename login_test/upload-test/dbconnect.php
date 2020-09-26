<?php
//connect mysql database
$host = "localhost";
$user = "root";
$pass = "Nhattan12";
$db = "demo";
$con = mysqli_connect($host, $user, $pass, $db) or die("Error connect " . mysqli_error($con));
?>