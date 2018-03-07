<?php
function make_connection () {
	// $db = new mysqli('localhost:3306', 'root', 'btXFvI3ZotGt', 'app_carpool');//server connection
	$db = new mysqli('localhost', 'root', '', 'app_carpool');//local connection

	if($db->connect_errno > 0){
		mysqli_close($db);
    	die('Unable to connect to database');
	}
	// echo "--success--";
	return $db;
}

function show_login_error_page() {	
	session_start();
    $_SESSION['login_status'] = "login_error";
    header("Location:/login.php");
}
function go_to_carpool_page($email) {	
    session_start();
    $_SESSION['login_status'] = "logged_in";
    $_SESSION['login_email'] = $email;
    header("Location:/");

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
		go_to_carpool_page($email);
	}
	else {
		$statement_fb = "select * from users where 
			connected_facebook_email = '$email' 
			and password = '$password'
			and is_facebook_user = 'Y';";
			$result = mysqli_query($connection, $statement_fb);
		if(mysqli_num_rows($result) == 1) {
			mysqli_free_result($result);
			mysqli_close($connection);
			go_to_carpool_page($email);
		}
		else {
			mysqli_free_result($result);
			mysqli_close($connection);
			show_login_error_page();
		}
	}
}


$conn = make_connection();
$recieved_email = mysqli_real_escape_string($conn, $_POST["login_email"]);
$recieved_password = mysqli_real_escape_string($conn, $_POST["login_password"]);
validate_login($conn, $recieved_email, $recieved_password);
?>
