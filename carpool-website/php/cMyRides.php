<?php
$DEBUG_INFO=0;
error_reporting(0);

require_once("DBConn.php");
require_once("ModelMyRides.php");
require_once("ModelMyRides2.php");
require_once("ModelRating.php");

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
$model = new ModelMyRides();
$model2= new ModelMyRides2();
$modelR = new ModelRating();


$target="";
$action="";
$id=0;
$routeId="";
$messageId="";
$message="";
$threadName="";
$isError="0";

# check sessions before post
$id = $_SESSION['id'];
$target = $_SESSION['target'];
$action = $_SESSION['action'];

# Filters
// $referrer = $_SERVER['HTTP_REFERRER'];
// $agent    = $_SERVER['HTTP_USER_AGENT'];
// $remoteIp = $_SERVER['REMOTE_ADDR'];

# Set local variables from url values
if (isset($_POST['id']) )
{
	$id =$_POST['id'];
}
if (isset($_POST['route_id']) )
{
	$routeId =$_POST['route_id'];
}
if (isset($_POST['thread_name']) )
{
	$threadName =$_POST['thread_name'];
}
if (isset($_POST['message_id']) )
{
	$messageId =$_POST['message_id'];
}
if (isset($_POST['message']) )
{
	$message =$_POST['message'];
}
if (isset($_POST['target']) )
{
	$target = strtolower($_POST['target']);
}
if (isset($_POST['action']) )
{
	$action = strtolower($_POST['action']);
}


debugMsg($DEBUG_INFO,"target($target) action($action)");

# Main logic
if ($target == "message" && $action == "list")
{
	$passengerNeedsRatingList = $modelR->checkPassengerNeedsRating($conn,$id);
	$driverNeedsRatingList = $modelR->checkDriverNeedsRating($conn,$id);

////////////////////


	$offeredRidesList   = $model->getOfferedRidesList($conn,$id);
	$requestedRidesList = $model->getRequestedRidesList($conn,$id);
	$offeredListNotOwned   = $model->getOfferedListNotOwned($conn,$id);
	$requestedListNotOwned = $model->getRequestedListNotOwned($conn,$id);
	$offeredListOwned   = $model->getOfferedListOwned($conn,$id);
	$requestedListOwned = $model->getRequestedListOwned($conn,$id);
	mysqli_close($conn);
	
	include '../myrides.php';
}
elseif ($target == "message" && $action == "create")
{
	$routeOwner = $model->getRouteOwner($conn,$routeId);

	include 'MessageView.php';
}
elseif ($target == "message" && $action == "reply")
{
	$message = $model->getMessage($conn,$messageId);

	include 'MessageReplyView.php';
}
elseif ($target == "message" && $action == "view")
{
	$message = $model->getMessage($conn,$messageId);

	include 'MessageView.php';
}
elseif ($target == "message" && $action == "save")
{
	# If threadName is blank then this is the first message of a thread so set it
	# to the username of the logged in user. Subsequent replied to a thread will have the 
	# threadName with it.
	if ($threadName == "")
	{
		$threadName = $id;
	}

	$model->saveMessage($conn,$threadName,$id,$message,$routeId);

}

?>
