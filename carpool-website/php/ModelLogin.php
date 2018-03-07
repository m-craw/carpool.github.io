<?php
class ModelLogin
{
	public $DEBUG_INFO=0;



	function go_to_carpool_page($email) {	
	    session_start();
	    $_SESSION['login_status'] = "logged_in";
	    $_SESSION['login_email'] = $email;
	    header("Location:/");

	}
	

//start carpool site login

	function show_login_error_page() {	
		session_start();
	    $_SESSION['login_status'] = "login_error";
	    header("Location:/login.php");
	}

	function validate_login($connection, $email, $password) {
		$statement = "select * from users where 
				email = '$email' 
				and password = '$password'
				and is_facebook_user = 'N';";
		$result = mysqli_query($connection, $statement);
		if(mysqli_num_rows($result) == 1) {
			mysqli_free_result($result);
			mysqli_close($connection);
			// go_to_carpool_page($email);
			return true;
		}
		else {
			mysqli_free_result($result);
			mysqli_close($connection);
			// show_login_error_page();
			return false;
		}
		// else {
		// 	$statement_fb = "select * from users where 
		// 		connected_facebook_email = '$email' 
		// 		and password = '$password'
		// 		and is_facebook_user = 'Y';";
		// 		$result = mysqli_query($connection, $statement_fb);
		// 	if(mysqli_num_rows($result) == 1) {
		// 		mysqli_free_result($result);
		// 		mysqli_close($connection);
		// 		// go_to_carpool_page($email);
		// 		return true;
		// 	}
		// 	else {
		// 		mysqli_free_result($result);
		// 		mysqli_close($connection);
		// 		// show_login_error_page();
		// 		return false;
		// 	}
		// }
	}

//end carpool site login

//start create account 

	function show_create_account_error_page() {	
		session_start();
	    $_SESSION['login_status'] = "create_account_error";
	    header("Location:/login.php");
	}

	function create_account($connection, $email, $password, $first_name, $last_name, $drivers_license, $month, $day, $year) {
		$statement = "select * from users where email = '$email';";
		$result = mysqli_query($connection, $statement);
		//if there is an account in the database with the entered email
		if(mysqli_num_rows($result) == 1) {
			mysqli_free_result($result);
			// show_create_account_error_page();
			return false;
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
			// go_to_carpool_page($email);
			return true;
		}
	}

//end create account

//start facebook login/create account

	function create_account_facebook($connection, $fb_email, $first_name, $last_name) {// $month, $day, $year) {
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
		// go_to_carpool_page($fb_email);
		mysqli_free_result($result);
	}

	function validate_login_facebook($connection, $fb_email) {
		$statement = "select * from users where connected_facebook_email = '$fb_email' and is_facebook_user = 'Y';";
		$result = mysqli_query($connection, $statement);
		if(mysqli_num_rows($result) == 1) {
			mysqli_free_result($result);
			mysqli_close($connection);
			// go_to_carpool_page($fb_email);
			return true;
		}
		else {
			mysqli_free_result($result);
			// create_account_facebook($connection, $fb_email, $first_name, $last_name);
			return false;
		}
		// mysqli_free_result($result);
	}
//end facebook login/create account

//start google login/create account

	function create_account_google($connection, $google_email, $first_name, $last_name) {// $month, $day, $year) {
		date_default_timezone_set('US/Pacific');
		$current_date_time = date("Y-m-d H:i:s");
		$primary_email = $google_email;
		$statement_insert = "INSERT INTO users (email, password,first_name, last_name, bday, drivers_license_number, connected_facebook_email, is_facebook_user, create_date, update_date) VALUES 
			('$primary_email', 
			'', 
			'$first_name',
			'$last_name',
			'1998-01-01',
			'',
			'$google_email', 
			'Y', 
			'$current_date_time', 
			'$current_date_time');";
		$result = mysqli_query($connection, $statement_insert);
		mysqli_close($connection);
		// go_to_carpool_page($fb_email);
		mysqli_free_result($result);
	}

	function validate_login_google($connection, $google_email) {
		$statement = "select * from users where connected_facebook_email = '$google_email' and is_facebook_user = 'Y';";
		$result = mysqli_query($connection, $statement);
		if(mysqli_num_rows($result) == 1) {
			mysqli_free_result($result);
			mysqli_close($connection);
			// go_to_carpool_page($fb_email);
			return true;
		}
		else {
			mysqli_free_result($result);
			// create_account_facebook($connection, $fb_email, $first_name, $last_name);
			return false;
		}
		// mysqli_free_result($result);
	}
//end google login/create account

//start twitter login/create account

	function create_account_twitter($connection, $twitter_email, $first_name, $last_name) {// $month, $day, $year) {
		date_default_timezone_set('US/Pacific');
		$current_date_time = date("Y-m-d H:i:s");
		$primary_email = $twitter_email;
		$statement_insert = "INSERT INTO users (email, password,first_name, last_name, bday, drivers_license_number, connected_facebook_email, is_facebook_user, create_date, update_date) VALUES 
			('$primary_email', 
			'', 
			'$first_name',
			'$last_name',
			'1998-01-01',
			'',
			'$twitter_email', 
			'Y', 
			'$current_date_time', 
			'$current_date_time');";
		$result = mysqli_query($connection, $statement_insert);
		mysqli_close($connection);
		// go_to_carpool_page($fb_email);
		mysqli_free_result($result);
	}

	function validate_login_twitter($connection, $twitter_email) {
		$statement = "select * from users where connected_facebook_email = '$twitter_email' and is_facebook_user = 'Y';";
		$result = mysqli_query($connection, $statement);
		if(mysqli_num_rows($result) == 1) {
			mysqli_free_result($result);
			mysqli_close($connection);
			// go_to_carpool_page($fb_email);
			return true;
		}
		else {
			mysqli_free_result($result);
			// create_account_facebook($connection, $fb_email, $first_name, $last_name);
			return false;
		}
		// mysqli_free_result($result);
	}
//end twitter login/create account
	

}

?>