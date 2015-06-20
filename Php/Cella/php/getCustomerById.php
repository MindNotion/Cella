<?php
session_start(); 

include("connect.php");
  

	$user_id = $_POST['user_id']	;
	
	$sqlId = "SELECT * FROM user_login WHERE user_id='$user_id' LIMIT 1";
	$result=mysql_query($sqlId);
	$obj = mysql_fetch_object($result);
		
	//customer attribute
	$sql = mysql_query("select * from user_details WHERE user_id='$user_id'");
					
	$responses = array();
	if(mysql_num_rows($sql) > 0)
	{
		while($row=mysql_fetch_array($sql)) 
		{
			$response = array('user_id' => $row['user_id'],'name' => $obj->username,'gender' => $row['gender'],'telephone' => $row['telephone'],'email' => $obj->email,'shipping_address' => $row['shipping_address'],'city' => $row['city']
						,'state' => $row['state'],'pincode' => $row['pincode']);
			$responses[] = $response;
		}
		
		//order attribute
		/*$sqlImg = mysql_query("select * from order WHERE user_id='$user_id'");
		
		$countImg=mysql_num_rows($sqlImg);
		$responseImg = array('img_count' => $countImg);
		$responses[] = $responseImg;
		if(mysql_num_rows($sqlImg) > 0)
		{
			while($row=mysql_fetch_array($sqlImg)) 
			{
				$response = array('order_code' => $row['order_code'],'order_date' => $row['order_date'],'total_price' => $row['total_price'],'total_discount' => $row['total_discount'],'total_quantity' => $row['total_quantity'],'order_delivery_status' => $row['order_delivery_status'],'order_delivery_date' => $row['order_delivery_date']
						,'isreturn' => $row['isreturn'],'order_return_date' => $row['order_return_date'],'return_goods_condition' => $row['return_goods_condition'],'warehouse_entry_date' => $row['warehouse_entry_date'],'return_reason' => $row['return_reason'],'order_confirmation' => $row['order_confirmation']);
				$responses[] = $response;
			}
		}
		
		//cashback attribute
		$totalPrice = 0;
		$totalDiscount = 0;
		$sqlQty = mysql_query("select * from user_cashback WHERE user_id='$user_id'");
		$countQty=mysql_num_rows($sqlQty);
		$responseQty = array('qty_count' => $countQty);
		$responses[] = $responseQty;
		if(mysql_num_rows($sqlQty) > 0)
		{
			while($row=mysql_fetch_array($sqlQty)) 
			{
				$response = array('product_id' => $row['product_id'],'order_code' => $row['order_code'],'order_return_date' => $row['order_return_date']);
				$totalPrice = $totalPrice + (int)$row['price'];
				$totalDiscount = $totalDiscount + (int)$row['discount_percent'];
				$responses[] = $response;
			}
		}
		
		//total cashback price
		$response = array('totalPrice' => $totalPrice,'totalDiscount' => $totalDiscount);
		$responses[] = $response;*/
		
		echo json_encode($responses);
		//return json_encode($responses);
				
	}
	else
	{
		$response = array('user_id' => '-1','name' => '-1','title' => 'gender','telephone'=>'empty');
		$responses[] = $response;

		echo json_encode($responses);
	}				

 
			
?>