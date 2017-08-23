<?php

$message = '<html><body>';
$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
$message .= "<tr style='background: #eee;'><td><strong>Subject:</strong> </td><td>Timing</td><td>Room</td></tr>";
$message .= "<tr><td><strong>".$subject."</strong> </td><td>".$timing."</td><td>".$room."</td></tr>";
$message .= "</table>";
$message .= "</body></html>";

echo $message;

?>