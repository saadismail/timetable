<?php

// If you are not using Composer
require("sendgrid-php/sendgrid-php.php");

$from = new SendGrid\Email("Timetable Notifier", "notify@timetable.host");
$subject = "Sending with SendGrid is Fun";
$to = new SendGrid\Email("Saad Ismail", "saad3112@gmail.com");
$content = new SendGrid\Content("text/plain", "and easy to do anywhere, even with PHP");
$mail = new SendGrid\Mail($from, $subject, $to, $content);

$apiKey = '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';
$sg = new \SendGrid($apiKey);

echo $apiKey;

$response = $sg->client->mail()->send()->post($mail);
echo $response->statusCode();
print_r($response->headers());
echo $response->body();

?>