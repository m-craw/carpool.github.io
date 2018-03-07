<?php
  // //make database connection 
  function make_connection () {
    // $db = mysqli_connect('localhost:3306', 'root', 'btXFvI3ZotGt', 'app_carpool');//server connection
    $db = new mysqli('localhost', 'root', '', 'app_carpool');//local connection

    if($db->connect_errno > 0){
      mysqli_close($db);
        die('Unable to connect to database');
    }
    // echo "--success--";
    return $db;
  }
  function find_rides($connection, $time_window_start, $time_window_end, $ride_type) {
    $statement = "select * from routes where 
        type = '$ride_type' 
        and
        ((time_window_start <= '$time_window_start' and time_window_end >= '$time_window_start') 
        or
        (time_window_start <= '$time_window_end' and time_window_end >= '$time_window_end'));";
    $result = mysqli_query($connection, $statement);
    if(mysqli_num_rows($result) == 0) {
      mysqli_free_result($result);
      mysqli_close($connection);
      //echo something--------------------------------------------------------------------------------------------
      echo '<script> alert("No Rides Found"); 
            var ridesFound = [];</script>';
    }
    else {
      $comma_first = 0;
      echo "<script> var ridesFound = [";
      while ($row = mysqli_fetch_assoc($result)) {
        if($comma_first == 1) {
          echo ',';
        }
        else {
          $comma_first = 1;
        }
        echo '["'.$row["route_id"].'", "'.$row["email"].'", "'.$row["start_address"].'", "'.$row["start_lat"].'", "'.$row["start_lng"].'","'.$row['start_google_place_id'].'", "'.$row["end_address"].'", "'.$row["end_lat"].'", "'.$row["end_lng"].'", "'.$row['end_google_place_id'].'", "'.$row["time_window_start"].'", "'.$row["time_window_end"].'", "'.$row["create_date"].'", "'.$row["status"].'", "'.$row["type"].'"]';
      }
      echo "];";
      mysqli_close($connection);
      
    }
  }



  $conn = make_connection();
  $time_window_start = $_POST['time_window_start'];
  $time_window_end = $_POST['time_window_end'];
  $ride_type = $_POST['radio_value'];
  find_rides($conn, $time_window_start, $time_window_end, $ride_type);
?>