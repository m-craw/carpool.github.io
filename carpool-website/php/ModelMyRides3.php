<?php

class ModelMyRides3
{
	public $DEBUG_INFO=0;
	public $sqlStmt = 'I am a Model.';

	// VISIBLE/HIDDEN/COMPLETED/CLOSED
	public function setRideVisible($dbConn,$routeId)
	{
		$this->updateRideStatus($dbConn,$routeId,"VISIBLE");
	}

	public function setRideHidden($dbConn,$routeId)
	{
		$this->updateRideStatus($dbConn,$routeId,"HIDDEN");
	}

	public function setRideCompleted($dbConn,$routeId)
	{
		$sqlStmt = "
			insert into messages
			(
				route_id,
				thread_name,
				username,
				message_text
			)
			select
				distinct
				r.route_id,
				m.thread_name,
				r.email,
				'This ride has completed.  Thank you for riding. Please rate the driver: <a href=\"abc.php\">1 2 3 4 5</a>' as message_text
			from
				routes r
				join messages m
					on m.route_id = r.route_id
			where
				r.route_id = $routeId 
				and r.status not in ('COMPLETED','CLOSED')";

		$sth = mysqli_query($dbConn,$sqlStmt);

		mysqli_free_result($sth);
		mysqli_next_result($dbConn);

		$this->updateRideStatus($dbConn,$routeId,"COMPLETED");
	}

	public function setRideClosed($dbConn,$routeId)
	{
		$this->updateRideStatus($dbConn,$routeId,"CLOSED");
	}

	public function updateRideStatus($dbConn,$routeId,$status)
	{
		$sqlStmt = " update routes set
				status = upper('$status')
			where route_id = $routeId ";
#echo "sqlStmt($sqlStmt)<br>";

		$sth = mysqli_query($dbConn,$sqlStmt);

		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
	}

	public function getRide($dbConn,$routeId)
	{
		$rows = array();
		$sqlStmt = "
			select 
				r.*
			from
				routes r 
			where
				r.route_id = $routeId
			order by
				r.route_id
			";
#echo "sqlStmt($sqlStmt)<br>";
		$sth = mysqli_query($dbConn,$sqlStmt);
		while ($row = mysqli_fetch_assoc($sth))
		{
			$rows[] = $row;
		}
		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
		return $rows;
	}

	public function debugMsg($debugLevel,$pMsg)
	{
		if ($debugLevel == 1)
		{
			echo "$pMsg<br>";
		}
	}
}

?>
