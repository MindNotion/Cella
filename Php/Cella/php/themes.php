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
	
	<script type="text/javascript" src="../js/jscolor.js"></script>
	<SCRIPT type="text/javascript">
    function setDefaultTheme() 
	{
		
     	$.ajax({ 
			type: 'post', 
			url: "setDefaultTheme.php", 				   
			data: {}, 
			dataType: 'json',
			success: function(data1){
				if(data1[0].sucess == 1)
					alert('Default Theme applied');
				else
					alert('Failed to apply Default Theme');
				}
		});
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
					<li>
                        <a href="miscellaneous.php"><i class="fa fa-fw fa-dashboard"></i> Miscellaneous <i></i></a>
                       
                    </li>
					<li class="active">
                        <a href="themes.php"><i class="fa fa-fw fa-crosshairs"></i> Styles and Themes <i></i></a>
                       
                    </li>
                    
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">
			
				 <h1 class="page-header">Color Theme</h1>
        
            </div>
            	<div class="row">
                    <div class="col-lg-6">
						<h1 class="page-header">
                            Select primary and secondary colour of your website!
                        </h1>
						<h1><small>This will set color theme for your website.</small></h1>
						<form role="form" method="POST"  action="cssFileGenerator.php" name=pform enctype="multipart/form-data">
						
							
							
							<div class="form-group">
                                <label>Set Primary Colour*</label>
								<div class="form-group input-group">
									<span class="input-group-addon">Click here</span>
									<input class="color" value="2b338e" name="primary_color" readonly required>
									
								 </div>	
								
                            </div>
							
							<div class="form-group">
                                <label>Set Secondary Colour*</label>
								<div class="form-group input-group">
									<span class="input-group-addon">Click here</span>
									<input class="color" value="f05327" name="secondary_color" readonly required>
									
								 </div>	
								
                            </div>
							<button type="submit" class="btn btn-success" >Submit</button>
                            <button type="button"  class="btn btn-warning" onclick="setDefaultTheme();" > Set Default Theme</button>
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
