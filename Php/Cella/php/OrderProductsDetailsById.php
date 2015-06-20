<?php
session_start(); 

include("connect.php");
  

	$order_id = $_POST['order_id']	;

	//customer attribute
	$sql = mysql_query("select * from order_product WHERE order_id='$order_id'");
					
	$responses = array();
	if(mysql_num_rows($sql) > 0)
	{
		
		while($row=mysql_fetch_array($sql)) 
		{
			$response = array('order_id' => $row['order_id'],'product_id' => $row['product_id'],'title' => $row['title'],'price' => $row['price'],'discount_percent' => $row['discount_percent'],'quantity' => $row['quantity'],'isreturn' => $row['isreturned']
					,'order_return_date' => $row['order_return_date'],'return_item_condition' => $row['return_item_condition']
					,'return_reason' => $row['return_reason'],'warehouse_entry_date' => $row['warehouse_entry_date'],'actual_price' => $row['actual_price']);
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