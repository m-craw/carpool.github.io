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
    $_SESSION['login_status'] = "fb_create_account_error";
    header("Location:http://492ateam1.bitnamiapp.com/login.php");
}
function go_to_carpool_page($email) {	
	session_start();
    $_SESSION['login_status'] = "logged_in";
    $_SESSION['login_email'] = $email;
    header("Location:http://492ateam1.bitnamiapp.com/");
}

function create_account($connection, $fb_email, $first_name, $last_name) {// $month, $day, $year) {
	date_default_timezone_set('US/Pacific');
	$current_date_time = date("Y-m-d H:i:s");
	$primary_email = $fb_email;
	$statement_insert = "INSERT INTO users (email, password,first_name, last_name, bday, drivers_license_number, connected_facebook_email, is_facebook_user, create_date, update_date) VALUES 
		('$primary_email', 
		'', 
		'$first_name',
		'$last_name',
		'1998-01-01',
		'',
		'$fb_email', 
		'Y', 
		'$current_date_time', 
		'$current_date_time');";
	$result = mysqli_query($connection, $statement_insert);
	mysqli_close($connection);
	go_to_carpool_page($fb_email);
	mysqli_free_result($result);
}

function validate_login($connection, $fb_email, $first_name, $last_name) {
	$statement = "select * from users where connected_facebook_email = '$fb_email' and is_facebook_user = 'Y';";
	$result = mysqli_query($connection, $statement);
	if(mysqli_num_rows($result) == 1) {
		mysqli_free_result($result);
		mysqli_close($connection);
		go_to_carpool_page($fb_email);
	}
	else {
		mysqli_free_result($result);
		create_account($connection, $fb_email, $first_name, $last_name);
	}
	// mysqli_free_result($result);
}


$conn = make_connection();
$fb_email = mysqli_real_escape_string($conn, $_POST["fb_email"]);
$fb_first_name = mysqli_real_escape_string($conn, $_POST["fb_first_name"]);
$fb_last_name = mysqli_real_escape_string($conn, $_POST["fb_last_name"]);
validate_login($conn, $fb_email, $fb_first_name, $fb_last_name);

?>