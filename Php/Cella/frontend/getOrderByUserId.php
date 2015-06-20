<?php
session_start(); 

include("../php/connect.php");
  

	$user_id = $_POST['user_id']	;
	
		
	//customer attribute
	$sql = mysql_query("select * from `order` WHERE user_id='$user_id'");
					
	$responses = array();
	if(mysql_num_rows($sql) > 0)
	{
		
		while($row=mysql_fetch_array($sql)) 
		{
			$response = array('order_id' => $row['order_id'],'order_code' => $row['order_code'],'user_id' => $row['user_id'],'order_date' => $row['order_date'],'total_price' => $row['total_price'],'total_discount' => $row['total_discount'],'total_quantity' => $row['total_quantity']
					,'order_delivery_status' => $row['order_delivery_status'],'order_delivery_date' => $row['order_delivery_date']
					,'order_confirmation' => $row['order_confirmation']);
			$order_id = $row['order_id'];	
			$responses[] = $response;
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