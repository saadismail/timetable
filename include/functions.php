<?php

// Don't send emails if development mode is on
$developmentMode = True;

// Returns a random string of length = $length
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Displays $message in javascript alert box
function alertUser($message) {
	echo "<script type='text/javascript'>alert('".$message."');</script>";
}


?>