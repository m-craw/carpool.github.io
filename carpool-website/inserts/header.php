
<!-- <script src="https://apis.google.com/js/platform.js" async defer></script>
<script src="https://apis.google.com/js/platform.js?onload=onLoad" async defer></script> -->
<script type="text/javascript">
  function logOut() {
      
    // console.log('User signed out.');
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
      console.log('User signed out.');
    });
    // <?php
    // $cb->logout();
    // ?>
    // console.log("test");
    $.ajax( "../ajax_php/sign_out.php" )
      .done(function(data) {
        console.log("successful logout");
        // $('#twitter').html(data);
        window.location = "/login.php";
      })
      .fail(function(data) {
        console.log("error");
      });
    document.cookie = "PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
  
  }
</script>
<nav class="navbar navbar-inverse " style = "margin-bottom: 0px">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="/" class="navbar-brand"><img src="/images/cplogo.png"></a>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id=".navbar-collapse">
      <ul class="nav navbar-nav">
        <li><a href="/">Home</a></li>
        <li><a href="/about.html">About</a></li>
        <?php
          error_reporting(0);
          session_start();
          if($_SESSION['login_email'] <> "") {
            echo '<li><a href="php/cProfile.php?target=profile&action=display&email='.$_SESSION["login_email"].'">Profile</a></li>';
          }
        ?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a id = "logout-button" href="/login.php" onclick = "logOut();">Log Out</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
