<?php
ob_start();
session_start(); 
header('Cache-control: private');
include("connect.php");

if(!isset($_SESSION['username']))
{
header("Location: ../login.html");
exit;
}?>

<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Cella Admin</title>

    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/sb-admin.css" rel="stylesheet">


    <!-- Custom Fonts -->
    <link href="../font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- jQuery -->
    <script src="../js/jquery.js"></script>
	<script src="../js/jquery-2.1.1.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

	<script type="text/javascript">
	var qtysize = 0;
	var sizeIdArray = new Array();
		function getProductToDB() {		
		
			$.ajax({ 
		  type: 'post', 
		  url: "getproduct.php", 				   
		  data: {}, 
		  dataType: 'json',
		  success: function(data1){ 
		  //console.log(data1);
		  $('#dataTable').empty();
		  
				for(var i=0; i<data1.length; i++) 
				{
					if(data1[i].product_id != -1)				
						$('#dataTable').append('<TR><TD>' + data1[i].product_id +'</TD><TD>' + data1[i].product_code +'</TD><TD>' + data1[i].title +'</TD><TD>' + data1[i].category +'</TD><TD><INPUT class="pid" type="button" value="Details" alt="' + data1[i].product_id + '"/></TD></TR>');
                                   
                }
				 $('.pid').click(viewDetails);
            }
				
		   
		}); 
          
        }
		
	function viewDetails()
	{
			var product_id = $(this).attr('alt');
			$.ajax({ 
		  type: 'post', 
		  url: "getProductById.php", 				   
		  data: {product_id:product_id}, 
		  dataType: 'json',
		  success: function(data1)
		  { 
			  //console.log(data1);
			  $('#productViewTable').empty();
				//alert(data1.length);
				
				var levelRight = <?php echo $_SESSION['level'] ; ?>;
				if((data1[0].product_id != -1) && levelRight == 0)
				{						
					$('#productViewTable').append('<TR><TD> Product Id</TD><TD>' + data1[0].product_id +'</TD><TR>' );   
					$('#productViewTable').append('<TR><TD> Product code</TD><TD>' + data1[0].product_code +'</TD><TR>' );  
					$('#productViewTable').append('<TR><TD> Product Category</TD><TD>' + data1[0].category +'</TD><TR>' );   
					$('#productViewTable').append('<TR><TD> Product Subcategory</TD><TD>' + data1[0].subcategory +'</TD><TR>' );
					
					$('#productViewTable').append('<TR><TD> Product Color</TD><TD>' + data1[0].color +'</TD><TR>' );   
					$('#productViewTable').append('<TR><TD> Product Price Rs.</TD><TD>' + data1[0].price +'</TD><TR>' );  
					$('#productViewTable').append('<TR><TD> Product Discount %</TD><TD>' + data1[0].discount_percent +'</TD><TR>' );  
					// 2nd element in array correspodes to no. of images size i.e. data[1].img_count
					//hence then we start iterating from 3rd item in array i.e. 2
					for(var i=2; i<=(data1[1].img_count+1); i++)
					{
						//<img src="smiley.gif" alt="Smiley face" height="42" width="42">
						$('#productViewTable').append('<TR><TD> Product Image</TD><TD><img src="' + data1[i].image_path +'" height="150" width="150"></TD><TR>' );  
					}
					
					var qtyCountIndex = data1[1].img_count +2;
					//alert(qtyCountIndex);
					var qtyCountSize =  data1[qtyCountIndex].qty_count ;
					//alert(qtyCountSize);
					// for size and quantity details
					for(var i=(qtyCountIndex+1); i<=(qtyCountIndex+qtyCountSize); i++)
					{					
						$('#productViewTable').append('<TR><TD> Product Size</TD><TD>' + data1[i].size_type +'</TD><TR>' );
						$('#productViewTable').append('<TR><TD> Product Dimension</TD><TD>' + data1[i].dimension +'</TD><TR>' );
						$('#productViewTable').append('<TR><TD> Product Quantity</TD><TD>' + data1[i].quantity +'</TD><TR>' );
						
					}				
					
				}
				else
				{
					if((data1[0].product_id != -1) && levelRight == 1)
					{
						
						
						$('#productViewTable').append('<TR><TD> Product Id</TD><TD>' + data1[0].product_id +'</TD><TR>' );   
						$('#productViewTable').append('<TR><TD> Product code</TD><TD>' + data1[0].product_code +'</TD><TR>' );  
						$('#productViewTable').append('<TR><TD> Product Category</TD><TD><textarea rows="1" cols="40" required id="pcategory" >' + data1[0].category +' </textarea></TD><TR>' );   
						$('#productViewTable').append('<TR><TD> Product Subcategory</TD><TD><textarea rows="1" cols="40" required id="psubcategory">' + data1[0].subcategory +' </textarea></TD><TR>' );						
						$('#productViewTable').append('<TR><TD> Product Color</TD><TD>' + data1[0].color +'</TD><TR>' );   
						$('#productViewTable').append('<TR><TD> Product Price Rs.</TD><TD><input type="text" value=' + data1[0].price +' onkeypress="return event.charCode >= 48 && event.charCode <= 57" required id="pprice"></TD><TR>' );  
						$('#productViewTable').append('<TR><TD> Product Discount %</TD><TD><input type="text" value=' + data1[0].discount_percent +' onkeypress="return event.charCode >= 48 && event.charCode <= 57" required id="pdiscount_percent"></TD><TR>' );  
						
						
						// 2nd element in array correspodes to no. of images size i.e. data[1].img_count
						//hence then we start iterating from 3rd item in array i.e. 2
						for(var i=2; i<=(data1[1].img_count+1); i++)
						{
							//<img src="smiley.gif" alt="Smiley face" height="42" width="42">
							$('#productViewTable').append('<TR><TD> Product Image</TD><TD><img src="' + data1[i].image_path +'" height="150" width="150"></TD><TR>' );  
						}
						
						var qtyCountIndex = data1[1].img_count +2;
						//alert(qtyCountIndex);
						var qtyCountSize =  data1[qtyCountIndex].qty_count ;
						qtysize = 0;
						//alert(qtyCountSize);
						// for size and quantity details
						sizeIdArray.length = 0;
						for(var i=(qtyCountIndex+1); i<=(qtyCountIndex+qtyCountSize); i++)
						{	
										
							$('#productViewTable').append('<TR><TD> Product Size</TD><TD><textarea rows="4" cols="40" required id=psize'+qtysize+'>'+data1[i].size_type+'</textarea></TD><TR>' );
										
							$('#productViewTable').append('<TR><TD> Product Dimension</TD><TD><textarea rows="4" cols="40" required  id=pdim'+qtysize+'>' + data1[i].dimension +'</textarea></TD><TR>' );
							$('#productViewTable').append('<TR><TD> Product Quantity</TD><TD><input type="text" value=' + data1[i].quantity +' id=pqty'+qtysize+' onkeypress="return event.charCode >= 48 && event.charCode <= 57" required ></TD><TR>' );
							sizeIdArray.push(data1[i].id);
							qtysize = qtysize+1;
						}
						
						//console.log("product_view product id "+data1[0].product_id);	
						$('#productViewTable').append('<TR><TD><button type="button" onclick="updateProductById('+data1[0].product_id+');">Update</button></TD><TD><button type="button" onclick="deleteProductById('+data1[0].product_id+');"><i class="fa fa-trash-o"></i></button></TD><TR>' );
						
					
					}
				
				}
				
            }
				
		   
		}); 
			
	}
		
		function updateProductById(product_id)
		{
			var errorFlag =0;
			var pcategory = document.getElementById("pcategory");
			var psubcategory= document.getElementById("psubcategory");
			var pprice= document.getElementById("pprice");
			var pdiscount_percent= document.getElementById("pdiscount_percent");
			
			var category = pcategory.value;
			var subcategory = psubcategory.value;
			var price = pprice.value;
			var discount = pdiscount_percent.value;
			if(pcategory.value == '' || psubcategory.value == '' ||  pprice.value == '' ||  pdiscount_percent.value == '')
			{
				errorFlag =1;
				alert('Please fill the empty fields');
			}
			else
			{
				for(j =0 ; j< qtysize; j++)
				{
					var psize = document.getElementById("psize"+j);
					var pdim = document.getElementById("pdim"+j);
					var pqty = document.getElementById("pqty"+j);
					if(psize.value == '' || pdim.value == '' ||  pqty.value == '')
					{
						errorFlag =1;
						alert('Please fill the empty fields');
					}
						
					//console.log(sizeIdArray[j]);	
				}
			}
			if(errorFlag == 0)
			{
						
				
				$.ajax({ 
					type: 'post', 
					url: "updateProductById.php", 				   
					data: {product_id:product_id, category:category, subcategory:subcategory, price:price, discount_percent:discount}, 
					dataType: 'json',
					success: function(data1){ 
				 // console.log(data1[0].update_status);
					var successFlag = 0;
						if(data1[0].update_status != -1)
						{
							for(j =0 ; j< qtysize; j++)
							{
								var psize = document.getElementById("psize"+j);
								var pdim = document.getElementById("pdim"+j);
								var pqty = document.getElementById("pqty"+j);
								
								var size = psize.value;
								var dim = pdim.value;
								var qty = pqty.value;
								var id = sizeIdArray[j];
								console.log("updateProductById j "+j);
								console.log("updateProductById product id "+product_id);
								console.log("updateProductById  id "+id);
								$.ajax({ 
									type: 'post', 
									url: "updateDimensionById.php", 				   
									data: {product_id:product_id,id:id,size:size,dim:dim,qty:qty}, 
									dataType: 'json',
									success: function(data1){ 
										successFlag = -1
										
									}
								});
								
								if(successFlag == 0)	
									alert('DB updated successfully');
							}
							
							
						
						}
						else
						{
							alert("Fail to update the DB");
						
						}
		
					}
				});
				
				
				
			}
		}
		function deleteProductById(product_id)
		{
			//alert(product_id);
			if (confirm("Are you you want to delete from database!") == true) 
			{
				$.ajax({ 
					  type: 'post', 
					  url: "deleteProductById.php", 				   
					  data: {product_id:product_id}, 
					  dataType: 'json',
					  success: function(data1){ 
							getProductToDB();
							$('#productViewTable').empty();
						}
							
					   
					}); 
			} 
			
		}
		function searchByProduct() {		
		
		var searchBy=document.getElementsByName("search_by");
		var searchvalue=document.getElementsByName("search_value");
		
		if (searchvalue[0].value == '')
		{
			alert("Please fill empty fields");
			searchvalue[0].focus();
		}
		else
		{
			$.ajax({ 
			  type: 'post', 
			  url: "searchByProduct.php", 				   
			  data: {search_by: searchBy[0].value, search_value: searchvalue[0].value}, 
			  dataType: 'json',
			  success: function(data1){ 
			  //console.log(data1);
			  $('#dataTable').empty();
			  $('#productViewTable').empty();
				for(var i=0; i<data1.length; i++) 
				{
					if(data1[i].product_id != -1)				
						$('#dataTable').append('<TR><TD>' + data1[i].product_id +'</TD><TD>' + data1[i].product_code +'</TD><TD>' + data1[i].title +'</TD><TD>' + data1[i].category +'</TD><TD><INPUT class="pid" type="button" value="Details" alt="' + data1[i].product_id + '"/></TD></TR>');
                                   
                }
				 $('.pid').click(viewDetails);
					
				}
				
		   
			}); 
		}
          
    }
	</script>
	 

