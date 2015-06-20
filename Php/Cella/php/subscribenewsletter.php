<?php
date_default_timezone_set('Etc/UTC');
require '../php/PHPMailer-master/PHPMailerAutoload.php';
session_start(); 

	$email=$_POST["email"];
	$domain_link = "$_SERVER[HTTP_HOST]";

	$subject = $domain_link." newsletter subscriber email Id" ; 
	$body = "E-mail: <b>".$email."</b><br>";		
	$body .= "<a href='http://www.mindnotion.com/project.htm'><img src='http://www.mindnotion.com/images/big_logo.png' height='42' width='42' >Cella Free E-Commerce Solution. Download now!</a>";		
	$mail = new PHPMailer;

	//Tell PHPMailer to use SMTP
	$mail->isSMTP();

	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$mail->SMTPDebug = 0;

	//Ask for HTML-friendly debug output
	$mail->Debugoutput = 'html';

	//Set the hostname of the mail server
	$mail->Host = 'smtp.gmail.com';

	//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
	$mail->Port = 587;

	//Set the encryption system to use - ssl (deprecated) or tls
	$mail->SMTPSecure = 'tls';

	//Whether to use SMTP authentication
	$mail->SMTPAuth = true;

	//Username to use for SMTP authentication - use full email address for gmail
	$mail->Username = "XXXXX@gmail.com"; //change your XXXXX.com by your e-commerce gmail ID

	//Password to use for SMTP authentication
	$mail->Password = "your gmail password"; //replace this by your gmail password

	//Set who the message is to be sent from
	$mail->setFrom('XXXXX@gmail.com', $domain_link); //change your XXXXX by your gmail e-mail Id

	//Set an alternative reply-to address
	$mail->addReplyTo('XXXXX@gmail.com', $domain_link); //change your XXXXX by your gmail e-mail Id

	//Set who the message is to be sent to
	$mail->addAddress('XXXXX@gmail.com', $domain_link); //change your XXXXX by your gmail e-mail Id
	//Set the subject line
	$mail->Subject = $subject;
	$mail->Body =$body;


	//Replace the plain text body with one created manually
	$mail->AltBody = $domain_link.' newsletter subscriber email Id'; 
			
	 
	if (!$mail->send()) 
	{		
		$response = array('Result'=>'$mail->ErrorInfo','Success'=>'0');
		$responses[] = $response;

		echo json_encode($response);
	} 
	else 
	{
		$response = array('Result'=>'Thank you for subscribing newsletter from us!','Success'=>'1');
		$responses[] = $response;

		echo json_encode($response);
		
	}	
   

?> 