<?php

function cmp($a, $b)
{
	$timing1 = $a; $timing2 = $b;
    if ($timing1 >= 8 && $timing2 >= 8 || $timing1 <= 3 && $timing2 <= 3) {
        return ($timing1 < $timing2) ? -1 : 1;
    }
    return ($timing1 < $timing2) ? 1 : -1;

}

echo cmp(3, 12);
?>