<?php

include_once "include/db.php";
include_once "include/functions.php";

if (!isset($_GET['id']) || !isset($_GET['email'])) {
    die("Remove Failed");
}

$id = $_GET['id'];
$email = $_GET['email'];

$sql = "SELECT id FROM students WHERE `email` = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stid =  $row['id'];
    $sql = "SELECT code FROM remove_vercode WHERE `studentID` = '$stid'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $code = $row['code'];
        if ($code == $id) {
            $sql = "DELETE FROM `students` WHERE `students`.`id` = '$stid'";
            if ($conn->query($sql) == TRUE) {
                alertUser("Successfully Removed");
            } else {
                alertUser("Remove failed");
            }
        }
    } else {
        alertUser("Remove failed");
    }
} else {
    alertUser("Remove failed");
}

$conn->close();

?>