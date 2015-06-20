<?php
session_start(); 

include("../php/connect.php");

//SELECT DISTINCT(Date) AS Date FROM buy ORDER BY Date DESC;
$category = $_POST['category']	;

	$sql = mysql_query("SELECT DISTINCT subcategory, gender FROM `product` WHERE category ='$category' ORDER BY category DESC");
					
	$responses = array();
	$response = array();
	if(mysql_num_rows($sql) > 0)
	{

		while($row=mysql_fetch_array($sql)) 
		{
			$response['gender'] = $row['gender'];
			$response['subcategory'] = $row['subcategory'];
			$responses[] = $response;
			
		}	
		
		echo json_encode($responses);
		//return json_encode($responses);
				
	}
	else
	{
		$response['gender'] = -1;
		$response['subcategory'] = -1;
		$responses[] = $response;

		echo json_encode($responses);
	}				

 
			
?>