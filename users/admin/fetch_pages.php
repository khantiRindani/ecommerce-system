<?php

session_start();
global $type;
$type = $_SESSION['admin']['table'];
get_heading();

//establishing connection
include("../../includes/connect.php");
include("../../includes/constants.php");

//Get page number from Ajax
if (isset($_POST["page"])) {
    //filter number
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
    //incase of invalid page number
    if (!is_numeric($page_number)) {
        die('Invalid page number!');
    }
}
//if there's no page number, set it to 1
else {
    $page_number = 1;
}


$no_of_records_per_page = 3;
$offset = ($page_number - 1) * $no_of_records_per_page;

//......base query
$query = "SELECT * FROM " . $_SESSION['admin']['table'];

if (isset($_POST['search']) && $_POST['search'] != '' && !isset($_POST['reset'])) {

    $str = htmlspecialchars($_POST['search']);

    switch($_SESSION['admin']['table']){
        case 'form_db':
            $query .= " WHERE `firstname` LIKE '%$str%' OR `lastname` LIKE '$str' OR `dob` LIKE '$str' OR `gender` LIKE '%$str%' OR `city` LIKE '$str' OR `state` LIKE '$str'";
            $total_pages_sql = "SELECT COUNT(*) FROM form_db WHERE firstname LIKE '$str' OR lastname LIKE '$str' OR dob LIKE '$str' OR gender LIKE '$str' OR city LIKE '$str' OR state LIKE '$str'";
            break;
        case 'products':
            $query.=" WHERE product_name LIKE '%$str%' OR description LIKE '%$str%' OR category LIKE '%$str%'";
            $total_pages_sql = "SELECT COUNT(*) FROM products WHERE product_name LIKE '%$str%' OR description LIKE '%$str%' OR category LIKE '%$str%'";
            break;
        case 'contact':
            $query.=" WHERE name LIKE '%$str%' OR email LIKE '%$str%' OR subject LIKE '%$str' OR msg LIKE '%$str%'";
            $total_pages_sql = "SELECT COUNT(*) FROM contact WHERE name LIKE '%$str%' OR email LIKE '%$str%' OR subject LIKE '%$str' OR msg LIKE '%$str%'";
            break;
    }
    //.....counting total no. of pages
   
    $result = mysqli_query($con, $total_pages_sql);
    $total_rows = mysqli_fetch_array($result)[0];
    $total_pages = ceil($total_rows / $no_of_records_per_page);


    //.....when sorting is applied
    if (isset($_POST['sort']) && $_POST['sort'] != '') {
        $sort = htmlspecialchars($_POST['sort']);
        if ($_POST['order'] == '')
            $order = 'asc';
        else
            $order = $_POST['order'];

        $query .= " ORDER BY `$sort` $order";
    }

    $records = mysqli_query($con, $query . " LIMIT $offset, $no_of_records_per_page");
} else {

    $total_pages_sql = "SELECT COUNT(*) FROM " . $_SESSION['admin']['table'];
    $result = mysqli_query($con, $total_pages_sql);
    $total_rows = mysqli_fetch_array($result)[0];
    $total_pages = ceil($total_rows / $no_of_records_per_page);

    if (isset($_POST['sort']) && $_POST['sort'] != '') {
        $sort = htmlspecialchars($_POST['sort']);
        if ($_POST['order'] == '')
            $order = 'asc';
        else
            $order = $_POST['order'];

        $query .= " ORDER BY `$sort` $order";
    }
    $records = mysqli_query($con, $query . " LIMIT $offset, $no_of_records_per_page");
}
while ($field = mysqli_fetch_array($records)) {
    echo "<tr>";

    get_row($field);

    echo "</tr>";
}

echo '</table>';


if($_SESSION['admin']['table']=='products'){
  echo '<div class="row"><div class="col-md-2">';
    echo '<div class="dropdown">
                        <button class="btn foo dropdown-toggle" type="button" data-toggle="dropdown">Categories
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">';
                         $result=mysqli_query($con,"SELECT * FROM categories");
                         while($field= mysqli_fetch_array($result)){
                             echo "<li><a>".$field['name']."</a></li>";
                         }
                         echo "<li><button class='btn btn-primary' id='insert_category'>Insert</button>".
                                 "<input type='text' id='category_name'/></li>";
                         echo '</ul>
    </div></div>';
    echo '<div class="col-md-1 col-md-offset-8">'
        . '<button class="btn foo" id="form_item" data-remote=false" data-toggle="modal" data-target="#myModal2">Insert an item</button></div></div>';
            }
            
echo '<div align="center">';
//pagination

// To generate links, we call the pagination function here.
echo paginate_function($no_of_records_per_page, $page_number, $total_rows, $total_pages);
echo '</div>';

function get_heading() {
    global $type;
    switch ($type) {
        case 'products': {
                $output = '<table class="table table-striped table-bordered" ><thread class="thead-dark">
            <tr>
                <th scope="col">Product Id</th>
                <th scope="col">Product Name</th>
                <th scope="col">Image</th>
                <th scope="col">Description</th>
                <th scope="col">Price</th>
                <th scope="col">Stock</th>
                <th scope="col">Category</th>
                <th colspan="2" scope="col">Action</th>
                </tr></thread>';
                echo $output;
                break;
            }
        case 'form_db': {
                $output = '<table class="table table-striped table-bordered" ><thread class="thead-dark">
            <tr>
                <th scope="col">id</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Date of Birth</th>
                <th scope="col">Email</th>
                <th scope="col">Mobile Number</th>
                <th scope="col">Gender</th>
                <th scope="col">Address</th>
                <th scope="col">City</th>
                <th scope="col">Pin code</th>
                <th scope="col">State</th>
                <th scope="col">Country</th>
                <th scope="col">Hobby</th>
                <th scope="col">Course</th>
                <th colspan="2" scope="col">Action</th>
            </tr>
                 
            </thread>';
                echo $output;
                break;
            }
        case 'contact': {
                $output = '<table class="table table-striped table-bordered" ><thread class="thead-dark">
            <tr>
                <th scope="col">id</th>
                <th scope="col">Name</th>
                <th scope="col">Email Id</th>
                <th scope="col">Subject</th>
                <th scope="col">Message</th>
             </tr></thread>';
                echo $output;
                break;
            }
    }
}

