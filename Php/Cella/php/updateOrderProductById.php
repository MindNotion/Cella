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
	$isreturn = $_POST['isreturn'];
	$order_return_date = $_POST['order_return_date'];
	$return_item_condition = $_POST['return_item_condition'];
	$return_reason = $_POST['return_reason'];
	$warehouse_entry_date = $_POST['warehouse_entry_date'];
	$is_cashback_approved = $_POST['is_cashback_approved'];
	
	/*echo $order_id."\n";
	echo $isreturn."\n";
	echo $order_return_date."\n";
	echo $return_item_condition."\n";
	echo $return_reason."\n";
	echo $warehouse_entry_date."\n";*/
	
	$sql = "UPDATE `order_product` SET isreturned='$isreturn', order_return_date='$order_return_date',return_goods_condition='$return_item_condition',return_reason='$return_reason',warehouse_entry_date='$warehouse_entry_date',is_cashback_approved ='$is_cashback_approved' WHERE order_id='$order_id'";
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