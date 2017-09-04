<?php
	require "PHPMailerAutoload.php";

	$mail = new PHPMailer();

	// Uncomment to debug smtp connection/any other email issue
    // $mail->SMTPDebug = 3;

    // Uncomment to debug SSL/curl related issues
 //    $mail->SMTPOptions = array(
	//     'ssl' => array(
	//         'verify_peer' => false,
	//         'verify_peer_name' => false,
	//         'allow_self_signed' => true
	//     )
	// );

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->isHTML(true);                                  // Set email format to HTML
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 587;                                    // TCP port to connect to
	$mail->XMailer = ' ';								  // Remove PHPMailer from headers
	$mail->From = ' ';
	$mail->setFrom('smtp@timetable.host', 'TimeTable Notifier');
	$mail->addReplyTo('k164060@nu.edu.pk', 'Saad Ismail');

	// SendGrid SMTP
	$mail->Host = 'smtp.sendgrid.net';  // Specify main and backup SMTP servers
	$mail->Username = 'apikey';                 // SMTP username
	$mail->Password = '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';                           // SMTP password

	// MXRoute SMTP
	// $mail->Host = 'ghost.mxroute.com';  // Specify main and backup SMTP servers
	// $mail->Username = 'smtp@timetable.host';                 // SMTP username
	// $mail->Password = 'dQFPDE2LWN6c';                           // SMTP password
	
	
?>