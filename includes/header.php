<?php 

//------------------------------------------Header file to be included in each page---------------------------------------------------------
if(!isset($_SESSION))
    session_start();
include("constants.php");
include("connect.php");

//---------This code is used to remember the user whenever he/she visits the page again
if(isset($_COOKIE['user_login']) && $_COOKIE['user_login']){
    $check_user=$_COOKIE['user_login'];
    $check_pass=$_COOKIE['user_password'];
    
    //check cookies are correct(No malfunctioning in password)
    if(count(mysqli_query($con, "SELECT * FROM users WHERE username='$check_user' AND password='$check_pass'"))==1){
        $_SESSION['logged']=true;
        $_SESSION['username']=$_COOKIE['user_login'];
    }
}

if ($_SESSION['page'] == 'product' || $_SESSION['page']=='home'){
    $_SESSION['admin']['table']='';
}
?>
<html>
    <head>

        <link rel="stylesheet" type="text/css" href="<?php echo PATH;?>styles/mycss.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">       
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <script>
            $(function () {
                $('#menu1').click(function () {
                    //alert("clicked :"+ $(this).height());
                    $('#loginBar').css("height", $(this).height());
                });
            });
        </script>
    </head>

    <body>
        <header>
            <div class="container" style="overflow: hidden;height: auto">


                <!--navigation bar-->
                <nav class="navbar navbar-inverse" style="border: none">

                    <div class="navbar-header">
                        <a class="navbar-brand"><img src='<?php echo PATH;?>images/icons/logo.png' height='70px;' style="border:none!important;"/></a>
                        
                        <!--for responsive behavior-->
                        <button class="navbar-toggle navbar-dark bg-dark" type="button" data-toggle="collapse" data-target="#data-bar" aria-controls="databar" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="icon-bar" ></span>
                            <span class="icon-bar" ></span>
                            <span class="icon-bar" ></span>
                        </button>
                    </div>


                    <div class="collapse navbar-collapse"  id="data-bar">
                        <ul class="nav navbar-nav navbar-right" id="myNavbar">

                            <!--Home-->
                            <li class="nav-item"><a href="<?php echo PATH;?>index.php" id='home' <?php
                            
                                if ($_SESSION['page'] == 'home') { //this block is used to highlight the text when we are on corresponding page
                                    echo "class='myActive'";
                                }
                                
                                ?>>Home</a></li>
                            
                            <!-----Now, links are available only for admin-------------
                                    We can also place these links unconditionally with disable attribute for non-admin users-------->
                            
                                <!--Products' table-->
                                <?php if (isset($_SESSION['username']) && $_SESSION['username'] === 'admin') { ?>
                                <li class="nav-item"><a href="<?php echo PATH;?>users/admin/admin_table.php?type=products" <?php
                                    if (isset($_SESSION['admin']['table']) && $_SESSION['admin']['table'] == 'products') {
                                        echo "class='myActive'";
                                    }
                                    ?>>Products</a></li>
                                <!--Users' table-->
                                <li class="nav-item"><a href="<?php echo PATH;?>users/admin/admin_table.php?type=form_db" <?php
                                    if (isset($_SESSION['admin']['table']) && $_SESSION['admin']['table'] == 'form_db'){
                                        echo "class='myActive'";
                                    }
                                    ?>>Users</a></li>
                                <!--Feedbacks' table-->
                                 <li class="nav-item"><a href="<?php echo PATH;?>users/admin/admin_table.php?type=contact" <?php
                                    if (isset($_SESSION['admin']['table'])&& $_SESSION['admin']['table'] == 'contact') {
                                        echo "class='myActive'";
                                    }
                                    ?>>Feedbacks</a></li>
                                    <?php
                                }
                           //---------------------------------------------------------
                                
                                //----------if a user is logged in, show logout
                                if (isset($_SESSION['username']) && $_SESSION['username'] != '') {
                                    
                                    echo '<li class="nav-item"><a href="'.PATH.'users/logout.php"';
                                    if ($_SESSION['page'] == 'logout') {
                                        echo "class='myActive'";
                                    }echo ">Log Out</a></li>";
                                } 
                                //-----------otherwise show log in and sign up page------------
                                else {
                                 
                                    echo '<li class="nav-item"><a href="'.PATH.'users/login.php"';
                                    if ($_SESSION['page'] == 'login') {
                                        echo "class='myActive'";
                                    }echo ">Log In</a></li>";
                                    ?>

                                <li class="nav-item"><a href="<?php echo PATH;?>users/register.php" <?php
                                    if ($_SESSION['page'] == 'signup') {
                                        echo "class='myActive'";
                                    }
                                    ?>>Sign Up</a></li>
                                <?php }
                                ?>
                                
                            <!--------------show products' page unconditionally-------------------------->    
                            <li class="nav-item"><a href="<?php echo PATH;?>shop/products.php" id='product' <?php
                                if ($_SESSION['page'] == 'product') {
                                    echo "class='myActive'";
                                }
                                ?>>Store</a></li>
                            
                            <!-------------for non-admin users: show contact us page----------------------->
                                <?php if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') { ?>
                                <li class="nav-item"><a href="<?php echo PATH;?>users/contact-form.php" id='contact' <?php
                                                        if ($_SESSION['page'] == 'contact') {
                                                            echo "class='myActive'";
                                                        }
                                                        ?>>Contact Us</a></li>
                                <?php } ?>


                        </ul>
                    </div>



                </nav>



            </div>
            <!--------this section is a dropdown button used for displaying utilities of a non-admin user
                        like display profile,reset password,display cart,log out--------------------->
            <div align="right">

                <?php
                if (isset($_SESSION['username']) && $_SESSION['username'] !== 'admin') {
                    echo '<div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">' . $_SESSION["username"] .
                    '<span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-menu-right">
                          <li><a href="'.PATH.'users/profile.php?auth=1">Profile</a></li>
                          <li><a href="'.PATH.'users/reset_pass.php?auth=1">Change password</a></li>
                          <li><a href="'.PATH.'shop/cart.php">My Cart</a></li>
                          <li><a href="'.PATH.'users/logout.php">Log Out</a></li>
                        </ul>
                      </div>';
                }
                ?>




            </div>

        </header>

    </body>
</html>

