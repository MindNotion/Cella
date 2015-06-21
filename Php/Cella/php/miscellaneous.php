<?php
	ob_start();
	session_start(); 
	header('Cache-control: private');
	include("connect.php");

	if(!isset($_SESSION['username']))
	{
	header("Location: ../login.html");
	exit;
	}


	$_SESSION['product_dimension_count'] =1;
			
	$_SESSION['product_image_count'] =1;

	$orderPending=0;
	$orderInShipping = 0;
	$noOfUsers = 0;
	
	$sqlOrderPending = "SELECT order_id FROM  `order` WHERE order_delivery_status =0";
	$result=mysql_query($sqlOrderPending);
	$orderPending=mysql_num_rows($result);	
	
	$sqlOrderInShipping = "SELECT order_id FROM  `order` WHERE order_delivery_status =1";
	$result=mysql_query($sqlOrderInShipping);
	$orderInShipping=mysql_num_rows($result);
	
	$sqlNoOfUsers = "SELECT user_id FROM  `user_login` ";
	$result=mysql_query($sqlNoOfUsers);	
	
	$noOfUsers=mysql_num_rows($result);	


?>

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

    <!-- Morris Charts CSS -->
    <link href="../css/AdminLTE.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- jQuery -->
    <script src="../js/jquery.js"></script>
	<script src="../js/jquery-2.1.1.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>
	<script src="../js/Chart.js"></script>
    <script>
	$(document).ready(function(){
	
	  $("#addImage").click(function(){
	  
	  
		   if( $("#image_grp").children().length < 4)
		   {
				$("#image_grp").append("<div class='form-group'> <label>Browse jpg Image File</label> <input type='file' onchange='ValidateImgFileUpload(this);' required name='image"+($("#image_grp").children().length +1) +"'></div>");
				var imgCountValue = $("#image_grp").children().length;
				var ajaxurl = 'add_product_image_count.php',
				data =  {'action': imgCountValue};
				$.post(ajaxurl, data, function (response) {
					
				});
		   }
		   else
		   {
			alert('Maximum 4 jpg images can be added');
		   }
	  });
	  
	   $("#addSize").click(function(){
	  

			$("#size_grp").append("<div class='form-group'><label>Product Size *</label><input class='form-control' required name='size"+($("#size_grp").children().length +1) +"'><label>Product Dimension *</label><input class='form-control' required  name='dim"+($("#size_grp").children().length +1) +"'><label>Product Quantity *</label><input class='form-control' required name='qty"+($("#size_grp").children().length +1) +"' type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57'></div>");
		  var imgCountValue = $("#size_grp").children().length;
				var ajaxurl = 'add_product_dimension_count.php',
				data =  {'action': imgCountValue};
				$.post(ajaxurl, data, function (response) {
					
				});
		   
	  });
	  
		monthlySalesData();
		yearlySalesData();
		
		
	  
	});
	
		function yearlySalesData() {		
			$('#yearly_sale').empty();
			$.ajax({ 
		  type: 'post', 
		  url: "getYearlySalesData.php", 				   
		  data: {}, 
		  dataType: 'json',
		  success: function(data1){ 
				if(data1[0].month == -1)
					return;
				var noOfMonth = 12;	
				var dayArray=[];
				var revenueArray=[];
				var itemSoldArray=[];
				var saleDayArray=[];
				var soldArray =[];
				var amtArray =[];
				
				for(var i=0; i<data1.length; i++) 
				{
					revenueArray.push(data1[i].total_price);  
					itemSoldArray.push(data1[i].total_quantity);
					saleDayArray.push(data1[i].month);

					console.log("data1[i].total_price: "+data1[i].total_price);
                }
				for(i =0 ; i<noOfMonth ; i++)
				{
					var flag = 0;
					for(j =0 ; j<saleDayArray.length ; j++)
					{
						//console.log("day: "+ (i+1));
						if(saleDayArray[j] == (i+1))
						{
							//console.log(saleDayArray[j]);
							soldArray.push(itemSoldArray[j]);

							amtArray.push(revenueArray[j]);
							flag = 1;
							break;
						}
					}
					
					if(flag == 0)
					{
						soldArray.push(0);

						amtArray.push(0);
					}
						dayArray.push(i+1);
				}
				
				
				var lineChartData = {
						labels : ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
						datasets : [
							{
								label: "Total Revenue Amount In Rs",
								fillColor : "rgba(220,220,220,0.2)",
								strokeColor : "rgba(220,220,220,1)",
								pointColor : "rgba(220,220,220,1)",
								pointStrokeColor : "#fff",
								pointHighlightFill : "#fff",
								pointHighlightStroke : "rgba(220,220,220,1)",
								data : amtArray
							},
							{
								label: "Total Product Sold",
								fillColor : "rgba(151,187,205,0.2)",
								strokeColor : "rgba(151,187,205,1)",
								pointColor : "rgba(151,187,205,1)",
								pointStrokeColor : "#fff",
								pointHighlightFill : "#fff",
								pointHighlightStroke : "rgba(151,187,205,1)",
								data : soldArray
							}
						]

					}
	  
				
				var ctx2 = document.getElementById("yearly_sale").getContext("2d");
				window.myLine2 = new Chart(ctx2).Line(lineChartData, {	responsive: true});
				
            }
				
		   
		}); 
          
    }
	
		function monthlySalesData() 
		{		
			$('#monthly_sale').empty();
			$.ajax({ 
		  type: 'post', 
		  url: "getMonthlySalesData.php", 				   
		  data: {}, 
		  dataType: 'json',
		  success: function(data1)
			{ 
				if(data1[0].days == -1)
					return;
				var DaysInMonth = numbersOfDaysInMonth();	
				var dayArray=[];
				var revenueArray=[];
				var itemSoldArray=[];
				var saleDayArray=[];
				var soldArray =[];
				var amtArray =[];
				
				for(var i=0; i<data1.length; i++) 
				{
					revenueArray.push(data1[i].total_price);  
					itemSoldArray.push(data1[i].total_quantity);
					saleDayArray.push(data1[i].days);

					//console.log("data1[i].days: "+data1[i].days);
                }
				for(i =0 ; i<DaysInMonth ; i++)
				{
					var flag = 0;
					for(j =0 ; j<saleDayArray.length ; j++)
					{
						//console.log("day: "+ (i+1));
						if(saleDayArray[j] == (i+1))
						{
							//console.log(saleDayArray[j]);
							soldArray.push(itemSoldArray[j]);

							amtArray.push(revenueArray[j]);
							flag = 1;
							break;
						}
					}
					
					if(flag == 0)
					{
						soldArray.push(0);

						amtArray.push(0);
					}
						dayArray.push(i+1);
				}
				
				
				var lineChartData = {
						labels : dayArray,
						datasets : [
							{
								label: "Total Revenue Amount In Rs",
								fillColor : "rgba(220,220,220,0.2)",
								strokeColor : "rgba(220,220,220,1)",
								pointColor : "rgba(220,220,220,1)",
								pointStrokeColor : "#fff",
								pointHighlightFill : "#fff",
								pointHighlightStroke : "rgba(220,220,220,1)",
								data : amtArray
							},
							{
								label: "Total Product Sold",
								fillColor : "rgba(151,187,205,0.2)",
								strokeColor : "rgba(151,187,205,1)",
								pointColor : "rgba(151,187,205,1)",
								pointStrokeColor : "#fff",
								pointHighlightFill : "#fff",
								pointHighlightStroke : "rgba(151,187,205,1)",
								data : soldArray
							}
						]

					}
	  
				var ctx = document.getElementById("monthly_sale").getContext("2d");
				window.myLine = new Chart(ctx).Line(lineChartData, {responsive: true});
				
            }
				
		   
		}); 
          
    }
	
	function numbersOfDaysInMonth()
	{
		var curdate = new Date();
	  //alert("Month: "+curdate.getMonth() );
	  //alert("Year: "+curdate.getYear() );
	  var daysInMonth = 32 -new Date(curdate.getYear(),curdate.getMonth(),32).getDate();
	  
	  //alert("Number of Days: "+daysInMonth);
		return  daysInMonth;
	}
