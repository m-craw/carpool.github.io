<?php
function make_connection () {
	$db = new mysqli('localhost:3306', 'root', 'btXFvI3ZotGt', 'app_carpool');//server connection
	// $db = new mysqli('localhost', 'root', '', 'app_carpool');//local connection

	if($db->connect_errno > 0){
    	die('Unable to connect to database [' . $db->connect_error . ']');
	}
	return $db;
}

function show_create_account_error_page() {	
	session_start();
    $_SESSION['login_status'] = "create_account_error";
    header("Location:http://492ateam1.bitnamiapp.com/login.php");
}
function go_to_carpool_page($email) {	
	session_start();
    $_SESSION['login_status'] = "logged_in";
    $_SESSION['login_email'] = $email;
    header("Location:http://492ateam1.bitnamiapp.com/");
}

function create_account($connection, $email, $password, $first_name, $last_name, $drivers_license, $month, $day, $year) {
	$statement = "select * from users where email = '$email';";
	$result = mysqli_query($connection, $statement);
	//if there is an account in the database with the entered email
	if(mysqli_num_rows($result) == 1) {
		mysqli_free_result($result);
		show_create_account_error_page();
	}
	else {
		mysqli_free_result($result);
		date_default_timezone_set('US/Pacific');
		$current_date_time = date("Y-m-d H:i:s");
		// $connected_facebook_email = $email;
		$statement_insert = "INSERT INTO users (email, password,first_name, last_name, bday, drivers_license_number, connected_facebook_email, is_facebook_user, create_date, update_date) VALUES 
			('$email', 
			'$password', 
			'$first_name',
			'$last_name',
			'".$year."-".$month."-".$day."',
			'$drivers_license',
			'', 
			'N', 
			'$current_date_time', 
			'$current_date_time');";
		$resultC = mysqli_query($connection, $statement_insert);
		go_to_carpool_page($email);
	}
}


$conn = make_connection();
$create_email = mysqli_real_escape_string($conn, $_POST["create_email"]);
$create_password = mysqli_real_escape_string($conn, $_POST["create_password"]);
$create_first_name = mysqli_real_escape_string($conn, $_POST["create_first_name"]);
$create_last_name = mysqli_real_escape_string($conn, $_POST["create_last_name"]);
$create_drivers_license = mysqli_real_escape_string($conn, $_POST["create_drivers_license"]);
$create_month = mysqli_real_escape_string($conn, $_POST["create_month"]);
$create_day = mysqli_real_escape_string($conn, $_POST["create_day"]);
$create_year = mysqli_real_escape_string($conn, $_POST["create_year"]);
create_account($conn, $create_email, $create_password, $create_first_name, $create_last_name, $create_drivers_license, $create_month, $create_day, $create_year);




?>