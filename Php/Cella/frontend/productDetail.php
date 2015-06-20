<?php
ob_start();
session_start(); 
header('Cache-control: private');
include("../php/connect.php");
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$actual_link =base64_encode($actual_link);
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
    <title>Product | Cella</title>
	
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
	
		<style>
		
		.zoom {
			display:inline-block;
			position: relative;
		}
		
		/* magnifying glass icon */
		.zoom:after {
			content:'';
			display:block; 
			width:33px; 
			height:33px; 
			position:absolute; 
			top:0;
			right:0;
			background:url(../images/icon.png);
		}

		.zoom img {
			display: block;
		}

		.zoom img::selection { background-color: transparent; }
	</style>
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
	
	$('[data-toggle="tooltip"]').tooltip();   
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
						$('#panel-heading'+j).append('<h4 class="panel-title"><a href="productlist.php?gender='+splitResult[0]+'&category='+subMenuResult[j]+'">'+subMenuResult[j]+'</a></h4>');
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
				parseURL(document.URL);
		   
			} 
          
        });
		}
		
		function parseURL(url) {
		var parser = document.createElement('a'),
			searchObject = {},
			queries, split, i;
		// Let the browser do the work
		parser.href = url;
		// Convert query string to object
		queries = parser.search.replace(/^\?/, '').split('&');
		var productFlag = 0;
		var productValue ;
		for( i = 0; i < queries.length; i++ ) {
			split = queries[i].split('=');
			searchObject[split[0]] = split[1];
			
			if(split[0] =="product_id")
			{			
				productValue = split[1];
			}
	
			
		}
		
		getProductDetail(productValue);

			
		
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
		document.getElementById("size_info").title = dimensionArray[x];
		document.getElementById("avaiable_info").innerHTML = quantityArray[x];
		document.getElementById("product_size").value = sizeArray[x];
		document.getElementById("product_dim").value = dimensionArray[x];
		
	}
	
	function getProductDetail(productId)
	{
	
			//alert(sqlQueryData);
		$.ajax({ 
		  type: 'post', 
		  url: "getProductById.php", 				   
		  data: {product_id:productId}, 
		  dataType: 'json',
		  success: function(data1)
		  { 
			 
				
				
				if(data1.length > 0 && data1[0].product_id !=-1 )
				{	
					//productData = data1;
					productSubcategory = data1[0].subcategory;
					productID  = data1[0].product_id;
					productCode  = data1[0].product_code;
					productColor = data1[0].color;
					productPrice = data1[0].price;
					productDiscount = data1[0].discount_percent;
					productGender = data1[0].gender;
					document.getElementById("title").innerHTML = data1[0].title;
					var img_count = data1[1].img_count +2;
					
					document.getElementById('product_id').value = productID;
					document.getElementById('product_qty').value = 1;					
					document.getElementById('return_url').value = base64_encode(document.URL);
					/***************************************************/
					if(productDiscount == 0)
					{
						$('#rec_act_productinfo').append('<h4>Price: &#8377; '+data1[0].price +'</h4>');
						
					}
					else
					{							
						var actualPrice = parseFloat( parseFloat(data1[0].price).toFixed(2));
						var sellingPrice = parseFloat( parseFloat(data1[0].price).toFixed(2) -((parseFloat(data1[0].discount_percent).toFixed(2)/parseFloat(100).toFixed(2)) * parseFloat(data1[0].price).toFixed(2)));
						
						$('#rec_act_productinfo').append('<h4><s>Price: &#8377;'+actualPrice +'</s> &#8377;'+sellingPrice +' Discount: '+parseFloat(data1[0].discount_percent)+'% </h4>');
					}
					/*****************************************************************/
					document.getElementById("disp1").style.visibility = "hidden";
					document.getElementById("disp2").style.visibility = "hidden";
					document.getElementById("disp3").style.visibility = "hidden";
					document.getElementById("disp4").style.visibility = "hidden";
					
					//console.log(data1[1].img_count);
					for (var x = 2; x < img_count;x++)
					{
						if(x == 2 )
						{
							//console.log(data1[x].image_path);							
							document.getElementById("disp1").style.visibility="visible";
							document.getElementById("disp1").src = data1[x].image_path;
							document.getElementById("disp5").src = data1[x].image_path;
							document.getElementById('product_img').value = data1[x].image_path;
							$('#disp5').each(function() {
								var maxWidth = 300; // Max width for the image
								var maxHeight = 413;    // Max height for the image
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
						if(x == 3 )
						{
							//console.log(data1[x].image_path);
							document.getElementById("disp2").src = data1[x].image_path;
							document.getElementById("disp2").style.visibility="visible";
							
							
						}
						if(x == 4 )
						{
							document.getElementById("disp3").style.visibility="visible";
							document.getElementById("disp3").src = data1[x].image_path;
						}
						if(x == 5 )
						{
							document.getElementById("disp4").style.visibility="visible";
							document.getElementById("disp4").src = data1[x].image_path;
						}								
					}
					
					if(document.getElementById("disp1").style.visibility == "hidden") 
						document.getElementById("disp1").remove();
					if(document.getElementById("disp2").style.visibility == "hidden")
						document.getElementById("disp2").remove();
					if(document.getElementById("disp3").style.visibility == "hidden")
						document.getElementById("disp3").remove();
					if(document.getElementById("disp4").style.visibility == "hidden")
						document.getElementById("disp4").remove();
					
					
					var qty_count = data1[img_count].qty_count;
					var init = img_count+1;
					var end = init+ qty_count;
					//console.log(qty_count);
					var totalQty = 0;
					for (var x = init; x < end;x++)
					{
						sizeArray.push(data1[x].size_type);
						dimensionArray.push(data1[x].dimension);
						quantityArray.push(data1[x].quantity );
						totalQty = totalQty+parseInt(data1[x].quantity);
						
					}
					
					//console.log(totalQty);
					if(totalQty > 0)
					{
						document.getElementById("soldout").remove();
						document.getElementById("atc").style.visibility = "visible";
					}
					else
					{						
						document.getElementById("atc").remove();
					}
					$("#size_select_dropdown").empty();
					for(h = 0; h < sizeArray.length ; h++)
					{
						$("#size_select_dropdown").append($("<option></option>").val(h).html(sizeArray[h])); 
					}
					
					$("#avaiable_info").empty();
					document.getElementById("size_info").title = dimensionArray[0];
					document.getElementById("avaiable_info").innerHTML = quantityArray[0];
					document.getElementById("product_size").value =sizeArray[0] ;
					document.getElementById("product_dim").value = dimensionArray[0];
					
					productDesc = data1[data1.length-1].product_desc;
					productCare = data1[data1.length-1].product_care;
					$("#product_desc").empty();
					$("#product_care").empty();
					$("#product_desc").append(data1[data1.length-1].product_desc);
					$("#product_care").append(data1[data1.length-1].product_care);
					
					showProductSubCategories();
						
				}
				

					
			}	
		   
		});
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
						$('#rec_act_productinfo'+x).append('<div class="product-image-view"><img class ="product_list_image" src="'+data1[x].image_path +'" alt="" /></div>');
							var discount_percent = data1[x].discount_percent;
							if(discount_percent == 0)
								$('#rec_act_productinfo'+x).append('<h2>&#8377; '+data1[x].price +'</h2>');
							else
							{							
								var actualPrice = parseFloat( parseFloat(data1[x].price).toFixed(2));
								var sellingPrice = parseFloat( parseFloat(data1[x].price).toFixed(2) -((parseFloat(discount_percent).toFixed(2)/parseFloat(100).toFixed(2)) * parseFloat(data1[x].price).toFixed(2)));
								
								$('#rec_act_productinfo'+x).append('<h2><s>&#8377;'+actualPrice +'</s> &#8377;'+sellingPrice +' discount: '+parseFloat(discount_percent)+'% </h2>');
							}
								
							$('#rec_act_productinfo'+x).append('<p>'+data1[x].title +'</p>');
							$('#rec_act_productinfo'+x).append('<a href="productDetail.php?product_id='+data1[x].product_id +'" class="btn btn-success"><i class="fa fa-shopping-cart"></i>View</a>');
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
						$('#rec_inact_productinfo'+y).append('<div class="product-image-view"><img class ="product_list_image" src="'+data1[y].image_path +'" alt="" /></div>');
							var discount_percent = data1[y].discount_percent;
							if(discount_percent == 0)
								$('#rec_inact_productinfo'+y).append('<h2>&#8377; '+data1[y].price +'</h2>');
							else
							{
								var sellingPrice = parseFloat( parseFloat(data1[y].price).toFixed(2) -((parseFloat(discount_percent).toFixed(2)/parseFloat(100).toFixed(2)) * parseFloat(data1[y].price).toFixed(2)));
								
								$('#rec_inact_productinfo'+y).append('<h2>&#8377; '+sellingPrice +'</h2>');
							}
								
							$('#rec_inact_productinfo'+y).append('<p>'+data1[y].title +'</p>');
							$('#rec_inact_productinfo'+y).append('<a href="productDetail.php?product_id='+data1[y].product_id +'" class="btn btn-success"><i class="fa fa-shopping-cart"></i>View</a>');
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
	function AddToCart()
	{
		//productDetail.php?product_id="+productID
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
		
function base64_encode(data) {

  var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
    ac = 0,
    enc = '',
    tmp_arr = [];

  if (!data) {
    return data;
  }

  do { // pack three octets into four hexets
    o1 = data.charCodeAt(i++);
    o2 = data.charCodeAt(i++);
    o3 = data.charCodeAt(i++);

    bits = o1 << 16 | o2 << 8 | o3;

    h1 = bits >> 18 & 0x3f;
    h2 = bits >> 12 & 0x3f;
    h3 = bits >> 6 & 0x3f;
    h4 = bits & 0x3f;

    // use hexets to index into b64, and append result to encoded string
    tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
  } while (i < data.length);

  enc = tmp_arr.join('');

  var r = data.length % 3;

  return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
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
				<div class="col-sm-3">
					<div class="left-sidebar">
						<div><!--shipping-->
							<a href="#"><img id ="disp1" class="img-thumbnail" src="../images/home/shipping.jpg" alt="" vspace="10" onclick="setMainImage(this);" style="width:48px;height:64px;border:2px solid #0000A0"/></a>
						</div><!--/shipping-->
						<div><!--shipping-->
							<a href="#"><img id ="disp2" class="img-thumbnail" src="../images/home/shipping.jpg" alt="" vspace="10" onclick="setMainImage(this);" style="width:48px;height:64px;border:2px solid #0000A0"/></a>
						</div><!--/shipping-->
						<div><!--shipping-->
							<a href="#"><img id ="disp3" class="img-thumbnail" src="../images/home/shipping.jpg" alt="" vspace="10" onclick="setMainImage(this);" style="width:48px;height:64px;border:2px solid #0000A0"/></a>
						</div><!--/shipping-->
						<div><!--shipping-->
							<a href="#"><img id ="disp4"class="img-thumbnail"  src="../images/home/shipping.jpg" alt="" vspace="10" onclick="setMainImage(this);" style="width:48px;height:64px;border:2px solid #0000A0"/></a>
						</div><!--/shipping-->
					<h2>Category</h2>
					<div class="panel-group category-products" id="accordian"><!--category-productsr-->
						
					</div><!--/category-products-->
					</div>
				</div>
				
				<div class="col-sm-9 padding-right">
					<div class="product-details">
						<div class="col-sm-5">
							
								<span class='zoom' id='ex1'>								
										<img id ="disp5"  alt="" width="300" height="auto"/>	
								</span>	
														
						</div>
						<div class="col-sm-7">
							<div class="product-information"><!--/product-information-->							
								<h2 id="title"></h2>
								<div id="rec_act_productinfo"> </div>
								<span>								
									<label>Select Size</label>
									<select id = "size_select_dropdown"  class="select-picker" onchange="sizeDropDown()"></select>								
									<a href="#" id="size_info" data-toggle="tooltip" data-placement="bottom">Size Chart</a>
								</span>
								<p><b>Availability:</b> <span id="avaiable_info"></span></p>
								<span id="atc">
									<form id="cartForm" method="post" action="cart_update.php">
										<a onclick="cartForm.submit();" href="#"  class="btn btn-success"><i class="fa fa-shopping-cart"></i> Add to cart</a></td>
										<input type="hidden" name="product_id" id="product_id" />
										<input type="hidden" name="product_qty" id="product_qty" />
										<input type="hidden" name="product_img" id="product_img" />
										<input type="hidden" name="product_size" id="product_size" />
										<input type="hidden" name="product_dim" id="product_dim" />
										<input type="hidden" name="type" value="add" />
										<input type="hidden" name="return_url" id ="return_url"  />
									</form>
								</span>
								<div id ="soldout"><img  src="../images/soldout.png" /></div>
									
																
															
							</div><!--/product-information-->
						</div>
					</div><!--/product-details-->
					<div class="category-tab shop-details-tab"><!--category-tab-->
						<div class="col-sm-12">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#prod_desc" data-toggle="tab">Product Descripition</a></li>
								<li><a href="#prod_care" data-toggle="tab">Product Care</a></li>
								
							</ul>
						</div>
						<div class="tab-content">
							<div class="tab-pane fade active in" id="prod_desc" >
								<div class="col-sm-12">
									<p id="product_desc"></p>
								</div>
							</div>
							
							<div class="tab-pane fade" id="prod_care" >
								<div class="col-sm-12">
									<p id="product_care"></p>
								</div>
							</div>
														
						</div>
					</div><!--/category-tab-->
						<div class="recommended_items" id =""><!--recommended_items-->
							<h2 class="title text-center">recommended items</h2>
							
							<div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
							<div class="carousel-inner">
								<div class="item active" id="rec_active">	
																		
								</div>
								<div class="item" id="rec_inactive">	
									
								</div>
							</div>
							 <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
								<i class="fa fa-angle-left"></i>
							  </a>
							  <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
								<i class="fa fa-angle-right"></i>
							  </a>			
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