<?php

require dirname(__FILE__) . '/include/db.php';
require dirname(__FILE__) . "/include/functions.php";
require dirname(__FILE__) . '/include/email.php';

date_default_timezone_set('Asia/Karachi');


// // // Comment this to make this work
// die();


/** PHPExcel_IOFactory */
require_once dirname(__FILE__) . '/Classes/PHPExcel/IOFactory.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(true);
try {
    $objPHPExcel = $objReader->load(dirname(__FILE__) . "/include/BSCS-modified.xlsx");
} catch (Exception $e) {
    echo $e->getMessage();
    die();
}

$message = "";
$dowMap = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri');

for ($day_of_week=0; $day_of_week<=4; $day_of_week++) {

// $day_of_week-1 as sheet starts from 0 while $day_of_week has 1 for Monday
$worksheet = $objPHPExcel->setActiveSheetIndex(($day_of_week));

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

        $message .= '<br><html><body>';
        $message .= "Hi ".$name.",<br><br>";
        $message .= 'Below is the schedule of your classes on '.$dowMap[$day_of_week].': <br><br>';
        $message .= '<table rules="all" style="border-color: #666;" cellpadding="10" border="6" >';
        $message .= "<tr style='background: #eee;'><td>Subject</td><td>Timing</td><td>Room</td></tr>";

        for ($i=0; $i<sizeof($sections); $i++) {
            $sql = "SELECT `short` FROM subjects WHERE `id` = '$subjects[$i]'";
            $row = $conn->query($sql)->fetch_assoc();
            $short = $row['short'];
            $section = $sections[$i];
            foreach ($worksheet->getColumnIterator() as $column) {
                $cellIterator = $column->getCellIterator();
                foreach ($cellIterator as $cell) {
                    if (!is_null($cell) && !is_null($cell->getCalculatedValue())) {
                        if (strpos($cell->getCalculatedValue(), $short) !== false && strpos(ltrim($cell->getCalculatedValue()), $short) === 0) {
                            // Dont show labs for course classes
                            if (strpos($short, "Lab") == false && strpos($cell->getCalculatedValue(), "Lab") == false || strpos($short, "Lab") !== false && strpos($cell->getCalculatedValue(), "Lab") !== false) {
                                if (strripos($cell->getCalculatedValue(), '+' . $section) !== false || strripos($cell->getCalculatedValue(), ' ' . $section . ' ') !== false  || strripos($cell->getCalculatedValue(), ' ' . $section . '(') !== false || strripos($cell->getCalculatedValue(), '-' . $section) !== false || strripos($cell->getCalculatedValue(), ',' . $section) !== false || strripos($cell->getCalculatedValue(), $section . ',') !== false) {
                                    $colindex = substr($cell->getCoordinate(), 0, 1);
                                    $rowindex = substr($cell->getCoordinate(), 1, 2);
                                    $timing = $objPHPExcel->getActiveSheet()->getCell($colindex . '3')->getValue();
                                    $room = $objPHPExcel->getActiveSheet()->getCell('A' . $rowindex)->getValue();
                                    $subject = $cell->getCalculatedValue();
                                    // Manipulate $timing for labs (assumes that labs are of 3 hours)
                                    if (strpos($cell->getCalculatedValue(), "Lab") !== false) {
                                        $firstTiming = explode('-', $objPHPExcel->getActiveSheet()->getCell($colindex . '3')->getValue());
                                        $colindex++; $colindex++;
                                        $lastTiming = explode('-', $objPHPExcel->getActiveSheet()->getCell($colindex . '3')->getValue());
                                        $timing = $firstTiming[0].'-'.$lastTiming[1];
                                    }
                                    $entries[$current]['subject'] = $subject;
                                    $entries[$current]['timing'] = $timing;
                                    $entries[$current]['room'] = $room;
                                    $current++;
                                    // echo $short." ".$subject."<br>";
                                }
                            }
                        }
                    }
                }
            }
        }

        // Only go here if $entries is not empty
        if (!empty($entries)) {
            // Sorts $entries with respect to timing
            usort($entries, "cmp");
            for ($i=0; $i<sizeof($entries); $i++){
                $message .= "<tr><td><strong>".$entries[$i]['subject']."</strong> </td><td>".$entries[$i]['timing']."</td><td>".$entries[$i]['room']."</td></tr>";
            }
        }
        $message .= "</table>";
        $message .= "<br>Version of timetable being used: ".$version."<br>";
        $message .= '<br><b>DO NOT RELY ON THIS, MUST DOUBLE-CHECK</b>';
        $message .= "</body></html>";
        unset($entries);
        echo "<br> <br>";
    }
}
}


        echo $message;

$conn->close();

?>