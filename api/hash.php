<?php

require_once "../include/functions.php";

if (isset($_GET['data'])) {

	$data = $_GET['data'];

	echo $data."<br>";

	echo encrypt($data)."<br>";
	echo "http://localhost/api/fetch.php?payload=".urlencode(encrypt($data));
	echo '<br>';
    echo decrypt(encrypt($data))."<br>";


	// $crypted = crypt($data, 'e')."<br>";
	// echo $crypted.'<br>';
	// echo crypt($crypted, 'd')."<br>";


}



?>