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
	
	var user_id ="<?php echo $_GET["user_id"];  ?>";
	
		function getOrderDetailsByCustomerId() {		
		//alert(user_id);
			$.ajax({ 
		  type: 'post', 
		  url: "CustomerOrderAndCashbackDetailsById.php", 				   
		  data: {user_id:user_id}, 
		  dataType: 'json',
		  success: function(data1){ 
		  //console.log(data1);
		  $('#dataTable').empty();
		  
				for(var i=0; i<data1.length; i++) 
				{
					if(data1[i].order_id != -1)				
						$('#dataTable').append('<TR><TD>' + data1[i].order_id +'</TD><TD>' + data1[i].order_date +'</TD><TD>' + data1[i].order_delivery_status +'</TD><TD>' + data1[i].order_confirmation +'</TD><TD><INPUT class="pid" type="button" value="Details" alt="' + data1[i].order_id + '"/></TD></TR>');
                                   
                }
				 $('.pid').click(viewDetails);
            }
				
		   
		}); 
		
			$.ajax({ 
		  type: 'post', 
		  url: "CustomerCashbackDetailsById.php", 				   
		  data: {user_id:user_id}, 
		  dataType: 'json',
		  success: function(data1){ 
		  //console.log(data1);
		  $('#cashback_info').empty();
		  
				$('#cashback_info').append("Cashback Amount: &#8377; "+ data1[0].total_cashback);
				
            }
				
		   
		});
          
        }
		
		function viewDetails()
		{
			var order_id = $(this).attr('alt');
			$.ajax({ 
		  type: 'post', 
		  url: "CustomerOrderByOrderId.php", 				   
		  data: {order_id:order_id}, 
		  dataType: 'json',
		  success: function(data1){ 
		  //console.log(data1);
		  $('#customersViewTable').empty();
			//alert(data1.length);
			
			for(var i=0; i<data1.length; i++)				
			{						
                $('#customersViewTable').append('<TR><TD>Order Id</TD><TD>' + data1[i].order_id +'</TD><TR>' );   
				$('#customersViewTable').append('<TR><TD>Order_date</TD><TD>' + data1[i].order_date +'</TD><TR>' ); 
				
				$('#customersViewTable').append('<TR><TD>Total Price Rs.</TD><TD>' + data1[i].total_price +'</TD><TR>' ); 
				$('#customersViewTable').append('<TR><TD>Total Discount %</TD><TD>' + data1[i].total_discount +'</TD><TR>' );
						
				$('#customersViewTable').append('<TR><TD>Total Quantity</TD><TD>' + data1[i].total_quantity +'</TD><TR>' ); 
				if(data1[i].order_delivery_status =='0')				
					$('#customersViewTable').append('<TR><TD>Delivery Status</TD><TD> In Warehouse (Pending)</TD><TR>' ); 
				else if(data1[i].order_delivery_status =='1')				
					$('#customersViewTable').append('<TR><TD>Delivery Status</TD><TD>In Courier (In Process)</TD><TR>' ); 
				else
				   $('#customersViewTable').append('<TR><TD>Delivery Status</TD><TD>Delivered to Customer</TD><TR>' ); 
					
				$('#customersViewTable').append('<TR><TD>Delivery Date</TD><TD>' + data1[i].order_delivery_date +'</TD><TR>' );
				
				if(data1[i].order_confirmation =='0')
					$('#customersViewTable').append('<TR><TD>Order Confirmation</TD><TD>No</TD><TR>' );  
				else
					$('#customersViewTable').append('<TR><TD>Order Confirmation</TD><TD>Yes</TD><TR>' );
				

				 $.ajax({
					type: 'post', 
					url: "OrderProductsDetailsById.php", 				   
					data: {order_id:data1[i].order_id}, 
					dataType: 'json',
				success: function(data2){
					for(var i=0; i<data2.length; i++)				
					{
						$('#customersViewTable').append('<TR><TD>Product Id</TD><TD>' + data2[i].product_id +'</TD><TR>' );
						$('#customersViewTable').append('<TR><TD>Product Title</TD><TD>' + data2[i].title +'</TD><TR>' );
						$('#customersViewTable').append('<TR><TD>Product Price Rs.</TD><TD>' + data2[i].price +'</TD><TR>' );
						$('#customersViewTable').append('<TR><TD>Product discount %</TD><TD>' + data2[i].discount_percent +'</TD><TR>' );
						$('#customersViewTable').append('<TR><TD>Product Quantity</TD><TD>' + data2[i].quantity +'</TD><TR>' );
						var discount_percent = data2[i].discount_percent;
						if(discount_percent != 0)							
						{							
							var actualPrice = parseFloat( parseFloat(data2[i].price).toFixed(2));
							var sellingPrice = parseFloat( parseFloat(data2[i].price).toFixed(2) -((parseFloat(discount_percent).toFixed(2)/parseFloat(100).toFixed(2)) * parseFloat(data2[i].price).toFixed(2)));
							$('#customersViewTable').append('<TR><TD>Product Price After Discount (&#8377;)</TD><TD>'+sellingPrice+'</TD><TR>' );
							
						}
						//alert(data2[i].isreturn);
						if(data2[i].isreturn == '0')
							$('#customersViewTable').append('<TR><TD>Is Product Returned</TD><TD> Not returned</TD><TR>' );
						else
						{
							$('#customersViewTable').append('<TR><TD>Is Product Returned</TD><TD> Yes returned</TD><TR>' );
							$('#customersViewTable').append('<TR><TD>Product Returned Date</TD><TD>' + data2[i].order_return_date +'</TD><TR>' );
							if(data2[i].return_item_condition == '0')
								$('#customersViewTable').append('<TR><TD>Product Condition</TD><TD>Product is in Good Condition</TD><TR>' );
							else
								$('#customersViewTable').append('<TR><TD>Product Condition</TD><TD>Product is in Bad Condition</TD><TR>' );
								
							$('#customersViewTable').append('<TR><TD>Reason Of Returned</TD><TD>' + data2[i].return_reason +'</TD><TR>' );
							$('#customersViewTable').append('<TR><TD>Date of Arrival in Warehouse After Returned</TD><TD>' + data2[i].warehouse_entry_date +'</TD><TR>' );
							
						}
					}
            
					}
				});
					
					
			}
			
					
            }
				
		   
		}); 
			
		}

	
		function searchByCustomerOrderId() {		
		
		var searchBy=document.getElementsByName("search_by");
		var searchvalue=document.getElementsByName("search_value");
		//alert(searchBy[0].value+" : "+searchvalue[0].value);
		var column = searchBy[0].value;
		var val = searchvalue[0].value;
		
		if (searchvalue[0].value == '')
		{
			alert("Please fill empty fields");
			searchvalue[0].focus();
		}
		else
		{
			$.ajax({ 
			  type: 'post', 
			  url: "searchOrderBy.php", 				   
			  data: {search_by:column,search_value:val}, 
			  dataType: 'json',
			  success: function(data1){ 
			  //console.log(data1);
			  //console.log(data1);
				$('#dataTable').empty();
				$('#customersViewTable').empty();
				
				for(var i=0; i<data1.length; i++) 
				{
					if(data1[i].order_id != -1)				
						$('#dataTable').append('<TR><TD>' + data1[i].order_id +'</TD><TD>' + data1[i].order_date +'</TD><TD>' + data1[i].order_delivery_status +'</TD><TD>' + data1[i].order_confirmation +'</TD><TD><INPUT class="pid" type="button" value="Details" alt="' + data1[i].order_id + '"/></TD></TR>');
                                   
                }
				 $('.pid').click(viewDetails);
					
				}
				
		   
			}); 
		}
          
    }
	</script>
	 