function get_row($field) {
    global $type;
    switch ($type) {
        case 'products': {
                echo "<td>" . $field['product_id'] . "</td>";
                echo "<td>" . $field['product_name'] . "</td>";
                echo "<td><a href=" . PATH . $field['product_img'] . "><img class='product_img' src='" .PATH. $field['product_img'] . "' alt='Image error'></a></td>";
                echo "<td>" . $field['description'] . "</td>";
                echo "<td>" . $field['price'] . "</td>";
                echo "<td>" . $field['stock'] . "</td>";
                echo "<td>" . $field['category'] . "</td>";
                echo '<td><button type="button" class="btn btn-primary update-btn" id="' . $field['product_id'] . '" data-remote="false" data-toggle="modal" data-target="#myModal">Update</button></td>';
                //delete
                echo '<td><button type="button" id="' . $field['product_id'] . '" class="btn btn-danger delete">Delete</button></td>';
                break;
            }
        case 'form_db': {
                echo "<td>" . $field['id'] . "</td>";
                echo "<td>" . $field['firstname'] . "</td>";
                echo "<td>" . $field['lastname'] . "</td>";
                echo "<td>" . $field['dob'] . "</td>";
                echo "<td>" . $field['email'] . "</td>";
                echo "<td>" . $field['mobile'] . "</td>";
                echo "<td>" . $field['gender'] . "</td>";
                echo "<td>" . $field['address'] . "</td>";
                echo "<td>" . $field['city'] . "</td>";
                echo "<td>" . $field['pin'] . "</td>";
                echo "<td>" . $field['state'] . "</td>";
                echo "<td>" . $field['country'] . "</td>";
                echo "<td>" . $field['hobby'] . "</td>";
                echo "<td>" . $field['course'] . "</td>";
                $x = $field['id'];


                //update
                echo '<td><button type="button" class="btn btn-primary update-btn" id="' . $field['id'] . '" data-remote="false" data-toggle="modal" data-target="#myModal">Update</button></td>';
                //delete
                echo '<td><button type="button" id="' . $field['id'] . '" class="btn btn-danger delete">Delete</button></td>';
                break;
            }
        case 'contact': {
                echo "<td>" . $field['id'] . "</td>";
                echo "<td>" . $field['name'] . "</td>";
                echo "<td>" . $field['email'] . "</td>";
                echo "<td>" . $field['subject'] . "</td>";
                echo "<td>" . $field['msg'] . "</td>";
            }
    }
}

function paginate_function($item_per_page, $current_page, $total_records, $total_pages) {
    $pagination = '';
    $_SESSION['paginate']['page'] = $current_page;
    if ($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages) { //verify total pages and current page number
        $pagination .= '<ul class="pagination">';
        $right_links = $current_page + 3;
        $previous = $current_page - 1; //previous link
        $next = $current_page + 1; //next link
        $first_link = true; //boolean var to decide our first link

        if ($current_page > 1) {
            // $previous_link = ($previous <= 0) ? 1 : $previous;
            $previous_link = $current_page - 1;
            $pagination .= '<li class="first"><a href="#" data-page="1" title="First">&laquo;</a></li>'; //first link
            $pagination .= '<li><a href="#" data-page="' . $previous_link . '" title="Previous">&lt;</a></li>'; //previous link
            for ($i = ($current_page - 2); $i < $current_page; $i++) { //Create left-hand side links
                if ($i > 0) {
                    $pagination .= '<li><a href="#" data-page="' . $i . '" title="Page' . $i . '">' . $i . '</a></li>';
                }
            }
            $first_link = false; //set first link to false
        }

        if ($first_link) { //if current active page is first link
            $pagination .= '<li class="first active"><a>' . $current_page . '</a></li>';
        } elseif ($current_page == $total_pages) { //if it's the last active link
            $pagination .= '<li class="last active"><a>' . $current_page . '</a></li>';
        } else { //regular current link
            $pagination .= '<li class="active"><a>' . $current_page . '</a></li>';
        }

        for ($i = $current_page + 1; $i < $right_links; $i++) { //create right-hand side links
            if ($i <= $total_pages) {
                $pagination .= '<li><a href="#" data-page="' . $i . '" title="Page ' . $i . '">' . $i . '</a></li>';
            }
        }
        if ($current_page < $total_pages) {
            // $next_link = ($i > $total_pages) ? $total_pages : $i;
            $next_link = $current_page + 1;
            $pagination .= '<li><a href="#" data-page="' . $next_link . '" title="Next">&gt;</a></li>'; //next link
            $pagination .= '<li class="last"><a href="#" data-page="' . $total_pages . '" title="Last">&raquo;</a></li>'; //last link
        }
        $pagination .= '</ul>';
    }
    return $pagination; //return pagination links
}
