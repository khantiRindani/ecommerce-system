<?php
$name = $email = $subject = $msg = '';
if (!isset($_SESSION)) {
    session_start();
}
$_SESSION['page'] = 'contact';
include("../includes/header.php");
include("../includes/connect.php");
if(isset($_SESSION['username'])){
    $email=$_SESSION['username'];
    $res=mysqli_query($con, "SELECT * FROM form_db WHERE email='$email'");
    $field= mysqli_fetch_array($res);
    $name=$field['firstname']." ".$field['lastname'];
}
?>
<html> 
    <head>
        <title>Contact us</title>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
         <style>
            .error{
                color:red;
            }
        </style>
        <script>
            $(function () {
                
                    $("#myform").validate({
                    errorElement: "p",
                    errorPlacement: function (error, element) {
                        error.appendTo(element.parent());
                    },
                    rules: {
                        fname:"required",
                        email:"required",
                        subject:"required",
                        msg:"required"
                    },
                    messages:{
                        fname:"Please enter your name",
                        email:"Please enter your email",
                        subject:"Please enter subject",
                        msg:"Please enter your message"
                        
                    },
                    submitHandler:function(e){
                       
                        //e.preventDefault();
                       
                        var data = new FormData();

//Form data
                        var form_data = $('#myform').serializeArray();
                        $.each(form_data, function (key, input) {
                            data.append(input.name, input.value);
                        });

                        $.ajax({
                            type: "POST",
                            url: "<?php echo PATH;?>/includes/common-ajax.php?type=contact",
                            data: data,
                            processData: false,
                            contentType: false,
                            enctype: 'multipart/form-data',
                            cache: false,

                            beforeSend: function () {
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
        <div class="container-fluid" style="overflow: hidden">
            <div class="row" >
                <div class="col-md-3 col-md-offset-2">

                    <div id="map" style="width:100%;height: 500px;margin-top: 20px"></div>

                    <script>
                        function myMap() {
                            var mapCanvas = document.getElementById("map");
                            var mapOptions = {
                                center: new google.maps.LatLng(22.27, 70.68),
                                zoom: 10
                            };
                            var map = new google.maps.Map(mapCanvas, mapOptions);
                        }
                    </script>
                    <script src="<?php echo 'https://maps.googleapis.com/maps/api/js?key='.MAP_KEY.'"';?>></script>


                </div>
                <div class="col-md-4 col-md-offset-1">
                    <form id="myform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                        <h3>Contact Us</h3>

                        <div id="response"></div>
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" maxlength="30" class="form-control" name="fname" placeholder="firstname lastname" value="<?php echo $name;?>" <?php if($name){ echo "disabled";}?>/>
                        </div>

                        <div class="form-group">
                            <label for="email">Email address:</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $email;?>" <?php if($email){ echo "disabled";}?> />
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject:</label>
                            <input type="text" class="form-control" name="subject" id="subject" />
                        </div>
                        <div class="form-group">
                            <label for="msg">Message:</label>
                            <textarea class="form-control" rows="5" id="msg" name="msg"></textarea>
                        </div>

                        <button type="submit" id='submit' name="submit" class="btn btn-primary">Submit</button>
                    </form> 
                </div>
               
            </div>
        </div>
        <?php include("../includes/footer.php"); ?>

    </body>
</html>