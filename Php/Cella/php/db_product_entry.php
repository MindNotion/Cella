<?php
	ob_start();
	session_start(); 
	header('Cache-control: private');
	include("connect.php");

	if(!isset($_SESSION['username']))
	{
	header("Location: ../login.html");
	exit;
	}

	if($_SESSION['level']== 0)
	{
		echo "<script>alert('Permission Denied: No rights to enter products');window.location ='admin_panel.php'</script>";
		exit;
	}
	
	
	$title = $_POST['title'];	
			
	$now = new DateTime();
    $product_code =$now->format('U'); ;
	
	$category = $_POST['category'];
	$subcategory = $_POST['subcategory'];
	$color = $_POST['color'];
	$price = $_POST['price'];
	$discount_percent = $_POST['discount_percent'];
	$new_arrival = $_POST['new_arrival'];
	$best_seller = $_POST['best_seller'];
	$hit_count = '0';
	$gender = $_POST['gender'];
	$brand = $_POST['brand'];
	$desc = $_POST['desc'];
	$care = $_POST['care'];

	
	
  //enter product detail description in product table
  $sql = mysql_query("INSERT INTO product (product_id,title, product_code,category,subcategory,color,price,discount_percent,new_arrival,best_seller,hit_count,gender)
						VALUES (NULL,'$title','$product_code','$category','$subcategory','$color','$price','$discount_percent','$new_arrival','$best_seller','$hit_count','$gender')");
	if (!$sql) 
	{
		echo "<script>alert('Error in query');window.location ='product_entry.php'</script>";
		exit;
	}

	
	//copying images to server location
	$path = "../product_images/";		
	//echo $_SESSION['product_image_count'];
	$timestamp = array();	
	for($x = 0; $x < $_SESSION['product_image_count']; $x++)
	{	
		$timestamp[$x] = time();
		$img = 'image'.($x+1);
		//echo $img;
		$file = $path .$timestamp[$x].$_FILES[$img]["name"];
		//echo $file;
		move_uploaded_file($_FILES[$img]['tmp_name'], $file);
	}
	
	$product_id =-1;
	$product_code =-1;
	
	// get last row product id and product code
	$last_row_sql = mysql_query("SELECT product_id, product_code  FROM product ORDER BY product_id DESC LIMIT 1");
	while($row = mysql_fetch_array($last_row_sql))
	{
		$product_id = $row['product_id'];
		$product_code = $row['product_code'];
	}
	//echo $product_id;
	//echo "\t".$product_code;
	
	
	//insert into product_brand table
	$brand_sql = mysql_query("INSERT INTO product_brand (id,product_id,product_code,product_brand_title)
						VALUES (NULL,'$product_id','$product_code','$brand')");
	
	
	//insert into product_desc_care table
	$desc_sql = mysql_query("INSERT INTO product_desc_care (id,product_id,product_code,product_desc,product_care)
						VALUES (NULL,'$product_id','$product_code','$desc','$care')");
	
	//insert into product_image table
	for($i = 0; $i < $_SESSION['product_image_count']; $i++)
	{
		$imgx = 'image'.($i+1);
		//echo $img;
		$img_path = $path .$timestamp[$i].$_FILES[$imgx]["name"];
		
		$img_sql = mysql_query("INSERT INTO product_image (id,product_id,product_code,image_path)
						VALUES (NULL,'$product_id','$product_code','$img_path')");
	}
	
	//insert into product_size_quantity table
	for($i = 0; $i < $_SESSION['product_dimension_count']; $i++)
	{
	
			$size = $_POST['size'.($i+1)];
			$dim = $_POST['dim'.($i+1)];
			$qty = $_POST['qty'.($i+1)];
			$availability = 1;
		
		
		$img_sql = mysql_query("INSERT INTO product_size_quantity (id,product_id,product_code,size_type,dimension,quantity,availability)
						VALUES (NULL,'$product_id','$product_code','$size','$dim','$qty','$availability')");
	}
	
	echo "<script>alert('Product detail stored successfully');window.location ='product_entry.php'</script>";
		exit;
	
	
	
?><? ob_flush(); ?>