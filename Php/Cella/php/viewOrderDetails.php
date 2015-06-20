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
	
	var order_id ="<?php echo $_GET["order_id"];  ?>";
	var levelRight = "<?php echo $_SESSION['level'] ; ?>";
	var productCount = 0;	
		function getOrderDetailsByCustomerId()
		{
			//alert(order_id);
			$.ajax({ 
			  type: 'post', 
			  url: "GetOrderProductsDetailsById.php", 				   
			  data: {order_id:order_id}, 
			  dataType: 'json',
			  success: function(data1){ 
			  //console.log(data1);
			  $('#orderViewTable').empty();
				//alert(data1.length);
			
				if(levelRight == 1)
				{
					$('#orderViewTable').append('<TR><TD><INPUT class="pid" type="button" value="Update" alt="' + data1[0].order_id + '"/></TD></TR>');
					$('.pid').click(updateOrder);
				}
				$('#orderViewTable').append('<TR><TD><h3>Order Brief Summary</h3></TD><TD></TD><TR>' ); 	
                $('#orderViewTable').append('<TR><TD>Order Id</TD><TD>' + data1[0].order_id +'</TD><TR>' );   
				$('#orderViewTable').append('<TR><TD>Order_date</TD><TD>' + data1[0].order_date +'</TD><TR>' ); 
				
				$('#orderViewTable').append('<TR><TD>(Including Shipping Charges*) Total Price (&#8377;)</TD><TD>' + data1[0].total_price +'</TD><TR>' ); 
				$('#orderViewTable').append('<TR><TD>Total Discount %</TD><TD>' + data1[0].total_discount +'</TD><TR>' );
						
				$('#orderViewTable').append('<TR><TD>Total Quantity</TD><TD>' + data1[0].total_quantity +'</TD><TR>' ); 
				$('#orderViewTable').append('<TR><TD>Order Delivery Status</TD><TD><select id="ds"><option value="0">In Warehouse (Pending)</option> <option value="1" selected="selected">In Courier (In Process)</option>  <option value="2">Delivered to Customer</option></select></TD><TR>' ); 

				if(data1[0].order_delivery_status =="0")
				
					document.getElementById("ds").selectedIndex ="0" ;
				else if(data1[0].order_delivery_status =="1")				
					document.getElementById("ds").selectedIndex ="1"  ;
				else
				   document.getElementById("ds").selectedIndex ="2" ;
				   
			
				$('#orderViewTable').append('<TR><TD>Delivery Date</TD><TD><input type="text" readonly id="delivery_datepicker" value="'+data1[0].order_delivery_date+'" onMouseOver="datePickerDel();"></TD><TR>' );
				if(data1[0].order_delivery_date == "0000-00-00")				
					$('#orderViewTable').append('<TR><TD></TD><TD bgcolor="#FC6C85">Please Set Delivery Date Above</TD><TR>' );
				
				
				$('#orderViewTable').append('<TR><TD>Order Confirmation</TD><TD><select id="oc"><option value="0" >No</option><option value="1">Yes</option> </select></TD><TR>' ); 
				if(data1[0].order_confirmation == "0")
					document.getElementById("oc").selectedIndex = "0"; 
				else
					document.getElementById("oc").selectedIndex = "1";
			
				 
				$('#orderViewTable').append('<TR><TD><h3>Customer Details</h3></TD><TD></TD><TR>' ); 	
				$('#orderViewTable').append('<TR><TD>Customer Name</TD><TD>' + data1[1].name +'</TD><TR>' );
				$('#orderViewTable').append('<TR><TD>Gender</TD><TD>' + data1[1].gender +'</TD><TR>' );
				$('#orderViewTable').append('<TR><TD>Customer Telephone</TD><TD>' + data1[1].telephone +'</TD><TR>' );
				$('#orderViewTable').append('<TR><TD>Customer E-mail</TD><TD>' + data1[1].email +'</TD><TR>' );
				$('#orderViewTable').append('<TR><TD>Customer Shipping Address</TD><TD>' + data1[1].shipping_address +'</TD><TR>' );
				$('#orderViewTable').append('<TR><TD>City</TD><TD>' + data1[1].city +'</TD><TR>' );
				$('#orderViewTable').append('<TR><TD>State</TD><TD>' + data1[1].state +'</TD><TR>' );
				$('#orderViewTable').append('<TR><TD>Pincode</TD><TD>' + data1[1].pincode +'</TD><TR>' );
				
				
				productCount = 	data1[2].product_count;
				//alert(productCount);

				var endIndex = 3+productCount
				var c = 0;
				for(var i=3; i<endIndex; i++)				
				{
					$('#orderViewTable').append('<TR><TD><h3>Product - '+(c+1) +' Details</h3></TD><TD></TD><TR>' );
					$('#orderViewTable').append('<TR><TD>Product Id</TD><TD>'+data1[i].product_id +'</TD><TR>' );
					$('#orderViewTable').append('<TR><TD>Product Title</TD><TD>'+data1[i].title +'</TD><TR>' );
					$('#orderViewTable').append('<TR><TD>Product Price</TD><TD>'+data1[i].price +'</TD><TR>' );
					$('#orderViewTable').append('<TR><TD>Product Discount</TD><TD>'+data1[i].discount_percent +'</TD><TR>' );
					$('#orderViewTable').append('<TR><TD>Product Quantity</TD><TD>'+data1[i].quantity +'</TD><TR>' );
					var discount_percent = data1[i].discount_percent;
					if(discount_percent != 0)							
					{							
						var actualPrice = parseFloat( parseFloat(data1[i].price).toFixed(2));
						var sellingPrice = parseFloat( parseFloat(data1[i].price).toFixed(2) -((parseFloat(discount_percent).toFixed(2)/parseFloat(100).toFixed(2)) * parseFloat(data1[i].price).toFixed(2)));
						$('#orderViewTable').append('<TR><TD>Product Price After Discount (&#8377;)</TD><TD>'+sellingPrice+'</TD><TR>' );
						
					}
					$('#orderViewTable').append('<TR><TD>Product Size</TD><TD>'+data1[i].size+'</TD><TR>' );
					$('#orderViewTable').append('<TR><TD>Product Dimension</TD><TD>'+data1[i].dimension+'</TD><TR>' );					
					$('#orderViewTable').append('<TR><TD>Product Image</TD><TD><img src='+data1[i].image_path+' height="150" width="150"></TD><TR>' );					
					$('#orderViewTable').append('<TR><TD>Is Product Returned</TD><TD><select id="ipr'+(c+1)+'"> <option value="0" >No</option><option value="1">Yes</option></select></TD><TR>' ); 
					
					//alert(data1[i].isreturn);
					if(data1[i].isreturn == "0" )
						document.getElementById("ipr"+(c+1)).selectedIndex = "0";
					else
						document.getElementById("ipr"+(c+1)).selectedIndex = "1";
						
					$('#orderViewTable').append('<TR><TD>Product Returned Date</TD><TD><input type="text" readonly  id="order_return_datepicker'+(c+1)+'" value="'+data1[i].order_return_date+'" onMouseOver="datePickerOrderReturn('+(c+1) +');"></TD><TR>' );
					
					$('#orderViewTable').append('<TR><TD>Product Returned Condition</TD><TD><select id="prc'+(c+1)+'"> <option value="0" >Bad</option><option value="1">Good</option></select></TD><TR>' ); 
					
					$('#orderViewTable').append('<TR><TD>Product Warehouse Entry Date</TD><TD><input type="text" readonly id="pwed'+(c+1)+'" value="'+data1[i].warehouse_entry_date+'" onMouseOver="datePickerWarehouseEntry('+(c+1) +');"></TD><TR>' );
					
					$('#orderViewTable').append('<TR><TD>Cashback  Approval</TD><TD><select id="pcba'+(c+1)+'"> <option value="0" >No</option><option value="1">Yes</option></select></TD><TR>' ); 
		
					
					//alert(data1[i].return_item_condition);
					if(data1[i].return_item_condition == "0" )
						document.getElementById("prc"+(c+1)).selectedIndex = "0";
					else
						document.getElementById("prc"+(c+1)).selectedIndex = "1";
					$('#orderViewTable').append('<TR><TD>Product Return Reason</TD><TD><textarea rows="4" cols="40"   id=prr'+(c+1)+'>' + data1[i].return_reason +'</textarea></TD><TR>' ); 
					
					if(data1[i].is_cashback_approved == "1" )
						document.getElementById("pcba"+(c+1)).selectedIndex = "1";
					else
						document.getElementById("pcba"+(c+1)).selectedIndex = "0";
					
					
					c++;
				}
			
				$('#orderViewTable').append('<TR><TD bgcolor="#E3E4FA"></TD><TD bgcolor="#E3E4FA" font size="3" color="red">Developed By MindNotion</TD><TR>' ); 
				$('#orderViewTable').append('<TR><TD bgcolor="#E3E4FA"></TD><TD bgcolor="#E3E4FA" font size="3" color="red" ><a href="http://www.mindnotion.com">www.mindnotion.com</a></TD><TR>' ); 
				$('#orderViewTable').append('<TR><TD bgcolor="#E3E4FA"></TD><TD bgcolor="#E3E4FA"></TD><TR>' ); 	
            }
				
		   
		}); 
			
		}

	
		function searchByCustomerOrderId() {		
		
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
			  url: "searchOrderById.php", 				   
			  data: {search_by: searchBy[0].value, search_value: searchvalue[0].value}, 
			  dataType: 'json',
			  success: function(data1){ 
			  //console.log(data1);
			  //console.log(data1);
				$('#dataTable').empty();
				$('#orderViewTable').empty();
				
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
	
	function printTable()
	{
	  var divToPrint=document.getElementById('order_table');
	  newWin= window.open("");	 
	  newWin.document.write(divToPrint.outerHTML);	  
	  newWin.print();
	  newWin.close(); 	
	}
	
	function datePickerDel() 
	{	
		$( "#delivery_datepicker" ).datepicker();
	
	}
	function datePickerOrderReturn(id)
	{
	//	alert(id);
		$( "#order_return_datepicker"+id).datepicker();
	}
	
	function datePickerWarehouseEntry(id)
	{
	//	alert(id);
		$( "#pwed"+id).datepicker();
	}
	
	function updateOrder()
	{
		var order_id  = $(this).attr('alt');//productCount
		
		var oDeliveryStatus = document.getElementById("ds");
		var oDeliveryDate= document.getElementById("delivery_datepicker");
		var oConfirmationStatus= document.getElementById("oc");
				
		var odsValue = oDeliveryStatus.value;
		var odd = oDeliveryDate.value;
		var ocs = oConfirmationStatus.value;
		//alert(ocs);
			$.ajax({ 
					type: 'post', 
					url: "updateOrderById.php", 				   
					data: {order_id:order_id, order_delivery_status:odsValue, order_delivery_date:odd, order_confirmation:ocs}, 
					dataType: 'json',
					success: function(data1){ 
				  console.log(data1[0].update_status);
					var successFlag = 0;
						if(data1[0].update_status != -1)
						{
							for(j =0 ; j< productCount; j++)
							{
								var pIsReturn = document.getElementById("ipr"+(j+1));
								var oReturndate = document.getElementById("order_return_datepicker"+(j+1));
								var pReturnCondition = document.getElementById("prc"+(j+1));
								var pReturnReason = document.getElementById("prr"+(j+1));
								var pWarehouseEntryDate = document.getElementById("pwed"+(j+1));
								var pCashbackApproval = document.getElementById("pcba"+(j+1));
								
								var ir = pIsReturn.value;
								var rd = oReturndate.value;
								var rc = pReturnCondition.value;
								var rr = pReturnReason.value;
								var wed = pWarehouseEntryDate.value;
								var cba = pCashbackApproval.value;
								
								console.log(ir);
								console.log(rd);
								console.log(rc);
								console.log(rr);
								console.log(wed);
								console.log(cba);
								
								
								$.ajax({ 
									type: 'post', 
									url: "updateOrderProductById.php", 				   
									data: {order_id:order_id,isreturn:ir,order_return_date:rd,return_item_condition:rc,return_reason:rr,warehouse_entry_date:wed,is_cashback_approved:cba}, 
									dataType: 'json',
									success: function(data1){ 
										console.log(data1[0].update_status);
										if(data1[0].update_status != -1)
											successFlag = 0 ;
										
									}
								});
								
								if(successFlag == 0)	
									alert('DB updated successfully');
								else
									alert('DB updated failed');
							}
							
							
						getOrderDetailsByCustomerId();
						}
						else
						{
							alert("Fail to update the DB");
						
						}
		
					}
				});
	
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
                <a class="navbar-brand" href="index.html">Cella Admin </a>
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
            
						<table class="table table-bordered table-hover">
						  <tr>
							<td>
							<div><label>Order Details</label></div>
							<input type='button' id="print" value="Print" onclick="printTable();">
								<div  style="width: 1000px;height:auto;overflow:auto;">
								
									 <TABLE  width="1000px" border="1" class="table table-bordered table-striped" id="order_table">
										<thead>
										<tr>
											<th>Order Attributes</th>
											<th>Order Attributes Details</th>
											
										</tr>
										</thead>
										<tbody id="orderViewTable">
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