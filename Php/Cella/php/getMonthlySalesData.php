<?php
session_start(); 

include("connect.php");
		
	//customer attribute
	$sql = mysql_query("select Day(order_date) as days, SUM(total_quantity) as total_quantity, SUM(total_price) as total_price from `order` where MONTH((order_date) ) =MONTH(CURDATE()) group by order_date ");
					
	$responses = array();
	if(mysql_num_rows($sql) > 0)
	{
		
		while($row=mysql_fetch_array($sql)) 
		{
			$response = array('days' => $row['days'],'total_quantity' => $row['total_quantity'],'total_price' => $row['total_price']);
			$responses[] = $response;
		}
		
		echo json_encode($responses);
		//return json_encode($responses);
				
	}
	else
	{
		$response = array('days' => '-1','total_quantity' => '-1','total_price' => '-1');
		$responses[] = $response;

		echo json_encode($responses);
	}				

 
			
?>