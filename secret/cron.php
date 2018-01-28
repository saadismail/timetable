<?php

require dirname(__FILE__) . '/../include/db.php';
require dirname(__FILE__) . "/../include/functions.php";
require dirname(__FILE__) . '/../include/email.php';

date_default_timezone_set('Asia/Karachi');

// If current time is less than 4:00 PM then send email of current day else of the next day
if (date('H') < 16) {
    $julianday = (gregoriantojd(date('m'),date('d'),date('Y')));
    $dayAndDate = date('l\, jS F Y');
} else {
    $julianday = (gregoriantojd(date('m'),date('d'),date('Y'))) + 1;
    $dayAndDate = date('l\, jS F Y', strtotime(' +1 day'));
}

$day_of_week = jddayofweek($julianday);
// Stop execution if next day is weekend or invalid $day_of_week
if ($day_of_week < 1 || $day_of_week > 5) die();

require_once dirname(__FILE__) . '/../vendor/autoload.php';

$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
$reader->setReadDataOnly(true);

try {
    $spreadsheet = $reader->load(dirname(__FILE__) . "/../include/BSCS-modified.xlsx");
} catch (Exception $e) {
    die($e->getMessage());
}

$worksheet = $spreadsheet->setActiveSheetIndex($day_of_week-1);

$sql = "SELECT `id`, `active`, `name`, `email`, `subjects`, `sections` FROM students";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Skip if the user has not verified his email address yet
        if ($row['active'] == 0) {
            continue;
        }

        $current = 0;

        $id = $row['id'];
        $email = $row['email'];
        $name = $row['name'];

        $subjects = $row['subjects'];
        $subjects = explode(',', $subjects);

        $sections = $row['sections'];
        $sections = explode(',', $sections);

        $message = '<html><body>';
        $message .= "Hi ".$name.",<br><br>";
        $message .= 'Below is the schedule of your classes on '.$dayAndDate.': <br><br>';
        $message .= '<table rules="all" style="border-color: #666;" cellpadding="10" border="6" >';
        $message .= "<tr style='background: #eee;'><td>Subject</td><td>Timing</td><td>Room</td></tr>";

        for ($i=0; $i<sizeof($sections); $i++) {
            if ($subjects[$i] == 12) $sections[$i] = preg_replace('/[0-9]+/', '', $sections[$i]); // DLD-Lab only have sections like A/B and not A1/B1/A2/B2
            $sql = "SELECT `short` FROM subjects WHERE `id` = '$subjects[$i]'";
            $row = $conn->query($sql)->fetch_assoc();
            $short = $row['short'];
            $section = $sections[$i];
            foreach ($worksheet->getColumnIterator() as $column) {
                $cellIterator = $column->getCellIterator();
                foreach ($cellIterator as $cell) {
                    if (!is_null($cell) && !is_null($cell->getCalculatedValue())) {
                        if (foundClass($cell->getCalculatedValue(), $short, $section)) {
                            $colindex = substr($cell->getCoordinate(), 0, 1);
                            $rowindex = substr($cell->getCoordinate(), 1, 2);
                            $timing = $spreadsheet->getActiveSheet()->getCell($colindex . '3')->getValue();
                            $room = $spreadsheet->getActiveSheet()->getCell('A' . $rowindex)->getValue();
                            $subject = $cell->getCalculatedValue();

                            // Manipulate $timing for labs (assumes that labs are of 3 hours)
                            if (strpos($cell->getCalculatedValue(), "Lab") !== false) {
                                $firstTiming = explode('-', $spreadsheet->getActiveSheet()->getCell($colindex . '3')->getValue());
                                $colindex++; $colindex++;
                                $lastTiming = explode('-', $spreadsheet->getActiveSheet()->getCell($colindex . '3')->getValue());
                                $timing = $firstTiming[0].'-'.$lastTiming[1];
                            }

                            $entries[$current]['subject'] = $subject;
                            $entries[$current]['timing'] = $timing;
                            $entries[$current]['room'] = $room;
                            $current++;
                        }
                    }
                }
            }
        }

        // Only go here if $entries is not empty
        if (!empty($entries)) {
            // Sorts $entries with respect to timing, cmp is a custom function in functions.php
            usort($entries, "cmp");
            for ($i=0; $i<sizeof($entries); $i++){
                $message .= "<tr><td><strong>".$entries[$i]['subject']."</strong> </td><td>".$entries[$i]['timing']."</td><td>".$entries[$i]['room']."</td></tr>";
            }
        }
        $message .= "</table>";
        $message .= "<br>Version of timetable being used: ".$version."<br>";
        $message .= '<br><b>DO NOT RELY ON THIS, MUST DOUBLE-CHECK</b>';
        $message .= "</body></html>";
        echo $message;

        // Only send emails if development mode (in functions.php) is false
        if (!$developmentMode && !empty($entries)) {
            $mail->clearAddresses();
            $mail->addAddress($email, $name);
            $mail->Subject = "TimeTable Notifier";
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