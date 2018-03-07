<?php
class ModelEditRides
{
	public $DEBUG_INFO=0;
	public $sqlStmt = 'I am a Model.';
	

	public function getRideInfo($dbConn,$route_id)
	{
		$rows = array();
		$sqlStmt = "
			select 
				email,
			   	start_address,
			   	start_lat,
			   	start_lng,
			   	start_google_place_id,
			   	end_address,
			   	end_lat,
			   	end_lng,
			   	end_google_place_id,
			   	time_window_start,
			   	time_window_end,
			   	create_date,
			   	status,
			   	type
			from routes
			where route_id = $route_id
		;";
		$sth = mysqli_query($dbConn,$sqlStmt);
		while ($row = mysqli_fetch_assoc($sth))
		{
			$rows[] = $row;
		}
		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
		return $rows;
	}
	public function hasPassenger($dbConn,$routeId)
	{
		$rows = array();
		$sqlStmt = "select * from passenger_list where route_id = $routeId ";
		$sth = mysqli_query($dbConn,$sqlStmt);
		$ret = true;
		if (mysqli_num_rows($sth) == 0)
		{
			$ret = false;
		}
		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
		return $ret;
	}

	

}

?>