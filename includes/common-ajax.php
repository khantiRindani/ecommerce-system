<?php

/* --------------------------------------------------------------------------------------------------------------------------------
 * --------------------------------------------This file handles all the ajax calls-----------------------------------------------
  ---------------------------------------------------------------------------------------------------------------------------------- */

session_start();
$type = $_GET['type'];

include("connect.php");
include("constants.php");

//-------------------------------------------function for removing spaces and html entities (Can cause XSS)----------------------------------------------------
function test_input($data) {
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

//for validating recaptcha
class GoogleRecaptcha {

    public function VerifyCaptcha($response) {
        $url = CAPTCHA_URL . "?secret=" . CAPTCHA_SECRET . "&response=" . $response;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        $curlData = curl_exec($curl);

        curl_close($curl);

        $res = json_decode($curlData, TRUE);

        return $res;
    }

}

switch ($type) {

    /* -------------[[[[[[[[[[[[[[[[[[[[[[[                  Users' Utilities                      ]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]-----------------------------
     *                                           contact us, register, login, reset password
     */


    //-------------------------------------------Submission of contact us form by user--------------------------------------------------------------
    case 'contact': {

            $error = '';
            //---------Access data through post
            $name = test_input($_POST['fname']);
            $email = test_input($_POST['email']);
            $subject = test_input($_POST['subject']);
            $msg = test_input($_POST['msg']);

            $count = 0;

            //-----------------------server side validations------------------------------
            if (!$name) {
                $error .= 'Please enter your name';
                $count++;
            }
            if (!$email) {
                $error .= '<br>Please enter your email';
                $count++;
            }
            if (!$subject) {
                $error .= '<br>Please enter subject';
                $count++;
            }
            if (!$msg) {
                $error .= '<br>Please enter your message';
                $count++;
            }
            echo $error;
            //-----------------------------------------------------------------------------
            //--------if no error exists, insert the record 
            if ($count === 0) {
                //-------sql syntax
                $res = mysqli_query($con, "INSERT INTO contact(name,email,subject,msg) values ('$name','$email','$subject','$msg')");

                //print messages for success and failure
                if ($res) {
                    echo "<p class='alert alert-success'>Your response has been recorded.</p>";
                } else {
                    echo "<p class='alert alert-danger'>Sorry, some error occured while submitting your response.</p>";
                }
            }
            break;
        }

    //------------------------------------------New user just filled registration form-------------------------------------------------------------
    case 'signup': {

            $error = '';
            $firstname = test_input($_POST["firstname"]);
            $lastname = test_input($_POST["lastname"]);
            $mobile = test_input($_POST['mobile']);
            $email = test_input($_POST["email"]);
            $add = test_input($_POST['add']);
            $city = test_input($_POST['city']);
            $pin = test_input($_POST['pin']);
            $state = test_input($_POST['state']);
            $country = test_input($_POST['country']);

            $pass = test_input($_POST['pass']);
            $c_pass = md5($pass);

            $gender = $_POST['gender'];
            $hobby = $_POST['hobby'];
            $course = $_POST['course'];
            $datepick = $_POST['datepick'];
            $hobbyArr = '';
            $i = 0;
            $count = 0;

            //--------------------We can provide more validations for each field separately.---------------------------------------------
            if (!($firstname && $lastname && $mobile && $email && $add && $city && $pin && $state && $country && $pass && $gender && $hobby && $course && $datepick)) {
                $count++;
                $error .= "<br>All the fields must be filled";
            }

            //Email id should be unique to detect repetation
            else if (mysqli_num_rows(mysqli_query($con, "SELECT * FROM form_db WHERE email='$email'"))!=0) {
                $error .= "<br>Sorry, email id already exists";
                $count++;
            }

            //hobby is posted as array: we need to convert it into a string with comma sepsrated entities
            else if ($count == 0) {
                foreach ($hobby as $i) {
                    $hobbyArr = $hobbyArr . ',' . $i;
                }
                if (in_array('other', $hobby)) {
                    $hobbyArr = $hobbyArr . ':' . $_POST['hobbytext'];
                }

                $res = mysqli_query($con, "INSERT INTO form_db (firstname, lastname,dob, email, mobile,gender,address,city,pin,state,country,hobby,course) VALUES ('$firstname','$lastname','$datepick','$email','$mobile','$gender','$add','$city','$pin','$state','$country','$hobbyArr','$course')");
                $res2 = mysqli_query($con, "INSERT INTO users(username,password) VALUES ('$email','$c_pass')");
                if ($res && $res2) {
                    echo "<p class='alert alert-success'>You are successfully registered.</p>";
                } else {
                    echo "<p class='alert alert-danger'>Sorry, some error occured while submitting your response.:" . mysqli_error($con) . "</p>";
                }
            }
            echo $error;
            break;
        }


    //----------------------------------------------user wants to log in----------------------------------------------------------
    case 'login': {

            $username = test_input($_POST['username']);
            $pass = test_input($_POST['pass']);
            $err = '';
            $count = 0;
            if ($username == '') {
                $err .= 'Enter your username';
                $count++;
            }
            if ($pass == '') {
                $err .= '<br>Enter your password';
                $count++;
            }
            if (!isset($_POST['g-recaptcha-response']) || !$_POST['g-recaptcha-response']) {
                $err .= '<br>Captcha is empty';
                $count++;
            } else {
                $response = $_POST['g-recaptcha-response'];
                $cap = new GoogleRecaptcha();
                $verified = $cap->VerifyCaptcha($response);
                if (!$verified) {
                    $err .= "<br>Captcha verification failed";
                    $count++;
                }
            }
            if ($count === 0) {

                $c_pass = md5($pass);
                
                if($username=='admin' && $c_pass==ADMIN_PASS){
                    $_SESSION['logged'] = true;
                    $_SESSION['username'] = $username;

                    //---------cookie to remember the user
                    if (!empty($_POST["remember"])) {
                        setcookie("user_login", $username, time() + (10 * 365 * 24 * 60 * 60), '/');
                        setcookie("user_password", $c_pass, time() + (10 * 365 * 24 * 60 * 60), '/');
                    }
                    echo "<span class='alert alert-success'>You are successfully logged in</span>"
                    . "<a class='btn btn-primary' href='".PATH."' style='cursor:pointer'>Home</a>";
                    exit;
                }
                $res = mysqli_query($con, "SELECT id FROM users WHERE `username` LIKE '$username' AND `password` LIKE '$c_pass'");
                $user_res = mysqli_fetch_assoc($res);


                if (mysqli_num_rows($res) === 1) {

                    //---------seeting as sessoin variable because we want to access it from everywhere
                    $_SESSION['logged'] = true;
                    $_SESSION['username'] = $username;

                    //---------cookie to remember the user
                    if (!empty($_POST["remember"])) {
                        setcookie("user_login", $username, time() + (10 * 365 * 24 * 60 * 60), '/');
                        setcookie("user_password", $c_pass, time() + (10 * 365 * 24 * 60 * 60), '/');
                    }
                    echo "<span class='alert alert-success'>You are successfully logged in</span>"
                    . "<a class='btn btn-primary' href='".PATH."' style='cursor:pointer'>Home</a>";
                } else {

                    $_SESSION['logged'] = false;
                    if (isset($_COOKIE["user_login"])) {
                        setcookie("user_login", "");
                    }
                    if (isset($_COOKIE["user_password"])) {
                        setcookie("user_password", "");
                    }
                    $err .= '<br>Invalid username or password';
                }
            }
            echo $err;
            break;
        }


    //-------------------------------------To reset the password of user-------------------------------------------
    case 'reset': {
            if(!isset($_SESSION['username'])){
                echo "you need to log in first";
                break;
            }
            $user = $_SESSION['username'];
            $oldpass = test_input($_POST['pass']);
            $newpass = test_input($_POST['newpass']);
            $count = 0;
            if (!($user && $oldpass && $newpass)) {
                $err .= '<br>All the fields must bt filled';
                $count++;
            }
            if (!isset($_POST['g-recaptcha-response']) || !$_POST['g-recaptcha-response']) {
                $err .= '<br>Captcha is empty';
                $count++;
            } else {
                $response = $_POST['g-recaptcha-response'];
                $cap = new GoogleRecaptcha();
                $verified = $cap->VerifyCaptcha($response);
                if (!$verified) {
                    $err .= "<br>Captcha verification failed";
                    $count++;
                }

                if ($count == 0) {
                    $c_oldpass = md5($oldpass);
                    $c_newpass = md5($newpass);
                    $reset = mysqli_query($con, "SELECT * FROM users WHERE username='$user' AND password='$c_oldpass'");
                    if (mysqli_num_rows($reset) == 1) {
                        $change = mysqli_query($con, "UPDATE users SET password='$c_newpass' WHERE username='$user'");
                        if ($change) {
                            echo "<span class='alert alert-success'>Your password is updated successfully</span>";
                        } else {
                            echo "<span class='alert alert-danger'>Sorry, some error occured :" . mysqli_error($con) . "</span>";
                        }
                    } else {
                        echo "<span class='alert alert-danger'>Password incorrect</span>";
                    }
                }
                break;
            }
        }

    /* -------------------------[[[[[[[[[[[[[[[[[[[[[[[        Admin rights              ]]]]]]]]]]]]]]]]]]]]]]]]]]-------------------------
     *                 All the utilities for main tables:: products, users, categories (Contact us table doesn't have any utilities)
     */


    //----------------------------------------This block of code is used for every pop-up form------------------------------------------
    case 'loadForm': {

            if(!isset($_GET['subtype']) && !isset($_SESSION['admin']['table'])){
                echo "Sorry, some error occured";
                break;
            }
            //this variable has the value of the table to be displaed
            if(isset($_GET['subtype']))
                    $type=$_GET['subtype'];
            else
                $type = $_SESSION['admin']['table'];
            
            switch ($type) {

                case 'products': {

                        //-------------------Pop up form for inserting new item--------------------------
                        if (isset($_GET['form_item']) && $_GET['form_item'] === '1') {

                            echo '<div id="updateRes"></div>' //this is to display messages from backend when form is submitted.
                            . '<h2>INSERT ITEM</h2>'
                            . '<form id="myInsert" name="myInsert" method="POST" enctype="multipart/form-data"> 
                                
                            <div class="form-group">
                                <label for="name">Product Name:</label> 
                                <input type="text" required maxlength="30" class="form-control input-field" name="name">
                            </div>
                            
                             <div class="form-group">
                                <label for="image">Product Image:</label>
                                <input type="file" required name="fileToUpload" id="fileToUpload" multiple="">
                                <div id="thumb">'  //to display demo of the image uploaded
                            . '</div>
                            </div>
                            
                            <div>
                                <label for="descrip" >Description:</label>
                                <textarea required rows="3" cols="30" name="descrip"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="price">Price:</label> 
                                <input type="number" required maxlength="5" min="0" class="form-control input-field" name="price">
                                </div>
                            
                            <div class="form-group">
                                <label for="stock">Stock:</label> 
                                <input type="number"required maxlength="5" min="0" class="form-control input-field" name="stock">
                            </div>
                            
                            <div class="form-group">
                                <label for="price">Category:</label> 
                                <select name="cat" required>'   //list of existing categories.....user can only select from th given list
                            . '<option></option>';

                            $result = mysqli_query($con, "SELECT * FROM categories");
                            while ($field = mysqli_fetch_array($result)) {
                                echo "<option value='" . $field['name'] . "'>" . $field['name'] . "</option>";
                            }

                            echo '</select>
                            </div>'
                            //button for submitting the form
                            . '<button type="button" id="insert_item" name="insert_item" class="btn btn-primary" value="submit">Submit</button>
                            </form>';
                            exit;
                        }


                        //-----------------------Pop up form for updating existing items----------------------------
                        else {
                            if(!isset($_GET['id'])){
                                echo "Sorry, some error occured";
                                break;
                            }
                            $id = $_GET['id'];
                            $record = mysqli_query($con, "SELECT * FROM products WHERE product_id=$id");
                            //print_r($record);
                            if (count($record) == 1) {
                                $n = mysqli_fetch_array($record);
                                $name = $n['product_name'];
                                $price = $n['price'];
                                $stock = $n['stock'];
                            }
                            echo '<div id="updateResponse"></div><h2>UPDATE RECORD</h2><br><h4>' . $name . '</h4>
                    <form id="myform" method="post" action="">
                        
                        <div class="form-group">
                         <label for="price">Price:</label> 
                                <input type="number" maxlength="5" min="0" class="form-control input-field" name="price" value="' . $price . '">
                        
                        </div>
                        <div class="form-group">
                         <label for="stock">Stock:</label> 
                                <input type="number" maxlength="5" min="0" class="form-control input-field" name="stock" value="' . $stock . '">
                        
                        </div>
                        <button type="button" name="submit" id="update" class="btn btn-primary" value="submit">Submit</button>
                        
                    </form> ';
                        }
                        break;
                    }

                /* ------------------------------------------------------------------------------
                 * -----------------------Pop up form for updating users' data (except password)
                 */
                case 'form_db': {
                    if(!isset($_GET['id'])){
                        echo "Sorry, some error occured";
                        break;
                    }
                        $id = $_GET['id'];
                        $record = mysqli_query($con, "SELECT * FROM form_db WHERE id=$id");
                        //print_r($record);
                        //we need to display old values of the fields.
                        if (count($record) == 1) {
                            $n = mysqli_fetch_array($record);
                            $id = $n['id'];
                            $firstname = $n["firstname"];
                            $lastname = $n["lastname"];
                            $date = $n["dob"];
                            $mobile = $n['mobile'];
                            $email = $n["email"];
                            $add = $n['address'];
                            $city = $n['city'];
                            $pin = $n['pin'];
                            $state = $n['state'];
                            $country = $n['country'];
                            $gender = $n['gender'];
                            $course = $n['course'];

                            $hobby = explode(',', $n['hobby']); //makes array from string
                        }
                        echo '<div id="updateResponse"></div><h2>UPDATE RECORD</h2>
                    <form id="myform" method="post" action="">

                        <div class="form-group">
                            <label for="firstname">First Name:</label>
                            
                                <input type="text" required maxlength="30" class="form-control input-field" name="firstname" value="' . $firstname . '">
                           
                        </div>
                        <div class="form-group">
                            <label for="lastname">Last Name:</label>
                           
                                <input type="text" required maxlength="30" class="form-control" name="lastname" value="' . $lastname . '">
                           
                        </div>
                        <div>
                            <label for="dob">Date of birth:</label>
                            
                                <input type="date" required name="datepick" value="' . $date . '">
                           
                        </div>
                        <div>
                            <label for="gender">Gender:</label>
                           
                                <label class="radio-inline"><input type="radio" name="gender" value="m"';
                        if (isset($gender) && $gender == "m") {
                            echo "checked";
                        }
                        echo '>Male</label>
                                <label class="radio-inline"><input type="radio" name="gender" value="f"';

                        if (isset($gender) && $gender == "f") {
                            echo "checked";
                        }
                        echo '>Female</label>
                            
                        </div>
                        <div class="form-group">
                            <label for="email">Email address:</label>
                            
                                <input type="email" required maxlength="30" class="form-control" id="email" name="email" value="' . $email . '">
                            
                        </div>

                                               
                        <div class="form-group">

                            <label for="mobile">Mobile Number:</label>
                           
                                <input type="number" required maxlength="10" minlength="10" class="form-control" name="mobile" value="' . $mobile . '">
                            
                        </div>
                        <div class="form-group">
                            <label for="msg">Address:</label>
                           
                                <textarea class="form-control" rows="3" id="add" name="add">' . $add . '</textarea>
                            
                        </div>
                        <div class="form-group">
                            <label for="city">City:</label>
                           
                                <input type="text" required maxlength="30" class="form-control" name="city" value="' . $city . '">
                            
                        </div>
                        <div class="form-group">

                            <label for="pin">PIN:</label>
                           
                                <input type="text" required maxlength="6" minlength="6" class="form-control" name="pin" value="' . $pin . '">
                            
                        </div>
                        <div class="form-group">
                            <label for="state">State:</label>
                            
                                <input type="text" required maxlength="30" class="form-control" name="state" value="' . $state . '">
                            
                        </div>
                        <div class="form-group">
                            <label for="country">Country:</label>
                           
                                <input type="text" required maxlength="30" class="form-control" name="country" value="' . $country . '">
                            
                        </div>

                        <div class="form-group">
                            <label for="hobby" class="check-box inline">Hobbies:</label>
                           
                                <span class="checkbox-inline">
                                    <input type="checkbox" name="hobby[]" value="draw"';
                        if ($hobby != '' && in_array('draw', $hobby)) {
                            echo "checked";
                        }
                        echo '>Drawing</span>
                                <span class="checkbox-inline"><input type="checkbox" name="hobby[]" value="sing"';
                        if ($hobby != '' && in_array('sing', $hobby)) {
                            echo "checked";
                        }
                        echo '>Singing</span>
                                <span class="checkbox-inline"><input type="checkbox" name="hobby[]" value="dance"';
                        if ($hobby != '' && in_array('dance', $hobby)) {
                            echo "checked";
                        }
                        echo '>Dancing</span>
                                <span class="checkbox-inline"><input type="checkbox" name="hobby[]" value="sketch"';

                        if ($hobby != '' && in_array('sketch', $hobby)) {
                            echo "checked";
                        }
                        echo '>Sketching</span>
                                <span class="checkbox-inline"><input id="other" type="checkbox" name="hobby[]" value="other"';

                        if ($hobby != '' && in_array('other', $hobby)) {
                            echo "checked";
                        }
                        echo '>Other</span><div id="othertext"></div>
                            
                        </div>
                        <div>
                            <label for="course">Course:</label>
                            
                                <label class="radio-inline"><input type="radio" required name="course" value="bca"';

                        if (isset($course) && $course == "bca") {
                            echo "checked";
                        }
                        echo '>BCA</label>
                                <label class="radio-inline"><input type="radio" required name="course" value="bcom"';

                        if (isset($course) && $course == "bcom") {
                            echo "checked";
                        }
                        echo '> B.com</label>
                                <label class="radio-inline"><input type="radio" required name="course" value="bsc"';

                        if (isset($course) && $course == "bsc") {
                            echo "checked";
                        }
                        echo '>B.Sc</label>
                                <label class="radio-inline"><input type="radio" required name="course" value="ba"';

                        if (isset($course) && $course == "ba") {
                            echo "checked";
                        }
                        echo '>B.A</label>
                            
                        </div>       
                        <button type="button" name="submit" id="update" class="btn btn-primary" value="submit">Submit</button>
                        
                    </form> ';
                        break;
                    }
            }

            break;
        }

    //--------------------------------------Submission of form used to update particular record of a table--------------------------------------
    case 'update': {

            if(!isset($_GET['id']) || (!isset($_GET['subtype']) && !isset($_SESSION['admin']['table']) )){
                echo "Sorry, some error occured";
                break;
            }
            $id = $_GET['id'];
             if(isset($_GET['subtype']))
                    $type=$_GET['subtype'];
            else
                $type = $_SESSION['admin']['table'];
           //------------all of these are under admin rights

            switch ($type) {
                case 'products': {
                        $price = test_input($_POST['price']);
                        $stock = test_input($_POST['stock']);

                        if(!($price && $stock)){
                            echo "All the fields must be filled";
                            break;
                        }
                        $up = mysqli_query($con, "UPDATE products SET price='$price', stock='$stock' WHERE product_id='$id'");
                        if ($up) {
                            echo '<div class="alert alert-success">Record updated successfully</div>';
                        } else {
                            echo '<div class="alert alert-danger">ERROR: Could not able to execute $sql. ' . mysqli_error($con) . '</div>';
                        }
                        break;
                    }
                case 'form_db': {
                        $firstname = test_input($_POST["firstname"]);
                        $lastname = test_input($_POST["lastname"]);
                        $mobile = test_input($_POST['mobile']);
                        $email = test_input($_POST["email"]);
                        $add = test_input($_POST['add']);
                        $city = test_input($_POST['city']);
                        $pin = test_input($_POST['pin']);
                        $state = test_input($_POST['state']);
                        $country = test_input($_POST['country']);
                        //$pass = test_input($_POST['pass']); ---------we don't allow to change password( It can only be done by user )
                        $gender = $_POST['gender'];
                        $hobby = $_POST['hobby'];
                        $course = $_POST['course'];
                        $datepick = $_POST['datepick'];
                        $hobbyArr = '';
                        $i = 0;

                        if (!($firstname && $lastname && $mobile && $email && $add && $city && $pin && $state && $country  && $gender && $hobby && $course && $datepick)) {
                            echo "Alle the fields must be filled";
                            break;
                        }
                        foreach ($hobby as $i) {
                            $hobbyArr = $hobbyArr . ',' . $i;
                        }
                        if (in_array('other', $hobby)) {
                            $hobbyArr = $hobbyArr . ':' . $_POST['hobbytext'];
                        }

                        //update queries
                        //echo $id;
                        $sql = "UPDATE form_db SET firstname='$firstname', lastname='$lastname',dob='$datepick',gender='$gender',email='$email', mobile='$mobile',address='$add',city='$city',pin='$pin',state='$state',country='$country',hobby='$hobbyArr',course='$course' WHERE id=$id";
                        $sql2 = "UPDATE users SET username='$email' WHERE id='$id'";
                        if (mysqli_query($con, $sql) && mysqli_query($con, $sql2)) {
                            echo '<div class="alert alert-success">Record updated successfully</div>';
                        } else {
                            echo '<div class="alert alert-danger">ERROR: Could not able to execute $sql. ' . mysqli_error($con) . '</div>';
                        }
                        break;
                    }
            }
            break;
        }


    //------------------------------------------To delete a particular record for any table--------------------------------------
    case 'delete': {
            if(!isset($_GET['id']) || !isset($_SESSION['admin']['table'])){
                echo "Sorry, some error occured";
                break;
            }
            $id = $_GET['id'];
            $type = $_SESSION['admin']['table'];
            $sql = "DELETE FROM " . $type . " WHERE";

            //--product table has field: product_id; other tables have id
            if ($type == 'products')
                $sql .= " product_id='$id'";
            else
                $sql .= " id='$id'";
            $stat = mysqli_query($con, $sql);

            //---for user table, we also need to delete record for storing password from users' table
            if ($type == 'form_db')
                $stat2 = mysqli_query($con, "DELETE FROM users WHERE id=$id");
            else
                $stat2 = true;

            if ($stat && $stat2) {
                echo '<div class="alert alert-success">Record deleted successfully</div>';
            } else {
                echo '<div class="alert alert-danger">ERROR: Could not able to execute: ' . mysqli_error($con) . '</div>';
            }
            break;
        }


    //---------------------------------------To insert a new category--------------------------------------------
    case 'insert_category': {
            if(!isset($_GET['cat'])){
                echo "You must specify the category you want to add";
                break;
            }
            $name = test_input($_GET['cat']);

            $cate = mysqli_query($con, "INSERT INTO categories (name) VALUES ('$name')");
            if ($cate) {
                echo "<span class='alert alert-success'>Category is inserted successfully.</span>";
            } else {
                echo "<span class='alert alert-danger'>Error:" . mysqli_error($con) . "</span>";
            }
            break;
        }


    //---------------------------------------To insert a new item-------------------------------------------------
    case 'insert_item': {

            $name = test_input($_POST['name']);
            $descrip = test_input($_POST['descrip']);
            $price = test_input($_POST['price']);
            $stock = test_input($_POST['stock']);
            $cat = test_input($_POST['cat']);
            $count = 0;
            $error = "";
            if (!($name && $descrip && $price && $stock && $cat)) {
                $count++;
                $error .= '<br>All the fields must be filled';
            }
            $image_set = false;
            $target_dir = "images/products/";
            $imageErr = '';
            if (!isset($_FILES['fileToUpload'])) {
                $imageErr .= 'Image is required';
                $count++;
                $error .= '<br>' . $imageErr;
            } else if ($count == 0) {

                $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if ($check !== false) {
                    //$imageErr .= "<br>File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    $imageErr .= "<br> File is not an image.";
                    $error .= '<br>' . $imageErr;
                    $uploadOk = 0;
                }

                // Check if image file is an actual image or fake image

                if (file_exists($target_file)) {
                    $imageErr .= "<br> Sorry, file already exists.";
                    $uploadOk = 0;
                }
                // Check file size
                if ($_FILES["fileToUpload"]["size"] > 500000) {
                    $imageErr .= " <br>Sorry, your file is too large.";
                    $uploadOk = 0;
                }
                // Allow certain file formats
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    $imageErr .= " <br>Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    $imageErr .= " <br>Sorry, your file was not uploaded.";
                    // if everything is ok, try to upload file
                } else {
                    $image_set = true;

                    $im = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], '../images/products/'. basename($_FILES["fileToUpload"]["name"]));
                    if ($im) {
                        //echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
                        $image_set = true;

                        $item = mysqli_query($con, "INSERT INTO products (product_name,product_img,description,price,stock,category) VALUES ('$name','$target_file','$descrip','$price','$stock','$cat')");
                        if ($item) {
                            echo "<span class='alert alert-success'>Item is added successfully</span>";
                        } else {
                            echo "<span class='alert alert-danger'>Error: " . mysqli_error($con) . "</span>";
                        }
                    } else {
                        echo "<br>Sorry, there was an error uploading your file.";
                    }
                }
            }

            echo $error;
            echo $imageErr;
            break;
        }
}