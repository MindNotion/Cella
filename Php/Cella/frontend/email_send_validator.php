<?php
date_default_timezone_set('Etc/UTC');
require 'PHPMailer-master/PHPMailerAutoload.php';
session_start(); 

include("../php/connect.php");
$email=$_POST["email"];
	$sql = "SELECT * FROM user_login WHERE email='$email'";

	$result=mysql_query($sql);
	$count=mysql_num_rows($result);	
	
	if($count==1)
	{
		
		$i=0;
		while ($i < $count) 
		{
			$username=mysql_result($result,$i,"username");
			$password=mysql_result($result,$i,"password");
			$i++;
		}
		$domain_link = "$_SERVER[HTTP_HOST]";
		$to =$email;
		$subject = "Login credentials of ".$domain_link ;
		$body = "User name: <b>".$username."</b><br>";
		$body .= "Password: <b>".$password."</b><br>";
		$body .= "E-mail: <b>".$email."</b><br><br>";
		$body .= "<b>Thank you for shopping with ".$domain_link." </b><br><br>";	
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
		$mail->Username = "XXXXX@gmail.com";//change your XXXXX by your gmail email-ID

		//Password to use for SMTP authentication
		$mail->Password = "your gmail password"; //replace this by your gmail password

		//Set who the message is to be sent from
		$mail->setFrom('XXXXX@gmail.com', $domain_link); //change your XXXXX by your gmail e-mail Id

		//Set an alternative reply-to address
		$mail->addReplyTo('XXXXX@gmail.com', $domain_link); //change your XXXXX by your gmail e-mail Id

		//Set who the message is to be sent to
		$mail->addAddress($to, $username);
		//Set the subject line
		$mail->Subject = $subject;
		$mail->Body =$body;


		//Replace the plain text body with one created manually
		$mail->AltBody = $domain_link.' credentials';
                
		 
			if (!$mail->send()) {
			
			echo "<script>alert('error".$mail->ErrorInfo."');window.location ='login.php'</script>";
		} else {
			echo "<script>alert('Login credentials had send to respective email address');window.location ='login.php'</script>";
		}	
		
		
    }
	else 
	{
		echo "<script>alert('Invalid email address');window.location ='login.php'</script>";
         	
    }


?> 