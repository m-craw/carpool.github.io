<?php
  session_start();
  error_reporting(0);

require_once ('vendor/autoload.php');
\Codebird\Codebird::setConsumerKey('AeqD1VXFLxni6wINS9AzYZhJv', '44fHeMU0GzeK5tcFjUhi8ESiqgDwU79UUqtsF9i0nc6IpF8EKI'); // static, see README

$cb = \Codebird\Codebird::getInstance();

  if (isset($_GET['oauth_verifier']) && isset($_SESSION['oauth_verify'])) {
    // verify the token
      // echo $_SESSION['oauth_token_secret'];
    $cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
    unset($_SESSION['oauth_verify']);
    // get the access token
    $reply = $cb->oauth_accessToken([
      'oauth_verifier' => $_GET['oauth_verifier']
    ]);
    // store the token (which is different from the request token!)
    $_SESSION['oauth_token'] = $reply->oauth_token;
    $_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;
    $cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

    // send to same URL, without oauth GET parameters
    // header('Location: ' . basename(__FILE__));
    $reply = $cb->account_verifyCredentials(['include_email' => 'true']);
   //retrieve information
    $twitter_email = $reply->email;

    $full_name = $reply->name;
    $name_parts = explode(" ", $full_name);
    $twitter_first_name = $name_parts[0];
    $twitter_last_name = $name_parts[1];

    //echo form submission
    echo '
      <form id = "twitter-form" action = "/php/cLogin.php" method = "post">
        <input name="twitter_email" id = "twitter-email" type="hidden" value="'.$twitter_email.'">
        <input name="twitter_first_name" id = "twitter-first-name" type="hidden" value="'.$twitter_first_name.'">
        <input name="twitter_last_name" id = "twitter-last-name" type="hidden" value="'.$twitter_last_name.'">
        <input type="hidden" name="target" value="login">
        <input type="hidden" name="action" value="twitter_login">
        <input type="hidden" value="Submit">
      </form>
      <script>
        document.getElementById("twitter-form").submit();
      </script>
    ';
  }



?>
<!doctype html>

<html>
<head>
	<meta charset = "utf-8">
  
	<!--CDN for bootstrap-->
	<title>Carpool::Login</title>
  <script>
    //php code to check login status and redirect to login page if the 
    //user isn't logged in, add before loading anything unnecessary
    // $(document).ready(function(){
      <?php
        $login_status = $_SESSION['login_status'];
        if($login_status == "logged_in") {
          // echo 'window.location = "http://492ateam1.bitnamiapp.com/";';
          echo 'window.location = "/";';
        }
        //get the login email of the logged in user
        $login_email = $_SESSION['login_email'];
      ?>
  </script>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

  <link href="/css/main.css" rel="stylesheet">
	<!--for mobile devices-->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <!-- google sign in -->
  <script src="https://apis.google.com/js/platform.js" async defer></script>
  <meta name="google-signin-client_id" content="473326774931-juk0h7odee36c2kaj75anc7ou36tm0on.apps.googleusercontent.com">


</head>

<body>

