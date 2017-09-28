<?php
require "functions.php";
require "email.php";
require "recaptchalib.php";

// your secret key
$secret = "6LcPUjIUAAAAADzatC4WoqQVnZbRLULoJ9oJRe9f";
 
// empty response
$response = null;
 
// check secret key
$reCaptcha = new ReCaptcha($secret);

// if submitted check response
if (!isset($_POST['g-recaptcha-response'])) die();
if ($_POST["g-recaptcha-response"]) {
    $response = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
    );
}

if ($response != null && $response->success) {
    if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])) {
    $email_to = "saad3112@gmail.com";
    $email_subject = "Feedback recieved from ".$_POST['name'];

    $name = $_POST['name']; // required
    $email = $_POST['email']; // required
    $feedback = $_POST['message']; // required

    $mail->addAddress($email_to, "Saad Ismail");
    $mail->Subject = $email_subject;

    $message = "Name: ".$name."<br>";
    $message .= "Email: ".$email."<br><br>";
    $message .= "Message: ".$feedback;

    $mail->Body = $message;
    if (true) {
        http_response_code(200);
        echo "Thank You! Your message has been sent.";
    } else {
        http_response_code(500);
        echo "Oops! Something went wrong and we couldn't send your message.";
    }
    } else {
        http_response_code(400);
        echo "Oops! There was a problem with your submission. Please complete the form and try again.";
    }   
} else {
    http_response_code(400);
    echo "Captcha verification failed.";
}
?>
