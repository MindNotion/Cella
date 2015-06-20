<?php
session_start(); 

include("connect.php");

	$search_by = $_POST['search_by']	;
	$search_value= $_POST['search_value']	;	
	if(strcmp($search_by,"user_id") == 0 || strcmp($search_by,"telephone") == 0)
	{
		$sql = mysql_query("select user_id, telephone from user_details WHERE $search_by='$search_value' Limit 1");
						
		$responses = array();
		if(mysql_num_rows($sql) > 0)
		{
			while($row=mysql_fetch_array($sql)) 
			{
				$uid = $row['user_id'];
				$tel = $row['telephone'];
				$sql2 = mysql_query("select * from user_login where user_id='$uid' Limit 1");
				while($row2=mysql_fetch_array($sql2)) 
					$response = array('user_id' => $uid,'name' => $row2['username'],'telephone' =>$tel ,'email' => $row2['email']);
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
	}	
	else if(strcmp($search_by,"username") == 0 || strcmp($search_by,"email") == 0)
	{
		
		$sql = mysql_query("select * from user_login WHERE $search_by='$search_value' Limit 1");			
		$responses = array();
		if(mysql_num_rows($sql) > 0)
		{
			while($row=mysql_fetch_array($sql)) 
			{
				$uid = $row['user_id'];
				$username = $row['username'];
				$email = $row['email'];
				
				$sql2 = mysql_query("select * from user_details where user_id='$uid' Limit 1");
				while($row2=mysql_fetch_array($sql2)) 
					$response = array('user_id' => $uid,'name' => $username,'telephone' =>$row2['telephone'] ,'email' => $email);
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
	}

 
			
?>