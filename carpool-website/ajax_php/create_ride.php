<?php
  // //make database connection 
  function make_connection () {
    // $db = mysqli_connect('localhost:3306', 'root', 'btXFvI3ZotGt', 'app_carpool');//server connection
    $db = new mysqli('localhost', 'root', '', 'app_carpool');//local connection

    if($db->connect_errno > 0){
      mysqli_close($db);
        die('Unable to connect to database');
    }
    return $db;
  }
  function insert_into_rides($connection, $email, $origin, $origin_lat, $origin_long, $origin_place_id, $destination, $destination_lat, $destination_long, $destination_place_id, $time_window_start, $time_window_end, $ride_type) {
    $statement = "insert into routes values
(NULL,'$email','$origin',$origin_lat,$origin_long,'$origin_place_id','$destination',$destination_lat,$destination_long,'$destination_place_id','$time_window_start','$time_window_end',now(), 'VISIBLE','$ride_type');";
    $result = mysqli_query($connection, $statement);


    $route_id = mysqli_insert_id($connection);
    $sqlStmt = "insert into messages
      (
        route_id,
        thread_name,
        username,
        message_text
      )
      values
      (
        $route_id,
        'admin@carpool.com',
        'admin@carpool.com',
        ''
      )";
    $sth = mysqli_query($dbConn,$sqlStmt);

    mysqli_free_result($sth);
    mysqli_next_result($dbConn);

  }



  $conn = make_connection();
  $email = $_POST['email_value'];
  $origin = $_POST['origin_value'];
  $origin_lat = $_POST['origin_lat_value'];
  $origin_long = $_POST['origin_long_value'];
  $origin_place_id = $_POST['origin_place_id_value'];
  $destination = $_POST['destination_value'];
  $destination_lat = $_POST['destination_lat_value'];
  $destination_long = $_POST['destination_long_value'];
  $destination_place_id = $_POST['destination_place_id_value'];
  $time_window_start = $_POST['time_window_start'];
  $time_window_end = $_POST['time_window_end'];
  $ride_type = $_POST['radio_type_value'];
  insert_into_rides($conn, $email, $origin, $origin_lat, $origin_long, $origin_place_id, $destination, $destination_lat, $destination_long, $destination_place_id, $time_window_start, $time_window_end, $ride_type);
?>