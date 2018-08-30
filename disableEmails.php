<?php

include_once "include/db.php";
include_once "include/functions.php";

if (!isset($_GET['id']) || !isset($_GET['email'])) {
    die("Email disable failed");
}

$email = $_GET['email'];
$id = $_GET['id'];

$sql = "SELECT id FROM students WHERE `email` = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stid =  $row['id'];
    $expected_hash = md5($stid + $email);
    if ($expected_hash == $id) {
        $sql = "UPDATE `students` SET active = 0 WHERE `students`.`id` = '$stid'";
        if ($conn->query($sql) == TRUE) {
            alertUser("Email notifications disabled.");
        } else {
            alertUser("Email disable failed");
        }
    }
} else {
    alertUser("Email disable failed");
}

$conn->close();

?>