<?php
	date_default_timezone_set('Etc/UTC');
	require 'PHPMailer-master/PHPMailerAutoload.php';
	session_start(); 
	include("../php/connect.php");

	$email = $_POST["new_user_email"];
	$name = $_POST["new_user_name"];
	$pswd = $_POST["new_user_pswd"];
	if (empty($email) || empty($name)|| empty($pswd))
	{
		echo "<script>alert('Please fill empty fields');window.location ='login.php'</script>";
	}
	else
	{
		$sql = "SELECT * FROM user_login WHERE email='$email' LIMIT 1";

		$result=mysql_query($sql);
		$count=mysql_num_rows($result);	
		$obj = mysql_fetch_object($result);
		if($count==1)
		{
		
			echo "<script>alert('User with ".$email." email Id already present.');window.location ='login.php'</script>";
		}
		else 
		{
			$username_sql = mysql_query("INSERT INTO user_login (user_id,username,email,password)
							VALUES (NULL,'$name','$email','$pswd')");
			$_SESSION['username'] = $name;
			$_SESSION['password'] = $pswd;
			$_SESSION['email'] = $email;
			$result=mysql_query($sql);
			$obj = mysql_fetch_object($result);
			//echo $obj->user_id;
		$domain_link = "$_SERVER[HTTP_HOST]";	
		$subject = "Welcome to ".$domain_link ;
		$body = "Dear <b>".$name."</b>,<br>";
		$body .= "<p>Thank you for signing up for your online account at ".$domain_link.".</p>";
		$body .= "<p>To access your account, please visit your ".$domain_link." account. You will need to provide this e-mail address and your password each time you sign in.</p>";
		$body .= "Your e-mail: <b>".$email."</b><br><br>";
		$body .= "<p>Your password is your personal gateway into your account. Please do not disclose/share this password to anyone. To protect your account, you shall need to confirm your password each time you visit. You can change your password as often as you like after your first visit.</p>";
		$body .= "<b>Happy shopping with ".$domain_link." </b><br><br>";	
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
		$mail->setFrom('XXXXX@gmail.com', $domain_link);//change your XXXXX by your gmail email-ID

		//Set an alternative reply-to address
		$mail->addReplyTo('XXXXX@gmail.com', $domain_link);//change your XXXXX by your gmail email-ID

		//Set who the message is to be sent to
		$mail->addAddress($email, $name);
		//Set the subject line
		$mail->Subject = $subject;
		$mail->Body =$body;


		//Replace the plain text body with one created manually
		$mail->AltBody = 'Welcome to '.$domain_link;                
		 
		$mail->send();
		echo "<script>window.location ='user_account.php'</script>";
				
		}
	}

?> 