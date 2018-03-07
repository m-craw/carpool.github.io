<?php
class ModelMyRides
{
	public $DEBUG_INFO=0;
	public $sqlStmt = 'I am a Model.';
	

		public function getOfferedRidesList($dbConn,$username)
	{
		$rows = array();
		$sqlStmt = "
			select 
				route_id,
			   	start_address,
			   	end_address,
			   	substr(time_window_end,1,10) date
			from routes
			where email = '$username' and type = 'OFFER' and (status = 'VISIBLE' or status = 'HIDDEN' or status = 'COMPLETED')
			order by
			   	route_id
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
	

	public function getRequestedRidesList($dbConn,$username)
	{
		$rows = array();
		$sqlStmt = "
			select 
				route_id,
			   	start_address,
			   	end_address,
			   	substr(time_window_end,1,10) date
			from routes
			where email = '$username' and type = 'REQUEST' and (status = 'VISIBLE' or status = 'HIDDEN' or status = 'COMPLETED')
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

	public function getOfferedListNotOwned($dbConn,$username)
	{
		$rows = array();
		$sqlStmt = "
			select 
				m.route_id,
			  	m.thread_name,
				m.message_id,
				m.username,
			   	m.message_text,
			   	r.start_address,
			   	r.end_address,
			   	substr(r.time_window_end,1,10) date
			from
			   messages m
			   join routes r 
			   on m.route_id = r.route_id
			 where
			         m.thread_name = '$username' and r.type = 'REQUEST' and (status = 'VISIBLE' or status = 'HIDDEN' or status = 'COMPLETED')
			 order by
			   m.route_id,
			   m.thread_name,
			   m.message_id
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

	public function getOfferedListOwned($dbConn,$username)
	{
		$rows = array();
		$sqlStmt = "
			select 
				m.route_id,
			  	m.thread_name,
				m.message_id,
				m.username,
			   	m.message_text,
			   	r.start_address,
			   	r.end_address,
			   	substr(r.time_window_end,1,10) date
			from
			   messages m
			   join routes r 
			   on m.route_id = r.route_id
			 where
			         r.email = '$username' and r.type = 'OFFER' and (status = 'VISIBLE' or status = 'HIDDEN' or status = 'COMPLETED')
			 order by
			   m.route_id,
			   m.thread_name,
			   m.message_id
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


	public function getRequestedListNotOwned($dbConn,$username)
	{
		$rows = array();
		$sqlStmt = "
			select 
				m.route_id,
			  	m.thread_name,
				m.message_id,
				m.username,
			   	m.message_text,
			   	r.start_address,
			   	r.end_address,
			   	substr(r.time_window_end,1,10) date
			from
			   messages m
			   join routes r 
			   on m.route_id = r.route_id
			 where
			         m.thread_name = '$username' and r.type = 'OFFER' and (r.status = 'VISIBLE' or r.status = 'HIDDEN' or status = 'COMPLETED')
			 order by
			   m.route_id,
			   m.thread_name,
			   m.message_id
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
	public function getRequestedListOwned($dbConn,$username)
	{
		$rows = array();
		$sqlStmt = "
			select 
				m.route_id,
			  	m.thread_name,
				m.message_id,
				m.username,
			   	m.message_text,
			   	r.start_address,
			   	r.end_address,
			   	substr(r.time_window_end,1,10) date
			from
			   messages m
			   join routes r 
			   on m.route_id = r.route_id
			 where
			         r.email = '$username' and r.type = 'REQUEST' and (r.status = 'VISIBLE' or r.status = 'HIDDEN' or status = 'COMPLETED')
			 order by
			   m.route_id,
			   m.thread_name,
			   m.message_id
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

	public function getMessage($dbConn,$messageId)
	{
		$rows = array();
		$sqlStmt = "select * from message where message_id = $messageId";
#echo $sqlStmt."<br>";
		$sth = mysqli_query($dbConn,$sqlStmt);
		while ($row = mysqli_fetch_assoc($sth))
		{
			$rows[] = $row;
		}
		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
		return $rows;
	}

	public function getRouteOwner($dbConn,$routeId)
	{
		$rows = array();
		$sqlStmt = "select email from routes where route_id = $routeId";
#echo $sqlStmt."<br>";
		$sth = mysqli_query($dbConn,$sqlStmt);
		while ($row = mysqli_fetch_assoc($sth))
		{
			$rows[] = $row;
		}
		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
		return $rows[0][email];
	}

	public function saveMessage($dbConn,$threadName,$username,$message,$routeId)
	{
		$sqlStmt = "insert into messages
			(
				route_id,
				thread_name,
				username,
				message_text
			)
			values
			(
				$routeId,
				'$threadName',
				'$username',
				'$message'
			)";
		$sth = mysqli_query($dbConn,$sqlStmt);

		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
	}

	public function changeRouteStatusComplete($dbConn,$routeId)
	{
		$sqlStmt = "update routes set status='COMPLETED' where route_id = $routeId";
		// echo $sqlStmt."<br>";

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