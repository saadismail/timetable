<?php

$host = "localhost";
$user = "tt";
$pass = "tt1234";
$db = "timetable";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: ".$conn->connect_errorno);
}


?>