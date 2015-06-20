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

if($_SESSION['level']== 0)
{
	echo "<script>alert('Permission Denied: No rights to enter products');window.location ='admin_panel.php'</script>";
	exit;
}
$_SESSION['product_dimension_count'] =1;
		
$_SESSION['product_image_count'] =1;

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
    <link href="../css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- jQuery -->
    <script src="../js/jquery.js"></script>
	<script src="../js/jquery-2.1.1.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

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
	  
	});
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
                            if (height !== 800 || width !== 581) {
                                alert("Image Dimension Should Be 581x800 Pixles.");
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
						 <ul id="product" class="Expand">
                            <li>
                                <a href="product_view.php"><i class="fa fa-fw fa-info-circle"></i> View Product</a>
                            <li STYLE="background: #000000;">
                                <a href="product_entry.php"><i class="fa fa-fw fa-edit"></i> Enter Product</a>
                            </li>
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
                        <a href="miscellaneous.php"><i class="fa fa-fw fa-crosshairs"></i> Miscellaneous <i></i></a>
                       
                    </li>
                    
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">
				<div class="row">
                    <div class="col-lg-6">
						<h1 class="page-header">
                            Product Entry 
                        </h1>
						<h1><small>General Product Description</small></h1>
						<form role="form" method="POST"  action="db_product_entry.php" name=pform enctype="multipart/form-data">
						
							<div class="form-group ">
                                <label>Product Title*</label>
                                <input class="form-control" name="title" required >
                                
                            </div>
							<div class="form-group">
                                <label>Product Category*</label>
                                <input class="form-control" name="category" required>
                                <p class="help-block">Example clothes, jewelley, shoes e.t.c</p>
                            </div>
							<div class="form-group">
                                <label>Product Sub-Category*</label>
                                <input class="form-control" name="subcategory" required>
                                <p class="help-block">Example Kurtis, earring, Sandals e.t.c</p>
                            </div>
							<div class="form-group">
                                <label>Select Product Color*</label>
                                <select class="form-control" name="color">
									<option value="black" class="black">&#9679; black</option>
									<option value="blue" class="blue">&#9679; blue</option>									
									<option value="beige" class="beige">&#9679; beige</option>
									<option value="brown" class="brown">&#9679; brown</option>									
									<option value="green" class="green">&#9679; green</option>
									<option value="grey" class="grey">&#9679; grey</option>
                                    <option value="red" class="red">&#9679; red</option>
									<option value="magenta" class="magenta">&#9679; magenta</option>
									<option value="maroon" class="maroon">&#9679; maroon</option>									
									<option value="orange" class="orange">&#9679; orange</option>
									<option value="purple" class="purple">&#9679; purple</option>
									<option value="violet" class="violet">&#9679; violet</option>
									<option value="white" class="white">&#9679; white</option>									
									<option value="yellow" class="yellow">&#9679; yellow</option>
                                </select>
                            </div>
							
							<div class="form-group">
                                <label>Product Price*</label>
								<div class="form-group input-group">
									<span class="input-group-addon">Rs</span>
									<input class="form-control" name="price" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required>
									<span class="input-group-addon">.00</span>
								 </div>	
								<p class="help-block">Example 400, 900 e.t.c</p>
                            </div>
							
							<div class="form-group">
                                <label>Product Discount*</label>
								<div class="form-group input-group">
									<input class="form-control" name="discount_percent" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required>
									<span class="input-group-addon">%</span>
								 </div>	
								<p class="help-block">Example 10, 40 e.t.c</p>
                            </div>
							
							<div class="form-group">
                                <label>Is Product New Arrvied*</label>
                                <select class="form-control" name="new_arrival">
									<option value="1" >Yes</option>
									<option value="0">No</option>									
                                </select>
                            </div>
							<div class="form-group">
                                <label>Is Product Best Seller*</label>
                                <select class="form-control" name="best_seller">
									<option value="1" >Yes</option>
									<option value="0">No</option>									
                                </select>
                            </div>
							<div class="form-group">
                                <label>Gender</label>
                                <select class="form-control" name="gender">
									<option value="female" >Female</option>
									<option value="male">Male</option>
									<option value="unisex">Unisex</option>										
                                </select>
                            </div>
							
							<h1><small>Add Product Images</small></h1>
							<p class="help-block">Minimum One jpg Image Need To Added. Maximum Can Be 4 Files. Image Files Max Size Should Not Be More Than 500 KB & Image Dimension Should Be 581x800 Pixles</p>
							<div id="image_grp">
								<div class="form-group" >
									<label>Browse jpg Image File *</label>
									<input type="file" name="image1" required onchange="ValidateImgFileUpload(this);">
								</div>
							</div>
							<button id="addImage" type="button" class="btn btn-primary" >Add Image</button>
							
							
							<h1><small>Add Product Size and Quantity*</small></h1>
							<p class="help-block">Minimum One size and respective Quantity Need To Added.</p>
							<div id="size_grp">
								<div class="form-group">
									<label>Product Size *</label>
									<input class="form-control" name="size1" required>
									<p class="help-block">Example for clothes Xl,M,L; For shoes 9,10,11 e.t.c</p>
									<label>Product Dimension *</label>
									<input class="form-control" name="dim1" required>
									<p class="help-block">Example for clothes waist 32 & length 38; For shoes 28cms,29cms e.t.c</p>
									<label>Product Quantity *</label>
									<input class="form-control" name="qty1" type="text" required onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
									<p class="help-block">Example 1,2, e.t.c</p>
								</div>
							</div>
							<button id="addSize" type="button" class="btn btn-primary" >Add Size</button>
							<h1><small>Add Product Description and Care*</small></h1>
							<div class="form-group">
                                <label>Product Description</label>
                                <input class="form-control" placeholder="Enter Product Description" name="desc" required>
                            </div>
							<div class="form-group">
                                <label>Product Care</label>
                                <input class="form-control" placeholder="Enter Product Care" name="care" required>
                            </div>
							
							<h1><small>Add Product Brand Details*</small></h1>
							<div class="form-group">
                                <label>Product Brand Name *</label>
                                <input class="form-control" name="brand" type="text"  required>
                                <p class="help-block">Example Nike, Puma e.t.c</p>
                            </div>
							
							
							<button type="submit" class="btn btn-success" >Submit</button>
                            <button type="reset" class="btn btn-warning">Reset</button>
                        </form>
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