</script>
<SCRIPT type="text/javascript">
    function ValidateImgFileUpload(input) {
	
        var fuData = input;
        var FileUploadPath = fuData.value;

//To check if user upload any file
        if (FileUploadPath == '') {
            alert("Please upload an image");

        } else {
            var Extension = FileUploadPath.substring(
                    FileUploadPath.lastIndexOf('.') + 1).toLowerCase();

//The file uploaded is an image

			if (Extension == "jpeg" || Extension == "jpg") 
			{
							
				if((fuData.files[0].size/1024) >500)
				{
					alert("Photo size should be less than 500kb");
					input.value= "";
				}
				 if (fuData.files && fuData.files[0]) {
				 
				 
                     //Initiate the FileReader object.
                    var reader = new FileReader();
                    //Read the contents of Image File.
                    reader.readAsDataURL(fuData.files[0]);
                    reader.onload = function (e) {
                        //Initiate the JavaScript Image object.
                        var image = new Image();
                        //Set the Base64 string return from FileReader as source.
                        image.src = e.target.result;
                        image.onload = function () {
                            //Determine the Height and Width.
                            var height = this.height;
                            var width = this.width;
                            if (height !== 329 || width !== 270) {
                                alert("Image Dimension Should Be 270x329 Pixles.");
								input.value= "";  
                            }
                           
                        };
                    }

                    
                }
               
            } 
			else 
			{
                alert("Photo only allows file types of JPG and JPEG. ");
				input.value= "";
            }
        }
    }
	

