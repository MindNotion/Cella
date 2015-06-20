<?php
ob_start();
session_start(); //start session
include("../php/connect.php");

//empty cart by distroying current session
if(isset($_GET["emptycart"]) && $_GET["emptycart"]==1)
{
    $return_url = base64_decode($_GET["return_url"]); //return url
    session_destroy();
    header('Location:'.$return_url);
}

//add item in shopping cart
if(isset($_POST["type"]) && $_POST["type"]=='add')
{
    $product_id   = filter_var($_POST["product_id"], FILTER_SANITIZE_STRING); //product product_id
    $product_qty    = filter_var($_POST["product_qty"], FILTER_SANITIZE_NUMBER_INT); //product product_id
    $return_url     = base64_decode($_POST["return_url"]); //return url
    $product_img = $_POST['product_img']	;
    $product_size = $_POST['product_size']	;
	$product_dim = $_POST['product_dim']	;
	
    //MySqli query - get details of item from db using product product_id
    $results = mysql_query("SELECT title,price,discount_percent FROM product WHERE product_id='$product_id' LIMIT 1");
    $obj = mysql_fetch_object($results);
    
    if ($results) { //we have the product info 
        
        //prepare array for the session variable
        $new_product = array(array('title'=>$obj->title, 'product_id'=>$product_id, 'qty'=>$product_qty, 'price'=>$obj->price,'discount_percent'=>$obj->discount_percent,
					'product_img'=> $product_img,'product_size'=> $product_size,'product_dim'=> $product_dim ));
        
        if(isset($_SESSION["products"])) //if we have the session
        {
            $found = false; //set found item to false
            
            foreach ($_SESSION["products"] as $cart_itm) //loop through session array
            {
                if($cart_itm["product_id"] == $product_id){ //the item exist in array

                    $product[] = array('title'=>$cart_itm["title"], 'product_id'=>$cart_itm["product_id"], 'qty'=>$product_qty, 'price'=>$cart_itm["price"],'discount_percent'=>$cart_itm["discount_percent"]
					,'product_img'=>$cart_itm["product_img"],'product_size'=>$cart_itm["product_size"],'product_dim'=>$cart_itm["product_dim"]);
                    $found = true;
                }else{
                    //item doesn't exist in the list, just retrive old info and prepare array for session var
                    $product[] = array('title'=>$cart_itm["title"], 'product_id'=>$cart_itm["product_id"], 'qty'=>$cart_itm["qty"], 'price'=>$cart_itm["price"],'discount_percent'=>$cart_itm["discount_percent"]
					,'product_img'=>$cart_itm["product_img"],'product_size'=>$cart_itm["product_size"],'product_dim'=>$cart_itm["product_dim"]);
                }
            }
            
            if($found == false) //we didn't find item in array
            {
                //add new user item in array
                $_SESSION["products"] = array_merge($product, $new_product);
            }else{
                //found user item in array list, and increased the quantity
                $_SESSION["products"] = $product;
            }
            
        }else{
            //create a new session var if does not exist
            $_SESSION["products"] = $new_product;
        }
        
    }
    
    //redirect back to original page
    header('Location: view_cart.php');
}

//remove item from shopping cart
if(isset($_GET["removep"]) && isset($_GET["return_url"]) && isset($_SESSION["products"]))
{
    $product_id   = $_GET["removep"]; //get the product product_id to remove
    $return_url     = base64_decode($_GET["return_url"]); //get return url

    
    foreach ($_SESSION["products"] as $cart_itm) //loop through session array var
    {
        if($cart_itm["product_id"]!=$product_id){ //item does,t exist in the list
            $product[] = array('title'=>$cart_itm["title"], 'product_id'=>$cart_itm["product_id"], 'qty'=>$cart_itm["qty"], 'price'=>$cart_itm["price"],'discount_percent'=>$cart_itm["discount_percent"]
			,'product_img'=>$cart_itm["product_img"],'product_size'=>$cart_itm["product_size"],'product_dim'=>$cart_itm["product_dim"]);
        }
        
        //create a new product list for cart
        $_SESSION["products"] = $product;
    }
    
    //redirect back to original page
    header('Location:'.$return_url);
}