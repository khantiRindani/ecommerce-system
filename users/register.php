<?php
if (!isset($_SESSION)) {
    session_start();
}
$_SESSION['page'] = 'signup';
include("../includes/header.php");
$firstname = $lastname = $email = $mobile = $datepick = $add = $pin = $state = $country = $gender = $hobby = $city = $pass = $duppass = $course = '';

?>

<html> 
    <head>
        <title>Register</title>
       
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
        <style>
            .error{
                color:red;
            }
            .input-container {
                display: -ms-flexbox; /* IE10 */
                display: flex;
                width: 100%;
                margin-bottom: 15px;
            }
            .icon {
                padding: 10px;
                min-width: 50px;
                text-align: center;
                background: #222;
                color: yellowgreen;
            }

            .input-field {
                width: 100%;
                padding: 10px;
                outline: none;
                border-radius: 0px!important;
            }

        </style>
        <script>
            $(function () {
                $('#other').click(function(){
                    $('#othertext').html("<input class='form-control' name='hobbytext' id='hobbytext'>");
                });
                $("#register").validate({
                    errorElement: "p",
                    errorPlacement: function (error, element) {
                        error.appendTo(element.parent().parent());
                    },
                    rules: {
                        // The key name on the left side is the name attribute
                        // of an input field. Validation rules are defined
                        // on the right side
                        firstname: {
                            required: true,
                            maxlength: 30
                        },
                        lastname: {
                            required: true,
                            maxlength: 30
                        },
                        pass: {
                            required: true,
                            minlength: 6,
                            maxlength: 15
                        },
                        duppass: {
                            required: true,
                            equalTo: "#pass"
                        },
                        datepick: "required",
                        email: {
                            required: true,
                            // Specify that email should be validated
                            // by the built-in "email" rule
                            email: true
                        },
                        mobile: {
                            required: true,
                            number: true,
                            min: 1000000000,
                            max: 9999999999
                        },
                        gender: "required",
                        add: "required",
                        city: {
                            required: true,
                            maxlength: 30
                        },
                        pin: {
                            required: true,
                            number: true,
                            min: 100000,
                            max: 999999
                        },
                        state: {
                            required: true,
                            maxlength: 30
                        },
                        'hobby[]': "required",
                        hobbytext: {
                            required: "#other:checked"
                        },
                        
                        course: "required"
                    },

                    // Specify validation error messages
                    messages: {
                        firstname: {
                            required: "Please enter your firstname.",
                            maxlength: "Maximum 30 characters allowed."
                        },
                        lastname: {
                            required: "Please enter your lastname.",
                            maxlength: "Maximum 30 characters allowed."
                        },
                        pass: {
                            required: "Please enter a strong password",
                            minlength: "Minimum 6 characters needed",
                            maxlength: "Maximum 15 characters allowed."
                        },
                        duppass: {
                            required: "Please confirm your password",
                            equalTo: "Password doesn't match"
                        },
                        datepick: "Please fill your birthdate",
                        email: {
                            required: "Please enter your email address.",
                            email: "Not a valid email address."
                        },
                        mobile: {
                            required: "Please enter your mobile number.",
                            number: "Invalid.",
                            min: "Mobile number should be a 10 digit number.",
                            max: "Mobile number should be a 10 digit number."
                        },
                        gender: "Please select a gender.",
                        add: "Please enter your address.",
                        city: {
                            required: "Please enter your city.",
                            maxlength: "Maximum 30 characters allowed."
                        },
                        pin: {
                            required: "Please enter your pin code.",
                            number: "Invalid.",
                            min: "pin should be a 6 digit number.",
                            max: "pin should be a 6 digit number."
                        },
                        state: {
                            required: "Please enter your state.",
                            maxlength: "Maximum 30 characters allowed."
                        },
                        'hobby[]': "Please select atleast one hobby",
                        hobbytext: "You have to fill this field as you have slected 'others'",
                       
                        course: "Please select a course."
                    },
                    // Make sure the form is submitted to the destination defined
                    // in the "action" attribute of the form when valid
                    submitHandler: function () {
                        var data = new FormData();

                        //Form data
                        var form_data = $('#register').serializeArray();
                        $.each(form_data, function (key, input) {
                            data.append(input.name, input.value);
                        });
                        
                        $.ajax({
                            type: "POST",
                            url: "<?php echo PATH;?>/includes/common-ajax.php?type=signup",
                            data: data,
                            processData: false,
                            contentType: false,
                            enctype: 'multipart/form-data',
                            cache: false,

                            beforeSend: function () {
                                 $('#response').focus();
                                $('#response').html('<span class="text-info">Loading response...</span>');
                            },
                            success: function (data) {
                                $('#response').focus();
                                $('#response').html('<span class="text-info" style="color:red">' + data + '</span><p>');
                            },
//               
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.log(errorThrown);
                            }
                        });
                    }
                });
            });
        </script>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div id="response"></div>
                    <h2>SIGN UP</h2>
                    <form id='register' method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                        <div class="form-group">
                            <label for="firstname">First Name:</label>
                            <div class="input-container">
                                <i class='fa fa-user icon'></i>
                                <input type="text" class="form-control input-field" name="firstname" value="<?php echo $firstname; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lastname">Last Name:</label>
                            <div class="input-container">
                                <i class='fa fa-user icon'></i>
                                <input type="text" class="form-control" name="lastname" value="<?php echo $lastname; ?>">
                            </div>
                        </div>
                        <div>
                            <label for="dob">Date of birth:</label>
                            <div class="input-container">
                                <i class='fa fa-calendar icon'></i>
                                <input type="date" name="datepick" value="<?php echo $datepick; ?>">
                            </div>
                        </div>
                        <div>
                            <label for="gender">Gender:</label>
                            <div class="input-container">
                                <i class='fa fa-mars icon'></i>
                                <label class="radio-inline"><input type="radio" name="gender" value="m" <?php