</head>

<body onload="getOrderDetailsByCustomerId();">

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
        
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper" >

            <div class="container-fluid">
				<div class="row">
                    <div class="col-lg-6">
						<h1 class="page-header">
                            Customer Order View 
                        </h1>
						<h1><small>Search Customer Order</small></h1>
            
						<form role="form">
							
							
							<table style="width:100%">
							  <tr>
								<td>
									
										<label>Search By</label>
										<select  name="search_by">
											<option value="order_id" >Order Id</option>
										</select>
																			
                                
								</td>
								<td>
								
								
									<input type="text" name="search_value" required">
									<span><button type="button" onclick="searchByCustomerOrderId();"><i class="fa fa-search"></i></button></span>
								    <span><button type="button" onclick="getOrderDetailsByCustomerId();">All</button></span>
								</td>		
							   
							  </tr>
							 
							</table>
							
						<h1><small id="cashback_info">Cashback Amount:</small></h1>
						
                        </form>
						<table class="table table-bordered table-hover">
						  <tr>
							<td>
								<div><label>List Of Customers Orders</label></div>
								<div  style="width: 500px;height:800px;overflow:auto;">
								
									 <TABLE  width="500px" border="1" class="table table-bordered table-hover">
										<thead>
										<tr>
											<th>Order Id</th>
											<th>Order Date</th>
											<th>Delivery Status</th>
											<th>Confirmation</th>
											<th>View</th>
										</tr>
										</thead>
										<tbody id="dataTable">
										</tbody>
									</TABLE>
								
							</div>
							</td>
							<td>
							<div><label>Order Details</label></div>
								<div  style="width: 500px;height:800px;overflow:auto;">
								
									 <TABLE  width="500px" border="1" class="table table-bordered table-striped">
										<thead>
										<tr>
											<th>Order Attributes</th>
											<th>Order Details</th>
											
										</tr>
										</thead>
										<tbody id="customersViewTable">
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