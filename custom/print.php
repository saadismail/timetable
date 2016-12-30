<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Today's schedule?</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<?php

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

if (isset($_GET["section"]) && isset($_GET["batch"])) {
    $section = $_GET["section"];
    if (strlen($section) > 3 || !preg_match("/^[a-i]$/i", $section) && !preg_match("/^GR[1-9]$/", $section) ) {
        die('Invalid section');
    }

    $batch = $_GET["batch"];
    if ($batch > 16 || $batch < 13) {
        die('Invalid batch (13-16)');
    }
} else {
    die('Stop playing with me!!');
}

date_default_timezone_set('Asia/Karachi');
$julianday = gregoriantojd(date('m'),date('d'),date('Y'));
$day_of_week = jddayofweek($julianday);

if ($day_of_week > 5) {
    die('Enjoy the weekend');
} else {
    echo '<h3>DAY: '.jddayofweek($julianday,1).'</h3>'.EOL;
}

?>
<div class="table-responsive">
<table class="table table-striped table-hover" style="width: auto !important; margin-top: -70px;" border="6">
<thead class="thead-inverse"><tr><th>Room</th><th>Timing</th><th>Subject</th></tr></thead>

<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);


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
/** PHPExcel_IOFactory */
require_once dirname(__FILE__) . '/../Classes/PHPExcel/IOFactory.php';

$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load("./BSCS.xlsx");
$worksheet = $objPHPExcel->setActiveSheetIndex(($day_of_week-1));

foreach ($worksheet->getColumnIterator() as $column) {
    $cellIterator = $column->getCellIterator();
    foreach ($cellIterator as $cell) {
        if (!is_null($cell) && !is_null($cell->getCalculatedValue()) && $cell->getStyle()->getFill()->getStartColor()->getRGB() == $color) {
            if (strripos($cell->getCalculatedValue(), '-'.$section) !== false || strripos($cell->getCalculatedValue(), ','.$section) !== false || strripos($cell->getCalculatedValue(), $section.',') !== false) {
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
?>

</table>
</div>
</body>
</html>