<!-- Facebook sdk initial setup -->
  <div id="fb-root"></div>
  <div id = "includeFbLogin"></div>

	<header>
   		<div id = "includeHeader"></div>
 	</header>


  <!-- Website Account Login -->
  <div class = "container col-sm-6">
    <h2>Login</h2>

 	  <form action = "/php/cLogin.php" method = "post">
    <!-- Output something telling the user that an invalid login was attempted -->
      <div>
        <?php
          $login_status = $_SESSION['login_status'];
          if($login_status == "login_error") {
            echo '<script>document.write("<div class=\"alert alert-danger\" role=\"alert\"> <span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"> </span> <span class=\"sr-only\">Error:</span>Invalid Login Information</div>");</script>';
          }
        ?>
      </div>
      <div class = "">
        <div class = "input-group">
          <input name="login_email" id="login-email" type="email" placeholder="Enter email" class="form-control" required>
        </div>
      </div>
      <div class = "">
        <div class = "input-group">
          <input name="login_password" id="login-password" type="password" placeholder="Enter password" class="form-control" required>
        </div>
      </div>

      <input type="hidden" name="target" value="login">
      <input type="hidden" name="action" value="login">

      <div class = "">
        <button id = "login-button" class="btn btn-md btn-primary" type="submit">Sign in</button>
        <a href="php/cEmailPassword.php?target=email&action=request">Forgot Your Password?</a>
      </div>
      
    </form>
      <h2>Login with Facebook</h2>
    <div class = "">
        <div class="fb-login-button" data-max-rows="1" data-size="xlarge" data-show-faces="false" data-auto-logout-link="true"
            data-scope = "public_profile, email" onlogin="checkLoginState();"></div>
        <form id = "fb-form" action = "/php/cLogin.php" method = "post">
          <!-- Output something telling the user that an invalid login/creation was attempted -->
          <?php
            $login_status = $_SESSION['login_status'];
            if($login_status == "fb_create_account_error") {
              echo '<script>document.write("<div class=\"alert alert-danger\" role=\"alert\"> <span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"> </span> <span class=\"sr-only\">Error:</span>Error Logging in using Facebook</div>");</script>';
            }
          ?>
          <input name="fb_email" id = "fb-email" type="hidden" value="">
          <input name="fb_first_name" id = "fb-first-name" type="hidden" value="">
          <input name="fb_last_name" id = "fb-last-name" type="hidden" value="">
          <input type="hidden" name="target" value="login">
          <input type="hidden" name="action" value="fb_login">
          <input type="hidden" value="Submit">
        </form>
    </div>
    <h2>Login with Google</h2>
    <div class = "">
        <form id = "google-form" action = "/php/cLogin.php" method = "post">
          <input name="google_email" id = "google-email" type="hidden" value="">
          <input name="google_first_name" id = "google-first-name" type="hidden" value="">
          <input name="google_last_name" id = "google-last-name" type="hidden" value="">
          <input type="hidden" name="target" value="login">
          <input type="hidden" name="action" value="google_login">
          <input type="hidden" value="Submit">
        </form>
        <script>
          function onSignIn(googleUser) {
            var profile = googleUser.getBasicProfile();

            document.getElementById("google-last-name").value = profile.getFamilyName();
            console.log(profile.getFamilyName());
            document.getElementById("google-first-name").value = profile.getGivenName();
            console.log(profile.getGivenName());
            document.getElementById("google-email").value = profile.getEmail();
            console.log(profile.getEmail());
            document.getElementById("google-form").submit();
          }
        </script>
        <div class="g-signin2" data-onsuccess="onSignIn"></div>
    </div>
    <h2>Login with Twitter</h2>
    <div class = "">
      <!-- <div class="g-signin2" data-onsuccess="onSignIn"></div> -->
      <a id="twitterLogin" href="#"><img alt="Sign in with Twitter" src="https://g.twimg.com/dev/sites/default/files/images_documentation/sign-in-with-twitter-gray.png"></a>
    </div>
  </div>





  <!-- Create Account -->
  <div class = "container col-sm-6">
    <h2>Create Account</h2>
    <form action = "/php/cLogin.php" method = "post">
      <div>
      <!-- Output something telling the user that an invalid account creation was attempted -->
        <?php
          $login_status = $_SESSION['login_status'];
          if($login_status == "create_account_error") {
            echo '<script>document.write("<div class=\"alert alert-danger\" role=\"alert\"> <span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"> </span> <span class=\"sr-only\">Error:</span>Account Already Exists: Enter different email</div>");</script>';
          }
        ?>
      </div>
      <div class = "">
        <h4>Enter Email *</h4>
        <div class = "input-group">
          <input name="create_email" id="create-email" type="email" placeholder="Enter email" class="form-control" required>
        </div>
      </div>
      <div class = "">
        <h4>Enter Password *</h4>
        <div class = "input-group">
          <input name="create_password" id="create-password" type="password" placeholder="Enter password" class="form-control" required>
        </div>
      </div>
      <div class = "">
        <h4>Enter First Name *</h4>
        <div class = "input-group">
          <input name="create_first_name" id="create-first-name" type="text" placeholder="Enter First Name" class="form-control" required>
        </div>
      </div>
      <div class = "">
        <h4>Enter Last Name *</h4>
        <div class = "input-group">
          <input name="create_last_name" id="create-last-name" type="text" placeholder="Enter Last Name" class="form-control" required>
        </div>
      </div>
      <!-- <div class = "">
        <h4>Enter Driver's License Number</h4>
        <h5>Required if you plan on offering ride's as a driver.</h5>
        <div class = "input-group">
          <input name="create_drivers_license" id="create-drivers-license" type="text" placeholder="Enter Driver's License Number" class="form-control">
        </div>
      </div> -->
      <div class = "">
        <h4>Enter Birthday (MM/DD/YYYY)</h4>
        <div class = "input-group">
          <input name="create_month" id="create-month" type="text" placeholder="MM" class="form-control" required>
        </div>
        <div class = "input-group">
          <input name="create_day" id="create-day" type="text" placeholder="DD" class="form-control" required>
        </div>
        <div class = "input-group">
          <input name="create_year" id="create-year" type="text" placeholder="YYYY" class="form-control" required>
        </div>
        <input type="hidden" name="target" value="login">
        <input type="hidden" name="action" value="create">

      </div>
      <div class = "">
        <button id = "create-button" class="btn btn-md btn-primary" type="submit">Create Account</button>
      </div>
    </form>
  </div>

  <div class = "container col-sm-6">
    <iframe width="560" height="315" src="https://www.youtube.com/embed/okD8RMpu2Vs" frameborder="0" allowfullscreen></iframe>
  </div>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script> 
    $(function(){
      $("#includeHeader").load("/inserts/header.php"); 
      // $("#includeFooter").load("/inserts/footer.php"); 
      $("#includeFbLogin").load("/inserts/fblogin.php"); 
      $('#twitterLogin').click(function() {
        console.log("test");
        $.ajax( "ajax_php/twitter_sign_in.php" )
          .done(function(data) {
            // window.location = data;
            console.log("test3");
            console.log(data);
            $('#twitter').html(data);
            window.location = data;
          })
          .fail(function(data) {
            console.log("test3");
            console.log(data);
            $('#twitter').html(data);

            // alert("error");
          });
      });

    });
    </script> 
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>

</html>