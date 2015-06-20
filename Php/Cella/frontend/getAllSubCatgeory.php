<?php
session_start(); 

include("../php/connect.php");


	$sql = mysql_query("SELECT DISTINCT subcategory FROM `product` ORDER BY category DESC");
					
	$responses = array();
	$response = array();
	
	echo "{\"results\": [";
	if(mysql_num_rows($sql) > 0)
	{

		while($row=mysql_fetch_array($sql)) 
		{
			echo  $row[$i]['subcategory'];
			//$response['subcategory'] = $row['subcategory'];
			$arr[] = "{\"id\": \"".$row['subcategory']."\", \"value\": \"".$row['subcategory']."\", \"info\": \"\"}";
			
		}	
		echo implode(", ", $arr);
		echo "]}";
		
		//return json_encode($responses);
				
	}
	else
	{
		
		$response['subcategory'] = -1;
		$responses[] = $response;

		echo json_encode($responses);
	}				

 
			
?>