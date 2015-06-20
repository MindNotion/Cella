<?php
	session_start(); 
	include("../php/connect.php");


	$user_id  = $_POST["user_id"];
	$sex = $_POST["sex"];
	$username = $_POST["username"];
	$contact_number = $_POST["contact_number"];
	$ship_add  = $_POST["ship_add"];
	$city  = $_POST["city"];
	$state  = $_POST["state"];
	$pincode = $_POST["pincode"];
	
	if (empty($user_id) || empty($sex)|| empty($pincode)||empty($contact_number) || empty($ship_add)|| empty($city)|| empty($state))
	{
		echo "<script>alert('Please fill empty fields');window.location ='user_account.php'</script>";
	}
	else
	{	
		$sql = "SELECT * FROM user_details WHERE user_id='$user_id' LIMIT 1";
		$result=mysql_query($sql);
		$count=mysql_num_rows($result);
		if($count==1)
		{
			$sql = "UPDATE user_details SET gender='$sex', telephone='$contact_number', shipping_address='$ship_add',
					city='$city',state='$state', pincode='$pincode' WHERE user_id='$user_id' LIMIT 1";
			$retval = mysql_query( $sql);
		}else
		{
			
			$username_sql = mysql_query("INSERT INTO user_details (user_id,gender,telephone,shipping_address,city,state,pincode)
						VALUES ('$user_id','$sex','$contact_number','$ship_add','$city','$state','$pincode')");
		}
		
		echo "<script>window.location ='checkout.php'</script>";				
		
	}

?> 