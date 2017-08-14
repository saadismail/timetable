<?php

include "../include/db.php";

$_fp = fopen("inp.txt", "r");
while (!feof($_fp)) {
	// $line = explode(" ", trim(fgets($_fp)));
	$line = preg_split('/\s+/', trim(fgets($_fp)));
	$cCode = $line[1];
	echo $cCode."  ";
	$cName = "";
	for ($i=2; $i<10; $i++) {	
		if (!is_numeric(substr($line[$i], 0, 1))) {
			$cName = $cName." ".$line[$i];
		} else {
			break;
		}
	}

	$sql = "SELECT id FROM subjects WHERE `code` = '$cCode'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        continue;
    } else {
    	$sql = "INSERT INTO `subjects` (`id`, `name`, `short`, `code`) VALUES (NULL, '$cName', '', '$cCode')";
    	$result = $conn->query($sql);
    }
	echo $cName."<br>";
}
?>