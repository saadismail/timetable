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
    <?php if(file_exists("include/bootstrap.min.css")) echo "<link rel=\"stylesheet\" href=\"include/bootstrap.min.css\">"; 
    else echo "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\">"; ?>
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
            <input type="email" class="form-control" id="email" name="email" required placeholder="Enter email (k1#####@nu.edu.pk)" pattern="k1[0-9]{5}@nu.edu.pk$">
        </div>
    </div>

    <div class="form-group">
        <br><b>NOTE:</b> You should put sections as per NEON for all the subjects. Those can be different for different subjects.<br>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Short</th>
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
            echo "<td><input type='text' class=\"form-control\" placeholder='Enter section (A || A# || Gr#)' name=\"sections[]\"/>";
            echo "</tr>";
        }
    }
    ?>
            </tbody>
        </table>

        <div class="form-group">
            <div class="text-center pull-right">
                <?php echo "<div class=\"g-recaptcha\" data-sitekey=\"".$recaptchaSiteKey."\"></div>";?> 
            </div>
        </div>

        <div class="form-group">
            <div class="pull-right">
                <button id="submit" type="submit" name="submit" class="btn btn-primary">Register</button>
            </div>
        </div>
    </form>

<?php
    if (isset($_POST['submit'])) {
        if (!isset($_POST['sections']) || !isset($_POST['name']) || !isset($_POST['batch']) || !isset($_POST['email'])) {
            die("Stop");
        }

        if (!preg_match("/^[a-zA-Z\s]*$/", $_POST['name'])) {
            alertUser("Invalid name, should only contain letters");
            die();
        }

        if (!preg_match('/^[0-9]{4}$/', $_POST['batch'])) {
            alertUser("Batch should be exactly 4 digits i.e 2016");
            die();
        }

        if(!preg_match("/^k1[0-9]{5}@nu\.edu\.pk$/", $_POST['email'])) {
            alertUser("You must provide a Karachi student's nu.edu.pk email address to register.");
            die();
        }

        if (!isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response'])) {
            alertUser("You have missed the captcha in the bottom on the page.");
            die();
        }

        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$recaptchaSecretKey.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
        if (!$responseData->success) {
            alertUser("Captcha verification failed.");
            die();
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
                    $sectionstmp[$i] = removeAllDashes($sectionstmp[$i]);
                    if (strlen($sectionstmp[$i]) > 3 || !preg_match("/^[a-i]$/i", $sectionstmp[$i]) && !preg_match("/^[a-i][1-2]{1}$/i", $sectionstmp[$i]) && !preg_match("/^GR[1-9]$/i", $sectionstmp[$i])) {
                        alertUser('Invalid section');
                        die();
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

        // Check if user with email is already registered
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

                $mail->Body = "Hi $name,<br><br>Thank you for registering for Timetable Notifications. Open this link to verify your email address: "."<a href=\"https://".$_SERVER['SERVER_NAME']."/activate.php?id=$string&email=$email\">Verify</a><br><br>NOTE: You will not recieve any notification unless you verify your email address.<br><br>Have a great day!";
                if ($mail->send()) {
                    $message .= "Please check your email inbox for verfication email";
                    alertUser("Registered Successfully, Please check your email for verification email");
                } else {
                    $message .= "Verification email couldn't be sent. Please contact k164060@nu.edu.pk";
                }
            }
        } else {
            $message = "Something went wrong. Please contact k164060@nu.edu.pk";
        }
        // alertUser($message);
        $conn->close();
    }
?>
</div>

<script src='https://www.google.com/recaptcha/api.js'></script>
</body>

</html>