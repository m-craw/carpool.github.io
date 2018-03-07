<?php 
$DEBUG_INFO=0;
error_reporting(0);

require_once("DBConn.php");
require_once("ModelMyRides.php");
require_once("ModelMyRides2.php");
require_once("ModelMyRides3.php");
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

debugMsg($DEBUG_INFO,"$0 - BEGIN");

$db    = new DBConn();
$conn  = $db->connect();
$model = new ModelMyRides();
$model2= new ModelMyRides2();
$model3= new ModelMyRides3();
$modelR= new ModelRating();

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
if (isset($_GET['status']) )
{
	$status = strtolower($_GET['status']);
}
if (isset($_GET['rating']) )
{
	$rating = $_GET['rating'];
}


debugMsg($DEBUG_INFO,"target($target) action($action) routeId($routeId) status($status)");

# Main logic
if ($target == "rating" && $action == "rate")
{
	debugMsg($DEBUG_INFO,"updateRating.");
	// $modelR->getRating($conn,$id);

// echo $model->getRouteOwner($conn,$routeId) .'...'. $id;

	if($model->getRouteOwner($conn,$routeId) != $id ) {
// echo $model->getRouteOwner($conn,$routeId);
		$modelR->updateRating($conn,$routeId,$threadName,$rating,$messageId);
	}
	session_start();
    $_SESSION['id'] = $id;
    $_SESSION['target'] = 'message';
    $_SESSION['action'] = 'list';
 echo $stringTest;
    echo '<script> window.location = "/php/cMyRides.php"; </script>';
	exit;
}
else
{
	debugMsg($DEBUG_INFO,"Error if reached this point.");
}

?>
