<?php
session_start(); 

include("connect.php");
  

	$order_id = $_POST['order_id']	;
		
	//customer attribute
	$sql = mysql_query("select * from `order` WHERE order_id='$order_id'");
					
	$responses = array();
	if(mysql_num_rows($sql) > 0)
	{
		$user_id;
		while($row=mysql_fetch_array($sql)) 
		{
			$response = array('order_id' => $row['order_id'],'order_code' => $row['order_code'],'user_id' => $row['user_id'],'order_date' => $row['order_date'],'total_price' => $row['total_price'],'total_discount' => $row['total_discount'],'total_quantity' => $row['total_quantity']
					,'order_delivery_status' => $row['order_delivery_status'],'order_delivery_date' => $row['order_delivery_date']
					,'order_confirmation' => $row['order_confirmation']);
					$user_id = $row['user_id'];
			$responses[] = $response;
		}
		
		
		//customer attribute
		$sqlUser = mysql_query("select * from `user_details` WHERE user_id='$user_id' LIMIT 1");
		
		if(mysql_num_rows($sqlUser) > 0)
		{
			while($row=mysql_fetch_array($sqlUser)) 
			{
				$response = array('user_id' => $row['user_id'],'gender' => $row['gender'],'telephone' => $row['telephone'],'shipping_address' => $row['shipping_address'],'city' => $row['city']
							,'state' => $row['state'],'pincode' => $row['pincode']);
							
				$sqlUserName = mysql_query("select * from `user_login` WHERE user_id='$user_id' LIMIT 1");
				$objImg = mysql_fetch_object($sqlUserName);
				 
				$response['name']= $objImg->username;
				$response['email']= $objImg->email;
				
				$responses[] = $response;
			}
					
		}	
		
		$sqlOrderProduct = mysql_query("select * from `order_product`  WHERE order_id='$order_id'");
		$count=mysql_num_rows($sqlOrderProduct);
		$responseProd = array('product_count' => $count);
		$responses[] = $responseProd;
		if(mysql_num_rows($sqlOrderProduct) > 0)
		{
			while($row=mysql_fetch_array($sqlOrderProduct)) 
			{
				$response = array('product_id' => $row['product_id'],'title' => $row['title'],'price' => $row['price'],'discount_percent' => $row['discount_percent'],'quantity' => $row['quantity'],'isreturn' => $row['isreturned']
					,'order_return_date' => $row['order_return_date'],'return_item_condition' => $row['return_item_condition']
					,'return_reason' => $row['return_reason'],'warehouse_entry_date' => $row['warehouse_entry_date']
					,'size' => $row['size'],'dimension' => $row['dimension'],'is_cashback_approved'=>$row['is_cashback_approved']);
				$product_id = $row['product_id'];
				$sqlImg = mysql_query("select * from product_image WHERE product_id='$product_id' LIMIT 1");
				$objImg = mysql_fetch_object($sqlImg);
				 
				$response['image_path']= $objImg->image_path;
				$responses[] = $response;
			}
		}
		
		echo json_encode($responses);
		//return json_encode($responses);
				
	}
	else
	{
		$response = array('order_id' => '-1','product_id' => '-1','title' => 'empty','price'=>'empty');
		$responses[] = $response;

		echo json_encode($responses);
	}				

 
			
?>