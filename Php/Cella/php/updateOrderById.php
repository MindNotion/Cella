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
	
	$order_id = $_POST['order_id'];
	$order_delivery_status = $_POST['order_delivery_status'];
	$order_delivery_date = $_POST['order_delivery_date'];
	$order_confirmation = $_POST['order_confirmation'];
	
	/*echo $order_id."\n";
	echo $order_delivery_status."\n";
	echo $order_delivery_date."\n";
	echo $order_confirmation."\n";*/
	
	$sql = "UPDATE `order` SET order_delivery_status='$order_delivery_status',order_delivery_date='$order_delivery_date',order_confirmation='$order_confirmation' WHERE order_id='$order_id'";
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