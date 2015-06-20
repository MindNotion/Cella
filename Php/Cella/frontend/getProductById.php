<?php
session_start(); 

include("../php/connect.php");
  
  //$sql = mysql_query("INSERT INTO product (product_id,title, product_code,ptype,pdesc,size,price,quantity,discounted_price,rating,new_arrival,best_seller,availability,arrival_date,hit_count)
					//	VALUES (NULL,'$description')");
	$product_id = $_POST['product_id']	;
		

	$sql = mysql_query("select * from product WHERE product_id='$product_id'");
					
	$responses = array();
	if(mysql_num_rows($sql) > 0)
	{
		while($row=mysql_fetch_array($sql)) 
		{
			$response = array('product_id' => $row['product_id'],'product_code' => $row['product_code'],'title' => $row['title'],'category' => $row['category'],'subcategory' => $row['subcategory'],'color' => $row['color'],'price' => $row['price']
						,'discount_percent' => $row['discount_percent'],'hit_count' => $row['hit_count'],'gender' => $row['gender']);
			$responses[] = $response;
		}
		
		$sqlImg = mysql_query("select * from product_image WHERE product_id='$product_id'");
		
		$countImg=mysql_num_rows($sqlImg);
		$responseImg = array('img_count' => $countImg);
		$responses[] = $responseImg;
		if(mysql_num_rows($sqlImg) > 0)
		{
			while($row=mysql_fetch_array($sqlImg)) 
			{
				$response = array('image_path' => $row['image_path']);
				$responses[] = $response;
			}
		}
		
		$sqlQty = mysql_query("select * from product_size_quantity WHERE product_id='$product_id'");
		$countQty=mysql_num_rows($sqlQty);
		$responseQty = array('qty_count' => $countQty);
		$responses[] = $responseQty;
		if(mysql_num_rows($sqlQty) > 0)
		{
			while($row=mysql_fetch_array($sqlQty)) 
			{
				$response = array('id'=>$row['id'],'size_type' => $row['size_type'],'dimension' => $row['dimension'],'quantity' => $row['quantity'],'availability' => $row['availability']);
				$responses[] = $response;
			}
		}
		
		$sqlDescCare =  mysql_query("select * from product_desc_care WHERE product_id='$product_id'");
		if(mysql_num_rows($sqlDescCare) > 0)
		{
			while($row=mysql_fetch_array($sqlDescCare)) 
			{
				$response = array('product_desc'=>$row['product_desc'],'product_care' => $row['product_care']);
				$responses[] = $response;
			}
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