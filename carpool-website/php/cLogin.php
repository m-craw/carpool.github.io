<?php
$DEBUG_INFO=0;
error_reporting(0);

require_once("DBConn.php");
require_once("ModelLogin.php");

function debugMsg($debugLevel,$pMsg)
{
	if ($debugLevel == 1)
	{
		print "$pMsg<br>";
	}
}

session_start();
$db    = new DBConn();
$conn  = $db->connect();
$model_login = new ModelLogin();


$target=""; //create or login
$action=""; //create,login, login_facebook, login_gmail, login_twitter

// <input type="hidden" name="target" value="login">
// <input type="hidden" name="action" value="create">

// $id=0;
// $routeId="";
// $messageId="";
// $message="";
// $threadName="";
// $isError="0";

# check sessions before post
// $id = $_SESSION['id'];
// $target = $_SESSION['target'];
// $action = $_SESSION['action'];

$target="";
$action="";
$login_email="";
$login_password="";
$fb_email="";
$fb_first_name="";
$fb_last_name="";
$google_email="";
$google_first_name="";
$google_last_name="";
$twitter_email="";
$twitter_first_name="";
$twitter_last_name="";
$create_email="";
$create_password="";
$create_first_name="";
$create_last_name="";
$create_drivers_license="";
$create_month="";
$create_day="";
$create_year="";


# Set local variables from url values
if (isset($_POST['target']) )
{
	$target = strtolower($_POST['target']);
}
if (isset($_POST['action']) )
{
	$action = strtolower($_POST['action']);
}
if (isset($_POST['login_email']) )
{
	$login_email =$_POST['login_email'];
}
if (isset($_POST['login_password']) )
{
	$login_password =$_POST['login_password'];
}
if (isset($_POST['fb_email']) )
{
	$fb_email =$_POST['fb_email'];
}
if (isset($_POST['fb_first_name']) )
{
	$fb_first_name =$_POST['fb_first_name'];
}
if (isset($_POST['fb_last_name']) )
{
	$fb_last_name =$_POST['fb_last_name'];
}
if (isset($_POST['google_email']) )
{
	$google_email =$_POST['google_email'];
}
if (isset($_POST['google_first_name']) )
{
	$google_first_name =$_POST['google_first_name'];
}
if (isset($_POST['google_last_name']) )
{
	$google_last_name =$_POST['google_last_name'];
}
if (isset($_POST['twitter_email']) )
{
	$twitter_email =$_POST['twitter_email'];
}
if (isset($_POST['twitter_first_name']) )
{
	$twitter_first_name =$_POST['twitter_first_name'];
}
if (isset($_POST['twitter_last_name']) )
{
	$twitter_last_name =$_POST['twitter_last_name'];
}
if (isset($_POST['create_email']) )
{
	$create_email = strtolower($_POST['create_email']);
}
if (isset($_POST['create_password']) )
{
	$create_password = strtolower($_POST['create_password']);
}
if (isset($_POST['create_first_name']) )
{
	$create_first_name =$_POST['create_first_name'];
}
if (isset($_POST['create_last_name']) )
{
	$create_last_name =$_POST['create_last_name'];
}
if (isset($_POST['create_drivers_license']) )
{
	$create_drivers_license =$_POST['create_drivers_license'];
}
if (isset($_POST['create_month']) )
{
	$create_month =$_POST['create_month'];
}
if (isset($_POST['create_day']) )
{
	$create_day =$_POST['create_day'];
}
if (isset($_POST['create_year']) )
{
	$create_year =$_POST['create_year'];
}



debugMsg($DEBUG_INFO,"target($target) action($action)");

# Main logic
if ($target == "login" && $action == "login")
{
	$validation_result = $model_login->validate_login($conn, $login_email, $login_password);

	if($validation_result) {
		$model_login->go_to_carpool_page($login_email);
	}
	else {
		$model_login->show_login_error_page();
	}
}
elseif ($target == "login" && $action == "create")
{
	$validation_result = $model_login->create_account($conn, $create_email, $create_password, $create_first_name, $create_last_name, $create_drivers_license, $create_month, $create_day, $create_year);

	if($validation_result) {
		$model_login->go_to_carpool_page($create_email);
	}
	else {
		$model_login->show_create_account_error_page();
	}
}
elseif ($target == "login" && $action == "fb_login")
{
	$validation_result = $model_login->validate_login_facebook($conn, $fb_email);

	if($validation_result) {
		$model_login->go_to_carpool_page($fb_email);
	}
	else {
		$model_login->create_account_facebook($conn, $fb_email, $fb_first_name, $fb_last_name);
		$model_login->go_to_carpool_page($fb_email);
	}
}
elseif ($target == "login" && $action == "google_login")
{
	$validation_result = $model_login->validate_login_google($conn, $google_email);

	if($validation_result) {
		$model_login->go_to_carpool_page($google_email);
	}
	else {
		$model_login->create_account_google($conn, $google_email, $google_first_name, $google_last_name);
		$model_login->go_to_carpool_page($google_email);
	}
}
elseif ($target == "login" && $action == "twitter_login")
{
	$validation_result = $model_login->validate_login_google($conn, $twitter_email);

	if($validation_result) {
		$model_login->go_to_carpool_page($twitter_email);
	}
	else {
		$model_login->create_account_twitter($conn, $twitter_email, $twitter_first_name, $twitter_last_name);
		$model_login->go_to_carpool_page($twitter_email);
	}
}

?>