<?php
session_start(); 

include("connect.php");
  
				

	$sql = mysql_query("select * from user_login");
					
	$responses = array();
	if(mysql_num_rows($sql) > 0)
	{
		while($row=mysql_fetch_array($sql)) 
		{
			$response = array('user_id' => $row['user_id'],'name' => $row['username'],'email' => $row['email']);
			$user_id = $row['user_id'];
			$sql2 = mysql_query("SELECT * FROM user_details WHERE user_id='$user_id' LIMIT 1");
			$obj = mysql_fetch_object($sql2);
			$response['telephone'] = $obj->telephone;
			$response['gender'] =  $obj2->gender;
			$response['shipping_address'] = $obj2->shipping_address;
			$response['city'] =   $obj2->city;
			$response['state'] =   $obj2->state;
			$response['pincode'] =  $obj2->pincode;
			
			$responses[] = $response;
		}
		
		echo json_encode($responses);
		//return json_encode($responses);
				
	}
	else
	{
		$response = array('user_id' => '-1','name' => '-1','telephone' => 'empty','email'=>'empty');
		$responses[] = $response;

		echo json_encode($responses);
	}				

 
			
?>