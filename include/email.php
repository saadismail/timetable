<?php
	require "PHPMailerAutoload.php";

	$mail = new PHPMailer();

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->isHTML(true);                                  // Set email format to HTML
	$mail->Host = 'ghost.mxroute.com';  // Specify main and backup SMTP servers
 //    $mail->SMTPDebug = 3;
 //    $mail->SMTPOptions = array(
	//     'ssl' => array(
	//         'verify_peer' => false,
	//         'verify_peer_name' => false,
	//         'allow_self_signed' => true
	//     )
	// );
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'smtp@saad.ninja';                 // SMTP username
	$mail->Password = 'HbSecdpzbxRq';                           // SMTP password
	$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 587;                                    // TCP port to connect to

	$mail->setFrom('smtp@saad.ninja', 'TimeTable Notifier');
	$mail->addReplyTo('k164060@nu.edu.pk', 'Saad Ismail');
?>