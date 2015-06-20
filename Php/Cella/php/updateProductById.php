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
	
	$product_id = $_POST['product_id'];
	$category = $_POST['category'];
	$subcategory = $_POST['subcategory'];
	$price = $_POST['price'];
	$discount_percent = $_POST['discount_percent'];
	
	$sql = "UPDATE product SET category='$category', subcategory='$subcategory',price='$price', discount_percent='$discount_percent'  WHERE product_id='$product_id'";
	$responses = array();
	$result = mysql_query($sql);
	
	if ($result) 
	{
		$response = array('update_status' => '1');
		$responses[] = $response;
		echo json_encode($responses);
	}
	else
	{
		$response = array('update_status' => '-1');
		$responses[] = $response;
		echo json_encode($responses);
	}
	
	
	
	
	
?>