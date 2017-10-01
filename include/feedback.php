<?php
require "functions.php";
require "email.php";

if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message']) && !empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['message'])) {
    if (!isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response']) ) {
        http_response_code(400);
        echo "Complete the reCAPTCHA first.";
        die();
    }
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$recaptchaSecretKey.'&response='.$_POST['g-recaptcha-response']);
    $responseData = json_decode($verifyResponse);
    if ($responseData->success) {
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
        if ($mail->send()) {
            http_response_code(200);
            echo "Thank You! Your message has been sent.";
        } else {
            http_response_code(500);
            echo "Oops! Something went wrong and we couldn't send your message.";
        }
    } else {
        echo "reCAPTCHA verification failed.";
        die();
    }
} else {
    http_response_code(400);
    echo "Oops! There was a problem with your submission. Please complete the form and try again.";
}
?>