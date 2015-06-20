<?php
session_start(); 

include("../php/connect.php");

//SELECT DISTINCT(Date) AS Date FROM buy ORDER BY Date DESC;

	$sql = mysql_query("SELECT DISTINCT (subcategory) AS subcategory FROM  `product` ORDER BY RAND( )");
					
	$responses = array();
	$subcategoryList;
	$list = array();
	if(mysql_num_rows($sql) > 0)
	{
		$index = 0;
		while($row=mysql_fetch_array($sql)) 
		{
			$list[]=  $row['subcategory'];
			
			if($index == 0)
			{
				$subcategoryList = $row['subcategory'];
			}
			else
			{
				$subcategoryList= $subcategoryList.",".$row['subcategory'];
			}
			$index = $index +1;
			
		}
		$responses[] = array('subcategory' => $subcategoryList);
		$listCount = count($list);
		for ($i=0;$i<$listCount;$i++)
		{
			$categoryList;
			//echo"List ". $list[$i];
			$sql1 = mysql_query("SELECT  * FROM `product` WHERE subcategory = '$list[$i]' ORDER BY Rand() limit 4");
			if(mysql_num_rows($sql1) > 0)
			{
				$index = 0;
				while($row=mysql_fetch_array($sql1)) 
				{
					$product_id = $row['product_id'];
					$sql2 = mysql_query("SELECT  image_path FROM `product_image` WHERE product_id = '$product_id'");
					while($row2=mysql_fetch_array($sql2))
					{
						$response = array('product_id' => $row['product_id'],'product_code' => $row['product_code'],'title' => $row['title'],'category' => $row['category'],'subcategory' => $row['subcategory'],'color' => $row['color'],'price' => $row['price']
						,'discount_percent' => $row['discount_percent'],'hit_count' => $row['hit_count'],'gender' => $row['gender'],'image_path' => $row2['image_path']);
					
					}
					$responses[] = $response;
				}
				
				
			}
		}
		
		
		
		echo json_encode($responses);
		//return json_encode($responses);
				
	}
	else
	{
		$response = array('subcategory' => '-1');
		$responses[] = $response;

		echo json_encode($responses);
	}				

 
			
?>