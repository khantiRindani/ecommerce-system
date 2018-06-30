<?php
if (!isset($_SESSION))
    session_start();
$_SESSION['page'] = 'users';
$_SESSION['admin']['table'] = $_GET['type'];
include("../../includes/header.php");
?>

<html>
    <head>
        <title>Users' Info</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="<?php echo PATH; ?>scripts/pagination_jquery.js"></script>
        <style>
            .product_img {

                padding: 5px; /* Some padding */
                width: 100px;
                height:100px/* Set a small width */
            }

            /* Add a hover effect (blue shadow) */
            .product_img:hover {
                box-shadow: 0 0 2px 1px rgba(0, 140, 186, 0.5);
                transform: scale(2);
                border: 1px solid #ddd; /* Gray border */
                border-radius: 4px;  /* Rounded border */
            }
            .thumb{
                height:100px;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div id="response">
            </div>
            <div class="row">

                <!-- Modal -->
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Modal Header</h4>
                            </div>
                            <div class="modal-body" id="body1">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>

                    </div>
                </div>
                
                <div class="modal fade" id="myModal2" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Modal Header</h4>
                            </div>
                            <div class="modal-body" id="body1">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>

                    </div>
                </div>
                
                <h3 align='center'>USERS' INFORMATION</h3>
            </div>
            <div class="row">
                <div class="col-md-2">

                    <input type="text" placeholder="Search.." id="search">
                    <button type="submit" class="foo" style="display: inline;margin: 0px!important;width:50px;text-align: center" id="mysearch" value="submit"><i class="fa fa-search"></i></button>
                </div>


                <div class="col-md-1 col-md-offset-7">
                    <label>Sort by:</label>
                    <select id='sort' name='<?php echo $_SESSION['admin']['table']; ?>'>
                        <!--option value="field" disabled="" selected="">Field</option>-->


                    </select>
                </div>
                <script>
                    var type = document.getElementById('sort').name;
                    //alert(type);
                    switch (type) {
                        case 'form_db':
                            document.getElementById('sort').innerHTML = '<option></option>' +
                                    '<option value="id">ID</option>' +
                                    '<option value="firstname">First Name</option>' +
                                    '<option value="lastname">Last Name</option>' +
                                    '<option value="dob">Date of Birth</option>' +
                                    '<option value="email">Email</option>' +
                                    '<option value="mobile">Mobile Number</option>' +
                                    '<option value="gender">Gender</option>' +
                                    '<option value="address">Address</option>' +
                                    '<option value="city">City</option>' +
                                    '<option value="pin">Pin code</option>' +
                                    '<option value="state">State</option>' +
                                    '<option value="country">Country</option>' +
                                    '<option value="hobby">Hobby</option>' +
                                    '<option value="course">Course</option>';
                            break;

                        case 'products':
                            document.getElementById('sort').innerHTML = '<option></option>' +
                                    '<option value="product_id">ID</option>' +
                                    '<option value="product_name">Product Name</option>' +
                                    '<option value="price">Price</option>' +
                                    '<option value="category">Category</option>';
                            break;

                        case 'contact':
                            document.getElementById('sort').innerHTML = '<option></option>' +
                                    '<option value="id">ID</option>' +
                                    '<option value="name">Name</option>' +
                                    '<option value="email">Email Id</option>' +
                                    '<option value="subject">Subject</option>';
                            break;

                    }

                </script>
                <div class="col-md-2">
                    <div class="radio-inline">
                        <label><input type="radio" name='order' id="asc" value="asc">Ascending</label>
                    </div>
                    <div class="radio-inline">
                        <label><input type="radio" name='order' id="desc" value="desc">Descending</label>
                    </div>

                    <button type="submit" class="foo" style="display: inline;margin: 0px!important;width:100px;text-align: center" id='mysort' value='submit'>Sort</button>
                </div>
            </div>
            
                <div id="results">

                    <!-- content will be displayed here on the fly by jquery and ajax-->

                </div>
            
            <div class="row">
                <div align="center"> 
                    <button type="submit" class="foo" style="display: inline;margin: 0px;width:100px;text-align: center" id="reset" value="submit">Reset</button>
                </div>
            </div>
            
            <?php include("../../includes/footer.php"); ?>
    </body>
</html>

