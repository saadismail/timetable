<?php

// Current version of timetable being used
$version="V1.5";

// Don't send emails if development mode is on
$developmentMode = True;

// Google ReCaptcha
$recaptchaSiteKey = "6LcPUjIUAAAAAEXaFcTIOAzvghTo8TpbTFsxA2tv";
$recaptchaSecretKey = "6LcPUjIUAAAAADzatC4WoqQVnZbRLULoJ9oJRe9f";

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

function encrypt( $string ) {
    $secret_key = 'MwREkEFS1L98D2sapTfPsHpMwAZT1wSm';
    $secret_iv = 'WgutdoxidsxcZz4DKGA1tMT1Z77566Ec';

    return openssl_encrypt($string, "AES-256-CBC", $secret_key, 0, $secret_iv);
}

function decrypt( $string ) {
    $secret_key = 'MwREkEFS1L98D2sapTfPsHpMwAZT1wSm';
    $secret_iv = 'WgutdoxidsxcZz4DKGA1tMT1Z77566Ec';

    return openssl_decrypt($string, "AES-256-CBC", $secret_key, 0, $secret_iv);
}

?>