<?php
$DEBUG_INFO=0;
error_reporting(0);

#include "app/config.php";
#include "app/detect.php";
require_once("DBConn.php");
require_once("ModelEmail.php");

function debugMsg($debugLevel,$pMsg)
{
	if ($debugLevel == 1)
	{
		print "$pMsg<br>";
	}
}

function getData($url)
{
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

function removePunct($pString)
{
	$newStr = preg_replace('/[[:punct:]]/','',$pString);
	$newStr = preg_replace('/\s\s+/',' ',$newStr);
	return $newStr;
}
session_start();
$db    = new DBConn();
$conn  = $db->connect();
$modelE= new ModelEmail();


$target="";
$action="";
$id=0;
$email="";
$code="";
$pw="";
$nextAction="";

# Filters
$referrer = $_SERVER['HTTP_REFERRER'];
$agent    = $_SERVER['HTTP_USER_AGENT'];
$remoteIp = $_SERVER['REMOTE_ADDR'];

# Set local variables from url values
if (isset($_GET['id']) )
{
	$id =$_GET['id'];
}
if (isset($_GET['email']) )
{
	$email =$_GET['email'];
}
if (isset($_GET['code']) )
{
	$code =$_GET['code'];
}
if (isset($_GET['p']) )
{
	$password =$_GET['p'];
}
if (isset($_GET['target']) )
{
	$target = strtolower($_GET['target']);
}
if (isset($_GET['action']) )
{
	$action = strtolower($_GET['action']);
}



# Main logic
if ($target == "email" && $action == "request")
{
	# Logic for initial password reset screen
	$nextAction="reset";
}
elseif ($target == "email" && $action == "reset")
{
	# Once the user enters email and clicks Submit, a temp code is generated and emailed to them and the
	# password update screen is displayed
	$code = $modelE->generateCode($conn,$email);
	$message = "This is your temporary password: $code\r\n";
	$mail = mail($email,"CARPOOL: Temp Password",$message);
	// smtp($email,"CARPOOL: Temp Password",$message);
	debugMsg($DEBUG_INFO,"Email sent to email=$email with code=$code.");
	$nextAction="update";
}
elseif ($target == "email" && $action == "update")
{
	if ($modelE->verifyCode($conn,$email,$code))
	{
		$modelE->updatePassword($conn,$email,$code,$password);
		$nextAction="login";
		debugMsg($DEBUG_INFO,"email=$email password reset.");
	}
	else
	{
		$nextAction="error";
		debugMsg($DEBUG_INFO,"email=$email code not verified.");
	}
}
else
{
	$nextAction = "login";
}

debugMsg($DEBUG_INFO,"nextAction=$nextAction");

include 'ResetPasswordView.php';
?>
