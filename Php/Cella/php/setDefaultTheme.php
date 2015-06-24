<?php
ob_start();
session_start();
	$file = '../css/default-main.css';
	$newfile = '../css/main.css';
		if (!copy($file, $newfile)) 
		{
			$response = array('sucess' => '0');
			$responses[] = $response;

			echo json_encode($responses);
		}
		else
		{
			$response = array('sucess' => '1');
			$responses[] = $response;

			echo json_encode($responses);
		}
ob_flush();

?>