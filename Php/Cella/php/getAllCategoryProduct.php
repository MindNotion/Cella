<?php
session_start(); 

include("connect.php");

//SELECT DISTINCT(Date) AS Date FROM buy ORDER BY Date DESC;

	$sql = mysql_query("SELECT DISTINCT(gender) As gender FROM `product` ORDER BY gender DESC");
					
	$responses = array();
	
	$count=mysql_num_rows($sql);
	$responseGender = array('gender_count' => $count);
	$responses[] = $responseGender;
	$genderList;
	$list = array();
	if(mysql_num_rows($sql) > 0)
	{
		$index = 0;
		while($row=mysql_fetch_array($sql)) 
		{
			$list[]=  $row['gender'];
			
			if($index == 0)
			{
				$genderList = $row['gender'];
			}
			else
			{
				$genderList= $genderList.",".$row['gender'];
			}
			$index = $index +1;
			
		}
		$responses[] = array('gender' => $genderList);
		$listCount = count($list);
		for ($i=0;$i<$listCount;$i++)
		{
			$categoryList;
			//echo"List ". $list[$i];
			$sql1 = mysql_query("SELECT DISTINCT category FROM `product` WHERE gender = '$list[$i]' ORDER BY category DESC");
			if(mysql_num_rows($sql1) > 0)
			{
				$index = 0;
				while($row=mysql_fetch_array($sql1)) 
				{
					if($index == 0)
					{
						$categoryList = $row['category'];
					}
					else
					{
						$categoryList= $categoryList.",".$row['category'];
					}
					$index = $index +1;
				}
				
				$responses[] = array('category' => $categoryList);
			}
		}
		
		
		
		echo json_encode($responses);
		//return json_encode($responses);
				
	}
	else
	{
		$response = array('gender' => '-1');
		$responses[] = $response;

		echo json_encode($responses);
	}				

 
			
?>