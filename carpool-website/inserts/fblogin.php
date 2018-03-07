<script>
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      // testAPI();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      // statusChangeCallback(response);
      statusChangeCallback(response);
        getInfo();
    });
  }


  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1804550726431791',//actual appID------------------------------------------
      // appId      : '1255636187822549',//for testing----------------------------------------
      cookie     : true,  // enable cookies to allow the server to access 
                          // the session
      xfbml      : true,  // parse social plugins on this page
      version    : 'v2.7' // use graph api version 2.5
    });

    // Now that we've initialized the JavaScript SDK, we call 
    // FB.getLoginStatus().  This function gets the state of the
    // person visiting this page and can return one of three states to
    // the callback you provide.  They can be:
    //
    // 1. Logged into your app ('connected')
    // 2. Logged into Facebook, but not your app ('not_authorized')
    // 3. Not logged into Facebook and can't tell if they are logged into
    //    your app or not.
    //
    // These three cases are handled in the callback function.

    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.7&appId=1804550726431791";//actual app--------------
    // js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.7&appId=1255636187822549";//for testing----------
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));


  function getInfo() {
    FB.api('/me', {fields: 'last_name, first_name, email'}, function(response) {
      document.getElementById("fb-last-name").value = response.last_name;
      console.log(response.last_name);
      document.getElementById("fb-first-name").value = response.first_name;
      console.log(document.getElementById("fb-first-name").value);
      document.getElementById("fb-email").value = response.email;
      console.log(document.getElementById("fb-email").value);
      document.getElementById("fb-form").submit();
    });
  }
</script>