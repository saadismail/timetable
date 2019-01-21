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

// Remove first un-necessary sheets from modified file, only 5 sheets should be there at the end.
while ($objPHPExcel->getSheetCount() > 5) {
    $objPHPExcel->removeSheetByIndex(0);
}

// Go into every sheet, remove "(", ")" & the content in between to avoid ambiguity in the section checks
for ($i=0; $i<5; $i++) {
    $worksheet = $objPHPExcel->setActiveSheetIndex(($i));
    foreach ($worksheet->getColumnIterator() as $column) {
        $cellIterator = $column->getCellIterator();
        foreach ($cellIterator as $cell) {
            if (!is_null($cell) && !is_null($cell->getCalculatedValue())) {
                $isAllSections = false;
                $string =  $cell->getCalculatedValue();

                if (strpos($string, 'All Sections') !== false) {
                    $isAllSections = true;
                }
                // $string2 =  preg_replace("/\([^)]+\)/","",$string);
                // Remove "(", ")" & the content in between to avoid ambiguity in the section checks
                $string =  preg_replace("/\(([^()]*+|(?R))*\)/","", $string);

                //Trim extra spaces
                $string = preg_replace('/\s+/', ' ', $string);
                $string = trim($string);

                $string = str_replace("GR", "Gr", $string);

                if ($isAllSections) {
                    $string = $string . ' All Sections';
                }

                $worksheet->setCellValue($cell->getCoordinate(), $string);
            }
        }
    }
}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(dirname(__FILE__)."/../include/BSCS-modified.xlsx");

?>
