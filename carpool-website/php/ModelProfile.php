<?php
class ModelProfile
{
	public $DEBUG_INFO=0;

// 	function getRating($dbConn,$userId)
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
// // $this->debugMsg($this->DEBUG_INFO,"::rating(".$row[rating].")");
// 		}
// 		mysqli_free_result($sth);
// 		mysqli_next_result($dbConn);
// 		return $rows[0]["rating"];
// 	}
	function getRating($dbConn,$userId,$ratingType)
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

	function getAccountCreationDate($conn, $email) {	
		$rows = array();
		$sqlStmt = "
			select 
				email,
				first_name, 
				last_name, 
				bday, 
				substr(create_date,1,10) create_date 
			from 
				users
			where 
				email = '$email'
		";
		$sth = mysqli_query($conn,$sqlStmt);
		while ($row = mysqli_fetch_assoc($sth))
		{
			$rows[] = $row;
		}

		mysqli_free_result($sth);
		mysqli_next_result($conn);
		return $rows;
	}
	
	function getParticipatedAsDriverRidesList($conn, $email) {
		$rows = array();
		$sqlStmt = "
			select 
				route_id,
			   	start_address,
			   	end_address,
			   	substr(time_window_end,1,10) date
			from routes
			where 
				email = '$email' and status = 'COMPLETED'
			;";
		$sth = mysqli_query($conn,$sqlStmt);
		while ($row = mysqli_fetch_assoc($sth))
		{
			$rows[] = $row;
		}
		mysqli_free_result($sth);
		mysqli_next_result($conn);
		return $rows;
	}
	function getParticipatedAsPassengerRidesList($conn, $email) {
		$rows = array();
		$sqlStmt = "
			select 
				r.route_id,
			   	r.start_address,
			   	r.end_address,
			   	substr(r.time_window_end,1,10) date
			from
				routes r
				join ratings rtg 
					on r.route_id = rtg.route_id
			where
				rtg.username_passenger = '$email'
			order by
			   	route_id
			;";
		$sth = mysqli_query($conn,$sqlStmt);
		while ($row = mysqli_fetch_assoc($sth))
		{
			$rows[] = $row;
		}
		mysqli_free_result($sth);
		mysqli_next_result($conn);
		return $rows;
	}

	public function updateProfile($dbConn,$email,$fname,$lname,$bday,$pw)
	{
		$sqlStmt = "
			update users set 
				first_name='$fname',
				last_name ='$lname',
				bday      ='$bday',
				update_date = now()
			 where email = '$email' ";
		if($pw != "") {
			$sqlStmt = "
				update users set 
					first_name='$fname',
					last_name ='$lname',
					bday      ='$bday',
					update_date = now(),
					password = '$pw'
				 where email = '$email' ";
		}
		
#echo $sqlStmt."<br>";

		$sth = mysqli_query($dbConn,$sqlStmt);

		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
	}

	
}

?>
