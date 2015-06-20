<?php
session_start(); 

include("connect.php");
  

	$product_id = $_POST['product_id']	;
		

	$sql = mysql_query("delete from product WHERE product_id='$product_id'");
					
	$responses = array();
	
		
	$sqlImg = mysql_query("delete from product_image WHERE product_id='$product_id'");

		
	$sqlQty = mysql_query("delete from product_size_quantity WHERE product_id='$product_id'");
		
	$sqlDes = mysql_query("delete from product_desc_care WHERE product_id='$product_id'");	
	$sqlBrand = mysql_query("delete from product_brand WHERE product_id='$product_id'");	
		
		echo json_encode($responses);
		//return json_encode($responses);
					

 
			
?>