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
	if (isset($_POST['action'])) {
		$_SESSION['product_image_count'] = $_POST['action'];

	}
  
echo $_SESSION['product_image_count'];

?><? ob_flush(); ?>