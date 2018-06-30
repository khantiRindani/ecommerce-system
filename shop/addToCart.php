<?php
include("../includes/connect.php");
$id=$_POST['id'];
session_start();
$user=$_SESSION['username'];
$cat=$_SESSION['cat'];
    if (isset($_SESSION['cart'][$id])) {

        $quantity = ( ++$_SESSION['cart'][$id]['quantity']);
        $res = mysqli_query($con, "UPDATE cart SET quantity=$quantity WHERE user_name='$user' AND product_id=$id");
        echo "added: ".$_SESSION['cart'][$id]['quantity'];
    } else {

        $sql_s = "SELECT * FROM products WHERE product_id=$id";
        $query_s = mysqli_query($con, $sql_s);
        if ($query_s) {
            if (mysqli_num_rows($query_s) > 0) {
                $row_s = mysqli_fetch_array($query_s);

                $_SESSION['cart'][$row_s['product_id']] = array(
                    "quantity" => 1,
                    "price" => $row_s['price']
                );

                //$proId=$id;
                $res = mysqli_query($con, "INSERT INTO cart (user_name,product_id,quantity) VALUES ('$user',$id,1)");
                echo "added: ".$_SESSION['cart'][$id]['quantity'];
            } else {

                echo "<span class='alert alert-danger'>This product id is invalid!</span>";
            }
        } else {
            echo "<span class='alert alert-danger'>error occured</span>";
        }
    }


