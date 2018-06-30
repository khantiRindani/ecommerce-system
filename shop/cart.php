<?php

/************************************************To display cart and checkout through PayPal*********************************************/
if (!isset($_SESSION))
    session_start();
if (!isset($_SESSION['logged'])){
    echo "<span class='alert alert-danger'>You need to login first</span>";
    echo "<a class='btn btn-default' href='../users/login.php'>Log In</a>";
}

include("../includes/header.php");
include("../includes/connect.php");
$user = $_SESSION['username'];

//---------------------------update quantities--------------------------------------
if (isset($_POST['submit']) && isset($_POST['quantity']) ){
    foreach ($_POST['quantity'] as $key => $val) {

        if ($val == 0) {
            unset($_SESSION['cart'][$key]);
            $sql = mysqli_query($con, "DELETE FROM cart WHERE user_name='$user' AND product_id=$key");
            if(!$sql){
                echo "<span class='alert alert-danger'>Sorry, couldn't update your cart: ".mysqli_error($con)."</span>";
            }
                
        } else {
            $_SESSION['cart'][$key]['quantity'] = $val;
            $res = mysqli_query($con, "UPDATE cart SET quantity=$val WHERE user_name='$user' AND product_id=$key");
             if(!$res){
                echo "<span class='alert alert-danger'>Sorry, couldn't update your cart:".mysqli_error($con)."</span>";
            }
        }
    }
}
if(isset($_POST['reset'])){
    unset($_SESSION['cart']);
    $empty=mysqli_query($con, "DELETE FROM cart WHERE user_name='$user'");
     if(!$empty){
                echo "<span class='alert alert-danger'>Sorry, couldn't empty your cart</span>";
            }
            //echo "<script>location.reload();</script>";
}
?>

<html>
    <head>
        <title>My Cart</title>
       
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        
        <style>
            a{
                color:black;
            }
            a:hover{
        color:blue;
        text-decoration: underline;
    }
        </style>
       
    </head>
    <body>
        <div class="container">
            
            <!--link to store-->
            <a href="products.php">Continue Shopping <i class="fa fa-shopping-bag"></i></a> 
            <div class="row">
                <h1 align="center">My cart</h1>

            </div>
        
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    
                    <!--- We will link input fields externally
                            because we have two forms interleaved and we don't want nested input fields, both are separate-->
                    
                    <!------------Local Form::::form for updating quantities---------------->
                    <form method="post" id="cartUpdate" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"> </form>
                    <table class="table table-striped"> 

                        <tr> 
                            <th scope="col">Name</th> 
                            <th scope="col">Quantity</th> 
                            <th scope="col">Price</th> 
                            <th scope="col">Item's Price</th> 
                        </tr> 
    


                        <?php
                      

                        //---------------------------------------Join cart and products table since cart contains only the prdouct id and quantity----------------
                        $sql = "SELECT products.product_id, products.product_name, products.price, cart.quantity FROM products INNER JOIN cart ON products.product_id=cart.product_id WHERE cart.user_name='$user'";
                        $query = mysqli_query($con, $sql);
                        
                        //----------------------------------when cart is not empty-------------------------------------------
                        if (mysqli_num_rows($query)>0) {

                            $totalprice = 0;
                            
                            //-----------------------------PayPal Form:::form for adding data into PayPal cart----------------------------------
                            echo '<form id="cart" action="' . PAYPAL_URL . '" method="post">
                                
                            <!-- Identify your business so that you can collect the payments. -->
                            <input type="hidden" name="business" value="' . PAYPAL_ID . '">

                            <!-- Specify a Buy Now button. -->
                            <input type="hidden" name="cmd" value="_cart">  
                            <input type="hidden" name="upload" value="1">
                            <input type="hidden" name="currency_code" value="INR">

                            <!-- Specify URLs -->' .
                            "<input type='hidden' name='notify_url' value='http://localhost/khanti/project/payments.php'>
                                <input type='hidden' name='cancel_return' value='http://localhost/khanti/project/payment_cancel.php'>
                            <input type='hidden' name='return' value='http://localhost/khanti/project/payment_success.php'>
                            
                            </form>";
                            
                            $i = 0;
                            while ($row = mysqli_fetch_array($query)) {
                                $i++;
                                /* if(isset($_SESSION['cart']))
                                  $quantity=$_SESSION['cart'][$row['product_id']]['quantity'];
                                  else{ */


                                //$subtotal = $_SESSION['cart'][$row['product_id']]['quantity'] * $row['price'];
                                $subtotal = $row['price'] * $row['quantity'];
                                $totalprice += $subtotal;
                                ?> 
                        
                        <!---------------------Local Form::::Printing each record with quantity being an input field i.e. user can change the quantity of any item-->
                                <tr> 
                                    <td><?php echo $row['product_name'] ?></td> 
                                    <td><input form="cartUpdate" type="text" name="quantity[<?php echo $row['product_id'] ?>]" size="5" value="<?php echo $row['quantity']; ?>" /></td> 
                                    <td><?php echo $row['price'] ?>$</td> 
                                    <td><?php echo $subtotal ?>$</td> 
                                </tr> 

                                <!------------- PayPal Form:::Specify details about the item that buyers will purchase.-------->
                                <input type="hidden" form="cart" name="item_name_<?php echo $i; ?>" value="<?php echo $row['product_name']; ?>">
                                <input type="hidden" form="cart" name="item_number_<?php echo $i; ?>" value="<?php echo $row['product_id']; ?>">
                                <input type="hidden" form="cart" name="amount_<?php echo $i; ?>" value="<?php echo $row['price']; ?>">
                                <input type="hidden" form="cart" name="quantity_<?php echo $i; ?>" value="<?php echo $row['quantity']; ?>">



                            <?php
                            }
                            ?> 
                            <tr> 
                                <td colspan="4">Total Price: <?php echo $totalprice ?></td> 
                            </tr> 

                            <!---------------PayPal Form:::: Display the payment button. -------------------->
                            <input form="cart" type="image" name="submit" border="0" 
                                   src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" alt="PayPal - The safer, easier way to pay online">
                            <img form="cart" alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >

   
                    </table> 
                    <br /> 
                   
                    <!-------------------Local Form:::Update is display only when cart is not empty-------------------------->
                    <button form="cartUpdate" class="btn btn-primary" type="submit" name="submit">Update Cart</button> 
                     <?php
                        }
                      ?>
                    <button align="right" form="cartUpdate" class="btn btn-danger" type="submit" name="reset">Empty my cart</button> 
                    <br /> 
                    <p>To remove an item, set it's quantity to 0. </p>
                </div>
            </div>
        </div> 
     
    </body>

</html>
      