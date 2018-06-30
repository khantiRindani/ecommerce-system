<?php
    
session_start();

$root = $_SERVER['DOCUMENT_ROOT'];
include_once($root."/khanti/project/includes/constants.php");
include_once($root."/khanti/project/includes/connect.php");


// Include the autoloader provided in the SDK
require_once __DIR__.'/php-graph-sdk/autoload.php';
$fb = new \Facebook\Facebook([
    'app_id' => FBAPP_ID,
    'app_secret' => FBAPP_SECRET,
    'default_graph_version' => 'v2.10',
]);

$helper = $fb->getRedirectLoginHelper();

try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (!isset($accessToken)) {
  if ($helper->getError()) {
    header('HTTP/1.0 401 Unauthorized');
    echo "Error: " . $helper->getError() . "\n";
    echo "Error Code: " . $helper->getErrorCode() . "\n";
    echo "Error Reason: " . $helper->getErrorReason() . "\n";
    echo "Error Description: " . $helper->getErrorDescription() . "\n";
  } else {
    header('HTTP/1.0 400 Bad Request');
    echo 'Bad request';
  }
  exit;
}

//logged in
echo '<h3>Access Token</h3>';
var_dump($accessToken->getValue());

// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
echo '<h3>Metadata</h3>';
var_dump($tokenMetadata);

// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId(FBAPP_ID); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();

if (!$accessToken->isLongLived()) {
  // Exchanges a short-lived access token for a long-lived one
  try {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
  } catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo "<p class='alert alert-danger'>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
    exit;
  }

  echo '<h3>Long-lived</h3>';
  var_dump($accessToken->getValue());
}

$_SESSION['fb_access_token'] = (string) $accessToken;


 try {
        $profileRequest = $fb->get('/me?fields=name,first_name,last_name,email,birthday,link,gender,locale,cover,picture',$accessToken);
        $fbUserProfile = $profileRequest->getGraphNode()->asArray();
    } catch(FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        session_destroy();
        // Redirect user back to app login page
        header("Location:".PATH."users/login.php");
        exit;
    } catch(FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }

    
    // Insert or update user data to the database
    
        $oauth_provider='facebook';
        $oauth_uid= $fbUserProfile['id'];
        $first_name = $fbUserProfile['first_name'];
        $last_name = $fbUserProfile['last_name'];
        $email = $fbUserProfile['email'];
        $gender  = 'm';//$fbUserProfile['gender'];
        $dob ='';// $fbUserProfile['birthday'];
        $street  = '';//$fbUserProfile['street'];
        $city  = '';//$fbUserProfile['city'];
        $country  = '';//$fbUserProfile['country'];
        $pin  = '';//$fbUserProfile['zip'];
       
    $_SESSION['username']=$email;
   
    // Get logout url
    $logoutURL = $helper->getLogoutUrl($accessToken, PATH.'users/logout.php');

if(mysqli_num_rows(mysqli_query($con, "SELECT * FROM form_db WHERE email='$email'"))==0){
    
    $ins=mysqli_query($con,"INSERT INTO form_db (firstname, lastname, email) VALUES ('$first_name','$last_name','$email')");
    
    if(!$ins){
        echo 'Insertion failed: '. mysqli_error($con);
        exit;
    }
}

header("Location:".PATH);
