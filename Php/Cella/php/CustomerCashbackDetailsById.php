<?php
session_start(); 

include("connect.php");
  

	$total_cashback =0;
	$user_id = $_POST['user_id']	;
	$quy = mysql_query("select * from order_product WHERE user_id='$user_id' AND is_cashback_approved='1' AND is_cashback_returned='0' ");
	if(mysql_num_rows($quy) > 0)
	{
		$responses = array();
		while($row=mysql_fetch_array($quy)) 
		{	
			$discount_percent = $row['discount_percent'];
			$price = $row['price'];
			if($discount_percent == '0')
				$total_cashback = $total_cashback + $price;	
			else
			{										
				$sp = (int)$price -((int)$price *((int)$discount_percent/100));
				
				$total_cashback = $total_cashback + $sp;	
			}
		}
		$response = array('total_cashback' => $total_cashback);
		$responses[] = $response;
		echo json_encode($responses);
				//return json_encode($responses);				
	}
	else
	{
		$response = array('total_cashback' => '0');
		$responses[] = $response;

		echo json_encode($responses);
	}
			
?>