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
		function getCustomerToDB() {		
		
			$.ajax({ 
		  type: 'post', 
		  url: "getCustomerDetails.php", 				   
		  data: {}, 
		  dataType: 'json',
		  success: function(data1){ 
		  //console.log(data1);
		  $('#dataTable').empty();
		  
				for(var i=0; i<data1.length; i++) 
				{
					if(data1[i].user_id != -1)				
						$('#dataTable').append('<TR><TD>' + data1[i].user_id +'</TD><TD>' + data1[i].name +'</TD><TD>' + data1[i].telephone +'</TD><TD>' + data1[i].email +'</TD><TD><INPUT class="pid" type="button" value="Details" alt="' + data1[i].user_id + '"/></TD></TR>');
                                   
                }
				 $('.pid').click(viewDetails);
            }
				
		   
		}); 
          
        }
		
		function viewDetails()
		{
			var user_id = $(this).attr('alt');
			$.ajax({ 
		  type: 'post', 
		  url: "getCustomerById.php", 				   
		  data: {user_id:user_id}, 
		  dataType: 'json',
		  success: function(data1){ 
		  //console.log(data1);
		  $('#customersViewTable').empty();
			//alert(data1.length);
			
			if(data1[0].user_id != -1)
			{						
                $('#customersViewTable').append('<TR><TD> Customer Id</TD><TD>' + data1[0].user_id +'</TD><TR>' );   
				$('#customersViewTable').append('<TR><TD> Customer Name</TD><TD>' + data1[0].name +'</TD><TR>' ); 
				if(data1[0].gender === '0')				
					$('#customersViewTable').append('<TR><TD> Customer Gender</TD><TD> Female </TD><TR>' ); 
				else
					$('#customersViewTable').append('<TR><TD> Customer Gender</TD><TD> Male </TD><TR>' ); 
				$('#customersViewTable').append('<TR><TD> Customer Cell Number</TD><TD>' + data1[0].telephone +'</TD><TR>' );
				
				$('#customersViewTable').append('<TR><TD> Customer E-mail</TD><TD>' + data1[0].email +'</TD><TR>' );   
				$('#customersViewTable').append('<TR><TD> Customer Shipping Address</TD><TD>' + data1[0].shipping_address +'</TD><TR>' );  
				$('#customersViewTable').append('<TR><TD> Customer State</TD><TD>' + data1[0].state +'</TD><TR>' );  
				$('#customersViewTable').append('<TR><TD> Customer City </TD><TD>' + data1[0].city +'</TD><TR>' );  
				$('#customersViewTable').append('<TR><TD> Customer Pincode </TD><TD>' + data1[0].pincode +'</TD><TR>' ); 
				
			}
			$('#customersViewTable').append('<TR><TD></TD><TD><button type="button" onclick="CustomerOrderAndCashbackById('+data1[0].user_id+');">Orders And Cashback Details</button></TD><TR>' );
					
            }
				
		   
		}); 
			
		}
		
		
		function CustomerOrderAndCashbackById(user_id) {
			window.open("CustomerOrderAndCashbackById.php?user_id="+user_id);
		}
	
		function searchByCustomerDetails() {		
		
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
			  url: "searchByCustomerDetails.php", 				   
			  data: {search_by: searchBy[0].value, search_value: searchvalue[0].value}, 
			  dataType: 'json',
			  success: function(data1){ 
			  //console.log(data1);
			  //console.log(data1);
				$('#dataTable').empty();
				$('#customersViewTable').empty();
				
				for(var i=0; i<data1.length; i++) 
				{
					if(data1[i].user_id != -1)				
						$('#dataTable').append('<TR><TD>' + data1[i].user_id +'</TD><TD>' + data1[i].name +'</TD><TD>' + data1[i].telephone +'</TD><TD>' + data1[i].email +'</TD><TD><INPUT class="pid" type="button" value="Details" alt="' + data1[i].user_id + '"/></TD></TR>');
                                   
                }
				 $('.pid').click(viewDetails);
					
				}
				
		   
			}); 
		}
          
    }
	</script>
	 

</head>

<body onload="getCustomerToDB();">

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
                    <li class="active">
                        <a href="customerDetailView.php"><i class="fa fa-fw fa-user"></i> View Customer Details</a>
                    </li>
                    
                    <li>
                        <a href="order_list.php"><i class="fa fa-fw fa-shopping-cart"></i> Order <i></i></a>
                       
                    </li>
					<li>
                        <a href="miscellaneous.php"><i class="fa fa-fw fa-crosshairs"></i> Miscellaneous <i></i></a>
                       
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
							
							
							<table style="width:100%">
							  <tr>
								<td>
									
										<label>Search By</label>
										<select  name="search_by">
											<option value="user_id" >Id</option>
											<option value="username">Name</option>
											<option value="email">Email</option>
											<option value="telephone">Telephone</option>
										</select>
																			
                                
								</td>
								<td>
								
								
									<input type="text" name="search_value" required">
									<span><button type="button" onclick="searchByCustomerDetails();"><i class="fa fa-search"></i></button></span>
								    <span><button type="button" onclick="getCustomerToDB();">All</button></span>
								</td>		
							   
							  </tr>
							 
							</table>
							

                        </form>
						<table class="table table-bordered table-hover">
						  <tr>
							<td>
								<div><label>List Of Customers</label></div>
								<div  style="width: 500px;height:800px;overflow:auto;">
								
									 <TABLE  class="table_item table table-fixed" border='1'>
										<thead>
										<tr>
											<th>user Id</th>
											<th>Name</th>
											<th>Cell number</th>
											<th>E-mail</th>
											<th>View</th>
										</tr>
										</thead>
										<tbody id="dataTable">
										</tbody>
									</TABLE>
								
							</div>
							</td>
							<td>
							<div><label>Customer Details</label></div>
								<div  style="width: 500px;height:800px;overflow:auto;">
								
									 <TABLE  width="500px" border="1" class="table table-bordered table-striped">
										<thead>
										<tr>
											<th>Customer Attributes</th>
											<th>Customer Details</th>
											
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