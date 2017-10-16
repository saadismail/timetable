<?php

require dirname(__FILE__) . '/../include/db.php';
require dirname(__FILE__) . "/../include/functions.php";
require dirname(__FILE__) . '/../include/email.php';

$sql = "SELECT `id`, `active`, `name`, `email`, `subjects`, `sections` FROM students";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Skip if the user has not verified his email address yet
        if ($row['active'] == 0) {
            continue;
        }

        $name = $row['name'];
        $email = $row['email'];

        $message = '<html><body>';
        $message .= "Hi ".$name.",<br><br>";
        $message .= 'I hope TimeTable Notifier is serving the purpose for which it was built.<br><br>';
        $message .= 'This is to let you know that I have made various improvements recently, like fixing some minor bugs which could have caused some further issues (Two emails today was totally related). I\'d love to hear back if you ever encounter a issue or have any constructive feedback. If you ever notice one of your classes missing, then its always good to talk :)<br>';
        $message .= '<br>~Saad Ismail';

        $message .= "</body></html>";
        echo $message;

        // Only send emails if development mode (in functions.php) is false
        if (!$developmentMode) {
            $mail->clearAddresses();
            $mail->addAddress($email, $name);
            $mail->Subject = "Feedback Required - TimeTable Notifier";
            $mail->Body = $message;

            if (! $mail->send()) {
                die("Something went wrong");
            }
        }
        unset($entries);
        echo "<br> <br>";
    }
}

$conn->close();

?>