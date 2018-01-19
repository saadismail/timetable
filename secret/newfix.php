<?php

require_once dirname(__FILE__) . '/../vendor/autoload.php';

$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
$reader->setReadDataOnly(true);

try {
    $spreadsheet = $reader->load(dirname(__FILE__) . "/../include/BSCS.xlsx");
} catch (Exception $e) {
    die($e->getMessage());
}

// Remove first un-necessary sheets from modified file, only 5 sheets should be there at the end.
while ($spreadsheet->getSheetCount() > 5) {
    $spreadsheet->removeSheetByIndex(0);
}

// Go into every sheet, remove "(", ")" & the content in between to avoid ambiguity in the section checks
for ($i=0; $i<5; $i++) {
    $worksheet = $spreadsheet->setActiveSheetIndex(($i));
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

$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->save(dirname(__FILE__)."/../include/BSCS-modified.xlsx");

?>
