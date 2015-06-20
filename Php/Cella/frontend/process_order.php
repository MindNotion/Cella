<?php

    date_default_timezone_set('Etc/UTC');
    require 'PHPMailer-master/PHPMailerAutoload.php';
	ob_start();
	session_start(); 
	header('Cache-control: private');
	include("../php/connect.php");
	
	$total_price	= $_POST["total_price"];
	$total_discount = $_POST["total_discount"];
	$total_qty = $_POST["total_qty"];
	$cashbackreturn = $_POST["cashbackreturn"];
	
	$login=0;
	$sex;
	$contact_number;
	$ship_add;
	$city;
	$state;
	$pincode;
	$email;
	$invoice_no;
	$courier_charges = 0;
	$min_shopping_amt = 0;
	
	if(isset($_SESSION['username']))
	{
		
		
		$login=1;
		$email = $_SESSION['email'];
		
		$sqlShipping = "SELECT * FROM shipping";
		$result=mysql_query($sqlShipping);
		$count=mysql_num_rows($result);	
		$obj = mysql_fetch_object($result);
		if($count==1)
		{
			$courier_charges = $obj->shipping_amt;
			$min_shopping_amt = $obj->free_shipping_amt;
		}
		
		$sql = "SELECT * FROM user_login WHERE email='$email' LIMIT 1";
		$result=mysql_query($sql);
		$count=mysql_num_rows($result);	
		$obj = mysql_fetch_object($result);
		
		
		if($count==1)
		{
			$user_id = $obj->user_id;
			$user_name = $_SESSION['username'];
			if($cashbackreturn== 'true')
			{
				$cbsql = "UPDATE order_product SET is_cashback_returned='1' WHERE user_id='$user_id' AND is_cashback_approved='1'";
				$retval = mysql_query( $cbsql);
				
			}
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
				
				$now = new DateTime();
				$order_code =$now->format('U'); ;
				$today = date("Y-m-d");				
				
				if (mysql_query("INSERT INTO `order` (order_id,order_code,user_id,order_date,total_price,total_discount,total_quantity,order_delivery_status,order_delivery_date,order_confirmation)	VALUES (NULL,'$order_code','$user_id','$today','$total_price','$total_discount','$total_qty','0','0000-00-00','0')")  )
				{
					$order_id = mysql_insert_id($db);
					$invoice_no = $order_id;
					$cart_items = 0;
					$domain_link = "$_SERVER[HTTP_HOST]";
					$message = '<html><body>';
					$message .= '<h3>Thank you for shopping with '.$domain_link.'</h3>';
					$message .= '<h5>Soon our customer care will contact you for final confirmation of order.</h5>
								 <h5>You have to do cash on delivery (COD) for below listed products.</h5>
								<p> You will receive your order within 9-10 working days. You can track your order from your account on '.$domain_link.'</p>';
					$message.='<h3> Customer Details</h3>';
					$message.='<h5> Customer Id: '.$user_id.'</h5>';
					$message.='<h5> Customer Name: '.$user_name.'</h5>';
					$message.='<h5> Customer E-mail: '.$email.'</h5>';
					$message.='<h5> Customer Phone Number: '.$contact_number.'</h5>';
					$message.='<h5> Customer Shipping Address: '.$ship_add.'</h5>';
					$message.='<h5> Customer City: '.$city.'</h5>';
					$message.='<h5> Customer State: '.$state.'</h5>';
					$message.='<h5> Customer Pincode: '.$pincode.'</h5>';
					$message.='<h3> Order Details</h3>';
					$message.='<h5> Order Id: '.$order_id.'</h5>';
					$message.='<h5> Order code: '.$order_code.'</h5>';
					$message .= '<table border="1" BORDERCOLOR="#cb3327">';
					
					
					foreach ($_SESSION["products"] as $cart_itm)
					{
						$product_id = $cart_itm["product_id"];
						$results = mysql_query("SELECT * FROM product WHERE product_id='$product_id' LIMIT 1");
						$obj = mysql_fetch_object($results);
						$product_code = $obj->product_code;
						$title = $obj->title;
						$price = $cart_itm["price"];
						$discount = $cart_itm["discount_percent"];
						$qtyx = $cart_itm["qty"];
						$hit_count = $obj->hit_count;
						
						$hit_count = $hit_count+1;
						$message .= "<tr><td><h4> Product Code: </h4></td><td>".$product_code."</td></tr>";
						
						
						$size = $cart_itm["product_size"];
						$dimen = $cart_itm["product_dim"];
						
						if(mysql_query("INSERT INTO order_product (order_id,order_code,user_id,product_id,product_code,title,price,discount_percent,quantity,size,dimension,isreturned,order_return_date,return_goods_condition,return_reason,warehouse_entry_date,is_cashback_approved) 
									VALUES ('$order_id','$order_code','$user_id','$product_id','$product_code','$title','$price','$discount', '$qtyx','$size','$dimen','0',NULL,NULL,NULL,NULL,'0')") )
						{
							$product_size_qty = mysql_query("SELECT * FROM product_size_quantity WHERE product_id='$product_id' and size_type='$size' LIMIT 1");
							$obj_qty = mysql_fetch_object($product_size_qty);
							$productQty =  $obj_qty->quantity;
							$productQty = $productQty - $qtyx;
							
							$sql1 = "UPDATE product_size_quantity SET quantity='$productQty' WHERE product_id='$product_id' and size_type='$size' LIMIT 1";						 
							
							$sql2 = "UPDATE product SET hit_count='$hit_count' WHERE product_id='$product_id' LIMIT 1";
							mysql_query($sql1);
							mysql_query($sql2);
						}
						else
						{
							$message .= mysql_errno($db)."<br/>";
							$message .= mysql_error($db)."<br/>";
						}				   
					   $domain_link = "http://$_SERVER[HTTP_HOST]/";
					   $message .= '<tr>';						
						$message .= '<td>';
						$message .= '<h4>'.$obj->title.' (Product ID :'.$product_id.')</h4></td> ';									
						$message .= '<td><img id ="disp1" src='.$domain_link.substr($cart_itm["product_img"],2).'  style="width:48px;height:64px;border:2px solid #0000A0"/></td></tr> ';																
						$message .= '<tr><td> <h4> Size  </h4></td>';
						$message .= '<td><font face="arial" size="2" color="#3D3C3A">'.$cart_itm["product_size"].'</font></td></tr>';
						$message .= '<tr><td> <h4> Dimension:  </h4></td>';
						
						$message .= '<td><font face="arial" size="2" color="#3D3C3A"> '.$cart_itm["product_dim"].'</font></td></tr>';
						$message .= '<tr><td> <h4> Quantity:  </h4></td>';
						$message .= '<td><font face="arial" size="2" color="#3D3C3A"> '.$cart_itm["qty"].'</font></td></tr>';
						$subtotal = 0;
						if($cart_itm["discount_percent"] == 0)
						{
							$subtotal = ($cart_itm["price"]*$cart_itm["qty"]);
							$message .= '<tr><td> <h4> Price:  </h4></td>';
							$message .= '<td><font face="arial" size="2" color="#3D3C3A"> &#8377; '.$currency.$obj->price.'</font></td></tr>';
						}
						else
						{
							$subtotal = ($cart_itm["price"] -(($cart_itm["discount_percent"]/100)*$cart_itm["price"]))*$cart_itm["qty"];
							$message .= '<tr><td> <h4> Original Price &#8377;  </h4></td>';
							$message .= '<td><font face="arial" size="2" color="#3D3C3A">'.$cart_itm["price"].'</font></td></tr>';
							$message .= '<tr><td> <h4> Discount:  </h4></td>';
							$message .= '<td><font face="arial" size="2" color="#3D3C3A">'.$cart_itm["discount_percent"].'</font></td></tr>';
							$message .= '<tr><td> <h4> After Discount &#8377;  </h4></td>';
							$message .= '<td><font face="arial" size="2" color="#3D3C3A">After Discount &#8377;'.$subtotal.'</font></td></tr>';
							
							
						}
						
						$total = ($total + $subtotal);						
						$message .= '<input type="hidden" name="item_name['.$cart_items.']" value="'.$obj->title.'" />';
						$message .= '<input type="hidden" name="item_code['.$cart_items.']" value="'.$product_id.'" />';
						$message .= '<input type="hidden" name="item_qty['.$cart_items.']" value="'.$cart_itm["qty"].'" />';
						
						$cart_items ++;
						
					}
					$message .= '</table>';
					$message .= '<span><br>';
					if($total < $min_shopping_amt)
					{
						$total = $total+$courier_charges; 
						
						$message .= '<strong>Shipping charges(&#8377; '.$courier_charges.') + Total : &#8377; '.$total.'</strong>  ';
						
					}
					else
					{
						$message .= '<strong>Free Shipping on Total : &#8377; '.$total.'</strong>  ';
						
					}
					$message .= '</span><br>';
					$message .= "<a href='http://www.mindnotion.com/project.htm'><img src='http://www.mindnotion.com/images/big_logo.png' height='42' width='42' >Cella Free E-Commerce Solution. Download now!</a>";			
					$message .= '</body></html>';
					//remove all products from cart
					unset($_SESSION["products"]);
					
					//echo $message;
					// sending email to admin and customer
					$to = $email;

					$subject = $domain_link.' Order no. '.$invoice_no.' invoice';

                                        
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
					$mail->Username = "XXXXX@gmail.com";//change your XXXXX by your gmail email-ID

					//Password to use for SMTP authentication
					$mail->Password = "your gmail password"; //replace this by your gmail password

					//Set who the message is to be sent from
					$mail->setFrom('XXXXX@gmail.com', $domain_link);//change your XXXXX by your gmail email-ID

					//Set an alternative reply-to address
					$mail->addReplyTo('XXXXX@gmail.com', $domain_link);//change your XXXXX by your gmail email-ID
					$mail->addCC('XXXXX@any domain.com');//change your XXXXX by your CC email id
					$mail->addBCC('XXXXX@any domain.com');//change your XXXXX by your Bcc email id
					//Set who the message is to be sent to
					$mail->addAddress($to, $username);
					//Set the subject line
					$mail->Subject = $subject;
					$mail->Body =$message;


					//Replace the plain text body with one created manually
					$mail->AltBody = 'Order Invoice';
					if($mail->send())
					{
						echo "<script>alert('An email has been sent to your email account. ".$to."');window.location ='thanks.php'</script>";
					}
                                        

				} 
				else 
				{
					$message .= "Error: " ;
					$message .= mysql_errno($db);
					$message .= mysql_error($db);
				}
				
			}
		}
	}
	else
	{
		$message .= "<script>window.location ='login.php?rurl=". $actual_link ."'</script>";
		
	}
?>