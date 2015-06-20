<?php
ob_start();
session_start(); 
header('Cache-control: private');
include("../php/connect.php");

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
	
	var order_id ="<?php echo $_GET["oid"];  ?>";
	
	var productCount = 0;	
		function getOrderDetailsByCustomerId()
		{
			//alert(order_id);
			$.ajax({ 
			  type: 'post', 
			  url: "OrderProductsDetailsById.php", 				   
			  data: {order_id:order_id}, 
			  dataType: 'json',
			  success: function(data1){ 
			  console.log(data1);
			  $('#orderViewTable').empty();
				//alert(data1.length);
			
				$('#orderViewTable').append('<TR><TD><h3>Order Brief Summary</h3></TD><TD></TD><TR>' ); 	
                $('#orderViewTable').append('<TR><TD>Order Id</TD><TD>' + data1[0].order_id +'</TD><TR>' );   
				$('#orderViewTable').append('<TR><TD>Order_date</TD><TD>' + data1[0].order_date +'</TD><TR>' ); 
				
				$('#orderViewTable').append('<TR><TD>(Including Shipping Charges*) Total Price (&#8377;) </TD><TD>' + data1[0].total_price +'</TD><TR>' ); 
				$('#orderViewTable').append('<TR><TD>Total Discount %</TD><TD>' + data1[0].total_discount +'</TD><TR>' );
						
				$('#orderViewTable').append('<TR><TD>Total Quantity</TD><TD>' + data1[0].total_quantity +'</TD><TR>' ); 
				

				if(data1[0].order_delivery_status =="0")
					$('#orderViewTable').append('<TR><TD>Order Delivery Status</TD><TD>In Warehouse (Pending)</TD><TR>' ); 

				else if(data1[0].order_delivery_status =="1")				
					$('#orderViewTable').append('<TR><TD>Order Delivery Status</TD><TD>In Courier (In Process)</TD><TR>' ); 

				else
				   $('#orderViewTable').append('<TR><TD>Order Delivery Status</TD><TD>Delivered to Customer</TD><TR>' );	   
			
				
				if(data1[0].order_delivery_date == "0000-00-00")				
					$('#orderViewTable').append('<TR><TD>Order Delivery Date</TD><TD bgcolor="#FC6C85">In Process</TD><TR>' );
				else
					$('#orderViewTable').append('<TR><TD>Delivery Date</TD><TD>'+data1[0].order_delivery_date+'</TD><TR>' );
									
				productCount = 	data1[1].product_count;
				
				var endIndex = 2+productCount
				var c = 0;
				var cashback = 0;
				for(var i=2; i<endIndex; i++)				
				{
					$('#orderViewTable').append('<TR><TD><h3>Product - '+(c+1) +' Details</h3></TD><TD></TD><TR>' );
					$('#orderViewTable').append('<TR><TD>Product Id</TD><TD>'+data1[i].product_id +'</TD><TR>' );
					$('#orderViewTable').append('<TR><TD>Product Title</TD><TD>'+data1[i].title +'</TD><TR>' );
					$('#orderViewTable').append('<TR><TD>Product Price (&#8377;)</TD><TD>'+data1[i].price +'</TD><TR>' );
					
					cashback = data1[i].price;
					
					$('#orderViewTable').append('<TR><TD>Product Discount</TD><TD>'+data1[i].discount_percent +'</TD><TR>' );
					$('#orderViewTable').append('<TR><TD>Product Quantity</TD><TD>'+data1[i].quantity +'</TD><TR>' );
					var discount_percent = data1[i].discount_percent;
					if(discount_percent != 0)							
					{							
						var actualPrice = parseFloat( parseFloat(data1[i].price).toFixed(2));
						var sellingPrice = parseFloat( parseFloat(data1[i].price).toFixed(2) -((parseFloat(discount_percent).toFixed(2)/parseFloat(100).toFixed(2)) * parseFloat(data1[i].price).toFixed(2)));
						cashback = sellingPrice;
						$('#orderViewTable').append('<TR><TD>Product Price After Discount (&#8377;)</TD><TD>'+sellingPrice+'</TD><TR>' );
						
					}					
					$('#orderViewTable').append('<TR><TD>Product Size</TD><TD>'+data1[i].size+'</TD><TR>' );
					$('#orderViewTable').append('<TR><TD>Product Dimension</TD><TD>'+data1[i].dimension+'</TD><TR>' );					
					$('#orderViewTable').append('<TR><TD>Product Image</TD><TD><img src='+data1[i].image_path+' height="150" width="150"></TD><TR>' );
					
					//comparing product delivery date with current date
					var dt = new Date(data1[0].order_delivery_date);
					var today = new Date();
					
					var dateDiff =  parseInt((today.getTime()-dt.getTime())/(24*3600*1000));
					
					//alert(data1[i].isreturn);
					if((dateDiff >= "0") && data1[i].isreturn == "0")
					{
						//alert(data1[0].order_delivery_status);
						if((data1[0].order_delivery_status > "1") &&  dateDiff <= "15" ) // 15 is minimum days after delivering the product
						$('#orderViewTable').append('<TR><TD>Product Returned?</TD><TD><button onclick="returnProduct('+data1[i].product_id+')">Returned Product</button></TD><TR>' ); 
					
					
					}
					if(data1[i].isreturn == "1" && data1[i].is_cashback_approved=="0") // Product returned & cashback is under process by admin
					{
						$('#orderViewTable').append('<TR><TD>Wish to return this product</TD><TD>True</TD><TR>' );
					}
					
					if(data1[i].isreturn == "1" && data1[i].is_cashback_approved=="1") // Product returned & cashback approved by admin
					{						
						$('#orderViewTable').append('<TR><TD>Cashback Amount (&#8377;)</TD><TD>'+cashback+'</TD><TR>' ); 
					}
					
					c++;
				}
				
					
				$('#orderViewTable').append('<TR><TD bgcolor="#E3E4FA"></TD><TD bgcolor="#E3E4FA" font size="3" color="red">Developed By MindNotion</TD><TR>' ); 
				$('#orderViewTable').append('<TR><TD bgcolor="#E3E4FA"></TD><TD bgcolor="#E3E4FA" font size="3" color="red" ><a href="http://www.mindnotion.com">www.mindnotion.com</a></TD><TR>' ); 
				$('#orderViewTable').append('<TR><TD bgcolor="#E3E4FA"></TD><TD bgcolor="#E3E4FA"></TD><TR>' );
					
            }
				
		   
		}); 
			
		}

	function returnProduct(productId)
	{
		/*alert(productId);
		alert(order_id);*/
		
		$.ajax({ 
			  type: 'post', 
			  url: "updateReturnedStatus.php", 				   
			  data: {order_id:order_id, productId:productId}, 
			  dataType: 'json',
			  success: function(data1){
				if(data1.length > 0)
				{
					//alert(data1[0].update_status);
					if(data1[0].update_status == "1")
					{
						alert('Product is set for return. Your request will be soon process and our representative will come at you premises to collect the product.');
						location.reload();
					}
					else
					{
						alert('Unable to process request.');
					}
				}
			  
			}			  
			  
				
		   
		}); 
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
	
	
	
	</script>
	 

</head>

<body onload="getOrderDetailsByCustomerId();">

    <div id="wrapper">

        <div id="page-wrapper" >

            <div class="container-fluid">
				<div class="row">
                    <div class="col-lg-6">
						            
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