</head>

<body onload="getProductToDB();">

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Cella Admin</a>
				<a class="navbar-brand" href="http://www.mindnotion.com">By MindNotion </a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i><?php echo $_SESSION['username']; ?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
							<a href="#" onclick='window.open("http://www.mindnotion.com" );'><i class="fa fa-fw fa-globe"></i>About MindNotion</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#product"><i class="fa fa-fw fa-glass"></i> Product <i class="fa fa-fw fa-caret-down"></i></a>
						 <ul id="product" class="Expand">
                            <li STYLE="background: #000000;">
                                <a href="product_view.php"><i class="fa fa-fw fa-info-circle"></i> View Product</a>
                            </li>
                            <li>
                                <a href="product_entry.php"><i class="fa fa-fw fa-edit"></i> Enter Product</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="customerDetailView.php"><i class="fa fa-fw fa-user"></i> View Customer Details</a>
                    </li>
                    
                    <li>
                        <a href="order_list.php"><i class="fa fa-fw fa-shopping-cart"></i> Order <i></i></a>
                       
                    </li>
					<li>
                        <a href="miscellaneous.php"><i class="fa fa-fw fa-dashboard"></i> Miscellaneous <i></i></a>
                       
                    </li>
					<li>
                        <a href="themes.php"><i class="fa fa-fw fa-crosshairs"></i> Styles and Themes <i></i></a>
                       
                    </li>
                    
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper" >

            <div class="container-fluid">
				<div class="row">
                    <div class="col-lg-6">
						<h1 class="page-header">
                            Product View 
                        </h1>
						<h1><small>Search Product</small></h1>
            
						<form role="form">
							
							
							<table style="width:100%">
							  <tr>
								<td>
									
										<label>Search By</label>
										<select  name="search_by">
											<option value="product_id" >Product ID</option>
											<option value="product_code">Product Code</option>
											<option value="category">Category</option>
											<option value="subcategory">Subcategory</option>
										</select>
																			
                                
								</td>
								<td>
								
								
									<input type="text" name="search_value" required">
									<span><button type="button" onclick="searchByProduct();"><i class="fa fa-search"></i></button></span>
								    <span><button type="button" onclick="getProductToDB();">All</button></span>
								</td>		
							   
							  </tr>
							 
							</table>
							

                        </form>
						<table class="table table-bordered table-hover">
						  <tr>
							<td>
								<div><label>List of product in  database</label></div>
								<div  style="width: 500px;height:800px;overflow:auto;">
								
									 <TABLE  class="table_item table table-fixed" border='1'>
										<thead>
										<tr>
											<th>Product Id</th>
											<th>Product Code</th>
											<th>Product Title</th>
											<th>Category</th>
											<th>View</th>
										</tr>
										</thead>
										<tbody id="dataTable">
										</tbody>
									</TABLE>
								
							</div>
							</td>
							<td>
							<div><label>Product Details</label></div>
								<div  style="width: 500px;height:800px;overflow:auto;">
								
									 <TABLE  width="500px" border="1" class="table table-bordered table-striped">
										<thead>
										<tr>
											<th>Product Attributes</th>
											<th>Product Values</th>
											
										</tr>
										</thead>
										<tbody id="productViewTable">
										</tbody>	
									</TABLE>
								
							</div>
							</td>		
						   
						  </tr>
						 
						</table>
					</div>
				</div>
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->


</body>

</html>
<? ob_flush(); ?>