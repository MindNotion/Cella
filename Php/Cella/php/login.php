<?php
ob_start();
session_start(); 

include("connect.php");


	
	if($_POST)
	{
		$_SESSION['username']=$_POST["username"];
		$_SESSION['password']=$_POST["password"];  
		 
	} 

	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
	

	$username = addslashes($username);
	$password = addslashes($password);
	
	$username= stripslashes($username);
	$password = stripslashes($password);
	
	
	$username = mysql_real_escape_string($username);
	$password = mysql_real_escape_string($password);
	

	$sql = "SELECT * FROM admin_login WHERE username = '$username' AND password = '$password'";

	$result=mysql_query($sql);
	$count=mysql_num_rows($result);

	
	if($count>0)
	{
		
		//session_register("username");
		//session_register("password");
		
		$_SESSION['username'] = $username;
		$_SESSION['password'] = $password;
		
		// output data of each row
		while($row=mysql_fetch_array($result,1)) {
		   
			$_SESSION['level'] =$row['level'];
		}
	
		echo "session level".$_SESSION['level'];
		header("Location: miscellaneous.php");
		exit;
		
		
		
	}
	else 
	{
		echo "<script>alert('Login failed');window.location ='../login.html'</script>";
		

    }
ob_flush();

?> 