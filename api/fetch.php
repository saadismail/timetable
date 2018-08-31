<?php

require dirname(__FILE__) . '/../include/db.php';
require dirname(__FILE__) . '/../include/functions.php';

require_once dirname(__FILE__) . '/../vendor/autoload.php';

$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
$reader->setReadDataOnly(true);

try {
    $spreadsheet = $reader->load(dirname(__FILE__) . "/../include/BSCS-modified.xlsx");
} catch (Exception $e) {
    die($e->getMessage());
}

$response = array();
$color_hexs = array("E8E39F", "FF8B94", "D2C0F2", "E3AFBC",  "EDC7B7", "C9D79C", "84CEEB", "BEC6D5", "8EE4AF", "DAAD86");

if (isset($_GET['email'])) {
	$email = $_GET['email'];

	$sql = "SELECT `id`, `active`, `name`, `email`, `subjects`, `sections` FROM students WHERE `email` = $email";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();

		$id = $row['id'];
        $email = $row['email'];
        $name = $row['name'];

        $subjects = $row['subjects'];
        $subjects = explode(',', $subjects);

        $sections = $row['sections'];
        $sections = explode(',', $sections);

        $response['success'] = 1;
        $response['message'] = "Successfully recieved.";
        $response['id'] = $id;
        $response['name'] = $name;
        $response['email'] = $email;

        for ($day=0; $day<=4; $day++) {
        	$worksheet = $spreadsheet->setActiveSheetIndex($day);
        	$current = 0;

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

	                    	if (foundClass($email, $cell->getCalculatedValue(), $short, $section)) {
	                    		$colindex = substr($cell->getCoordinate(), 0, 1);
	                            $rowindex = substr($cell->getCoordinate(), 1, 2);
	                            $timing = $spreadsheet->getActiveSheet()->getCell($colindex . '3')->getValue();
	                            $room = $spreadsheet->getActiveSheet()->getCell('A' . $rowindex)->getValue();
	                            $subject = $cell->getCalculatedValue();

	                            // Manipulate $timing for labs (assumes that labs are of 3 hours)
	                            if (strpos($cell->getCalculatedValue(), "Lab") !== false) {
	                                $firstTiming = explode('-', $spreadsheet->getActiveSheet()->getCell($colindex . '3')->getValue());
	                                if (strpos($cell->getCalculatedValue(), "Eng") !== false || strpos($cell->getCalculatedValue(), "ICT") !== false) {
	                                    $colindex++;
                                    } else {
                                        $colindex++; $colindex++;
                                    }
	                                $lastTiming = explode('-', $spreadsheet->getActiveSheet()->getCell($colindex . '3')->getValue());
	                                $timing = $firstTiming[0].'-'.$lastTiming[1];
	                            }

	                            $entries[$current]['subject'] = $subject;
	                            $entries[$current]['timing'] = $timing;
	                            $entries[$current]['room'] = $room;
	                            $entries[$current]['color'] = $color_hexs[$i];
	                            $current++;
	                    	}
	                    }
	                }
	            }
			}

			if (!empty($entries)) {
	            // Sorts $entries with respect to timing, cmp is a custom function in functions.php
	            usort($entries, "cmp");
	        }

	        $response[$day] = $entries;
	        unset($entries);
	    }

	    $response['tt_version'] = $version;
        echo json_encode($response);

	} else {
		$response['success'] = 0;
		$response['message'] = "Student not found!";
		echo json_encode($response);
	}

} else {
	$response['success'] = 0;
	$response['message'] = "Not enough arguments provided.";
	echo json_encode($response);
}


?>