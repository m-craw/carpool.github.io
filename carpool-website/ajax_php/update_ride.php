<?php
require_once("../php/DBConn.php");

$db    = new DBConn();
$conn  = $db->connect();

function updateRide($dbConn,$route_id_value,$radio_status_value,$email_value,$origin_value,$origin_lat_value,$origin_long_value,$origin_place_id_value,$destination_value,$destination_lat_value,$destination_long_value,$destination_place_id_value,$time_window_start,$time_window_end,$create_date_value, $type_value){
	$rows = array();
	$sqlStmt = "
		update routes set 
			status = '$radio_status_value',
			start_address = '$origin_value',
			start_lat = $origin_lat_value,
			start_lng = $origin_long_value,
			start_google_place_id = '$origin_place_id_value',
			end_address = '$destination_value',
			end_lat = $destination_lat_value,
			end_lng = $destination_long_value,
			end_google_place_id = '$destination_place_id_value',
			time_window_start = '$time_window_start',
			time_window_end = '$time_window_end'
		where route_id = $route_id_value
	;";
	$sth = mysqli_query($dbConn,$sqlStmt);
	mysqli_free_result($sth);
	mysqli_next_result($dbConn);
}
function deleteRide($dbConn,$route_id_value,$radio_status_value){
	$rows = array();
	$sqlStmt = "
		update routes set 
			status = '$radio_status_value'
		where route_id = $route_id_value
	;";
	$sth = mysqli_query($dbConn,$sqlStmt);
	mysqli_free_result($sth);
	mysqli_next_result($dbConn);
}
function completeRide($dbConn,$route_id_value,$radio_status_value){
	$rows = array();
	$sqlStmt = "
		update routes set 
			status = '$radio_status_value'
		where route_id = $route_id_value
	;";
	$sth = mysqli_query($dbConn,$sqlStmt);
	mysqli_free_result($sth);
	mysqli_next_result($dbConn);
}
function setRideCompleted($dbConn,$routeId,$email_value)
{
	// $sqlStmt = "
	// 	insert into messages
	// 	(
	// 		route_id,
	// 		thread_name,
	// 		username,
	// 		message_text
	// 	)
	// 	select
	// 		distinct
	// 		r.route_id,
	// 		m.thread_name,
	// 		r.email,
	// 		'temp' as message_text
	// 	from
	// 		routes r
	// 		join messages m
	// 			on m.route_id = r.route_id
	// 		join passenger_list pl
	// 			on r.route_id = pl.route_id 
	// 			and m.thread_name = pl.username
	// 	where
	// 		r.route_id = $routeId
	// 		and r.status not in ('COMPLETED','CLOSED')

	// ";
	// $sth = mysqli_query($dbConn,$sqlStmt);
	// mysqli_free_result($sth);
	// mysqli_next_result($dbConn);

	updateRideStatus($dbConn,$routeId,"COMPLETED");

	$sqlStmt = "
		update passenger_list 
		set 
			driver_needs_rating = true,
			passenger_needs_rating = true

		where 
			route_id = $routeId
	";
	$sth = mysqli_query($dbConn,$sqlStmt);
	mysqli_free_result($sth);
	mysqli_next_result($dbConn);

	
}
function updateRideStatus($dbConn,$routeId,$status)
{
	$sqlStmt = " update routes set
			status = upper('$status')
		where route_id = $routeId ";

	$sth = mysqli_query($dbConn,$sqlStmt);

	mysqli_free_result($sth);
	mysqli_next_result($dbConn);
}





$ride_target = $_POST['target'];
$route_id_value = $_POST['route_id_value'];
$radio_status_value = $_POST['radio_status_value'];
$email_value = $_POST['email_value'];
$origin_value = $_POST['origin_value'];
$origin_lat_value = $_POST['origin_lat_value'];
$origin_long_value = $_POST['origin_long_value'];
$origin_place_id_value = $_POST['origin_place_id_value'];
$destination_value = $_POST['destination_value'];
$destination_lat_value = $_POST['destination_lat_value'];
$destination_long_value = $_POST['destination_long_value'];
$destination_place_id_value = $_POST['destination_place_id_value'];
$time_window_start = $_POST['time_window_start'];
$time_window_end = $_POST['time_window_end'];
$create_date_value = $_POST['create_date_value'];
$type_value = $_POST['type_value'];
if($ride_target == "UPDATE") {
	updateRide($conn,$route_id_value,$radio_status_value,$email_value,$origin_value,$origin_lat_value,$origin_long_value,$origin_place_id_value,$destination_value,$destination_lat_value,$destination_long_value,$destination_place_id_value,$time_window_start,$time_window_end,$create_date_value, $type_value);
}
else if($ride_target == "DELETE") {
	deleteRide($conn,$route_id_value,$radio_status_value);
}
else if($ride_target == "COMPLETE") {
	setRideCompleted($conn,$route_id_value, $email_value);
}

?>