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
    <title>Cella</title>
	
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
	<script src="../js/jquery-2.1.1.min.js"></script>
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
	
	var categoriesData;
	var priceArray = [];
	var sizeArray = [];
	var colorArray = [];
	var sqlQueryData = " WHERE ";
	var productData;
	var productMaxPrice;
	var productMinPrice;
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
		var categoryFlag = 0;
		var categoryValue ;
		for( i = 0; i < queries.length; i++ ) {
			split = queries[i].split('=');
			searchObject[split[0]] = split[1];
			
			if(split[0] =="category")
			{
				categoryFlag = 1;
				split[1] = split[1].replace(/%20/g, " ");
				categoryValue = split[1];
			}
			if(split[1] !="all")
			{
				split[1] = split[1].replace(/%20/g, " ");
				
				console.log(split[1])
				if(queries.length == 1)
				{
					if(split[0]=="discount_percent")
						sqlQueryData = sqlQueryData +split[0]+"> '0'";
					else
						sqlQueryData = sqlQueryData +split[0]+"='"+ split[1]+"'";
				}
				else if(i< queries.length -1 )
				{
					if(split[0]=="discount_percent")
						sqlQueryData = sqlQueryData +split[0]+">'0' AND ";
					else
						sqlQueryData = sqlQueryData +split[0]+"= '"+ split[1]+"' AND ";
				}
				else if(i== queries.length -1)
				{	
					if(split[0]=="discount_percent")
						sqlQueryData = sqlQueryData +split[0]+"> '0'";
					else
						sqlQueryData = sqlQueryData +split[0]+"='"+ split[1]+"'";
				}
			}	
			
		}
		
		getProducts();
		
		if(categoryFlag == 1)
			getSubcategory(categoryValue);
		else
		{
			$('#accordian2').hide();
			$('#h2_subcategory').hide();
		}
	}
	
	function getProductsInAscOrder()
	{
		$('#artwork').empty();
		if(productData.length > 0 && productData[0].product_id !=-1 )
		{	
			
			for (var x = 0; x < productData.length;x++)
			{
				$('#artwork').append('<div class="col-sm-4" id="rec_act_col-sm-4'+x+'">');
				$('#rec_act_col-sm-4'+x).append('<div class="product-image-wrapper" id="rec_act_product-image-wrapper'+x+'">');
				$('#rec_act_product-image-wrapper'+x).append('<div class="single-products" id="rec_act_single-products'+x+'">');
				$('#rec_act_single-products'+x).append('<div class="productinfo text-center" id="rec_act_productinfo'+x+'">');
				
				
				$('#rec_act_productinfo'+x).append('<div class="product-image-view"><img class ="product_list_image" src="'+productData[x].image_path0 +'" alt="" /></div>');
					var discount_percent = productData[x].discount_percent;
					if(discount_percent == 0)
					{
						$('#rec_act_productinfo'+x).append('<h2>&#8377; '+productData[x].price +'</h2>');
						
					}
					else
					{							
						var actualPrice = parseFloat( parseFloat(productData[x].price).toFixed(2));
						var sellingPrice = parseFloat( parseFloat(productData[x].price).toFixed(2) -((parseFloat(discount_percent).toFixed(2)/parseFloat(100).toFixed(2)) * parseFloat(productData[x].price).toFixed(2)));
						
						$('#rec_act_productinfo'+x).append('<h2><s>&#8377;'+actualPrice +'</s> &#8377;'+sellingPrice +' OFF: '+parseFloat(discount_percent)+'% </h2>');
					}
						
					$('#rec_act_productinfo'+x).append('<p>'+truncate(productData[x].title) +'</p>');
					$('#rec_act_productinfo'+x).append('<a href="productDetail.php?product_id='+productData[x].product_id +'" class="btn btn-success"><i class="fa fa-shopping-cart"></i> View</a>');
					$('#rec_act_single-products'+x).append('</div>');
					$('#rec_act_product-image-wrapper'+x).append('</div>');
					$('#rec_act_col-sm-4'+x).append('</div>');
				
						
			}		
			  $("div.holder").jPages({ containerID: "artwork"});
		}
		else
			$('#artwork').append("<h2>No item found</h2>");
	}
	
	
	function getProductsInDescOrder()
	{
		$('#artwork').empty();
		if(productData.length > 0 && productData[0].product_id !=-1 )
		{	
			
			for (var x = productData.length -1; x >= 0;x--)
			{
				$('#artwork').append('<div class="col-sm-4" id="rec_act_col-sm-4'+x+'">');
				$('#rec_act_col-sm-4'+x).append('<div class="product-image-wrapper" id="rec_act_product-image-wrapper'+x+'">');
				$('#rec_act_product-image-wrapper'+x).append('<div class="single-products" id="rec_act_single-products'+x+'">');
				$('#rec_act_single-products'+x).append('<div class="productinfo text-center" id="rec_act_productinfo'+x+'">');
				
				
				$('#rec_act_productinfo'+x).append('<div class="product-image-view"><img class ="product_list_image" src="'+productData[x].image_path0 +'" alt="" /></div>');
					var discount_percent = productData[x].discount_percent;
					if(discount_percent == 0)
					{
						$('#rec_act_productinfo'+x).append('<h2>&#8377; '+productData[x].price +'</h2>');
						
					}
					else
					{							
						var actualPrice = parseFloat( parseFloat(productData[x].price).toFixed(2));
						var sellingPrice = parseFloat( parseFloat(productData[x].price).toFixed(2) -((parseFloat(discount_percent).toFixed(2)/parseFloat(100).toFixed(2)) * parseFloat(productData[x].price).toFixed(2)));
						
						$('#rec_act_productinfo'+x).append('<h2><s>&#8377;'+actualPrice +'</s> &#8377;'+sellingPrice +' OFF: '+parseFloat(discount_percent)+'% </h2>');
					}
						
					$('#rec_act_productinfo'+x).append('<p>'+truncate(productData[x].title) +'</p>');
					$('#rec_act_productinfo'+x).append('<a href="productDetail.php?product_id='+productData[x].product_id +'" class="btn btn-success"><i class="fa fa-shopping-cart"></i> View</a>');
					$('#rec_act_single-products'+x).append('</div>');
					$('#rec_act_product-image-wrapper'+x).append('</div>');
					$('#rec_act_col-sm-4'+x).append('</div>');
				
						
			}		
			  $("div.holder").jPages({ containerID: "artwork"});
		}
		else
			$('#artwork').append("<h2>No item found</h2>");
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
	
	function getProducts()
	{
	
			//alert(sqlQueryData);
		$.ajax({ 
		  type: 'post', 
		  url: "getQueryProducts.php", 				   
		  data: {query:sqlQueryData}, 
		  dataType: 'json',
		  success: function(data1)
		  { 
			 
				$('#artwork').empty();
				sizeArray.push("all");								
				colorArray.push("all");
				if(data1.length > 0 && data1[0].product_id !=-1 )
				{	
					productData = data1;
					for (var x = 0; x < data1.length;x++)
					{
						$('#artwork').append('<div class="col-sm-4" id="rec_act_col-sm-4'+x+'">');
							$('#rec_act_col-sm-4'+x).append('<div class="product-image-wrapper" id="rec_act_product-image-wrapper'+x+'">');
							$('#rec_act_product-image-wrapper'+x).append('<div class="single-products" id="rec_act_single-products'+x+'">');
							$('#rec_act_single-products'+x).append('<div class="productinfo text-center" id="rec_act_productinfo'+x+'">');
							
							
							$('#rec_act_productinfo'+x).append('<div class="product-image-view"><img class ="product_list_image" src="'+data1[x].image_path0 +'" alt="" /></div>');
								var discount_percent = data1[x].discount_percent;
								if(discount_percent == 0)
								{
									$('#rec_act_productinfo'+x).append('<h2>&#8377; '+data1[x].price +'</h2>');
									priceArray.push(parseInt(data1[x].price));
								}
								else
								{							
									var actualPrice = parseFloat( parseFloat(data1[x].price).toFixed(2));
									var sellingPrice = parseFloat( parseFloat(data1[x].price).toFixed(2) -((parseFloat(discount_percent).toFixed(2)/parseFloat(100).toFixed(2)) * parseFloat(data1[x].price).toFixed(2)));
									priceArray.push(parseInt(sellingPrice));
									$('#rec_act_productinfo'+x).append('<h2><s>&#8377;'+actualPrice +'</s> &#8377;'+sellingPrice +' OFF: '+parseFloat(discount_percent)+'% </h2>');
								}
									
								$('#rec_act_productinfo'+x).append('<p>'+truncate(data1[x].title) +'</p>');
								$('#rec_act_productinfo'+x).append('<a href="productDetail.php?product_id='+data1[x].product_id +'" class="btn btn-success"><i class="fa fa-shopping-cart"></i> View</a>');
								$('#rec_act_single-products'+x).append('</div>');
								$('#rec_act_product-image-wrapper'+x).append('</div>');
								$('#rec_act_col-sm-4'+x).append('</div>');
								
								for(k = 0 ; k< data1[x].qty_count; k++)
								{
								
									var str = "size_type"+k;
									if(data1[x][str] !="-" && data1[x][str]!="")
										sizeArray.push(data1[x][str]);
								}
								colorArray.push(data1[x].color);
								
					}		
					  $("div.holder").jPages({ containerID: "artwork"});
				}
				else
					$('#artwork').append("<h2>No item found</h2>");
					 
					
					sliderPrice(priceArray.min() , priceArray.max() );
					fillSelectColor();
					fillSelectSize();
					
			}	
		   
		});
	}

		function sliderPrice(min, max)
		{
				$( "#slider-range" ).slider({
			  range: true,
			  min: min,
			  max: max,
			  values: [ min, max ],
			  slide: function( event, ui ) {
			   $( "#amount" ).val( "Rs." + ui.values[ 0 ] + " - Rs." + ui.values[ 1 ] );
			   
				$( "#amount" ).val( "Rs." + $( "#slider-range" ).slider( "values", 0 ) +
     " - Rs." + $( "#slider-range" ).slider( "values", 1 ) );
				$('#artwork').empty();					 

				productMaxPrice= ui.values[ 1 ];
				productMinPrice = ui.values[ 0 ];
				
				sliderPrice(priceArray.min() , priceArray.max() );
					
			   }
			});

			
		
		}
		
		function refreshProductList()
		{
				if(productData.length > 0 && productData[0].product_id !=-1 )
				{	
					
					for (var x = 0; x < productData.length;x++)
					{
						var price ;
						var discount_percent = productData[x].discount_percent;
						if(discount_percent == 0)
						{
							price= parseInt(productData[x].price );
							
						}
						else
						{							
							var actualPrice = parseFloat( parseFloat(productData[x].price).toFixed(2));
							var sellingPrice = parseFloat( parseFloat(productData[x].price).toFixed(2) -((parseFloat(discount_percent).toFixed(2)/parseFloat(100).toFixed(2)) * parseFloat(productData[x].price).toFixed(2)));
							price = parseInt(sellingPrice );
						}				
						if(price>= productMinPrice && price <= productMaxPrice)
						{
							$('#artwork').append('<div class="col-sm-4" id="rec_act_col-sm-4'+x+'">');
								$('#rec_act_col-sm-4'+x).append('<div class="product-image-wrapper" id="rec_act_product-image-wrapper'+x+'">');
								$('#rec_act_product-image-wrapper'+x).append('<div class="single-products" id="rec_act_single-products'+x+'">');
								$('#rec_act_single-products'+x).append('<div class="productinfo text-center" id="rec_act_productinfo'+x+'">');
								
								
								$('#rec_act_productinfo'+x).append('<div class="product-image-view"><img class ="product_list_image" src="'+productData[x].image_path0 +'" alt="" /></div>');
									var discount_percent = productData[x].discount_percent;
									if(discount_percent == 0)
									{
										$('#rec_act_productinfo'+x).append('<h2>&#8377; '+productData[x].price +'</h2>');
										
									}
									else
									{							
										var actualPrice = parseFloat( parseFloat(productData[x].price).toFixed(2));
										var sellingPrice = parseFloat( parseFloat(productData[x].price).toFixed(2) -((parseFloat(discount_percent).toFixed(2)/parseFloat(100).toFixed(2)) * parseFloat(productData[x].price).toFixed(2)));
										
										$('#rec_act_productinfo'+x).append('<h2><s>&#8377;'+actualPrice +'</s> &#8377;'+sellingPrice +' OFF: '+parseFloat(discount_percent)+'% </h2>');
									}
										
									$('#rec_act_productinfo'+x).append('<p>'+truncate(productData[x].title) +'</p>');
									$('#rec_act_productinfo'+x).append('<a href="productDetail.php?product_id='+productData[x].product_id +'" class="btn btn-success"><i class="fa fa-shopping-cart"></i> View</a>');
									$('#rec_act_single-products'+x).append('</div>');
									$('#rec_act_product-image-wrapper'+x).append('</div>');
									$('#rec_act_col-sm-4'+x).append('</div>');
						}
								
					}		
					  $("div.holder").jPages({ containerID: "artwork"});
				}
				else
					$('#artwork').append("<h2>No item found</h2>");
		}
		
		function fillSelectColor()
		{
			console.log(colorArray);
			colorArray = colorArray.unique();
			$("#color_select_dropdown").empty();
			for(h = 0; h < colorArray.length ; h++)
				$("#color_select_dropdown").append($("<option></option>").val(colorArray[h]).html(colorArray[h]));
		}
		
		function fillSelectSize()
		{
			console.log(sizeArray);
			sizeArray = sizeArray.unique();
			$("#size_select_dropdown").empty();
			for(h = 0; h < sizeArray.length ; h++)
				$("#size_select_dropdown").append($("<option></option>").val(sizeArray[h]).html(sizeArray[h]));
		}
		
		function getProductByColor(obj)
		{
			if(obj.value == "all")
				getProductsInAscOrder();
			else
			{
				$('#artwork').empty();
				if(productData.length > 0 && productData[0].product_id !=-1 )
				{	
					
					for (var x = 0; x < productData.length;x++)
					{
						if(productData[x].color == obj.value)
						{
							$('#artwork').append('<div class="col-sm-4" id="rec_act_col-sm-4'+x+'">');
							$('#rec_act_col-sm-4'+x).append('<div class="product-image-wrapper" id="rec_act_product-image-wrapper'+x+'">');
							$('#rec_act_product-image-wrapper'+x).append('<div class="single-products" id="rec_act_single-products'+x+'">');
							$('#rec_act_single-products'+x).append('<div class="productinfo text-center" id="rec_act_productinfo'+x+'">');
							
							
							$('#rec_act_productinfo'+x).append('<div class="product-image-view"><img class ="product_list_image" src="'+productData[x].image_path0 +'" alt="" /></div>');
								var discount_percent = productData[x].discount_percent;
								if(discount_percent == 0)
								{
									$('#rec_act_productinfo'+x).append('<h2>&#8377; '+productData[x].price +'</h2>');
									
								}
								else
								{							
									var actualPrice = parseFloat( parseFloat(productData[x].price).toFixed(2));
									var sellingPrice = parseFloat( parseFloat(productData[x].price).toFixed(2) -((parseFloat(discount_percent).toFixed(2)/parseFloat(100).toFixed(2)) * parseFloat(productData[x].price).toFixed(2)));
									
									$('#rec_act_productinfo'+x).append('<h2><s>&#8377;'+actualPrice +'</s> &#8377;'+sellingPrice +' OFF: '+parseFloat(discount_percent)+'% </h2>');
								}
									
								$('#rec_act_productinfo'+x).append('<p>'+truncate(productData[x].title) +'</p>');
								$('#rec_act_productinfo'+x).append('<a href="productDetail.php?product_id='+productData[x].product_id +'" class="btn btn-success"><i class="fa fa-shopping-cart"></i> View</a>');
								$('#rec_act_single-products'+x).append('</div>');
								$('#rec_act_product-image-wrapper'+x).append('</div>');
								$('#rec_act_col-sm-4'+x).append('</div>');
							
									
						}		
								  
					}
					$("div.holder").jPages({ containerID: "artwork"});
					
				}
				else
					$('#artwork').append("<h2>No item found</h2>");
			}
				
			
		}
		function getProductBySize(obj)
		{
			if(obj.value == "all")
				getProductsInAscOrder();
			else
			{
				$('#artwork').empty();
				if(productData.length > 0 && productData[0].product_id !=-1 )
				{	
					
					for (var x = 0; x < productData.length;x++)
					{
						var flag = 0;
						for(k = 0 ; k< productData[x].qty_count; k++)
						{
						
							var str = "size_type"+k;
							console.log(productData[x][str]);
							if(productData[x][str] == obj.value)
							{
								console.log("Index: "+k);
								console.log("obj: "+obj.value);
								flag =1;
								break;
							}
								
						}
						if(flag == 1)
						{
							$('#artwork').append('<div class="col-sm-4" id="rec_act_col-sm-4'+x+'">');
							$('#rec_act_col-sm-4'+x).append('<div class="product-image-wrapper" id="rec_act_product-image-wrapper'+x+'">');
							$('#rec_act_product-image-wrapper'+x).append('<div class="single-products" id="rec_act_single-products'+x+'">');
							$('#rec_act_single-products'+x).append('<div class="productinfo text-center" id="rec_act_productinfo'+x+'">');
							
							
							$('#rec_act_productinfo'+x).append('<div class="product-image-view"><img class ="product_list_image" src="'+productData[x].image_path0 +'" alt="" /></div>');
								var discount_percent = productData[x].discount_percent;
								if(discount_percent == 0)
								{
									$('#rec_act_productinfo'+x).append('<h2>&#8377; '+productData[x].price +'</h2>');
									
								}
								else
								{							
									var actualPrice = parseFloat( parseFloat(productData[x].price).toFixed(2));
									var sellingPrice = parseFloat( parseFloat(productData[x].price).toFixed(2) -((parseFloat(discount_percent).toFixed(2)/parseFloat(100).toFixed(2)) * parseFloat(productData[x].price).toFixed(2)));
									
									$('#rec_act_productinfo'+x).append('<h2><s>&#8377;'+actualPrice +'</s> &#8377;'+sellingPrice +' OFF: '+parseFloat(discount_percent)+'% </h2>');
								}
									
								$('#rec_act_productinfo'+x).append('<p>'+truncate(productData[x].title) +'</p>');
								$('#rec_act_productinfo'+x).append('<a href="productDetail.php?product_id='+productData[x].product_id +'" class="btn btn-success"><i class="fa fa-shopping-cart"></i> View</a>');
								$('#rec_act_single-products'+x).append('</div>');
								$('#rec_act_product-image-wrapper'+x).append('</div>');
								$('#rec_act_col-sm-4'+x).append('</div>');
							
									
						}		
								  
					}
					$("div.holder").jPages({ containerID: "artwork"});
					
				}
				else
					$('#artwork').append("<h2>No item found</h2>");
			
			}
		}
		
		
		function generateSearchURL()
		{
			var x=document.getElementById("srch-term");
  
			window.window.location ="productlist.php?gender=all&subcategory="+x.value;
			
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
	
	function truncate(string){
   if (string.length > 27)
      return string.substring(0,27)+'...';
   else
      return string;
};
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
						<h2 id="h2_subcategory">Sub-Category</h2>
						<div class="panel-group category-products" id="accordian2"><!--Sub category-productsr-->
							
						</div><!--/category-products-->
						<h2>Select Colour</h2>
						<div class="panel-group category-products" id="select_color"><!--Sub category-productsr-->
							<select id = "color_select_dropdown"  class="form-control" onchange="getProductByColor(this);"></select>
						</div><!--/category-products-->
						<h2>Select Size</h2>
						<div class="panel-group category-products" id="select_size"><!--Sub category-productsr-->
						<select id = "size_select_dropdown"  class="form-control" onchange="getProductBySize(this);">	</select> 	
						</div><!--/category-products-->
						
						<h2>Category</h2>
						<div class="panel-group category-products" id="accordian"><!--category-productsr-->
							
						</div><!--/category-products-->
				
						
						<div class="price-range"><!--price-range-->
						
						   <h2>Price Range</h2>
						   <input type="text" id="amount" style="border:0; color:#f6931f; font-weight:bold;">
						  
						  <div id="slider-range" onmouseup="refreshProductList();"></div>
						</div><!--/price-range-->
						
						<div class="shipping text-center"><!--shipping-->
							<img src="../images/home/shipping.jpg" alt="" />
						</div><!--/shipping-->
					
					</div>
				</div>
				
				<div class="col-sm-9 padding-right">
					<div class="features_items"><!--features_items-->
						<h2 class="title text-center">Features Items</h2>
						<div class="row">
							<div class="col-sm-9"></div>
							<div class="col-sm-3">
								<div class="pull-left">
									<a href="javascript:getProductsInAscOrder();">Low</a> | <a href="javascript:getProductsInDescOrder();">High</a>	
								</div>
							</div>
						</div>
						<div id="albumsDiv">
							<center>
							<ul id="artwork">
								
							</ul>		
							</center>		
						</div>
						<div class="holder">
						</div>	
					</div><!--features_items-->
				
					

					
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
</body>
</html>
<? ob_flush(); ?>