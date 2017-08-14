<?php

require dirname(__FILE__) . '/include/db.php';
require dirname(__FILE__) . '/include/email.php';

date_default_timezone_set('Asia/Karachi');
$julianday = gregoriantojd(date('m'),date('d'),date('Y'));
$day_of_week = jddayofweek($julianday);

/** PHPExcel_IOFactory */
require_once dirname(__FILE__) . '/Classes/PHPExcel/IOFactory.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(true);
try {
    if(file_exists("include/BSCS-modified.xlsx")) {
        $objPHPExcel = $objReader->load("include/BSCS-modified.xlsx");
    } else {
        $objPHPExcel = $objReader->load("include/BSCS.xlsx");
    }
} catch (Exception $e) {
    echo $e->getMessage();
    die();
}

$worksheet = $objPHPExcel->setActiveSheetIndex(($day_of_week));

$sql = "SELECT `active`, `name`, `email`, `subjects`, `sections` FROM students";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Skip if the user has not verified his email address yet
        if ($row['active'] == 0) {
            continue;
        }
        $email = $row['email'];
        $name = $row['name'];

        $subjects = $row['subjects'];
        $subjects = explode(',', $subjects);

        $sections = $row['sections'];
        $sections = explode(',', $sections);

        $message = '<html><head>';
        $message .= '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">';
        $message .= '</head>';
        $message .= '<body>';
        $message .= '<div class="table-responsive">';
        $message .= '<table class="table table-striped table-hover" style="width: auto;" border="6">';
        $message .= '<thead class="thead-inverse"><tr><th>Subject</th><th>Timing</th><th>Room</th></tr></thead>';


        for ($i=0; $i<sizeof($sections); $i++) {
            $sql = "SELECT `short` FROM subjects WHERE `id` = '$subjects[$i]'";
            $row = $conn->query($sql)->fetch_assoc();
            $short = $row['short'];
            $section = $sections[$i];

            foreach ($worksheet->getColumnIterator() as $column) {
                $cellIterator = $column->getCellIterator();
                foreach ($cellIterator as $cell) {
                    if (!is_null($cell) && !is_null($cell->getCalculatedValue())) {
                        if (strpos($cell->getCalculatedValue(), $short) !== false) {
                            if (strripos($cell->getCalculatedValue(), '+' . $section) !== false || strripos($cell->getCalculatedValue(), ' ' . $section . ' ') !== false  || strripos($cell->getCalculatedValue(), ' ' . $section . '(') !== false || strripos($cell->getCalculatedValue(), '-' . $section) !== false || strripos($cell->getCalculatedValue(), ',' . $section) !== false || strripos($cell->getCalculatedValue(), $section . ',') !== false) {
                                $colindex = substr($cell->getCoordinate(), 0, 1);
                                $rowindex = substr($cell->getCoordinate(), 1, 2);
                                $timing = $objPHPExcel->getActiveSheet()->getCell($colindex . '3')->getValue();
                                $room = $objPHPExcel->getActiveSheet()->getCell('A' . $rowindex)->getValue();
                                $subject = $cell->getCalculatedValue();
                                $message .= '<tr><td>'.$subject.'</td><td>'.$timing.'</td><td>'.$room.'</td></tr>';
                            }
                        }
                    }
                }
            }
        }

        $message .= '</table>';
        $message .= '</div>';
        $message .= '</body>';
        $message .= '</html>';
        echo $message;

        // $mail->clearAddresses();
        // $mail->addAddress($email, $name);
        // $mail->Subject = "Tomorrow's classes";
        // $mail->Body = $message;

        // if (! $mail->send()) {
        //     die("Something went wrong");
        // }
    }
}

$conn->close();

?>