if (isset($gender) && $gender == "m") {
    echo "checked";
}
?>>Male</label>
                                <label class="radio-inline"><input type="radio" name="gender" value="f" <?php
                                    if (isset($gender) && $gender == "f") {
                                        echo "checked";
                                    }
?>>Female</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email address:</label>
                            <div class="input-container">
                                <i class='fa fa-envelope icon'></i>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="pass">Password:</label>
                            <div class="input-container">
                                <i class='fa fa-unlock icon'></i>
                                <input type="password" class="form-control" id="pass" name="pass" value="<?php echo $pass; ?>">
                            </div>
                        </div>
                        <div class="form-group">

                            <label for="duppass">Confirm Password:</label>
                            <div class="input-container">
                                <i class='fa fa-lock icon'></i>
                                <input type="password" class="form-control" id="duppass" name="duppass" value="<?php echo $duppass; ?>">
                            </div>
                        </div>
                        <div class="form-group">

                            <label for="mobile">Mobile Number:</label>
                            <div class="input-container">
                                <i class='fa fa-phone icon'></i>
                                <input type="number" class="form-control" name="mobile" value="<?php echo $mobile; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="msg">Address:</label>
                            <div class="input-container">
                                <i class='fa fa-address-book icon'></i>
                                <textarea class="form-control" rows="3" id="add" name="add"><?php echo $add; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="city">City:</label>
                            <div class="input-container">
                                <i class='fa fa-address-card icon'></i>
                                <input type="text" class="form-control" name="city" value="<?php echo $city; ?>">
                            </div>
                        </div>
                        <div class="form-group">

                            <label for="pin">PIN:</label>
                            <div class="input-container">
                                <i class='fa fa-address-card icon'></i>
                                <input type="text" class="form-control" name="pin" value="<?php echo $pin; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="state">State:</label>
                            <div class="input-container">
                                <i class='fa fa-address-card icon'></i>
                                <input type="text" class="form-control" name="state" value="<?php echo $state; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="country">Country:</label>
                            <div class="input-container">
                                <i class='fa fa-globe icon'></i>
                                <input type="text" class="form-control" name="country" value="<?php echo $country; ?>">
                            </div>
                        </div>

                        <div class='form-group'>
                            <label for="hobby" class="check-box inline">Hobbies:</label>
                            <div class="input-container">
                                <i class='fa fa-paint-brush icon'></i>
                                <span class="checkbox-inline">
                                    <input type="checkbox" name="hobby[]" value="draw" <?php
                                    if ($hobby != '' && in_array('draw', $hobby)) {
                                        echo "checked";
                                    }
?>>Drawing</span>
                                <span class="checkbox-inline"><input type="checkbox" name="hobby[]" value="sing" <?php
                                    if ($hobby != '' && in_array('sing', $hobby)) {
                                        echo "checked";
                                    }
?>>Singing</span>
                                <span class="checkbox-inline"><input type="checkbox" name="hobby[]" value="dance" <?php
                                    if ($hobby != '' && in_array('dance', $hobby)) {
                                        echo "checked";
                                    }
?>>Dancing</span>
                                <span class="checkbox-inline"><input type="checkbox" name="hobby[]" value="sketch" <?php
                                    if ($hobby != '' && in_array('sketch', $hobby)) {
                                        echo "checked";
                                    }
?>>Sketching</span>
                                <span class="checkbox-inline"><input id='other' type="checkbox" name="hobby[]" value="other" <?php
                                    if ($hobby != '' && in_array('other', $hobby)) {
                                        echo "checked";
                                    }
                                    ?>>Other</span><div id="othertext"></div>
                            </div>
                        </div>
                        <div>
                            <label for="course">Course:</label>
                            <div class="input-container">
                                <i class='fa fa-graduation-cap icon'></i>
                                <label class="radio-inline"><input type="radio" name="course" value="bca" <?php
                                    if (isset($course) && $course == "bca") {
                                        echo "checked";
                                    }
?>>BCA</label>
                                <label class="radio-inline"><input type="radio" name="course" value="bcom" <?php
                                    if (isset($course) && $course == "bcom") {
                                        echo "checked";
                                    }
?>> B.com</label>
                                <label class="radio-inline"><input type="radio" name="course" value="bsc" <?php
                                    if (isset($course) && $course == "bsc") {
                                        echo "checked";
                                    }
?>>B.Sc</label>
                                <label class="radio-inline"><input type="radio" name="course" value="ba" <?php
                                    if (isset($course) && $course == "ba") {
                                        echo "checked";
                                    }
?>>B.A</label>
                            </div> 
                        </div>       
                        <button type="submit" name="submit" id='submit' class="btn btn-primary" value="submit">Submit</button>
                        <button type="reset" name="reset" class="btn btn-danger">Reset</button>
                    </form> 
                </div>
            </div>
        </div>
        <?php include("../includes/footer.php"); ?>
    </body>
</html>