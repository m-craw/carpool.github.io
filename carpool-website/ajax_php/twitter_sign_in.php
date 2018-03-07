<?php
session_start();
error_reporting(0);
$dir = realpath(__DIR__ . '/..').'/vendor/autoload.php';
require_once ($dir);
\Codebird\Codebird::setConsumerKey('AeqD1VXFLxni6wINS9AzYZhJv', '44fHeMU0GzeK5tcFjUhi8ESiqgDwU79UUqtsF9i0nc6IpF8EKI'); // static, see README


$cb = \Codebird\Codebird::getInstance();
if ($_SESSION['oauth_token'] == '' || !isset($_GET['oauth_verifier'])) {
  $reply = $cb->oauth_requestToken([
    'oauth_callback' => 'http://carpool.dev/login.php'
  ]);
  $_SESSION['oauth_token'] = $reply->oauth_token;
  $_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;
  $_SESSION['oauth_verify'] = true;
  $cb->setToken($reply->oauth_token, $reply->oauth_token_secret);
  $auth_url = $cb->oauth_authorize();
  echo $auth_url;
}
?>