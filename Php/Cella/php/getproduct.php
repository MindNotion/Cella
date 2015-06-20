<?php
session_start(); 

include("connect.php");
  
  //$sql = mysql_query("INSERT INTO product (product_id,title, product_code,ptype,pdesc,size,price,quantity,discounted_price,rating,new_arrival,best_seller,availability,arrival_date,hit_count)
					//	VALUES (NULL,'$description')");
				

	$sql = mysql_query("select product_id,title, product_code, category from product ");
					
	$responses = array();
	if(mysql_num_rows($sql) > 0)
	{
		while($row=mysql_fetch_array($sql)) 
		{
			$response = array('product_id' => $row['product_id'],'product_code' => $row['product_code'],'title' => $row['title'],'category' => $row['category']);
			$responses[] = $response;
		}
		
		echo json_encode($responses);
		//return json_encode($responses);
				
	}
	else
	{
		$response = array('product_id' => '-1','product_code' => '-1','title' => 'empty','category'=>'empty');
		$responses[] = $response;

		echo json_encode($responses);
	}				

 
			
?>