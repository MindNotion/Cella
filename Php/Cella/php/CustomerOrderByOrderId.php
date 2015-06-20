<?php
session_start(); 

include("connect.php");
  

	$order_id = $_POST['order_id']	;
	//echo $user_id;
	//customer attribute

	$result=mysql_query("SELECT * FROM  `order` WHERE order_id=$order_id LIMIT 1");
	$count = mysql_num_rows($result);					
	$responses = array();
	if($count > 0)
	{
		
		while($row=mysql_fetch_array($result)) 
		{
			$response = array('order_id' => $row['order_id'],'order_date' => $row['order_date'],'total_price' => $row['total_price'],'total_discount' => $row['total_discount'],'total_quantity' => $row['total_quantity'],'order_delivery_status' => $row['order_delivery_status'],'order_delivery_date' => $row['order_delivery_date']
					,'order_confirmation' => $row['order_confirmation']);
			$responses[] = $response;
		}
		
		echo json_encode($responses);
		//return json_encode($responses);
				
	}
	else
	{
		$response = array('order_id' => '-1','order_date' => '-1','total_price' => 'empty','total_discount'=>'empty');
		$responses[] = $response;

		echo json_encode($responses);
	}				

 
			
?>