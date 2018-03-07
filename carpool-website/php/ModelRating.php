<?php
require_once("ModelMyRides3.php");

class ModelRating
{
	public $DEBUG_INFO=0;
	public $sqlStmt = 'I am a Model.';

	public function updateRating($dbConn,$routeId,$userName,$rating,$msgId)
	{
		
		$statusModel = new ModelMyRides3();

		$this->addRating($dbConn,$routeId,$userName,$rating);
		$this->updateRatingMessage($dbConn,$msgId);
		if ($this->haveAllPassengersRated($dbConn,$routeId))
		{
			$statusModel->setRideClosed($dbConn,$routeId);
		}
	}

// 	public function addRating($dbConn,$routeId,$userName,$rating)
// 	{
// 		$sqlStmt = "insert into ratings
// 			(
// 				route_id,
// 				username_passenger,
// 				rating
// 			)
// 			values
// 			(
// 				$routeId,
// 				'$userName',
// 				$rating
// 			)";
// // echo "sqlStmt($sqlStmt)<br>";

// 		$sth = mysqli_query($dbConn,$sqlStmt);

// 		mysqli_free_result($sth);
// 		mysqli_next_result($dbConn);
// 	}

	public function updateRatingMessage($dbConn,$msgId)
	{
		$sqlStmt = "
			update messages set
				message_text = 'Thank you for your feedback.'
			where message_id = $msgId ";

		$sth = mysqli_query($dbConn,$sqlStmt);

		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
	}

	public function haveAllPassengersRated($dbConn,$routeId)
	{
		$status=0;
		$rows = array();
		$sqlStmt = "
			select count(*) as num_unrated 
			from 
				passenger_list p
				left join ratings r
					on p.route_id = r.route_id
					and p.username = r.username_passenger
			where 
				p.route_id = $routeId 
				and r.username_passenger is null
		";
// echo $sqlStmt."<br>";
		$sth = mysqli_query($dbConn,$sqlStmt);
		$row = mysqli_fetch_assoc($sth);
		if ($row[num_unrated] == 0)
		{
			$status=1;
		}

		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
		return $status;
	}

// 	public function getRating($dbConn,$userId)
// 	{
// 		$rows = array();
// 		$sqlStmt = "
// 			select 
// 				ifnull(avg(rtg.rating),0) as rating
// 			from
// 				routes r
// 				join ratings rtg 
// 					on r.route_id = rtg.route_id
// 			where
// 				r.email = '$userId'
// 			";
// 		$sth = mysqli_query($dbConn,$sqlStmt);
// 		while ($row = mysqli_fetch_assoc($sth))
// 		{
// 			$rows[] = $row;
// $this->debugMsg($this->DEBUG_INFO,"::rating(".$row[rating].")");
// 		}
// 		mysqli_free_result($sth);
// 		mysqli_next_result($dbConn);
// 		return $rows[0]["rating"];
// 	}

	public function debugMsg($debugLevel,$pMsg)
	{
		if ($debugLevel == 1)
		{
			echo "$pMsg<br>";
		}
	}

	public function checkPassengerNeedsRating($dbConn,$email)
	{
		$rows = array();
		////////////////////////////////////////////////////////////may have to change p.passenger_needs_rating depending on final word choice
		$sqlStmt = "
			select p.username, p.route_id 
			from 
				routes r
				left join passenger_list p
					on p.route_id = r.route_id
			where 
				r.email = '$email'
				and r.status = 'COMPLETED'
				and p.passenger_needs_rating = true;
		";
// echo $sqlStmt."<br>";
		$sth = mysqli_query($dbConn,$sqlStmt);
		while ($row = mysqli_fetch_assoc($sth))
		{
			$rows[] = $row;
		}

		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
		return $rows;
	}
	public function checkDriverNeedsRating($dbConn,$email)
	{
		$rows = array();
		////////////////////////////////////////////////////////////may have to change p.driver_needs_rating depending on final word choice
		$sqlStmt = "
			select r.email, r.route_id 
			from 
				routes r
				left join passenger_list p
					on p.route_id = r.route_id
			where 
				p.username = '$email'
				and p.driver_needs_rating = true
		";
// echo $sqlStmt."<br>";
		$sth = mysqli_query($dbConn,$sqlStmt);
		while ($row = mysqli_fetch_assoc($sth))
		{
			$rows[] = $row;
		}

		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
		return $rows;
	}

	public function addRating($dbConn,$routeId,$ratingType,$userName,$userNameRated,$rating)
	{
		$sqlStmt = "insert into ratings
			(
				route_id,
				rating_type,
				username,
				username_rated,
				rating
			)
			values
			(
				$routeId,
				upper('$ratingType'),
				'$userName',
				'$userNameRated',
				$rating
			)";
// echo "sqlStmt($sqlStmt)<br>";

		$sth = mysqli_query($dbConn,$sqlStmt);

		mysqli_free_result($sth);
		mysqli_next_result($dbConn);

		if($ratingType === "driver") {
			$sqlStmt = "
			update passenger_list set
			driver_needs_rating = 0
			where route_id = $routeId and username = '$userName'";
		}
		else {
			$sqlStmt = "
			update passenger_list set
			passenger_needs_rating = 0
			where route_id = $routeId and username = '$userNameRated'";
		}

		$sth = mysqli_query($dbConn,$sqlStmt);

		mysqli_free_result($sth);
		mysqli_next_result($dbConn);

	}
	public function getRating($dbConn,$userId,$ratingType)
	{
		$rows = array();
		$sqlStmt = "
			select 
				ifnull(avg(rtg.rating),0) as rating
			from
				ratings rtg 
			where
				rtg.username_rated  = '$userId'
				and rtg.rating_type = upper('$ratingType')
			";
// echo "sqlStmt($sqlStmt)<br>";
		$sth = mysqli_query($dbConn,$sqlStmt);
		while ($row = mysqli_fetch_assoc($sth))
		{
			$rows[] = $row;
// $this->debugMsg($this->DEBUG_INFO,"::rating(".$row["rating"].")");
		}
		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
		return $rows[0]["rating"];
	}
	public function checkCloseRide($dbConn,$routeId)
	{
		$close = true;
		$sqlStmt = " 
			select * 
			from passenger_list
			where route_id = $routeId
			and 
			(driver_needs_rating = 1 or passenger_needs_rating = 1)
		";

		$sth = mysqli_query($dbConn,$sqlStmt);
		if(mysqli_num_rows($sth) > 0) {
			$close = false;
		}

		mysqli_free_result($sth);
		mysqli_next_result($dbConn);

		return $close;
	}
	public function updateRideStatus($dbConn,$routeId,$status)
	{
		$sqlStmt = " update routes set
				status = upper('$status')
			where route_id = $routeId ";

		$sth = mysqli_query($dbConn,$sqlStmt);

		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
	}

}

?>
