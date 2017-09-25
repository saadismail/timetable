<?php

include_once "include/db.php";

if (!isset($_GET['id']) || !isset($_GET['email'])) {
    die("Activation Failed");
}

$id = $_GET['id'];
$email = $_GET['email'];

$sql = "SELECT id FROM students WHERE `email` = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stid =  $row['id'];
    $sql = "SELECT code FROM vercode WHERE `studentID` = '$stid'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $code = $row['code'];
        if ($code == $id) {
            $sql = "UPDATE students SET active=1 WHERE `id` = '$stid'";
            if ($conn->query($sql) == TRUE) {
                echo "Successfully activated";
                $sql = "DELETE FROM vercode WHERE `studentID` = '$stid'";
                $conn->query($sql);
            } else {
                die("Activation Failed");
            }
        }
    } else {
        die("Activation Failed");
    }
} else {
    die("Activation Failed");
}

$conn->close();

?>