<?php

require dirname(__FILE__) . '/include/db.php';
require dirname(__FILE__) . "/include/functions.php";
require dirname(__FILE__) . '/include/email.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Remove</title>
    <?php if(file_exists("include/bootstrap.min.css")) echo "<link rel=\"stylesheet\" href=\"include/bootstrap.min.css\">"; 
    else echo "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\">"; ?>
</head>

<body>
<div class="container">

<h3 style="text-align: center;">Remove</h3>
<br>

<form class="form-horizontal" method="post">
    <div class="form-group">
        <label class="control-label col-sm-2" for="email">Email:</label>
        <div class="col-sm-10">
            <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your registered email (k1#####@nu.edu.pk)" pattern="k1[0-9]{5}@nu.edu.pk$">
        </div>
    </div>

    <div class="form-group">
        <div class="text-center pull-right">
            <?php echo "<div class=\"g-recaptcha\" data-sitekey=\"".$recaptchaSiteKey."\"></div>";?> 
        </div>
    </div>

    <div class="form-group">
        <div class="pull-right">
            <button type="submit" name="submit" class="btn btn-primary">Remove!</button>
        </div>
    </div>
    </form>

<?php
    if (isset($_POST['submit'])) {
        if (!isset($_POST['email'])) {
            die("Stop");
        }
        
        if(!preg_match("/^k1[0-9]{5}@nu\.edu\.pk$/", $_POST['email'])) {
            alertUser("You must provide a Karachi student's nu.edu.pk email address to register.");
            die();
        }

        if (!isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response'])) {
            alertUser("You have missed the captcha.");
            die();
        }

        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$recaptchaSecretKey.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
        if (!$responseData->success) {
            alertUser("Captcha verification failed.");
            die();
        }


        $email = $_POST['email'];

        $sql = "SELECT id FROM students WHERE `email` = '$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
        	$row = $result->fetch_assoc();
        	$id = $row['id'];

            $sql = "SELECT id FROM remove_vercode WHERE `studentID` = '$id'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                alertUser("You already have a removal request pending. Use the old email sent to remove your record.");
                die();
            }

        	$string = generateRandomString(20);
        	$sql = "INSERT INTO `remove_vercode` (`id`, `studentID`, `code`) VALUES (NULL, '$id', '$string')";
            if ($conn->query($sql) != TRUE) {
                die("Something went wrong");
            }
            if (!$developmentMode) {
                $mail->addAddress($email);
                $mail->Subject = "Confirm your email address";
                $mail->Body = "Open this link to confirm the ownership of your email address: "."<a href=\"http://".$_SERVER['SERVER_NAME']."/removeVer.php?id=$string&email=$email\">Confirm</a>";
                if ($mail->send()) {
                	alertUser("Please check your email inbox for confirmation");
                } else {
                	alertUser("Confirmation email could not be sent. Please contact k164060@nu.edu.pk");
                }
            }
        } else {
        	alertUser("Could not find your entry");
        }
        $conn->close();
    }
?>
</div>

<script src='https://www.google.com/recaptcha/api.js'></script>

</body>
</html>