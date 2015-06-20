<?php

session_start(); 

include("connect.php");

$email=$_POST["email"];
 

	$sql = "SELECT * FROM login WHERE username='$email'";

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
		$to =$email;
		$domain_link = "$_SERVER[HTTP_HOST]";
		$subject = "Admin / database login credentials of ".$domain_link ;
		$body = "User name: ".$username."\n";
		$body .= "Password: ".$password."\n";
		$body .= "E-mail: ".$email."\n";
		$body .= "<a href='http://www.mindnotion.com/project.htm'><img src='http://www.mindnotion.com/images/big_logo.png' height='42' width='42' >Cella Free E-Commerce Solution. Download now!</a>";		
		
		if (!mail($to, $subject, $body)) 
		{
			echo "<script>alert('Message delivery failed.');window.location ='../login.html'</script>";
		}
		else		
			echo "<script>alert('Login credentials had send to respective email address');window.location ='../login.html'</script>";
       }
	else 
	{
		echo "<script>alert('Invalid email address');window.location ='../login.html'</script>";
         	
    }

?> 