</SCRIPT>
</head>

<body>

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
                    
                    <li>
                        <a href="order_list.php"><i class="fa fa-fw fa-shopping-cart"></i> Order <i></i></a>
                       
                    </li>
					<li class="active">
                        <a href="miscellaneous.php"><i class="fa fa-fw fa-crosshairs"></i> Miscellaneous <i></i></a>
                       
                    </li>
                    
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">
			
				 <h1 class="page-header">Dashboard</h1>
        <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?php echo $orderPending; ?></h3>
                  <p>New Orders Pending</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="order_list.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?php echo $orderInShipping; ?></h3>
                  <p>Order In Courier</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a href="order_list.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo $noOfUsers; ?></h3>
                  <p>User Registrations</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="customerDetailView.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
          
          </div><!-- /.row -->
          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <section class="col-lg-7 connectedSortable">
				<div class="box box-success">
				<div class="box-header">
                  <i class="fa fa-th"></i>
                  <h3 class="box-title">Current Month Sale</h3>
                  <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
                    <div class="btn-group" data-toggle="btn-toggle" >
                      <span class="color-box" style="width: 10px;    height: 10px; background-color: rgba(220,220,220,1);">&nbsp;&nbsp;&nbsp;&nbsp;</span> Total Revenue (&#8377;)
                      <span class="color-box" style="width: 10px;    height: 10px; background-color: rgba(151,187,205,1);">&nbsp;&nbsp;&nbsp;&nbsp;</span> Total Products Sold
                    </div>
                  </div>
                </div>
                <div class="box-body chat" id="chat-box">
					<div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 320px;">
						<canvas id="monthly_sale" ></canvas>
					</div>
				</div>
				</div>
				
				
				<div class="box box-success">
				<div class="box-header">
                  <i class="fa fa-th"></i>
                  <h3 class="box-title">Yearly Sale</h3>
                  <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
                    <div class="btn-group" data-toggle="btn-toggle" >
                      <span class="color-box" style="width: 10px;    height: 10px; background-color: rgba(220,220,220,1);">&nbsp;&nbsp;&nbsp;&nbsp;</span> Total Revenue (&#8377;)
                      <span class="color-box" style="width: 10px;    height: 10px; background-color: rgba(151,187,205,1);">&nbsp;&nbsp;&nbsp;&nbsp;</span> Total Products Sold
                    
                    </div>
                  </div>
                </div>
                <div class="box-body chat" id="chat-box">
                 <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 320px;">
				  <canvas id="yearly_sale" ></canvas>
				 </div>
                </div>
              </div>


            </section><!-- /.Left col -->
          </div><!-- /.row (main row) -->

        </section><!-- /.content -->
            </div>
            	<div class="row">
                    <div class="col-lg-6">
						<h1 class="page-header">
                            Miscellaneous Entry 
                        </h1>
						<h1><small>Courier Charges</small></h1>
						<form role="form" method="POST"  action="db_courier_entry.php" name=pform enctype="multipart/form-data">
						
							
							
							<div class="form-group">
                                <label>Minimum Shopping Amount For Free Shipping*</label>
								<div class="form-group input-group">
									<span class="input-group-addon">Rs</span>
									<input class="form-control" name="amt_free_shipping" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required>
									<span class="input-group-addon">.00</span>
								 </div>	
								<p class="help-block">Example 499, 799 e.t.c</p>
                            </div>
							
							<div class="form-group">
                                <label>Shipping Amount*</label>
								<div class="form-group input-group">
									<span class="input-group-addon">Rs</span>
									<input class="form-control" name="shipping_amt" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required>
									<span class="input-group-addon">.00</span>
								 </div>	
								<p class="help-block">Example 60, 80 e.t.c</p>
                            </div>
							
							
							
							<h1><small>Add Courier Images</small></h1>
							<p class="help-block">Maximum One jpg Image Need To Added. Image Files Max Size Should Not Be More Than 500 KB & Image Dimension Should Be 270x329 Pixles</p>
							<div id="image_grp">
								<div class="form-group" >
									<label>Browse jpg Image File *</label>
									<input type="file" name="shipping_image" required onchange="ValidateImgFileUpload(this);">
								</div>
							</div>
							
							
							
							
							
							
							<button type="submit" class="btn btn-success" >Submit</button>
                            <button type="reset" class="btn btn-warning">Reset</button>
                        </form>
					</div>
				</div><!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
		

    </div>
    <!-- /#wrapper -->


</body>

</html>
<? ob_flush(); ?>
