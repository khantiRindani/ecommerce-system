<?php
if (!isset($_SESSION))
    session_start();
if (!isset($_SESSION['logged'])) {
    echo "<span class='alert alert-danger'>You need to login first</span>";
    echo "<a class='btn btn-primary' href='../users/login.php'>Log In</a>";
    exit();
}
$_SESSION['page'] = 'product';
include("../includes/header.php");

$user = $_SESSION['username'];
if(isset($_GET['cat']))
    $_SESSION['cat']=$_GET['cat'];
else $_SESSION['cat'] = 'electronics';
$id = 0;

?>
<html>
    <head>
        <title>Products</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <style>
            .product {
   
    padding: 5px; /* Some padding */
        height:175px/* Set a small width */
}

/* Add a hover effect (blue shadow) */
.product:hover {
    box-shadow: 0 0 2px 1px rgba(0, 140, 186, 0.5);
    transform: scale(2);
     border: 1px solid #ddd; /* Gray border */
    border-radius: 4px;  /* Rounded border */
}
        </style>
        <script>
            $(function () {
                var cat = 'electronics';
                var search = '';
                var sort = '';
                var order = '';
                //var page='1';
                $("#productLoad").load("provide_store.php");

                $("#productLoad").on("click", ".pagination a", function (e) {
                    e.preventDefault();
                    // $(".loading-div").show(); //show loading element
                    var page = $(this).attr("data-pageno"); //get page number from link

                    //get content from PHP page
                    $("#productLoad").load("provide_store.php", {"pageno": page, "cat": cat, "search": search, "sort": sort, "order": order});

                });

                $("#electronics").click(function () {
                    cat = 'electronics';

                    //$("#clothing").removeClass('active');
                    //$(this).addClass('active');

                    $("#productLoad").load("provide_store.php", {'cat': cat});
                    $('#section').html("Electronics");
                    // $("#sidebar").load("bar.php");
                });
                $("#clothing").click(function () {
                    cat = 'clothing';
                    //$("#electronics").removeClass('active');
                    //$(this).addClass('active');
                    //$("sidebar").contentWindow.location.reload(true);

                    $("#productLoad").load("provide_store.php", {'cat': cat});
                    $('#section').html("Clothing");
                    //$("#sidebar").load("bar.php");
                });


                $('#mysearch').click(function () {
                    search = $('#search').val();
                    //$('#search').val('');
                    //$('#sort').val('');
                    $("#productLoad").load("provide_store.php", {"cat": cat, "search": search, "sort": sort, "order": order});
                });
                $('#mysort').click(function () {
                    sort = $('#sort').val();
                    if (sort == '') {
                        alert("Please select a field for sorting");
                    }
                    if ($('#asc').prop('checked') === true)
                        order = 'asc';
                    else if ($('#desc').prop('checked') === true)
                        order = 'desc';
                    $("#productLoad").load("provide_store.php", {"cat": cat, "search": search, "sort": sort, "order": order});
                });
                $('#reset').click(function () {
                    search = '';
                    sort = '';
                    page = 1;
                    order = '';
                    $('#search').val('');
                    $('#sort').val('');
                    $("#productLoad").load("provide_store.php", {"cat": cat, "search": search, "sort": sort, "order": order});
                });
            });
        </script>    

        <style>
            p{
                text-align: center;
            }
            .heading{
                font-size: 18pt;
                margin-top: 20px;
            }
            .price{
                font-style: italic;
            }
        </style>

    </head>
    <body>

        <div class="container-fluid">

            <div class="col-md-2" id="sidebar">
<?php include("../includes/bar.php"); ?>
            </div>
            <br>

            <div class="col-md-10">
                <div align="right"><h4><a href="cart.php">My Cart <i class="fa fa-shopping-cart"></i></a></h4></div>
                <h2 align="center" id="section">Electronics</h2>
                

                <div class="row">
                    <div class='col-md-2'>
                        <input type="text" placeholder="Search.." id="search">
                        <button class='btn btn-primary' type="submit" id="mysearch" value="submit"><i class="fa fa-search"></i></button>
                    </div>
                    <div class='col-md-4 col-md-offset-6' style="text-align:right">
                    Sort by:
                    <select id='sort'>
                        <!--option value="field" disabled="" selected="">Field</option>-->
                        <option></option>
                        <option value="product_name">Product Name</option>
                        <option value="price">Price</option>                 
                    </select>

                    Ascending<input type="radio" id="asc" value="asc">
                    Descending<input type="radio" id="desc" value="desc">

                    <button type="submit" class='btn btn-primary' id='mysort' value='submit'>Sort</button>
                    <br>
                    <button type="submit" class='btn btn-danger' id="reset" value="submit">Reset</button>
                    </div>
                </div>
                
                <div id="productLoad">

                </div>  
            
            </div>
        </div>
<?php include("../includes/footer.php"); ?>
    </body>
</html>