<?php
  session_start();
  error_reporting(0);
  require_once("php/DBConn.php");
?>
<!doctype html>
<html>
<head>
  <meta charset = "utf-8">
  <title>Carpool</title>
  <script>
    //php code to check login status and redirect to login page if the 
    //user isn't logged in, add before loading anything unnecessary
    <?php
      if(isset($_SESSION['login_status'])){
        $login_status = $_SESSION['login_status'];
      }
      else {
        $login_status = "logged_out";
      }
      // echo 'alert("'.$login_status.'");';
      if($login_status <> "logged_in") {
        session_destroy();
        // echo 'window.location = "http://492ateam1.bitnamiapp.com/login.php";';
        echo 'window.location = "/login.php";';

      }
      //get the login email of the logged in user
      $login_email = $_SESSION['login_email'];
      echo 'var login_email = "'.$login_email.'";';
      $db = new DBConn();
      $connection = $db->connect();
      if($connection->connect_errno > 0){
        mysqli_close($connection);
          die('Unable to connect to database');
      }
    ?>
  </script>
  <!--CDN for bootstrap-->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
  <!-- our site's css -->
  <link href="/css/main.css" rel="stylesheet">
  <!-- jquery ui css -->
  <link rel="stylesheet" href="/jquery-ui-custom/jquery-ui.min.css">
  <link rel="stylesheet" href="/jquery-ui-custom/jquery-ui.structure.min.css">
  <link rel="stylesheet" href="/jquery-ui-custom/jquery-ui.theme.min.css">
  <!--for mobile devices-->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <!-- google sign in -->
  <script>
    function onLoad() {
      gapi.load('auth2', function() {
        gapi.auth2.init();
      });
    }
  </script>
  <script src="https://apis.google.com/js/platform.js?onload=onLoad" async defer></script>
  <meta name="google-signin-client_id" content="473326774931-juk0h7odee36c2kaj75anc7ou36tm0on.apps.googleusercontent.com">
  
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.js"></script>
  <!-- Latest compiled and minified JavaScript needed for bootstrap-->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>

<body>
  <header>
    <div id = "includeHeader"></div>
  </header>
  <script> 
    //Import header from inserts
    $(function(){
      $("#includeHeader").load("/inserts/header.php");
    });
  </script> 
 
  <!-- ADD CONTENT HERE -->

  
</body>

</html>
