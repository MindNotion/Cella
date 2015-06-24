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
	<link rel="stylesheet" href="../css/jquery-ui.css">
	
	<script src="../js/jquery-ui.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

	<script type="text/javascript">
		function getAllOrder() {		
		
			$.ajax({ 
		  type: 'post', 
		  url: "getAllOrder.php", 				   
		  data: {}, 
		  dataType: 'json',
		  success: function(data1){ 
		  //console.log(data1);
		  $('#dataTable').empty();
		  
				for(var i=0; i<data1.length; i++) 
				{
					if(data1[i].order_id != -1)				
						$('#dataTable').append('<TR><TD>' + data1[i].order_id +'</TD><TD>' + data1[i].user_id +'</TD><TD>' + data1[i].order_delivery_status +'</TD><TD>' + data1[i].order_confirmation+'</TD><TD>' + data1[i].order_date +'</TD><TD><INPUT class="pid" type="button" value="Details" alt="' + data1[i].order_id + '"/></TD></TR>');
                                   
                }
				 $('.pid').click(viewOrderById);
            }
				
		   
		}); 
          
        }

		
		
		function viewOrderById() {
		
		var order_id  = $(this).attr('alt');
		//alert(order_id);
		window.open("viewOrderDetails.php?order_id="+order_id );
		}
		
		
		function searchOrderByDate() 
		{		
			var searchvalue=document.getElementsByName("search_value_date");
			//alert(" : "+searchvalue[0].value);
			
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
				  data: {search_by: "order_date", search_value: searchvalue[0].value}, 
				  dataType: 'json',
				  success: function(data1){ 
				  console.log(data1);
					$('#dataTable').empty();
					$('#customersViewTable').empty();
					
					for(var i=0; i<data1.length; i++) 
					{
						if(data1[i].order_id != -1)				
							$('#dataTable').append('<TR><TD>' + data1[i].order_id +'</TD><TD>' + data1[i].user_id +'</TD><TD>' + data1[i].order_delivery_status +'</TD><TD>' + data1[i].order_confirmation+'</TD><TD>' + data1[i].order_date +'</TD><TD><INPUT class="pid" type="button" value="Details" alt="' + data1[i].order_id + '"/></TD></TR>');
										
					}
					 $('.pid').click(viewOrderById);
						
					}
					
			   
				}); 
			}
          
    }
	
		function searchOrderBy() {		
		
		var searchBy=document.getElementsByName("search_by");
		var searchvalue=document.getElementsByName("search_value");
		//alert(searchBy[0].value+" : "+searchvalue[0].value);
		
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
			  data: {search_by: searchBy[0].value, search_value: searchvalue[0].value}, 
			  dataType: 'json',
			  success: function(data1){ 
			  console.log(data1);
				$('#dataTable').empty();
				$('#customersViewTable').empty();
				
				for(var i=0; i<data1.length; i++) 
				{
					if(data1[i].order_id != -1)				
						$('#dataTable').append('<TR><TD>' + data1[i].order_id +'</TD><TD>' + data1[i].user_id +'</TD><TD>' + data1[i].order_delivery_status +'</TD><TD>' + data1[i].order_confirmation +'</TD><TD>' + data1[i].order_date +'</TD><TD><INPUT class="pid" type="button" value="Details" alt="' + data1[i].order_id + '"/></TD></TR>');
                                    
                }
				 $('.pid').click(viewOrderById);
					
				}
				
		   
			}); 
		}
          
    }
	

	$(function() 
	{
	
		$( "#datepicker" ).datepicker();
	
	});
	
	</script>
	 

</head>

<body onload="getAllOrder();">

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
						 <ul id="product" class="collapse">
                            <li>
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
                    
                    <li class="active">
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
                            Customer Detail View 
                        </h1>
						<h1><small>Search Customer</small></h1>
            
						<form role="form">
							
							
							<table style="width:110%">
							  <tr>
								<td>
									
										<label>Search By</label>
										<select  name="search_by" id="search_by">
											<option value="order_id" >Order Id</option>
											<option value="user_id">Customer Id</option>
											<option value="order_delivery_status">Delivery Status</option>
											<option value="order_confirmation">Confirmation</option>
										</select>
																			
                                
								</td>
								<td>
								
								
									<input type="text" name="search_value" required">
									<span><button type="button" onclick="searchOrderBy();"><i class="fa fa-search"></i></button></span>
								    <span><button type="button" onclick="getAllOrder();">All</button></span>
								</td>		
							   
							  </tr>
											 
							</table>
							

                        </form>
						
							<form role="form">
							
							
							<table style="width:100%">
							  
							  <tr>
								<td>									
										<label>Search By Date</label>																													
                                
								</td>
								<td>
								
								
									<input type="text" name="search_value_date" id="datepicker" required">
									<span><button type="button" onclick="searchOrderByDate();"><i class="fa fa-search"></i></button></span>
								    
								</td>		
							   
							  </tr>
							 
							</table>
							

                        </form>
							<table style="width:110%">
							  
								<tr>
								<td>
									
										<label><font size="3" color="black">Order Delivery Status</font></label>
										<label><font size="2" color="red">0: In warehouse (In pending)</font></label>
										<label><font size="2" color="orange">1: In Courier (In process)</font></label>
										<label><font size="2" color="green">2: Delivered to customer</font></label>
																			
                                
								</td>
								<td>
								
									<label><font size="3" color="black">Order Confirmation</font></label>
										<label><font size="2" color="red">0: Order is not confirmed from customer</font></label>
										<label><font size="2" color="green">1: Order is confirmed from customer</font></label>
									
								</td>		
							   
							  </tr>					 
							</table>
						<table class="table table-bordered table-hover">
						  <tr>
							<td>
								<div><label>List Of Customers</label></div>
								<div  style="width: 1000px;height:800px;overflow:auto;">
								
									 <TABLE  width="1000px" border="1" class="table table-bordered table-hover">
										<thead>
										<tr>
											<th>Order Id</th>
											<th>Customer Id</th>
											<th>Order Delivery Status</th>
											<th>Order Confirmation</th>
											<th>Order Date</th>
											<th>View</th>
										</tr>
										</thead>
										<tbody id="dataTable">
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