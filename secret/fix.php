<?php

require_once dirname(__FILE__) . '/../Classes/PHPExcel/IOFactory.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(true);

try {
    $objPHPExcel = $objReader->load(dirname(__FILE__) . "/../include/BSCS.xlsx");
} catch (Exception $e) {
    echo $e->getMessage();
    die();
}

for ($i=0; $i<5; $i++) {
    $worksheet = $objPHPExcel->setActiveSheetIndex(($i));
    foreach ($worksheet->getColumnIterator() as $column) {
        $cellIterator = $column->getCellIterator();
        foreach ($cellIterator as $cell) {
            if (!is_null($cell) && !is_null($cell->getCalculatedValue())) {
                $string =  $cell->getCalculatedValue();
                // $string2 =  preg_replace("/\([^)]+\)/","",$string);
                $string2 =  preg_replace("/\(([^()]*+|(?R))*\)/","", $string);
                $worksheet->setCellValue($cell->getCoordinate(), $string2);
            }
        }
    }
}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(dirname(__FILE__)."/../include/BSCS-modified.xlsx");


?>
