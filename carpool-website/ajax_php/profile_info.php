<?php
  require_once("../php/ModelRating.php");
  
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
  function get_info($connection, $email) {
    $modelRating = new ModelRating();
    
    $statement = "select * from users where 
        email = '$email';";
    $result = mysqli_query($connection, $statement);
    if(mysqli_num_rows($result) == 0) {
      mysqli_free_result($result);
      mysqli_close($connection);
    }
    else {
      echo "<script> var ridesFound = [";
      while ($row = mysqli_fetch_assoc($result)) {
        if($comma_first == 1) {
          echo ',';
        }
        else {
          $comma_first = 1;
        }
        echo '<script> profileName = "'.$row["first_name"].'";</script><br>';
        echo 'First Name: '.$row["first_name"].'<br>';
        echo 'Last Name: '.$row["last_name"].'<br>';
        echo 'Email: '.$row["email"].'<br>';
        echo 'Rating: '.$modelRating->getRating($connection,$email).'<br>';
      }
      mysqli_close($connection);
      
    }
  }



  $conn = make_connection();
  $email = $_POST['email_view_profile'];
  get_info($conn, $email);
?>