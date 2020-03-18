<?php

echo 
require $_SERVER['DOCUMENT_ROOT']."/includes/conf/conf.php";
require_once DOC_ROOT.'/includes/functions.php';
$authorizeURL = 'https://api.vonage.com/authorize';
$tokenURL = 'https://api.vonage.com/token';
$apiURLBase = 'https://api.vonage.com';
$BaseAuth = base64_encode(OAUTH2_CLIENT_ID . ":" . OAUTH2_CLIENT_SECRET);
$RedirectURI = 'http://' . $_SERVER['SERVER_NAME']. $_SERVER['PHP_SELF']. "?action=token";
session_start();
// Start the login process by sending the user to Github's authorization page
if(get('action') == 'login') {
 echo 'login start';echo "<br>";
 // Generate a random hash and store in the session for security
 //$_SESSION['state'] = hash('sha256', microtime(TRUE).rand().$_SERVER['REMOTE_ADDR']);
 //unset($_SESSION['access_token']);

 $params = array(
   'client_id' =>$AppclientId ,
   'redirect_uri' => $RedirectURI,
   'scope' => 'openid',
   'response_type'=>'code'
 );

 header('Location: ' . $authorizeURL . '?' . http_build_query($params));
// die();
}
if(get('action') == 'token'){
// Exchange the auth code for a token
 $token = apiRequest($tokenURL, array(
   'client_id' => $AppclientId ,
   'client_secret' => $AppclientSecret,
   'redirect_uri' => $RedirectURI,
   'grant_type' => 'authorization_code',
   'code' => get('code'),
   'base_Auth' => $BaseAuth
 ));
//  $link = mysqli_connect("localhost", "root", "", "jay_acto");


 
 // Check connection
    $output = json_decode($token,true);
    echo "<pre>";print_R($output);exit;
    

    echo "access_token : ", $output["access_token"],"<br>";
    echo "refresh_token : ", $output["refresh_token"],"<br>";
    echo "scope : ", $output["scope"], "<br>";
    echo "id_token : ", $output["id_token"], "<br>";
    echo "token_type : ",$output["token_type"], "<br>";
    echo "expires_in : ", $output["expires_in"], "<br>";
    $access_token =$output["access_token"];
    // echo $access_token;
    $refresh_token =  $output["refresh_token"];
    $scope = $output["scope"];
    $id_token = $output["id_token"] ;
    $token_type = $output["token_type"] ;
    $expires_in =$output["expires_in"] ;
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jay_auto";
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO user (access_token, refresh_token, scope,id_token,token_type,expires_in) VALUES ('$access_token', '$refresh_token', '$scope','$id_token','$token_type','$expires_in')";

if ($conn->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

//  $_SESSION['access_token'] = $token->access_token;
}

if(session('access_token')) {
     echo "token";echo session('access_token');
} else {
 echo '<h3>Not logged in</h3>';
 echo '<p><a href="?action=login">Log In</a></p>';
}

function apiRequest($url, $post=FALSE, $headers=array()) {
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

 if($post)
   curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

 //$headers[] = 'Accept: application/json';
 $headers[] = 'ContentType: application/x-www-form-urlencoded';

// if(session('access_token'))
//   $headers[] = 'Authorization: Bearer ' . session('access_token');

 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

 $response = curl_exec($ch);

 return $response;
//  return json_encode($response);
}

function get($key, $default=NULL) {
 return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}

function session($key, $default=NULL) {
 return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}


