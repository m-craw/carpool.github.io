<?php
#require_once("ModelEmail.php");

class ModelEmail
{
	public $DEBUG_INFO=0;
	public $sqlStmt = 'I am a Model.';

	public function random_password($length) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
		$password = substr( str_shuffle( $chars ), 0, $length );
		return $password;
	}

	public function generateCode($dbConn,$email)
	{
		$tmpPasswd=$this->random_password(8);
		$rows = array();
		$sqlStmt = "delete from reset_requests where email = '$email' ";
#echo "sqlStmt($sqlStmt)<br>";

		$sth = mysqli_query($dbConn,$sqlStmt);

		$sqlStmt = "
			insert into reset_requests
			(
				email,
				code
			)
			values
			(
				'$email',
				'$tmpPasswd'
			);
			";
#echo "sqlStmt($sqlStmt)<br>";
		$sth = mysqli_query($dbConn,$sqlStmt);
		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
		return $tmpPasswd;
	}

	public function verifyCode($dbConn,$email,$code)
	{
		$status=0;
		$rows = array();
		$sqlStmt = "
			select count(*) as code_found 
			from 
				reset_requests r
				join users u
					on r.email = u.email
			where 
				r.email = '$email'
				and r.code = '$code'
		";
#echo $sqlStmt."<br>";
		$sth = mysqli_query($dbConn,$sqlStmt);
		$row = mysqli_fetch_assoc($sth);
#echo "code_found(".$row[code_found].")<br>";
		if ($row["code_found"] == 0)
		{
			$status=false;
		}
		else
		{
			$status=true;
		}
		
#echo "status($status)<br>";

		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
		return $status;
	}

	public function updatePassword($dbConn,$email,$code,$password)
	{
		$sqlStmt = "
			update users set
				password = '$password'
			where email = '$email' 
				and exists
					(select * from reset_requests
					where email = '$email'
						and code = '$code'
					) ";
#echo "sqlStmt($sqlStmt)<br>";

		$sth = mysqli_query($dbConn,$sqlStmt);

		$sqlStmt = "delete from reset_requests where email = '$email' ";
#echo "sqlStmt($sqlStmt)<br>";

		$sth = mysqli_query($dbConn,$sqlStmt);

		mysqli_free_result($sth);
		mysqli_next_result($dbConn);
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
