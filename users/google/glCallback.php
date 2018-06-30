<?php

session_start();

$root = $_SERVER['DOCUMENT_ROOT'];
include_once($root."/khanti/project/includes/constants.php");
include_once($root."/khanti/project/includes/connect.php");


// Include the autoloader provided in the SDK
require_once __DIR__.'/google-api-php/autoload.php';

// Get $id_token via HTTPS POST.
$id_token=$_POST['id_token'];
$client = new Google_Client(['client_id' => '734589888833-1naqaihio3tne3f7hbnsddlj3g8iean2.apps.googleusercontent.com']);  // Specify the CLIENT_ID of the app that accesses the backend
$payload = $client->verifyIdToken($id_token);
if ($payload) {
  $userid = $payload['sub'];
  // If request specified a G Suite domain:
  //$domain = $payload['hd'];
  $_SESSION['username']=$id_token;
} else {
  // Invalid ID token
}

