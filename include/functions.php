<?php

// Current version of timetable being used
$cs_tt_version="V3";

// Don't send emails if development mode is on
$developmentMode = False;

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

function foundClass ($email, $subject, $short, $section) {
    if ((strpos($subject, $short . ' ') !== false || strpos($subject, $short . '-') !== false) && strpos(ltrim($subject), $short) === 0) {

        // Dont show labs for course classes
        if (strpos($short, "Lab") == false && strpos($subject, "Lab") == false || strpos($short, "Lab") !== false && strpos($subject, "Lab") !== false) {
            if (strpos($subject, 'All Sections') !== false) {
                return true;
            }

            if (strpos($subject, ' ' . $section . ' ') !== false  || strpos($subject, '-' . $section) !== false || strpos($subject, ' ' . $section . '+') !== false  || strpos($subject, '+' . $section . ' ') !== false ) {
                // Dirty fix for Cal-II classes shown for Cal-I with section I (reported by k181268@nu.edu.pk)
                if (strpos($subject, 'Cal-II') !== false && strpos($short, 'Cal-II') === false) {
                    return false;
                }

                return true;
            }
        }
    }

    return false;
}

function removeAllDashes($string) {
    return str_replace("-", "", $string);
}

function transformSectionCase($section) {
    $section = strtoupper($section);
    $section = str_replace("GR", "Gr", $section);
    return $section;
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
