<?php
	ob_start();
	session_start(); 
	header('Cache-control: private');
	include("../php/connect.php");
	$product_id = $_POST['productId']	;
	$order_id= $_POST['order_id']	;
	
	if(isset($_SESSION['username']))
	{
		$sql = "UPDATE order_product SET isreturned='1' WHERE product_id='$product_id' and order_id='$order_id' LIMIT 1";
		$retval = mysql_query( $sql);
		if(! $retval )
		{
			$response = array('update_status' => '-1');
			$responses[] = $response;

			echo json_encode($responses);
		}
		else
		{
			$email = $_SESSION['email'];
			$sql = "SELECT * FROM user_login WHERE email='$email' LIMIT 1";
			$result=mysql_query($sql);
			$count=mysql_num_rows($result);	
			$obj = mysql_fetch_object($result);
			if($count==1)
			{
				$user_id = $obj->user_id;
				$user_name = $_SESSION['username'];
				
				$sql2 = "SELECT * FROM user_details WHERE user_id='$user_id' LIMIT 1";
				$result2=mysql_query($sql2);
				$count2=mysql_num_rows($result2);	
				$obj2 = mysql_fetch_object($result2);
				
				if($count2==1)
				{
					$sex = $obj2->gender;
					$contact_number = $obj2->telephone;
					$ship_add  = $obj2->shipping_address;
					$city  = $obj2->city;
					$state  = $obj2->state;
					$pincode = $obj2->pincode;	
			
					$to = $email;
					$subject = "Your request to returned product from KapdaUtsav.com" ;
					$body = "Product  with Id: ".$product_id." having Order Id: ".$order_id." is request for returned. Customer details below <br>";
					$body .= "User Name: ".$_SESSION['username']." User e-mail Id: ".$email." <br>";
					$body .= "User contact number: ".$contact_number." User Shipping address: ".$ship_add."<br>";
					$body .= "City: ".$city." State: ".$state."<br>";
					$body .= "Pincode: ".$pincode."<br>";
					$body .= "Your request will be soon process and our representative will come at you premises to collect the product.<br>";
					$body .= "Kindly attached returned product detail printout with product <br>";
					$body .= "Order and product details can be found on you KapdaUtsav.com account.<br>";
					$body .= "Feel free to contact on +91 9892015698 or e-mail us on customercare@kapdautsav.com for any queries.<br>";
					$body .= "Thank you for shopping with KapdaUtsav.com <br>";
					
					 $mail = new PHPMailer;

					//Tell PHPMailer to use SMTP
					$mail->isSMTP();

					//Enable SMTP debugging
					// 0 = off (for production use)
					// 1 = client messages
					// 2 = client and server messages
					$mail->SMTPDebug = 0;

					//Ask for HTML-friendly debug output
					$mail->Debugoutput = 'html';

					//Set the hostname of the mail server
					$mail->Host = 'smtp.gmail.com';

					//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
					$mail->Port = 587;

					//Set the encryption system to use - ssl (deprecated) or tls
					$mail->SMTPSecure = 'tls';

					//Whether to use SMTP authentication
					$mail->SMTPAuth = true;

					//Username to use for SMTP authentication - use full email address for gmail
					$mail->Username = "kapdautsav@gmail.com";

					//Password to use for SMTP authentication
					$mail->Password = "siddha2007";

					//Set who the message is to be sent from
					$mail->setFrom('customercare@kapdautsav.com', 'kapdautsav.com');

					//Set an alternative reply-to address
					$mail->addReplyTo('customercare@kapdautsav.com', 'kapdautsav.com');
                                        $mail->addCC('customercare@kapdautsav.com');
                                        $mail->addBCC('smitaruke22@gmail.com');
					//Set who the message is to be sent to
					$mail->addAddress($to, $username);
					//Set the subject line
					$mail->Subject = $subject;
					$mail->Body =$body;
					if($mail->send())
					{
						echo "<script>alert('Email of product return confirmation has be sent on your e-mail id ".$email."');window.location ='user_account.php'</script>";
					}
		
					
					
				}
			}
			
			
		}
	}
	else
	{
		$message .= "<script>window.location ='login.php'</script>";
		
	}
?>