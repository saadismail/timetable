<?php

require dirname(__FILE__) . '/../include/db.php';
require dirname(__FILE__) . "/../include/functions.php";
require dirname(__FILE__) . '/../include/email.php';

$sql = "SELECT `id`, `active`, `name`, `email`, `subjects`, `sections` FROM students";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Skip if the user has not verified his email address yet
        // if ($row['active'] == 0) {
        //     continue;
        // }

        $name = $row['name'];
        $email = $row['email'];

        $message = '<html><body>';
        $message .= "Hi ".$name.",<br><br>";
        $message .= 'Are you having trouble getting familiar with the timetable just issued?<br><br>';
        $message .= 'Do not worry, <b>Timetable Notifier</b> got you covered. In case you are not familiar with it, <b>Timetable Notifier</b> is an application created by students for students to inform about their next day\'s class schedules. If you are interested, then all you have to do is to get yourself registered at: https://timetable.ml/register.php with your nu.edu.pk email address (old users will also have to register again), and you will be sent an email instantly with a verification link that you will have to click to verify your email address and begin receiving emails. Once you have verified, you will start getting emails everyday (except Friday & Saturday) with your class schedule of the next day.<br><br>';
        $message .= 'Since the timetable is currently in its initial phase, there might be issues parsing the timetable so we would really appreicate if you would inform us in case you notice any discrepancy in the generated schedule and your actual timetable<br><br>';

        $message .= 'Note: This is an unofficial app & is currently in beta stage, therefore, you must verify classes with the excel timetable sheet. This app only sends regularly scheduled classes (which are in the excel timetable sheet) & does not cater to extra classes.';

        $message .= '<br><br>~Saad Ismail';

        $message .= "</body></html>";
        echo $message;

        // Only send emails if development mode (in functions.php) is false
        if (!$developmentMode) {
            $mail->clearAddresses();
            $mail->addAddress($email, $name);
            $mail->Subject = "TimeTable Notifier is back :)";
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
