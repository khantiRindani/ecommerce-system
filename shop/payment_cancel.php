<?php 
if(!isset($_SESSION))
    session_start ();
include("header.php");
?>
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
<h2>Your PayPal transaction has been canceled.</h2>
<a class="btn btn-primary" href="products.php">Go back to store<i class="fa fa-shopping-bag"></i></a> 
<?php 
include("footer.php");
?>