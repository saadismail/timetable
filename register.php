<?php

require dirname(__FILE__) . '/include/db.php';
require dirname(__FILE__) . "/include/functions.php";
require dirname(__FILE__) . '/include/email.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body>
<div class="container">

<h3 style="text-align: center;">Register</h3>
<br>

<form class="form-horizontal" method="post">
    <div class="form-group">
        <label class="control-label col-sm-2" for="name">Name:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="name" name="name" required placeholder="Enter name">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="batch">Batch:</label>
        <div class="col-sm-10">
            <input type="number" class="form-control" id="batch" name="batch" required placeholder="Enter batch (201#)">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="email">Email:</label>
        <div class="col-sm-10">
            <input type="email" class="form-control" id="email" name="email" required placeholder="Enter email (k16####@nu.edu.pk)" pattern="k16[0-9]{4}@nu.edu.pk$">
        </div>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Code</th>
            <th>Section</th>
        </tr>
        </thead>
        <tbody>

<?php

    $sql = "SELECT id, name, code, short FROM subjects";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<th scope=\"row\">" . $row['id'] . "</th>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['short'] . "</td>";
            echo "<td>" . $row['code'] . "</td>";
            echo "<td><input type='text' class=\"form-control\" placeholder='Enter section (a-i || GR(1-9))' name=\"sections[]\"/>";
            echo "</tr>";
        }
    }
    ?>
            </tbody>
        </table>
        <div class="form-group">
            <div class="pull-right">
                <button type="submit" name="submit" class="btn btn-primary">Register</button>
            </div>
        </div>
    </form>

<?php
    if (isset($_POST['submit'])) {
        if (!isset($_POST['sections']) || !isset($_POST['name']) || !isset($_POST['batch']) || !isset($_POST['email'])) {
            die("Stop");
        }

        if (!preg_match("/^[a-zA-Z\s]*$/", $_POST['name'])) {
            die("Invalid name, should only contain letters");
        }
        $name = $_POST['name'];
        $batch = $_POST['batch'];
        $email = $_POST['email'];

        // Form the valid format for subjects and sections
        $subjects = "";
        $sections = "";
        if (isset($_POST['sections'])) {
            $sectionstmp = $_POST['sections'];
            for ($i = 0; $i < sizeof($sectionstmp); $i++) {
                if ($sectionstmp[$i]) {
                    if (strlen($sectionstmp[$i]) > 3 || !preg_match("/^[a-i]$/i", $sectionstmp[$i]) && !preg_match("/^[a-i][1-2]{1}$/i", $sectionstmp[$i]) && !preg_match("/^GR[1-9]$/i", $sectionstmp[$i])) {
                        die('Invalid section');
                    }
                    if(strlen($sectionstmp[$i]) == 1) {
                        $sectionstmp[$i] = strtoupper($sectionstmp[$i]);
                    } else if (strlen($sectionstmp[$i]) == 3) {
                        $sectionstmp[$i][0] = 'G';
                        $sectionstmp[$i][1] = 'r';
                    }
                    $subjects = $subjects . ($i + 1) . ",";
                    $sections = $sections . $sectionstmp[$i] . ",";
                }
            }
            // Remove last comma
            $subjects = rtrim($subjects, ',');
            $sections = rtrim($sections, ',');
        }

        Check if user with email is already registered
        $sql = "SELECT id FROM students WHERE `email` = '$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $message = "User already exists.";
            echo "<script type='text/javascript'>alert('$message');</script>";
            die();
        }
        
        $message = "";

        $sql = "INSERT INTO `students` (`id`, `active`, `name`, `batch`, `email`, `subjects`, `sections`) VALUES (NULL, '0', '$name', '$batch', '$email', '$subjects', '$sections')";
        if ($conn->query($sql) == TRUE) {
            $message = "Successfully Registered\n";
            $string = generateRandomString(20);

            $sql = "SELECT id FROM `students` WHERE `email` = '$email'";
            $row = $conn->query($sql)->fetch_assoc();
            $id = $row['id'];

            $sql = "INSERT INTO `vercode` (`id`, `studentID`, `code`) VALUES (NULL, '$id', '$string')";
            if ($conn->query($sql) != TRUE) {
                die("Something went wrong");
            }

            // Only send emails if development mode (in functions.php) is false
            if (!$developmentMode) {
                $mail->addAddress($email, $name);
                $mail->Subject = "Verify your email address";
                $mail->Body = "Thank you for registering for Timetable Notifications. <br> Open this link to verify your email address: "."<a href=\"http://".$_SERVER['SERVER_NAME']."/activate.php?id=$string&email=$email\">Verify</a>";
                if ($mail->send()) {
                    $message .= "Please check your email inbox for verfication email";
                } else {
                    $message .= "Verification email couldn't be sent. Please contact help@timetable.host";
                }
            }
        } else {
            $message = "Something went wrong. Please contact help@timetable.host";
        }  
        echo $message;
        $message = "YES";
        echo $message;
        echo "<script type='text/javascript'>alert('$message');</script>";
        $conn->close();
    }
?>
</div>
</body>
</html>