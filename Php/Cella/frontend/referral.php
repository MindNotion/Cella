<?php
	session_start(); 
	include("../php/connect.php");


	$user_id  = $_POST["userid"];
	$ref_emails= trim($_POST["ref_email"]);
	
	if (empty($ref_emails))
	{
		echo "<script>alert('Please fill referral fields');window.location ='user_account.php'</script>";
	}
	else
	{	
		$email_add = array();
		$email_add = explode(",",$ref_emails);

			for($i = 0 ; $i< count($email_add) ; $i++)
			{
				$username_sql = mysql_query("INSERT INTO user_referrals (user_id,referral_email,email_send_status)
						VALUES ('$user_id','$email_add[$i]','1')");
			}
				
		
		$to ='customercare@kapdautsav.com';
		$subject = "Referrals emails Id's" ;
		$body = "User Id: ".$user_id."\n";
		$body .= "Referrals emails: ".$ref_emails."\n";	
		
		mail($to, $subject, $body);
		
		echo "<script>alert('Thank you for referral e-mail Ids');window.location ='../index.php'</script>";				
		
	}

?> 