<?php
	ob_start();
	session_start(); 
	header('Cache-control: private');
	include("../php/connect.php");
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$actual_link =base64_encode($actual_link);
	$email = $_SESSION['email'];
	$sql = "SELECT * FROM user_login WHERE email='$email' LIMIT 1";
	
	$sex;
	$contact_number;
	$ship_add;
	$city;
	$state;
	$pincode;

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
		}
	}
	$login=0;
	if(isset($_SESSION['username']))
	{
		$login=1;
	}
	else
	{
		echo "<script>window.location ='login.php?rurl=". $actual_link ."'</script>";
		
	}
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Account | Cella</title>
	
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/prettyPhoto.css" rel="stylesheet">
    <link href="../css/price-range.css" rel="stylesheet">
    <link href="../css/animate.css" rel="stylesheet">
	<link href="../css/main.css" rel="stylesheet">
	<link href="../css/responsive.css" rel="stylesheet">
	<link rel="stylesheet" href="../css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="stylesheet" href="../css/jPages.css"> 
	<link rel="stylesheet" href="../css/jquery-ui.css">
	<link rel="stylesheet" href="../css/selectric.css">
	<link href="../css/account.css"  rel="stylesheet" type="text/css">
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->       
    <link rel="shortcut icon" href="../images/favicon.ico">
     <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../images/ico/apple-touch-icon-57-precomposed.png">
	
	<script src="../js/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" src="../js/bsn.AutoSuggest_c_2.0.js"></script>

	<script type="text/javascript">
	 $(document).ready(function () {
	
	var options = {
		script:"getAllSubCatgeory.php?json=true&",
		varname:"input",
		json:true
	};
	var as_json = new AutoSuggest('srch-term', options);
	
	var loginStatus = '<?php echo $login; ?>';
	if(loginStatus == 1 )
	{
		document.getElementById("logout_link").style.visibility = "visible";
		document.getElementById("login_link").style.visibility = "hidden";
		document.getElementById("account_link").style.visibility = "visible";
		document.getElementById("acc").innerHTML = '<i class="fa fa-user"></i>'+'<?php echo $_SESSION['username']; ?>'+' account';
		
	}
	else
	{
		document.getElementById("logout_link").style.visibility = "hidden";
		document.getElementById("login_link").style.visibility = "visible";
		document.getElementById("account_link").style.visibility = "hidden";
	}
	
	
	
	});
	
	var productSubcategory, productID, productCode, productColor, productPrice, productDiscount,productGender, productDesc, productCare;
	
	var categoriesData;
	
	var sizeArray = [];
	var dimensionArray = [];
	var quantityArray = [];
	
	var sqlQueryData = " WHERE ";
	var productData;
		function getAllCategoryProduct() {		
		
			$.ajax({ 
		  type: 'post', 
		  url: "../php/getAllCategoryProduct.php", 				   
		  data: {}, 
		  dataType: 'json',
		  success: function(data1){ 
		  categoriesData = data1;
			//console.log(data1);
			$('#accordian').empty();
			var genderCount = data1[0].gender_count;
			var splitResult = data1[1].gender.split(",");
			
			$('#header_submenu').empty();
			
				if(genderCount == 1)
				{
					var subMenuResult = data1[2].category.split(",");
					for(var j=0; j<subMenuResult.length;j++)
					{
						$('#accordian').append('<div class="panel panel-default" id="panel-default'+ j+'">');
						$('#panel-default'+j).append('<div class="panel-heading" id="panel-heading'+ j+'">');
						$('#panel-heading'+j).append('<h4 class="panel-title"><a href="frontend/productlist.php?gender='+splitResult[0]+'&category='+subMenuResult[j]+'">'+subMenuResult[j]+'</a></h4>');
						$('#panel-default'+j).append('</div>');
						$('#accordian').append('</div>');
						
						$('#header_submenu').append('<li><a href="productlist.php?gender='+splitResult[0]+'&category='+subMenuResult[j]+'">'+subMenuResult[j]+'</a>');
					}
				}
				else
				{
					for(var i=0; i<genderCount; i++) 
					{								
						$('#accordian').append('<div class="panel panel-default" id="panel-default'+ i+'">');
						$('#panel-default'+i).append('<div class="panel-heading" id="panel-heading'+ i+'">');
						$('#panel-heading'+i).append('<h4 class="panel-title" id="panel-title'+ i+'">');
						
						$('#panel-title'+i).append('<a  data-toggle="collapse" data-parent="#accordian" href="#'+splitResult[i]+'" id="label'+ i+'">');
						
						$('#label'+i).append('<span class="badge pull-right"><i class="fa fa-plus"></i></span>');
						if(splitResult[i] =="female")
						$('#label'+i).append('Woman');
						else if(splitResult[i] =="male")
							$('#label'+i).append('Men');
						else
							$('#label'+i).append('Unisex');
							
						$('#panel-title'+i).append('</a>');
						$('#panel-heading'+i).append('</h4>');
						$('#panel-default'+i).append('</div>');
						$('#panel-default'+i).append('<div id="'+splitResult[i]+'" class="panel-collapse collapse">');
						$('#'+splitResult[i]).append('<div class="panel-body" id="panel-body'+ i+'">');
						$('#panel-body'+i).append('<ul id="submenu'+i+'">');
						
						var subMenuResult = data1[2+i].category.split(",");
						for(var j=0; j<subMenuResult.length;j++)
						{
							$('#submenu'+i).append('<li><a href="productlist.php?gender='+splitResult[i]+'&category='+subMenuResult[j]+'">'+subMenuResult[j]+'</a></li>');
							$('#header_submenu').append('<li><a href="productlist.php?gender='+splitResult[i]+'&category='+subMenuResult[j]+'">'+subMenuResult[j]+'</a>');
						}
						$('#panel-body'+i).append('</ul>');
						$('#'+splitResult[i]).append('</div>');
						$('#panel-default'+i).append('</div>');
						$('#accordian').append('</div>');
						
						
					 
					}
				}
						   
			} 
          
        });
		}
		
	
	function getSubcategory(cv)
	{
		$.ajax({ 
		  type: 'post', 
		  url: "getSubCatgeory.php", 				   
		  data: {category:cv}, 
		  dataType: 'json',
		  success: function(data)
		  { 
			if(data.length > 0 && data[0].gender != -1)
			{
				$('#h2_subcategory').show();
				$('#accordian2').show();
				for(var j=0; j<data.length;j++)
				{
					$('#accordian2').append('<div class="panel panel-default" id="panel-default'+ j+'">');
					$('#panel-default'+j).append('<div class="panel-heading" id="panel-heading'+ j+'">');
					$('#panel-heading'+j).append('<h4 class="panel-title"><a href="productlist.php?gender='+data[j].gender+'&subcategory='+data[j].subcategory+'">'+data[j].subcategory+'</a></h4>');
					$('#panel-default'+j).append('</div>');
					$('#accordian2').append('</div>');
					
					
				}
			}
			else
			{
				$('#accordian2').hide();
				$('#h2_subcategory').hide();
			}
		  }
		});
	}
	function sizeDropDown()
	{
		var x = document.getElementById("size_select_dropdown").value;
		document.getElementById("size_info").innerHTML = dimensionArray[x];
		document.getElementById("avaiable_info").innerHTML = quantityArray[x];
	}
	function showProductSubCategories()
		{
			$('#SubCategories').empty();
			$('#productSubCategories').empty();
			$.ajax({ 
		  type: 'post', 
		  url: "getAllSubCategoryProduct.php", 				   
		  data: {}, 
		  dataType: 'json',
		  success: function(data1)
			{
				
				$('#rec_active').empty();
				$('#rec_inactive').empty();
				var flag = 0;
				for(var x =1 ; x < data1.length; x++)
				{
					if((x%2 == 0) && flag < 6)
					{
						$('#rec_active').append('<div class="col-sm-4" id="rec_act_col-sm-4'+x+'">');
						$('#rec_act_col-sm-4'+x).append('<div class="product-image-wrapper" id="rec_act_product-image-wrapper'+x+'">');
						$('#rec_act_product-image-wrapper'+x).append('<div class="single-products" id="rec_act_single-products'+x+'">');
						$('#rec_act_single-products'+x).append('<div class="productinfo text-center" id="rec_act_productinfo'+x+'">');
						$('#rec_act_productinfo'+x).append('<img style="width:207px;height:183px"src="'+data1[x].image_path +'" alt="" />');
							var discount_percent = data1[x].discount_percent;
							if(discount_percent == 0)
								$('#rec_act_productinfo'+x).append('<h2>Rs. '+data1[x].price +'</h2>');
							else
							{							
								var actualPrice = parseFloat( parseFloat(data1[x].price).toFixed(2));
								var sellingPrice = parseFloat( parseFloat(data1[x].price).toFixed(2) -((parseFloat(discount_percent).toFixed(2)/parseFloat(100).toFixed(2)) * parseFloat(data1[x].price).toFixed(2)));
								
								$('#rec_act_productinfo'+x).append('<h2><s>Rs.'+actualPrice +'</s> Rs.'+sellingPrice +' discount: '+parseFloat(discount_percent)+'% </h2>');
							}
								
							$('#rec_act_productinfo'+x).append('<p>'+data1[x].title +'</p>');
							$('#rec_act_productinfo'+x).append('<a href="productDetail.php?product_id='+data1[x].product_id +'" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>');
							$('#rec_act_single-products'+x).append('</div>');
							$('#rec_act_product-image-wrapper'+x).append('</div>');
							$('#rec_act_col-sm-4'+x).append('</div>');
						
					}
					flag ++;
				}
				flag = 0;
				for(var y =1 ; y < data1.length; y++)
				{
					if((y%3 == 0) && flag < 9)
					{
						$('#rec_inactive').append('<div class="col-sm-4" id="rec_inact_col-sm-4'+y+'">');
						$('#rec_inact_col-sm-4'+y).append('<div class="product-image-wrapper" id="rec_inact_product-image-wrapper'+y+'">');
						$('#rec_inact_product-image-wrapper'+y).append('<div class="single-products" id="rec_inact_single-products'+y+'">');
						$('#rec_inact_single-products'+y).append('<div class="productinfo text-center" id="rec_inact_productinfo'+y+'">');
						$('#rec_inact_productinfo'+y).append('<img style="width:207px;height:183px"src="'+data1[y].image_path +'" alt="" />');
							var discount_percent = data1[y].discount_percent;
							if(discount_percent == 0)
								$('#rec_inact_productinfo'+y).append('<h2>Rs. '+data1[y].price +'</h2>');
							else
							{
								var sellingPrice = parseFloat( parseFloat(data1[y].price).toFixed(2) -((parseFloat(discount_percent).toFixed(2)/parseFloat(100).toFixed(2)) * parseFloat(data1[y].price).toFixed(2)));
								
								$('#rec_inact_productinfo'+y).append('<h2>Rs. '+sellingPrice +'</h2>');
							}
								
							$('#rec_inact_productinfo'+y).append('<p>'+data1[y].title +'</p>');
							$('#rec_inact_productinfo'+y).append('<a href="productDetail.php?product_id='+data1[y].product_id +'" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>');
							$('#rec_inact_single-products'+y).append('</div>');
							$('#rec_inact_product-image-wrapper'+y).append('</div>');
							$('#rec_inact_col-sm-4'+y).append('</div>');
					
					}
					flag ++;
				}
				
		
			} 
          
        });
		}
		
	
	function setMainImage(img)
	{
		document.getElementById("disp5").src = img.src;
		$('#disp5').each(function() {
			var maxWidth = 400; // Max width for the image
			var maxHeight = 400;    // Max height for the image
			var ratio = 0;  // Used for aspect ratio
			var width = $(this).width();    // Current image width
			var height = $(this).height();  // Current image height
 
			// Check if the current width is larger than the max
			if(width > maxWidth){
				ratio = maxWidth / width;   // get ratio for scaling image
				$(this).css("width", maxWidth); // Set new width
				$(this).css("height", height * ratio);  // Scale height based on ratio
				height = height * ratio;    // Reset height to match scaled image
				width = width * ratio;    // Reset width to match scaled image
			}
	 
			// Check if current height is larger than max
			if(height > maxHeight){
				ratio = maxHeight / height; // get ratio for scaling image
				$(this).css("height", maxHeight);   // Set new height
				$(this).css("width", width * ratio);    // Scale width based on ratio
				width = width * ratio;    // Reset width to match scaled image
			}
		});
		$('#ex1').zoom();
	}
	


	
		function generateSearchURL()
		{
			var x=document.getElementById("srch-term");
  
			window.open("productlist.php?gender=all&subcategory="+x.value);
			
		}
		
		Array.prototype.max = function() {
			var max = this[0];
			var len = this.length;
			for (var i = 1; i < len; i++) if (this[i] > max) max = this[i];
			return max;
		}
		Array.prototype.min = function() {
			var min = this[0];
			var len = this.length;
			for (var i = 1; i < len; i++) if (this[i] < min) min = this[i];
			return min;
		}
		Array.prototype.unique = function() 
		{
			var unique = [];
			for (var i = 0; i < this.length; i++) {
				if (unique.indexOf(this[i]) == -1) {
					unique.push(this[i]);
				}
			}
			return unique;
		}
		
	var newwindow;
	function poptastic(url)
	{
		newwindow=window.open(url,'name','height=550,width=750,left=50,top=100');
		if (window.focus) {newwindow.focus()}
	}
	
		function getOrders() 
		{		
			var user_id = <?php echo $user_id ; ?>;
			$.ajax({ 
		  type: 'post', 
		  url: "getOrderByUserId.php", 				   
		  data: {user_id:user_id}, 
		  dataType: 'json',
		  success: function(data1){ 
		  //console.log(data1);
		  $('#orderTable').empty();
		  
				for(var i=0; i<data1.length; i++) 
				{
					if(data1[i].order_id != -1)				
						$('#orderTable').append('<TR><TD>' + data1[i].order_id +'</TD><TD>' + data1[i].total_quantity +'</TD><TD>' + data1[i].total_price +'</TD><TD><INPUT class="pid" type="button" value="Details" alt="' + data1[i].order_id + '"/></TD></TR>');
                     else
						$('#orderTable').append('<br/><font face="arial" size="3" color="#cb3327">Sorry no order found. Please placed the order first.'+'<br/>'+'Happy Shopping!</font>');
                     
					 
                }
				 $('.pid').click(viewOrderDetails);
            }
				
		   
			}); 
        }
		
		function getCashback()
		{
			var user_id = <?php echo $user_id ; ?>;
			$.ajax({ 
			  type: 'post', 
			  url: "getCashbackByUserId.php", 				   
			  data: {user_id:user_id}, 
			  dataType: 'json',
			  success: function(data1){ 
					  //console.log(data1);
					  $('#cashback').empty();
					  if(data1.length > 0)
					  {
						$('#cashback').append('<h4 style="color:#2b338e;"> Your Cashback Amount is &#8377; '+data1[0].total_cashback+'</h4>');
					  
					  }
					
				}
					
			   
				});
		
		}
		
		function getReturnOrders()
		{
			var user_id = <?php echo $user_id ; ?>;
			$.ajax({ 
			  type: 'post', 
			  url: "getReturnOrderByUserId.php", 				   
			  data: {user_id:user_id}, 
			  dataType: 'json',
			  success: function(data1){ 
			  //console.log(data1);
			  $('#orderReturnTable').empty();
			  
					for(var i=0; i<data1.length; i++) 
					{
						if(data1[i].order_id != -1)				
							$('#orderReturnTable').append('<TR><TD>' + data1[i].order_id +'</TD><TD>' + data1[i].total_quantity +'</TD><TD>' + data1[i].total_price +'</TD><TD><INPUT class="pid" type="button" value="Details" alt="' + data1[i].order_id + '"/></TD></TR>');
						 else
							$('#orderReturnTable').append('<br/><font face="arial" size="3" color="#cb3327">Sorry no order found. Please placed the order first.'+'<br/>'+'Happy Shopping!</font>');
						 
						 
					}
					 $('.pid').click(viewOrderDetails);
				}
					
			   
				});
		}
		
	function viewOrderDetails()
	{
		var order_id = $(this).attr('alt');
		poptastic("view_order_detail.php?oid="+order_id);
	}
	
	</script>
	<script type='text/javascript'>
	
	$(function () {
    var items = $('#ib-v-nav>ul>li').each(function () {
        $(this).click(function () {
            //remove previous class and add it to clicked tab
            items.removeClass('current');
            $(this).addClass('current');

            //hide all content divs and show current one
            $('#ib-v-nav>div.acc-content').hide().eq(items.index($(this))).show('fast');

            window.location.hash = $(this).attr('tab');
			
			
			if($(this).attr('tab') == 'tab2') // get order details
			{
				getOrders();
			}
			else if($(this).attr('tab') == 'tab3') // get cashback details
			{
				getCashback();
			}
			else if($(this).attr('tab') == 'tab4') // get product return details
			{
				getReturnOrders();
			}
			
        });
    });

    if (location.hash) {
        showTab(location.hash);
    }
    else {
        showTab("tab1");
    }

    function showTab(tab) {
        $("#ib-v-nav ul li:[tab*=" + tab + "]").click();
    }

    // Bind the event hashchange, using jquery-hashchange-plugin
    $(window).hashchange(function () {
        showTab(location.hash.replace("#", ""));
    })

    // Trigger the event hashchange on page load, using jquery-hashchange-plugin
    $(window).hashchange();
});

	</script>

