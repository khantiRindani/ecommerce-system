<?php
    session_start();

$_SESSION['page'] = 'login';
include("../includes/header.php");

$root = $_SERVER['DOCUMENT_ROOT'];
include_once($root."/khanti/project/includes/constants.php");

require_once __DIR__ . '/fb/php-graph-sdk/autoload.php';

$fb = new \Facebook\Facebook([
    'app_id' => FBAPP_ID,
    'app_secret' => FBAPP_SECRET,
    'default_graph_version' => 'v2.10',
]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('http://localhost/khanti/project/users/fb/fbCallback.php', $permissions);
$output='<a href="' . htmlspecialchars($loginUrl) .'" class="fb btn">
                        <i class="fa fa-facebook fa-fw"></i> Login with Facebook
                    </a>'; 

?>
<html> 
    <head>
        <title>Log In</title>
        <meta name="google-signin-client_id" content="734589888833-1naqaihio3tne3f7hbnsddlj3g8iean2.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
       
       <script src='https://www.google.com/recaptcha/api.js'></script>	
      
        <style>
            .error{
                color:red;
            }
            .fb,.twitter,.google{
                color: white;
                margin-bottom:10px;
                width:170px;
            }
            
            .fb {
                background-color: #3B5998;
            
            }

            .twitter {
                background-color: #55ACEE;
          
            }

            .google {
                background-color: #dd4b39;
               
            }
            *{
                box-sizing: border-box;
            }
            
            .vl {
                position: absolute;
                margin-top: 100px;
                margin-left: 125px;
                left:50%;
                transform: translate(-50%);
                border: 2px solid #ddd;
                height: 175px;
              }


            /* text inside the vertical line */
            .vl-innertext {
                position: absolute;
                top: 50%;
                
                transform: translate(-50%, -50%);
                background-color: #f1f1f1;
                border: 1px solid #ccc;
                border-radius: 50%;
                padding: 8px 10px;
              }
              @media screen and (max-width: 650px) {
                  .vl{
                      display: none;
                  }
              }
        </style>
        <script>
            
            function onSignIn(googleUser) {
                var id_token = googleUser.getAuthResponse().id_token;

                var xhr = new XMLHttpRequest();
                xhr.open('POST', '/khanti/project/users/google/glCallback.php');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                  console.log('Signed in as: ' + xhr.responseText);
                };
                xhr.send('idtoken=' + id_token);
                  var profile = googleUser.getBasicProfile();
                  console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
                  console.log('Name: ' + profile.getName());
                  console.log('Image URL: ' + profile.getImageUrl());
                  console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.
                }

            $(function () {
                $("#login").validate({
                    errorElement: "p",
                    errorPlacement: function (error, element) {
                        error.appendTo(element.parent());
                    },
                    rules: {
                        username: "required",
                        pass: "required",
                        'g-recaptcha-response': "required"
                    },
                    messages: {
                        username: "Please enter your username",
                        pass: "Please enter your password",
                        'g-recaptcha-response': "Please verify captcha"
                    },
                    submitHandler: function () {
                        var data = new FormData();

//Form data
                        var form_data = $('#login').serializeArray();
                        $.each(form_data, function (key, input) {
                            data.append(input.name, input.value);
                        });

                        $.ajax({
                            type: "POST",
                            url: "<?php echo PATH; ?>/includes/common-ajax.php?type=login",
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
                                $('#response').html('<span class="text-info" style="color:red">' + data + '</span>');
                                var url = "../index.php";
                                //$(location).attr('href', url);
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
                <div class="col-md-3 col-md-offset-3">
                    <div id="response"></div>
                    <h2>LOG IN</h2>

                    <form method="post" id="login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class='form-group'>
                            <label for="username">Username:</label>
                            <input type='text' class="form-control" name="username" placeholder="username/email"/>

                        </div>
                        <div class='form-group'>
                            <label for="pass">Password</label>
                            <input type='password' class="form-control" name="pass"/>

                        </div>
                        <div>
                            <label for="remember">Remember me</label>
                            <input type="checkbox" name="remember" <?php
                            if (isset($_COOKIE["user_login"])) {
                                echo 'checked';
                            }
                            ?>>
                        </div>
                        <div class="g-recaptcha responsive" data-sitekey="6LfkBVwUAAAAAPoofODNV49U7kHpNhgReSsfljoI"></div>


                        <button type='submit' class='btn btn-primary' name='submit'>Log In</button>

                        <div>
                            <label for="signup">Don't have an account?</label>
                            <a href="register.php">Sign Up</a>
                        </div>
                    </form>
                </div>

                <div class="vl">
                    <span class="vl-innertext">or</span>
                </div>

                <div class="col-md-1 col-md-offset-2" style="margin-top:150px">
                    <?php echo $output;?>
                    <a href="#" class="twitter btn">
                        <i class="fa fa-twitter fa-fw"></i> Login with Twitter
                    </a>
                    <div class="g-signin2" data-onsuccess="onSignIn" data-theme='dark'></div>

                    <a class="google btn" data-onsuccess="onSignIn">
                        <i class="fa fa-google fa-fw"></i> Login with Google+
                    </a>
                    <a href="#" onclick="signOut();">Sign out</a>
<script>
  function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
      console.log('User signed out.');
    });
  }
</script>

                </div>
            </div>
        </div>

<?php include("../includes/footer.php"); ?>
    </body>
</html>

