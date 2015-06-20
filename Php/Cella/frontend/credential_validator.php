<?php

session_start(); 

include("../php/connect.php");
$email = $_POST["user_email"];
$pswd = $_POST["pswd"];
$return_url = $_POST["return_url"];


	$sql = "SELECT * FROM user_login WHERE email='$email'and password='$pswd' LIMIT 1";

	$result=mysql_query($sql);
	$count=mysql_num_rows($result);	
	$obj = mysql_fetch_object($result);
	if($count==1)
	{
	//echo $obj->username;
		$username = addslashes($obj->username);
		$password = addslashes($pswd);

		$username= stripslashes($username);
		$password = stripslashes($password);


		$username = mysql_real_escape_string($username);
		$password = mysql_real_escape_string($password);
		$_SESSION['username'] = $username;
		$_SESSION['password'] = $password;
		$_SESSION['email'] = $email;
		echo "<script>window.location ='".$return_url."'</script>";
    }
	else 
	{
		echo "<script>alert('Invalid Credentials');window.location ='login.php'</script>";
         	
    }

?> 

