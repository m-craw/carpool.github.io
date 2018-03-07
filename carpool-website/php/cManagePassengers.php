<?php 
$DEBUG_INFO=1;

require_once("DBConn.php");
require_once("ModelMyRides.php");
require_once("ModelMyRides2.php");

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

$db    = new DBConn();
$conn  = $db->connect();
$model = new ModelMyRides();
$model2= new ModelMyRides2();

$target="";
$action="";
$id="";
$routeId="";
$messageId="";
$message="";
$threadName="";
$isError="0";

# Filters
$referrer = $_SERVER['HTTP_REFERRER'];
$agent    = $_SERVER['HTTP_USER_AGENT'];
$remoteIp = $_SERVER['REMOTE_ADDR'];

# Set local variables from url values
if (isset($_GET['id']) )
{
	$id =$_GET['id'];
}
if (isset($_GET['route_id']) )
{
	$routeId =$_GET['route_id'];
}
if (isset($_GET['thread_name']) )
{
	$threadName =$_GET['thread_name'];
}
if (isset($_GET['message_id']) )
{
	$messageId =$_GET['message_id'];
}
if (isset($_GET['message']) )
{
	$message =$_GET['message'];
}
if (isset($_GET['ride_type']) )
{
	$rideType =$_GET['ride_type'];
}
if (isset($_GET['target']) )
{
	$target = strtolower($_GET['target']);
}
if (isset($_GET['action']) )
{
	$action = strtolower($_GET['action']);
}


// debugMsg($DEBUG_INFO,"target($target) action($action) id($id) route_id($routeId) thread name($threadName) ride type($rideType)");

# Main logic
if ($target == "route" && $action == "accept")
{
	$routeOwner     = $model->getRouteOwner($conn,$routeId);
	$messagePartner = $model2->getThreadOtherUser($conn,$routeId,$threadName,$id); // Other user on email thread
	debugMsg($DEBUG_INFO,"routeOwner($routeOwner) messagePartner($messagePartner)");

	if ($routeOwner == $id && $rideType == "OFFER")
	{
		// Driver owns ride and is adding a passenger
		$model2->addPassenger($conn,$routeId,$messagePartner);
		$model->saveMessage($conn,$threadName,$id,"You have been added to route_id=$routeId",$routeId);
	}
	elseif ($routeOwner == $id && $rideType== "REQUEST")
	{
		// Passenger owns ride and is accepting a driver
		$model2->changeRouteOwner($conn,$routeId,$messagePartner);
		
		$model2->addPassenger($conn,$routeId,$id);
		$routeOwner = $model->getRouteOwner($conn,$routeId);  // Get new route owner
		$model->saveMessage($conn,$threadName,$routeOwner        ,"You are now the driver for route_id=$routeId",$routeId);
		$model->saveMessage($conn,$threadName,$id,"You have been added to route_id=$routeId"    ,$routeId);
		$model2->changeThreadName($conn,$routeId,$threadName,$id);
	}
}
elseif ($target == "route" && $action == "decline")
{
	$routeOwner = $model->getRouteOwner($conn,$routeId);
	$messagePartner = $model2->getThreadOtherUser($conn,$routeId,$threadName,$id); // Other user on email thread

	if ($routeOwner == $id && $rideType == "OFFER")
	{
		// Driver owns ride and is removing a passenger
		$model2->removePassenger($conn,$routeId,$messagePartner);
		$model->saveMessage($conn,$threadName,$id,"You have been removed from route_id=$routeId",$routeId);
	}
	elseif ($routeOwner != $id && $rideType == "OFFER")
	{
		// Passenger is dropping from a ride
		$model2->removePassenger($conn,$routeId,$id);
		$model->saveMessage($conn,$threadName,$id,"I have dropped from route_id=$routeId",$routeId);
	}
	
}
elseif ($target == "passenger_list" && $action == "view")
{
	$exists = $model2->checkPassengerList($conn,$routeId,$id);

	echo $exists;
	// include 'MessageView.php';
}
else
{
	debugMsg($DEBUG_INFO,"Error if reached this point.");
}

?>
