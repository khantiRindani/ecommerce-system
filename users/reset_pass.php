<?php
if(!isset($_SESSION)){
    session_start();
}
include("../includes/header.php");
?>
<html> 
    <head>
        <title>Reset Password</title>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
        <script src='https://www.google.com/recaptcha/api.js'></script>	
        <style>
            .error{
                color:red;
            }
        </style>
        <script>
            $(function () {
                
                /****************************************jquery validatins************************************************/
                $("#reset").validate({
                    errorElement: "p",
                    errorPlacement: function (error, element) {
                        error.appendTo(element.parent());
                    },
                    rules: {
                        pass:"required",
                        newpass:{
                            required:true,
                            minlength:6
                        },
                        duppass:{
                            required:true,
                            equalTo:"#newpass"
                        },
                        'g-recaptcha-response':"required"
                    },
                    messages:{
                         pass:"Please enter your old password",
                        newpass:{
                            required:"Please enter a new password",
                            minlength:"Password should be atleast 6 characters long"
                        },
                        duppass:{
                            required:"Please confirm your password",
                            equalTo:"Password doesn't match"
                        },
                        'g-recaptcha-response':"Please verify captcha"
                    },
                    submitHandler:function(){
                         var data = new FormData();

                        var form_data = $('#reset').serializeArray();
                        $.each(form_data, function (key, input) {
                            data.append(input.name, input.value);
                        });

                        //..................................ajax submit...............................................
                        $.ajax({
                            type: "POST",
                            url: "<?php echo PATH;?>includes/common-ajax.php?type=reset",
                            data: data,
                            processData: false,
                            contentType: false,
                            //enctype: 'multipart/form-data',
                            cache: false,

                            beforeSend: function () {
                                $('#response').focus();
                                $('#response').html('<span class="text-info">Loading response...</span>');
                            },
                            success: function (data) {
                                $('#response').focus();
                                $('#response').html('<span class="text-info" style="color:red">' + data + '</span>');
                                
                            },
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
                    <h2>RESET PASSWORD</h2>
                    <!------------------------------------form------------------------------------>
                    <form method="post" id="reset" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        
                        <div class='form-group'>
                            <label for="pass">Old Password</label>
                            <input type='password' class="form-control" name="pass"/>
                           
                        </div>
                        <div class='form-group'>
                            <label for="pass">New Password</label>
                            <input type='password' class="form-control" id="newpass" name="newpass"/>
                           
                        </div>
                        <div class='form-group'>
                            <label for="pass">Confirm Password</label>
                            <input type='password' class="form-control" name="duppass"/>
                           
                        </div>
                        <div class="g-recaptcha responsive" data-sitekey="<?php echo CAPTCHA_SITEKEY;?>"></div>
                        
                        
                        <button type='submit' class='btn btn-primary' name='submit'>Reset</button>
                        
                    </form>
                </div>
            </div>
        </div>
        <?php include("../includes/footer.php");?>
    </body>
</html>

