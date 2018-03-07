<?php
class DBConn
{
	public function connect()
	{
		// $mysqli = mysqli_connect('localhost:3306', 'root', 'btXFvI3ZotGt', 'app_carpool');
		$mysqli = mysqli_connect('localhost', 'root', '', 'app_carpool');
		
		return $mysqli;
	}
}
?>