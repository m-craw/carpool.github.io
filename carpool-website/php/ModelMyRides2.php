<?php

class ModelMyRides2
{
	public $DEBUG_INFO=0;
	public $sqlStmt = 'I am a Model.';

	public function isPassenger($dbConn,$routeId,$userName)
	{
		$rows = array();
		$sqlStmt = "select count(distinct username) is_passenger from passenger_list where route_id = $routeId and username = '$userName' ";
// echo $sqlStmt."<br>";
		$sth = mysqli_query($dbConn,$sqlStmt);
		while ($row = mysqli_fetch_assoc($sth))
		{
			$rows[] = $row;
		}
		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
		return $rows[0][is_passenger];
	}

	public function getThreadOtherUser($dbConn,$routeId,$threadName,$userName)
	{
		$rows = array();
		$sqlStmt = "select distinct username from messages
			where route_id = $routeId and thread_name = '$threadName' and username != '$userName' ";
#echo $sqlStmt."<br>";
		$sth = mysqli_query($dbConn,$sqlStmt);
		while ($row = mysqli_fetch_assoc($sth))
		{
			$rows[] = $row;
		}
		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
		return $rows[0][username];
	}

	public function changeRouteOwner($dbConn,$routeId,$userName)
	{
		$sqlStmt = "update routes set email='$userName',type='OFFER' where route_id = $routeId";
		echo $sqlStmt."<br>";

		$sth = mysqli_query($dbConn,$sqlStmt);

		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
	}
	public function changeThreadName($dbConn,$routeId,$oldThread,$newThread)
	{
		$sqlStmt = "update messages set thread_name='$newThread' where route_id = $routeId and thread_name = '$oldThread'";
		echo $sqlStmt."<br>";

		$sth = mysqli_query($dbConn,$sqlStmt);

		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
	}

	public function addPassenger($dbConn,$routeId,$userName)
	{
		$sqlStmt = "insert into passenger_list
			(
				route_id,
				username
			)
			values
			(
				$routeId,
				'$userName'
			)";

		$sth = mysqli_query($dbConn,$sqlStmt);

		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
	}

	public function removePassenger($dbConn,$routeId,$userName)
	{
		$sqlStmt = "delete from passenger_list where route_id = $routeId and username = '$userName' ";

		$sth = mysqli_query($dbConn,$sqlStmt);

		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
	}

	public function debugMsg($debugLevel,$pMsg)
	{
		if ($debugLevel == 1)
		{
			print "$pMsg<br>";
		}
	}
}

?>
