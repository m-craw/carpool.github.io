<?php
$DEBUG_INFO=0;
error_reporting(0);

#include "app/config.php";
#include "app/detect.php";
require_once("DBConn.php");
require_once("ModelProfile.php");

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
$modelP= new ModelProfile();


$target="";
$action="";
$email="";


# Set local variables from url values
if (isset($_GET['target']) )
{
	$target = strtolower($_GET['target']);
}
if (isset($_GET['action']) )
{
	$action = strtolower($_GET['action']);
}
if (isset($_GET['email']) )
{
	$email =$_GET['email'];
}
if (isset($_GET['fname']) )
{
	$firstName =$_GET['fname'];
}
if (isset($_GET['lname']) )
{
	$lastName =$_GET['lname'];
}
if (isset($_GET['bday']) )
{
	$birthday =$_GET['bday'];
}
if (isset($_GET['pw']) )
{
	$pw =$_GET['pw'];
}

# Main logic
debugMsg($DEBUG_INFO,"Profile Page");
if ($target == "profile" && $action == "display")
{
	# Logic for retrieving the date the account was created
	debugMsg($DEBUG_INFO,"Profile Page - DISPLAY");
	$accountCreationDate = $modelP->getAccountCreationDate($conn, $email);
	$participatedAsDriverRidesList = $modelP->getParticipatedAsDriverRidesList($conn, $email);
	$participatedAsPassengerRidesList = $modelP->getParticipatedAsPassengerRidesList($conn, $email);
	$userRatingDriver = $modelP->getRating($conn, $email,'DRIVER');
	$userRatingPassenger = $modelP->getRating($conn, $email,'PASSENGER');
	if($accountCreationDate == '') {
		echo 'error getting creation date';
	}
	else {
		include 'ProfileView.php';
	}
}
elseif ($target == "profile" && $action == "save")
{
	debugMsg($DEBUG_INFO,"Profile Page - SAVE");
	$modelP->updateProfile($conn,$email,$firstName,$lastName,$birthday,$pw);
	print "Profile data updated successfully.<br>";
}

?>
