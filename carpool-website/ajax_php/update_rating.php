<?php
  require_once("../php/ModelRating.php");
  require_once("../php/DBConn.php");

	$db    = new DBConn();
	$conn  = $db->connect();
	$modelR = new ModelRating();


	// target: "rating",
 //    action: "passenger",
 //    id: "'.$login_email.'",
 //    route_id: '.$rating[route_id].',
 //    person_rated_id: "'.$rating[username].'",
 //    rating: ratingInt

	$target = "";
	$action = "";
	$id = "";
	$route_id = 0;
	$person_rated_id = "";
	$rating = 0;


	if (isset($_POST['target']) )
	{
		$target = strtolower($_POST['target']);
	}
	if (isset($_POST['action']) )
	{
		$action = strtolower($_POST['action']);
	}
	if (isset($_POST['id']) )
	{
		$id = strtolower($_POST['id']);
	}
	if (isset($_POST['route_id']) )
	{
		$route_id = strtolower($_POST['route_id']);
	}
	if (isset($_POST['person_rated_id']) )
	{
		$person_rated_id = strtolower($_POST['person_rated_id']);
	}
	if (isset($_POST['rating']) )
	{
		$rating = strtolower($_POST['rating']);
	}

	$modelR->addRating($conn, $route_id, $action, $id, $person_rated_id, $rating);
	$closeRide = $modelR->checkCloseRide($conn, $route_id);
	if($closeRide) {
		$modelR->updateRideStatus($conn, $route_id, "CLOSED");
	}
	// echo $target."...".$action ."...".$id."...".$route_id."...".$person_rated_id ."...".$rating;





?>