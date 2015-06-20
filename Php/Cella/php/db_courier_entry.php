<?php
	ob_start();
	session_start(); 
	header('Cache-control: private');
	include("connect.php");

	if(!isset($_SESSION['username']))
	{
	header("Location: ../login.html");
	exit;
	}

	if($_SESSION['level']== 0)
	{
		echo "<script>alert('Permission Denied: No rights to enter products');window.location ='admin_panel.php'</script>";
		exit;
	}
	
	
	$title = $_POST['title'];	
			
	$now = new DateTime();
  
	$amt_free_shipping = $_POST['amt_free_shipping'];
	$shipping_amt = $_POST['shipping_amt'];
		
  
	//copying images to server location
	$path = "../images/home/";		
	//echo $img;
	$img = 'shipping_image';
	$file = $path .'shipping.jpg';
	//echo $file;
	move_uploaded_file($_FILES[$img]['tmp_name'], $file);
	
	
	
	//insert into shipping_image table

	//echo $img;
	$img_path = $file;
	
	$img_sql = mysql_query("INSERT INTO shipping (id,free_shipping_amt,shipping_amt,shipping_img_path)
						VALUES (1,'$amt_free_shipping','$shipping_amt','$img_path')");
	
	
	
	
	echo "<script>alert('Shipping detail stored successfully');window.location ='miscellaneous.php'</script>";
		exit;
	
	
	
?><? ob_flush(); ?>