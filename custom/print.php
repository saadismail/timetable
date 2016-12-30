<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

if (isset($_GET["section"]) && isset($_GET["batch"])) {
    $section = $_GET["section"];
    if (strlen($section) > 1) {
        die('Section must be of 1 alphabet');
    }
    $batch = $_GET["batch"];
    if ($batch > 16 || $batch < 13) {
        die('I am only taught for Batch 13 to Batch 16');
    }
} else {
    die('Stop playing with me!!');
}

# Assign colors for batch
if ($batch == 16) {
    $color = '66FF33';
} else if ($batch == 15) {
    $color = 'FF66CC';
} else if ($batch == 14) {
    $color = '00B0F0';
} else if ($batch == 13) {
    $color = 'F79646';
} else {
    die('Something went wrong');
}

date_default_timezone_set('Asia/Karachi');
$julianday = gregoriantojd(date('m'),date('d'),date('Y'));
$day_of_week = jddayofweek($julianday);

if ($day_of_week > 5) {
    die('Enjoy the weekend');
}

/** PHPExcel_IOFactory */
require_once dirname(__FILE__) . '/../Classes/PHPExcel/IOFactory.php';

$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load("./BSCS.xlsx");
$worksheet = $objPHPExcel->setActiveSheetIndex(($day_of_week-1));
echo "<table style='text-align: center' border='4' class='stats' cellspacing='0'>";
//foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
    echo "<tr><th>Room</th><th>Timing</th><th>Subject</th></tr>";
	foreach ($worksheet->getColumnIterator() as $column) {
		$cellIterator = $column->getCellIterator();
		foreach ($cellIterator as $cell) {
			if (!is_null($cell) && !is_null($cell->getCalculatedValue()) && $cell->getStyle()->getFill()->getStartColor()->getRGB() == $color) {
                if (strpos($cell->getCalculatedValue(), '-'.$section) !== false) {Note:
//                if (preg_match('/-I/', $cell->getCalculatedValue()) ) {
                    $colindex = substr($cell->getCoordinate(), 0, 1);
                    $rowindex = substr($cell->getCoordinate(), 1, 2);
                    $timing =  $objPHPExcel->getActiveSheet()->getCell($colindex.'3')->getValue();
                    $room = $objPHPExcel->getActiveSheet()->getCell('A'.$rowindex)->getValue();
                    $subject = $cell->getCalculatedValue();
                    echo "<tr><td>".$room.'</td><td>'.$timing.'</td><td>'.$subject."</tr>".EOL;
                }
			}
		}
	}
//}