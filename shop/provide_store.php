<?php
if(!isset($_SESSION))
    session_start();
$user=$_SESSION['username'];
if(isset($_POST['cat']))
    $_SESSION['cat']=$_POST['cat'];
else
    $_SESSION['cat']='electronics';
$cat=$_SESSION['cat'];
//print_r($_SESSION);
include("../includes/connect.php");
include("../includes/constants.php");

function paginate_function($item_per_page, $current_page, $total_records, $total_pages) {
    $pagination = '';
    $_SESSION['paginate']['page']=$current_page;
    if ($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages) { //verify total pages and current page number
        $pagination .= '<ul class="pagination">';
        $right_links = $current_page + 3;
        $previous = $current_page - 1; //previous link
        $next = $current_page + 1; //next link
        $first_link = true; //boolean var to decide our first link

        if ($current_page > 1) {
           // $previous_link = ($previous <= 0) ? 1 : $previous;
            $previous_link=$current_page-1;
            $pagination .= '<li class="first"><a href="#" data-pageno="1" title="First">&laquo;</a></li>'; //first link
            $pagination .= '<li><a href="#" data-pageno="' . $previous_link . '" title="Previous">&lt;</a></li>'; //previous link
            for ($i = ($current_page - 2); $i < $current_page; $i++) { //Create left-hand side links
                if ($i > 0) {
                    $pagination .= '<li><a href="#" data-pageno="' . $i . '" title="Page' . $i . '">' . $i . '</a></li>';
                }
            }
            $first_link = false; //set first link to false
        }

        if ($first_link) { //if current active page is first link
            $pagination .= '<li class="first active"><a> ' . $current_page . '</a></li>';
        } elseif ($current_page == $total_pages) { //if it's the last active link
            $pagination .= '<li class="last active"><a>' . $current_page . '</a></li>';
        } else { //regular current link
            $pagination .= '<li class="active"><a>' . $current_page . '</a></li>';
        }

        for ($i = $current_page + 1; $i < $right_links; $i++) { //create right-hand side links
            if ($i <= $total_pages) {
                $pagination .= '<li><a href="#" data-pageno="' . $i . '" title="Page ' . $i . '">' . $i . '</a></li>';
            }
        }
        if ($current_page < $total_pages) {
           // $next_link = ($i > $total_pages) ? $total_pages : $i;
            $next_link=$current_page+1;
            $pagination .= '<li><a href="#" data-pageno="' . $next_link . '" title="Next">&gt;</a></li>'; //next link
            $pagination .= '<li class="last"><a href="#" data-pageno="' . $total_pages . '" title="Last">&raquo;</a></li>'; //last link
        }
        $pagination .= '</ul>';
    }
    return $pagination; //return pagination links
}



 echo '<form id ="view-cart" action="'.PAYPAL_URL.'" method="post" name="viewcart">';
 
 if (isset($_POST["pageno"])) {
    //filter number
    $page_number = filter_var($_POST["pageno"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
    //incase of invalid page number
    if (!is_numeric($page_number)) {
        die('Invalid page number!');
    }
}
//if there's no page number, set it to 1
else {
    $page_number = 1;
}


$no_of_records_per_page = 6;
$offset = ($page_number - 1) * $no_of_records_per_page;
$query="SELECT * FROM products WHERE category='$cat'";

if (isset($_POST['search']) && $_POST['search'] != '' &&!isset($_POST['reset'])) {

    $str = htmlspecialchars($_POST['search']);

    $query .= " AND product_name LIKE '%$str%'";


    //.....counting total no. of pages
    $total_pages_sql = "SELECT COUNT(*) FROM products WHERE category='$cat' AND product_name LIKE '%$str%'";
    $result = mysqli_query($con, $total_pages_sql);
    $total_rows = mysqli_fetch_array($result)[0];
    $total_pages = ceil($total_rows / $no_of_records_per_page);


    //.....when sorting is applied
    if (isset($_POST['sort']) && $_POST['sort'] != '') {
        $sort = htmlspecialchars($_POST['sort']);
        if($_POST['order']=='')
          $order='asc';
        else $order=$_POST['order'];
        
        $query .= " ORDER BY `$sort` $order";
    }

} else {

    $total_pages_sql = "SELECT COUNT(*) FROM products WHERE category='$cat'";
    $result = mysqli_query($con, $total_pages_sql);
    $total_rows = mysqli_fetch_array($result)[0];
    $total_pages = ceil($total_rows / $no_of_records_per_page);

    if (isset($_POST['sort']) && $_POST['sort'] != '') {
        $sort = htmlspecialchars($_POST['sort']);
        if($_POST['order']=='')
          $order='asc';
        else $order=$_POST['order'];
        
        $query .= " ORDER BY `$sort` $order";
    }
}
    $records = mysqli_query($con, $query . " LIMIT $offset, $no_of_records_per_page");


 
            echo '<div class="row">';

            while ($field = mysqli_fetch_array($records)){
            
                echo '<div class="col-sm-4">';
                echo "<p class='heading'><b>".$field['product_name']."</b></p>";
                echo "<p><a href=" . PATH . $field['product_img'] . "><img class='product' src='" . PATH .$field['product_img'] . "' alt='Image error'></a></p>";
                //echo "<p><img height='175px' src='".PATH.$field['product_img']."' alt='image error'></p>";
                echo "<p>".$field['description']."</p>";
                echo "<p class='price'>price: ".$field['price']."</p>";
                echo "<p>Stock: ".$field['stock']."</p>";
               /* if(isset($_SESSION['cart'][$field['product_id']])){
                echo "<p><a></a> ";//href='products.php?cat='.$cat.'&action=add&id=".$field['product_id']."'>Added ".$_SESSION['cart'][$field['product_id']]['quantity']."</a></p>";
                }
                else{*/
                echo '<p><a style="cursor:pointer" class="addCart" id="item_add" name='.$field['product_id'].'>Add to cart</a></p>';//href="products.php?cat='.$cat.'&action=add&id='.$field['product_id'].'">Add to cart</a></p>';
                //}
                ?>
            <form action="<?php echo PAYPAL_URL; ?>" method="post">
        <!-- Identify your business so that you can collect the payments. -->
        <input type="hidden" name="business" value="<?php echo PAYPAL_ID;?>">
       <input type="hidden" name="merchantid" value="QK3JN4DRPZF2N">
        
        <!-- Specify a Buy Now button. -->
        <input type="hidden" name="cmd" value="_xclick">
        <!--input type="hidden" name="cmd" value="_cart" --/>
        <!-- Specify details about the item that buyers will purchase. -->
        
        <!--input type="hidden" name="payer_email" value="" /-->
        <input type='hidden' name='no_shipping' value='1'>
        
		
        <input type="hidden" name="item_name" value="<?php echo $field['product_name']; ?>">
        <input type="hidden" name="item_number" value="<?php echo $field['product_id']; ?>">
        <input type="hidden" name="amount" value="<?php echo $field['price']; ?>">
        <input type="hidden" name="currency_code" value="INR">
        
        <!-- Specify URLs -->
        <input type='hidden' name='notify_url' value='http://localhost/khanti/project/payment_success.php'>
        <input type='hidden' name='cancel_return' value='http://localhost/khanti/project/payment_cancel.php'>
        <input type='hidden' name='return' value='http://localhost/khanti/project/payment_success.php'>
        
        <!--button type="submit" name="submit">Submit</button-->
        
        <!-- Display the payment button.-->
        <input type="image" name="submit" border="0" <?php if($field['stock']==0){echo 'disabled';}?>
        src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" alt="PayPal - The safer, easier way to pay online">
        <img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif">
    </form>
           </div>
            <?php }
echo '<br><div align="center">';
// To generate links, we call the pagination function here.
echo paginate_function($no_of_records_per_page, $page_number, $total_rows, $total_pages);
echo '</div></div>';?>

           
           <script>
               $(function(){
                   $('.addCart').click(function(){
                        $(this).load("addToCart.php",{'id':$(this).attr('name')});});
               });
           </script>