</head><!--/head-->

<body onload="getAllCategoryProduct();">
	<header id="header"><!--header-->
		<div class="header_top"><!--header_top-->
			<div class="container">
				<div class="row">
					<div class="col-sm-6">
						<div class="contactinfo">
							<ul class="nav nav-pills">
								<li><a href="#"><i class="fa fa-phone"></i> +91 xxxxxxxxxx</a></li>
								<li><a href="#"><i class="fa fa-envelope"></i> your-customer-care-email@domain.com</a></li>
							</ul>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="social-icons pull-right">
							<ul class="nav navbar-nav">
								<li><a href="#"><i class="fa fa-facebook"></i></a></li>
								<li><a href="#"><i class="fa fa-twitter"></i></a></li>
								<li><a href="#"><i class="fa fa-linkedin"></i></a></li>
								<li><a href="#"><i class="fa fa-dribbble"></i></a></li>
								<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div><!--/header_top-->
		
		<div class="header-middle"><!--header-middle-->
			<div class="container">
				<div class="row">
					<div class="col-sm-4">
						<div class="logo pull-left">
							<a href="index.php"><img src="../images/home/logo.png" alt="" /></a>
						</div>
					
					</div>
					<div class="col-sm-8">
						<div class="shop-menu pull-right">
							<ul class="nav navbar-nav">
								<li id="account_link"><a href="user_account.php" id ="acc"><i class="fa fa-user"></i> Account</a></li>
								<li id="logout_link"><a href="logout.php" ><i class="fa fa-crosshairs"></i>Logout</a></li>
								<li><a href="view_cart.php"><i class="fa fa-shopping-cart"></i> Cart</a></li>
								<li id="login_link"><a href="login.php"><i class="fa fa-lock"></i> Login</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div><!--/header-middle-->
	
		<div class="header-bottom"><!--header-bottom-->
			<div class="container">
				<div class="row">
					<div class="col-sm-9">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
						<div class="mainmenu pull-left">
							<ul class="nav navbar-nav collapse navbar-collapse">
								<li><a href="../index.php">Home</a></li>
								<li class="dropdown"><a href="#" class="active">Shop<i class="fa fa-angle-down"></i></a>
                                    <ul role="menu" class="sub-menu" id="header_submenu">
                                       
                                    </ul>
                                </li> 
								<li class="dropdown"><a href="#">Blog<i class="fa fa-angle-down"></i></a>
                                    <ul role="menu" class="sub-menu">
                                        <li><a href="../blog.php">Blog List</a></li>
                                    </ul>
                                </li> 
								
								<li><a href="../contact-us.php">Contact</a></li>
							</ul>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="search_box pull-right">
							<input type="text" placeholder="Search" id="srch-term" />
							<button class="btn btn-warning  getsearch" type="submit" ONCLICK="generateSearchURL()"><i class="glyphicon glyphicon-search"></i></button>
							
						</div>
					</div>
				</div>
			</div>
		</div><!--/header-bottom-->
	
	</header><!--/header-->

	
	<section>
		<div class="container">
			<div class="row">
				<h2 class="title text-center">User Details</h2>
				<div class="ib-wrapper" >
            
					<div id="ib-v-nav" >
						<ul>
							<li tab="tab1" class="first current">Profile</li>
							<li class="" tab="tab2">Order Details</li>
							<li class="" tab="tab3">Cashback</li>
							<li tab="tab4" class="" >Product Return</li>
							<li tab="tab5" class="last">Referral</li>
						</ul>

						<div style="display: block;" class="acc-content">
						<center>
							<h4 style="margin-bottom:19px;">
								Profile
							</h4>
							
							<div class="grid">
								<form action="profile.php" method="post" name="profileForm" id="profileForm" class="form-validate">
									<div>								
										<table CELLPADDING=9>
											<tr>
											
												<td><label for="username">Name*</label></td>
												<td><input id="username" name="username" size="30" readonly value=<?php echo $user_name ; ?> ></td>
											  
											</tr>
											<tr>
												<td><label for="gender">Gender*</label></td>
												<td>
													<?php if($sex == "male")
															{
																echo '<select id="sex" name="sex" style="width:170px">
																		<option value="male">Male</option>
																		<option value="female">Female</option>													
																	</select>';
															}
															else
															{
																echo '<select id="sex" name="sex" style="width:170px">
																		<option value="female">Female</option>
																		<option value="male">Male</option>
																	</select>';
															}
													?>
												
												</td>
											 
											</tr>
											
											<tr>
											
											  <td><label for="phone">Phone*</label></td>
											  <td><input type="text" name="contact_number" id="contact_number" size="30" required onkeypress='return event.charCode >= 48 && event.charCode <= 57' value=<?php echo $contact_number ; ?> ></td>
											  
											</tr>											
											<tr>
											  <td><label  for="contact_text" >Shipping Address*</label></td>
											  <td><textarea cols="30" rows="12" name="ship_add" id="ship_add" required ><?php echo $ship_add ; ?></textarea></td>
											 
											</tr>
											<tr>
											  <td><label for="contact_person">City*</label></td>
											  <td><select id="city" name="city">
													<option value="Mumbai">Mumbai</option>
												   </select></td>
											 
											</tr>
											<tr>
											  <td><label for="contact_person">State*</label></td>
											  <td><select id="state" name="state">
													<option value="Maharashtra">Maharashtra</option>
												   </select>
											  </td>
											 
											</tr>
											<tr>
											  <td><label for="contact_person">Pincode*</label></td>
											  <td><input type="text" name="pincode" id="pincode" size="30" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required value=<?php echo $pincode ; ?> ></td>
											 <td><input type="hidden" name="user_id" id ="user_id" value=<?php echo $user_id ; ?> /></td>
											</tr>
										</table>
										<div id="dm2"></div>
										<div style="width:40px; margin:0 auto;" >
											<div> <input align = "middle" type="submit" value="Save">
											</div>
										</div>
									
									
									
									</div>


								</form>
								
							</div>
							</center>
						</div>

						<div style="display: none;" class="acc-content">
							<center>
								<h4 style="margin-bottom:19px;">
									Order Details
								</h4>
								<div style="width: 700px;height:400px;overflow:auto;">
								
									<table class="account-table">
										<thead>
											<tr>
											  <th>Order number</th>											  
											  <th>Quantity</th>
											  <th>Amount (&#8377;)</th>
											  <th>View Details</th>
											</tr>
										</thead>
										<tbody id="orderTable">
										</tbody>
									</table>
							
								</div>
							<center>
						</div>
						

						<div style="display: none;" class="acc-content">
							<center>
								<h4 style="margin-bottom:19px;">
									Cashback Details
								</h4>
								<div style="width: 700px;height:400px;overflow:auto;">
									
									<div id="cashback">									
								
									</div>
							</center>
						</div>

						<div style="display: none;" class="acc-content">
							<center>
								<h4 style="margin-bottom:19px;">
									Product Return Details
								</h4>
								<div style="width: 700px;height:400px;overflow:auto;">
								
									<table class="account-table">
										<thead>
											<tr>
											  <th>Order number</th>
											  <th>Quantity</th>
											  <th>Amount</th>
											  <th>View Details</th>
											</tr>
										</thead>
										<tbody id="orderReturnTable">
										</tbody>
									</table>
							
								</div>
							</center>
						</div>
						
						
						<div style="display: none;" class="acc-content">
							<center>
								<h4 style="margin-bottom:19px;">
									Referral
								</h4>
								<h5 style="color:#2b338e;">Invite your friends to join today!</h5>
								<form method="post" id="refer_pal" action="referral.php">
											
									<div align='center' class="div_label">
									<h5 style="color:#cb3327;">Enter email addresses separated by Commas :</h5>
									</div>
									<div align='center' class="div_label">
										
											<textarea rows="4" cols="30" name="ref_email" id="ref_email" required>
											</textarea> 
										
									</div>
									<div align='center' class="div_label"><br/>
									<input type="hidden" name="userid" id ="userid" value=<?php echo $user_id ; ?> />
									<input  value="Submit"  type="submit">
									
									</div>
												
								</form>
							</center>
						
					</div>

					
				</div>     
	</div>
				
			</div>
		</div>
	</section>
	
<footer id="footer"><!--Footer-->
	
		
		<div class="footer-widget">
			<div class="container">
				<div class="row">
					<div class="col-sm-2">
						<div class="single-widget">
							<h2>Service</h2>
							<ul class="nav nav-pills nav-stacked">
								<li><a href="contact-us.html">Contact Us</a></li>
								<li><a href="login.php">Order Status</a></li>
								<li><a href="javascript:poptastic('faq.html');">FAQ&rsquo;s</a></li>
							</ul>
						</div>
					</div>
				
					<div class="col-sm-2">
						<div class="single-widget">
							<h2>Policies</h2>
							<ul class="nav nav-pills nav-stacked">
								<li><a href="javascript:poptastic('tnc.html');">Terms of Use</a></li>
								<li><a href="javascript:poptastic('privacy.html');">Privacy Policy</a></li>
								<li><a href="javascript:poptastic('return.html');">Return Policy</a></li>
								<li><a href="javascript:poptastic('pricing.html');">Pricing Policy</a></li>
								
							</ul>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="single-widget">
							<h2>About Shopper</h2>
							<ul class="nav nav-pills nav-stacked">								
								<li><a href="javascript:poptastic('licences.html');">Licences</a></li>
							</ul>
						</div>
					</div>
					<div class="col-sm-3 col-sm-offset-1">
						<div class="single-widget">
							<div id="newsletter-subscribe-block" class="newsletter-subscribe-block">
								<h5>Sign up for our newsletter:</h5>
								<div class="newsletter-email">
									<input class="form-control" id="newsletter-email" name="NewsletterEmail" placeholder="Enter email" type="text" value="">
									<span class="field-validation-valid" data-valmsg-for="NewsletterEmail" data-valmsg-replace="true"></span>
								</div>
								<div class="buttons">
									<input type="button" value="Subscribe" id="newsletter-subscribe-button" class="btn btn-warning">
									<span id="subscribe-loading-progress" style="display: none;" class="please-wait">Wait...</span>
								</div>
							</div>
							<div id="newsletter-result-block" class="newsletter-result-block">
							</div>
							    <script type="text/javascript">
								$(document).ready(function () {
									$('#newsletter-subscribe-button').click(function () {
										
										var email = $("#newsletter-email").val();
										var subscribeProgress = $("#subscribe-loading-progress");
										subscribeProgress.show();
										$.ajax({
											cache: false,
											type: "POST",
											url: "../php/subscribenewsletter.php",
											data: { "email": email },
											success: function (data1) {
												subscribeProgress.hide();
												var data = JSON.parse(data1);
												document.getElementById("newsletter-result-block").innerHTML =data.Result;
												 if (data.Success) {
													$('#newsletter-subscribe-block').hide();
													$('#newsletter-result-block').show();
												 }
												 else {
													$('#newsletter-result-block').fadeIn("slow").delay(2000).fadeOut("slow");
												 }
											},
											error:function (xhr, ajaxOptions, thrownError){
												alert('Failed to subscribe.');
												subscribeProgress.hide();
											}  
										});                
										return false;
									});
								});
							</script>
						</div>
					</div>
					
				</div>
			</div>
		</div>
		
		<div class="footer-bottom">
			<div class="container">
				<div class="row">
					<p class="pull-left">Copyright &copy; 2015 XXXX. All rights reserved.</p>
					<p class="pull-right">Developed by <span><a target="_blank" href="http://www.mindnotion.com">MindNotion</a></span></p>
				</div>
			</div>
		</div>
		
	</footer><!--/Footer-->
	

  
    <script src="../js/jquery.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/jquery.scrollUp.min.js"></script>
	<script src="../js/jquery-ui.js"></script>
    <script src="../js/jquery.prettyPhoto.js"></script>
    <script src="../js/main.js"></script>
	<script src="../js/jPages.js"></script>	
	<script src="../js/jquery.zoom.js"></script>
</body>
</html>
<? ob_flush(); ?>