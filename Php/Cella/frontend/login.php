<?php
ob_start();
session_start(); 
header('Cache-control: private');
include("../php/connect.php");
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$parts = parse_url($actual_link);
parse_str($parts['query'], $query);
$len = strlen(base64_decode($query['rurl']));
$returnURL;
if($len > 0) 
{
	$returnURL = base64_decode($query['rurl']);
}
else
{
	$returnURL="../index.php";
}
$login=0;
if(isset($_SESSION['username']))
{
	$login=1;
}
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Home | Cella</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/prettyPhoto.css" rel="stylesheet">
    <link href="../css/price-range.css" rel="stylesheet">
    <link href="../css/animate.css" rel="stylesheet">
	<link href="../css/main.css" rel="stylesheet">
	<link href="../css/responsive.css" rel="stylesheet">
	<link rel="stylesheet" href="../css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->       
    <link rel="shortcut icon" href="../images/ico/favicon.ico">
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
	
	function buy()
	{
		//productDetail.php?product_id="+productID
	}

	
		function generateSearchURL()
		{
			var x=document.getElementById("srch-term");
  
			window.location ="productlist.php?gender=all&subcategory="+x.value;
			
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
		
		function showhidefield()
		{
			if (document.pform2.chkbox.checked)
			{
			  document.getElementById("hideablearea").style.visibility = "visible";
			}
			else
			{
			  document.getElementById("hideablearea").style.visibility = "hidden";
			}
		}
		
	var newwindow;
	function poptastic(url)
	{
		newwindow=window.open(url,'name','height=550,width=750,left=50,top=100');
		if (window.focus) {newwindow.focus()}
	}
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
								<li id="login_link"><a href="login.php" class="active"><i class="fa fa-lock"></i> Login</a></li>
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
								<li class="dropdown"><a href="#">Shop<i class="fa fa-angle-down"></i></a>
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

	<section id="form"><!--form-->
		<div class="container">
			<div class="row">
				<div class="col-sm-4 col-sm-offset-1">
					<div class="login-form"><!--login form-->
						<h2>Login to your account</h2>
						<form method="post" action="credential_validator.php">
							
							<input type="email" name="user_email" placeholder="Email Address" />
							<input type="password" name="pswd" placeholder="Password" />
							<input type="hidden" name="return_url" id ="return_url" value=<?php echo$returnURL ; ?> />
							<button type="submit" class="btn btn-default">Login</button>
						</form>
						<form method="POST" action="email_send_validator.php" name=pform2>
							<span>
								<input type="checkbox" name="chkbox" onclick="showhidefield()">
								Forget username / password click here!
							</span>
							<div id='hideablearea' style='visibility:hidden;'>
								<input type="text" name="email" placeholder="Email Address">
								<button type="submit" class="btn btn-default">Send</button>
							</div>
							
						</form>
					</div><!--/login form-->
				</div>
				<div class="col-sm-1">
					<h2 class="or">OR</h2>
				</div>
				<div class="col-sm-4">
					<div class="signup-form"><!--sign up form-->
						<h2>New User Signup!</h2>
						<form method="POST" action="email_username_validator.php" name=pform3>
							<input type="text" name="new_user_name" placeholder="Name"/>
							<input type="email" name="new_user_email" placeholder="Email Address"/>
							<input type="password" name="new_user_pswd" placeholder="Password"/>
							<button type="submit" class="btn btn-default">Signup</button>
						</form>
					</div><!--/sign up form-->
				</div>
			</div>
		</div>
	</section><!--/form-->
	
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
	<script src="../js/price-range.js"></script>
    <script src="../js/jquery.prettyPhoto.js"></script>
    <script src="../js/main.js"></script>
</body>
</html>
<? ob_flush(); ?>