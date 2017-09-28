<?php

require dirname(__FILE__) . '/../include/db.php';
require dirname(__FILE__) . "/../include/functions.php";
require dirname(__FILE__) . '/../include/email.php';

date_default_timezone_set('Asia/Karachi');
$julianday = gregoriantojd(date('m'),date('d'),date('Y'));
$day_of_week = jddayofweek($julianday-2);

// Stop execution if next day is weekend or invalid $day_of_week
if ($day_of_week < 0 || $day_of_week > 4) die();

/** PHPExcel_IOFactory */
require_once dirname(__FILE__) . '/../Classes/PHPExcel/IOFactory.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(true);
try {
    if(file_exists("../include/BSCS-modified.xlsx")) {
        $objPHPExcel = $objReader->load(dirname(__FILE__) . 
"/../include/BSCS-modified.xlsx");
    } else {
        $objPHPExcel = $objReader->load(dirname(__FILE__) . 
"/../include/BSCS.xlsx");
    }
} catch (Exception $e) {
    echo $e->getMessage();
    die();
}

$worksheet = $objPHPExcel->setActiveSheetIndex(($day_of_week));

$message = '<html><body>';
$message .= 'Below is the schedule of your classes tomorrow: <br><br>';
$message .= '<table rules="all" style="border-color: #666;" cellpadding="10" border="6" >';
$message .= "<tr style='background: #eee;'><td>Subject</td><td>Timing</td><td>Room</td></tr>";

$current = 0;

foreach ($worksheet->getColumnIterator() as $column) {
    $cellIterator = $column->getCellIterator();
    foreach ($cellIterator as $cell) {
        if (!is_null($cell) && !is_null($cell->getCalculatedValue())) {
            if (strpos($cell->getCalculatedValue(), "Anum") !== false) {
                $colindex = substr($cell->getCoordinate(), 0, 1);
                $rowindex = substr($cell->getCoordinate(), 1, 2);
                $timing = $objPHPExcel->getActiveSheet()->getCell($colindex . '3')->getValue();
                $room = $objPHPExcel->getActiveSheet()->getCell('A' . $rowindex)->getValue();
                $subject = $cell->getCalculatedValue();
                $entries[$current]['subject'] = $subject;
                $entries[$current]['timing'] = $timing;
                $entries[$current]['room'] = $room;
                $current++;
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
$message .= '<br><b>DO NOT RELY ON THIS, MUST DOUBLE-CHECK<b>';
$message .= "</body></html>";
echo $message;

// Only send emails if development mode (in functions.php) is false
// if (!$developmentMode && !empty($entries)) {
//     $mail->clearAddresses();
//     $mail->addAddress($email, $name);
//     $mail->Subject = "Tomorrow's classes";
//     $mail->Body = $message;

//     if (! $mail->send()) {
//         die("Something went wrong");
//     }
// }
unset($entries);

$conn->close();

?>
