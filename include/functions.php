<?php

// Current version of timetable being used
$version="V1.5";

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

// To compare timings using usort() function
function cmp($a, $b)
{
    $timing1 = intval(current(explode('-', $a['timing'])));
    $timing2 = intval(current(explode('-', $b['timing'])));
    if ($timing1 >= 8 && $timing2 >= 8 || $timing1 <= 3 && $timing2 <= 3) {
        return ($timing1 < $timing2) ? -1 : 1;
    }
    return ($timing1 < $timing2) ? 1 : -1;
}

?>
