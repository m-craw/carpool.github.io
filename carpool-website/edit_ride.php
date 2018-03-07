<?php
    session_start();
    error_reporting(0);
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
      $login_status = $_SESSION['login_status'];
      if($login_status <> "logged_in") {
        session_destroy();
        echo 'window.location = "http://492ateam1.bitnamiapp.com/login.php";';
      }
      //get the login email of the logged in user
      $login_email = $_SESSION['login_email'];
      echo 'var login_email = "'.$login_email.'";';

      $route_id = $_POST['route_id'];

      require_once("php/DBConn.php");
      require_once("php/ModelEditRides.php");
      require_once("php/ModelMyRides2.php");
      $db    = new DBConn();
      $connection  = $db->connect();
      $modelEdit = new ModelEditRides();
      $ride = $modelEdit->getRideInfo($connection,$route_id);
      $model2 = new ModelMyRides2();


      foreach ($ride as $originalRide) {
        $email = $originalRide['route_id'];
        $start_address = $originalRide['start_address'];
        $start_lat = $originalRide['start_lat'];
        $start_lng = $originalRide['start_lng'];
        $start_google_place_id = $originalRide['start_google_place_id'];
        $end_address = $originalRide['end_address'];
        $end_lat = $originalRide['end_lat'];
        $end_lng = $originalRide['end_lng'];
        $end_google_place_id = $originalRide['end_google_place_id'];
        $time_window_start = $originalRide['time_window_start'];
        $time_window_end = $originalRide['time_window_end'];
        $create_date = $originalRide['create_date'];
        $status = $originalRide['status'];
        $type = $originalRide['type'];
      }
    ?>
    // alert(".." + "<?php echo $start_address;?>" );

    var emailValue = "<?php echo $login_email;?>";
    var originValue = "<?php echo $start_address;?>";
    var originLatValue = "<?php echo $start_lat;?>";
    var originLongValue = "<?php echo $start_lng;?>";
    var originPlaceIDValue = "<?php echo $start_google_place_id;?>";
    // var originSliderValue = $("#slider-search-origin" ).slider( "value" );
    var destinationValue = "<?php echo $end_address;?>";
    var destinationLatValue = "<?php echo $end_lat;?>";
    var destinationLongValue = "<?php echo $end_lng;?>";
    var destinationPlaceIDValue = "<?php echo $end_google_place_id;?>";

    var timeWindowStart = "<?php echo $time_window_start;?>";
    var timeWindowEnd = "<?php echo $time_window_end;?>";
    var radioStatusValue = "<?php echo $status;?>";

    var routeIdValue = "<?php echo $route_id;?>";
    var createDateValue = "<?php echo $create_date;?>";
    var typeValue = "<?php echo $type;?>";
  </script>
  
  <!--CDN for bootstrap-->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
  <!-- timepicki plugin css -->
  <link rel='stylesheet' type='text/css' href='/TimePicki/css/timepicki.css'/>
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
</head>

<body>
  <header>
    <div id = "includeHeader"></div>
  </header>

  <!-- end side menu section -->
         <!-- --------------------------------------------------------------------------------------------  -->
  <!-- map is inserted here -->
  <div id="map"></div>
  <div id="container-user-menu" style="background-color: black; padding: 10px;">
    <form action="/php/cMyRides.php" method="post">
      <input type="hidden" name="id" value=<?php echo '"'.$_SESSION['login_email'] .'"'; ?> >
      <input type="hidden" name="target" value="message">
      <input type="hidden" name="action" value="list">
      <input id = "my-rides-button" type="submit" value="My Rides">
    </form>
  </div>
  <!-- this section has the initial search bar, search ride, and edit ride all
       in the top left corner of the map -->
  <div id = "container-map-nav">
    <!-- initial search bar with google search and button to open side menu -->
    <div id = "container-map-search">
      <input name="search-input" id="search-input"  type="text" placeholder="Search"><!-- class="controls" -->
    </div>
    <!-- end search bar with menu button -->

    <!-- start container-edit-ride -->
    <div id = "container-edit-ride">
      <h3>Edit Ride</h3>
      <!-- google maps input field for edit:start location -->
      <div>
        <h3>Starting Location</h3>
        <input name="edit-box-origin-input" id="edit-box-origin-input" class="controls" type="text" placeholder="Enter an origin location" required>
        
      </div>

      <!-- google maps input field for edit:end location -->
      <div>
        <h3>Destination</h3>
        <input name="edit-box-destination-input" id="edit-box-destination-input" class="controls" type="text" placeholder="Enter a destination" required>
      </div>

      <!-- jquery ui plugin to have the user select the date for their ride -->
      <div>
        <h4>Ride Date</h4>
        <input type="text" name="date-edit-origin" id="date-edit-origin" required>
      </div>
      <!-- timepicki plugin to have the user select the start time for their ride -->
      <div>
        <h4>Minimum Time to Leave</h4>
        <input id='timepicker-edit-minimum' type='text' name='timepicker-edit-minimum' required>
      </div>
      <!-- timepicki plugin to have the user select the end time for their ride -->
      <div>
        <h4>Maximum Time to Leave</h4>
        <input id='timepicker-edit-maximum' type='text' name='timepicker-edit-maximum' required>
      </div>
      <br>
      <!-- radio button to classify your ride as as offered or requested -->
      <div>
        <h4>Ride Status</h4>
        <fieldset>
          <label id="radio-visible-label" for="radio-visible">Visible</label>
          <input type="radio" name="radio-edit-box" id="radio-visible" class = "input-radio-edit" value="VISIBLE" required>
          <label id="radio-hidden-label" for="radio-hidden">Hidden</label>
          <input type="radio" name="radio-edit-box" id="radio-hidden" class = "input-radio-edit" value="HIDDEN">
        </fieldset>
      </div>
      <!-- button to edit ride when clicked -->
      <button type="button" id="submit-edit-inputs" class="btn btn-default" aria-label="submit edit values">Update Ride</button>
      <button type="button" id="submit-delete-ride" class="btn btn-default" aria-label="submit hidden values">Delete Ride</button>
      <?php
        $printButton = $modelEdit->hasPassenger($connection,$route_id);
        if($printButton) {
          echo '<button type="button" id="submit-complete-ride" class="btn btn-default" aria-label="submit hidden values">Complete Ride</button>';
        }
      ?>
    </div>
    <!-- end container-edit-ride -->
  </div>

  <script>
    // initialize inputs with values of unedited ride
    function toDateRev(dt) {
      var date = dt.substring(5,7) + "/" + dt.substring(8,10) + "/" + dt.substring(0,4);
      return date;
    }
    function toTimeRev(dt) {
      var hour = dt.substring(11,13);
      var minutes = dt.substring(14,16);
      var hourNum = parseInt(hour);
      // alert(hourNum);
      if(hourNum == 0) {
        return "12:" + minutes + " AM";
      }
      else if(hourNum > 0 && hourNum < 10) {
        return "0" + hourNum + ":" + minutes + " AM";
      }
      else if(hourNum >= 10 && hourNum < 12) {
        return hourNum + ":" + minutes + " AM";
      }
      else if(hourNum == 12) {
        return hourNum + ":" + minutes + " PM";
      }
      else if(hourNum > 12) {
        hourNum = hourNum - 12;
        if(hourNum > 0 && hourNum < 10) {
          return "0" + hourNum + ":" + minutes + " PM";
        }
        else if(hourNum >= 10 && hourNum < 12) {
          return hourNum + ":" + minutes + " PM";
        }
        else if(hourNum == 12) {
          return hourNum + ":" + minutes + " AM";
        }
      }
    }
    document.getElementById("edit-box-origin-input").value = originValue;
    document.getElementById("edit-box-destination-input").value = destinationValue;
    document.getElementById("date-edit-origin").value = toDateRev(timeWindowStart);
    document.getElementById("timepicker-edit-minimum").value = toTimeRev(timeWindowStart);
    document.getElementById("timepicker-edit-maximum").value = toTimeRev(timeWindowEnd);
    if(radioStatusValue === "VISIBLE") {
      document.getElementById("radio-visible").checked = true;
    }
    else if(radioStatusValue === "HIDDEN") {
      document.getElementById("radio-hidden").checked = true;
    }
  </script>
  <!-- end container-map-nav -->
    <!-- ----------------------------------------------------------------------------------------------------  -->


    
    <script>
      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

      // "global" google maps variables to be used in all scopes of the page
      var currentLat = 0.0;
      var currentLong = 0.0;
      var map = null;
      var geocoder = null;
      var markers = [];
      var circles = [];
      var travel_mode = '';
      var directionsService = null;
      var directionsDisplay = null ;
      var rendererOptions = null;
      var search_box_origin_autocomplete = null;
      var search_box_destination_autocomplete = null;
      var edit_box_origin_autocomplete = null;
      var edit_box_destination_autocomplete = null;
      var search_box_origin_place = null;
      var search_box_destination_place = null;
      var edit_box_origin_place = null;
      var edit_box_destination_place = null;

      var originMarker = null;
      var destinationMarker = null;

      var rideOriginMarker = null;
      var tempRideOriginMarker = null;

      var sendMessageRideID = 0;
      var sendMessageContent = "";
      var sendMessageThreadName = "";
      var sendMessageUsername = "";

      var profileName = "";
      console.log("test33333");

      // this function initializes the map and all necessary fields associated with the map
      function initMap() {
        console.log("test4444");
        // {lat: 33.7818, lng: -118.1151},
        var origin_place_id = null;
        var destination_place_id = null;

        //edit the map object
        travel_mode = 'DRIVING';
        map = new google.maps.Map(document.getElementById('map'), {
          mapTypeControl: false,
          center: {lat: currentLat, lng: currentLong},
          zoom: 11
        });
        //initialize other map objects
        geocoder = new google.maps.Geocoder;

        directionsService = new google.maps.DirectionsService;
        rendererOptions = {
          suppressMarkers: true
        }
        directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
        directionsDisplay.setMap(map);
        directionsDisplaySearch = new google.maps.DirectionsRenderer(rendererOptions);
        directionsDisplaySearch.setMap(map);

        //search box objects
        var menu_container = document.getElementById('container-map-nav');
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(menu_container);
        var user_menu_container = document.getElementById('container-user-menu');
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(user_menu_container);

        var search_input = document.getElementById('search-input');
        var search_autocomplete = new google.maps.places.Autocomplete(search_input);
        search_autocomplete.bindTo('bounds', map);
        search_autocomplete.addListener('place_changed', function() {
          var place = search_autocomplete.getPlace();
          if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
          }
          expandViewportToFitPlace(map, place);
           search_place_id = place.place_id;
        });
        // map.controls[google.maps.ControlPosition.TOP_LEFT].push(search_input);




        //edit box inputs
        var edit_box_origin_input = document.getElementById('edit-box-origin-input');
        edit_box_origin_autocomplete = new google.maps.places.Autocomplete(edit_box_origin_input);
        edit_box_origin_autocomplete.bindTo('bounds', map);
        edit_box_origin_autocomplete.addListener('place_changed', function() {
          edit_box_origin_place = edit_box_origin_autocomplete.getPlace();
          if (!edit_box_origin_place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
          }
          expandViewportToFitPlace(map, edit_box_origin_place);

          setMapOnAll(null);
          directionsDisplay.setMap(null);
          directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
          directionsDisplay.setMap(map);

           edit_box_origin_place_id = edit_box_origin_place.place_id;
           if(originMarker != null) {
            originMarker.setMap(null);
           }
            
           originMarker = new google.maps.Marker({
              map: map,
              title: "Origin",
              place: {
                placeId: edit_box_origin_place.place_id,
                location: edit_box_origin_place.geometry.location
              },
              icon: "http://maps.google.com/mapfiles/ms/icons/red.png"
            });
          routeSearchInput(edit_box_origin_place_id , edit_box_destination_place_id, travel_mode,
                directionsService, directionsDisplay);
        });


        var edit_box_destination_input = document.getElementById('edit-box-destination-input');
        edit_box_destination_autocomplete = new google.maps.places.Autocomplete(edit_box_destination_input);
        edit_box_destination_autocomplete.bindTo('bounds', map);
        edit_box_destination_autocomplete.addListener('place_changed', function() {
          edit_box_destination_place = edit_box_destination_autocomplete.getPlace();
          if (!edit_box_destination_place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
          }
          expandViewportToFitPlace(map, edit_box_destination_place);

          setMapOnAll(null);
          directionsDisplay.setMap(null);
          directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
          directionsDisplay.setMap(map);

           edit_box_destination_place_id = edit_box_destination_place.place_id;
           if(destinationMarker != null) {
            destinationMarker.setMap(null);
           }
           destinationMarker = new google.maps.Marker({
              map: map,
              title: "Destination",
              place: {
                placeId: edit_box_destination_place.place_id,
                location: edit_box_destination_place.geometry.location
              },
              icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
            });
          routeSearchInput(edit_box_origin_place_id , edit_box_destination_place_id, travel_mode,
                directionsService, directionsDisplay);
        });
        //end edit box inputs

        function expandViewportToFitPlace(map, place) {
          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(13);
          }
        }
      }//end initMap()

      //moved to outside of init function in order to be usable by other functions
      function route(origin_place_id, destination_place_id, travel_mode,
                       directionsService, directionsDisplay) {
          if (!origin_place_id || !destination_place_id) {
            return;
          }
          directionsService.route({
            origin: {'placeId': origin_place_id},
            destination: {'placeId': destination_place_id},
            travelMode: travel_mode
          }, function(response, status) {
            if (status === 'OK') {
              directionsDisplay.setDirections(response);
            } else {
              window.alert('Directions request failed due to ' + status);
            }
          });
        }
        function routeSearchInput(origin_place_id, destination_place_id, travel_mode,
                       directionsService, directionsDisplaySearch) {
          if (!origin_place_id || !destination_place_id) {
            return;
          }
          directionsService.route({
            origin: {'placeId': origin_place_id},
            destination: {'placeId': destination_place_id},
            travelMode: travel_mode
          }, function(response, status) {
            if (status === 'OK') {
              directionsDisplaySearch.setDirections(response);
            } else {
              window.alert('Directions request failed due to ' + status);
            }
          });
        }

      // Sets the map on all markers in the array.
      function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
          markers[i].setMap(map);
        }
        for (var i = 0; i < circles.length; i++) {
          circles[i].setMap(map);
        }
      }


    </script>
    
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.js"></script>

    

    <!-- import timepicki script -->
    <script type='text/javascript' src='/TimePicki/js/jquery.min.js'></script>
    <script type='text/javascript' src='/TimePicki/js/timepicki.js'></script>
    <script type='text/javascript'> 
      $('#timepicker-edit-minimum').timepicki(); 
      $('#timepicker-edit-maximum').timepicki(); 
    </script>



    <!-- import jquery UI -->
    <script src="/jquery-ui-custom/external/jquery/jquery.js"></script>
    <script src="/jquery-ui-custom/jquery-ui.min.js"></script>

    <!-- datepicker and radio inputs -->
    <script type="text/javascript">
      $( "#date-edit-origin" ).datepicker();
      $( ".input-radio-edit" ).checkboxradio();
    </script>

<!-- ----------------------------------------------------------------------------------------------------  -->


    <script> 
      //functions to complete page setup
      $(function(){
        $("#includeHeader").load("/inserts/header.php"); 
      });
    </script> 

    <!-- GOOGLE MAPS API KEY GOES HERE -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-DZddl-gCY_8r8BjAGfk9DtrQVRUCa30&libraries=places,geometry"
      ></script>



    <script>
      currentLat = parseFloat(originLatValue);
      currentLong = parseFloat(originLongValue);
      //initialize map after the user's location is found
      initMap();
    </script>

    <div id="result"></div>

    <script>
      // this function converts a date and time string into the mysql datetime format
      function getDateTimeFormat(date, time) {
        var tempTime = time;
        var ampm = time.substring(6,8);
        var dateTime = "";
        if (ampm == "PM") {
          tempTime = parseInt(time.substring(0,2));
          tempTime = tempTime + 12;
          var hour = tempTime.toString();
          if(hour.length == 1) {
            hour = "0" + hour;
          }
          return date + hour + ":" + time.substring(3,5) + ":00"; 
        }
        else {
          return date + time.substring(0,2) + ":" + time.substring(3,5) + ":00"; 
        }
      }

      $(document).ready(function(){

        //edit ride
        $("#submit-edit-inputs").click(function(){
          originValue = document.getElementById("edit-box-origin-input").value;
          originLatValue = edit_box_origin_place.geometry.location.lat();
          originLongValue = edit_box_origin_place.geometry.location.lng();
          originPlaceIDValue = edit_box_origin_place.place_id;
          destinationValue = document.getElementById("edit-box-destination-input").value;
          destinationLatValue = edit_box_destination_place.geometry.location.lat();
          destinationLongValue = edit_box_destination_place.geometry.location.lng();
          destinationPlaceIDValue = edit_box_destination_place.place_id;
          var dateValue = document.getElementById("date-edit-origin").value;
          dateValue = dateValue.substring(6,10) + "-" + dateValue.substring(0,2) + "-" + dateValue.substring(3,5) + " ";
          var timeMinValue = document.getElementById("timepicker-edit-minimum").value;
          var timeMaxValue = document.getElementById("timepicker-edit-maximum").value;
          timeWindowStart = getDateTimeFormat(dateValue, timeMinValue);
          timeWindowEnd = getDateTimeFormat(dateValue, timeMaxValue);
          radioStatusValue = $("input[name='radio-edit-box']:checked").val();
          // alert(originValue + "..." + timeWindowStart + "..." + timeWindowEnd + "..." + originPlaceIDValue);
          // ajax post function to add rides to database

          $.post("ajax_php/update_ride.php/",
          {
            target: "UPDATE",
            route_id_value: routeIdValue,
            radio_status_value: radioStatusValue,
            email_value: emailValue,
            origin_value: originValue, 
            origin_lat_value: originLatValue, 
            origin_long_value: originLongValue, 
            origin_place_id_value: originPlaceIDValue, 
            // origin_slider_value: originSliderValue,
            destination_value: destinationValue,
            destination_lat_value: destinationLatValue,
            destination_long_value: destinationLongValue,
            destination_place_id_value: destinationPlaceIDValue,
            // destination_slider_value: destinationSliderValue,
            // date_value: dateValue,
            time_window_start: timeWindowStart,
            time_window_end: timeWindowEnd,
            create_date_value: createDateValue,
            type_value: typeValue
          }, function(data){
              $('#result').html(data);
              <?php
                $_SESSION['id'] = $login_email;
                $_SESSION['target'] = 'message';
                $_SESSION['action'] = 'list';
                echo 'window.location = "/php/cMyRides.php";';
              ?>
                // alert(data);
                // location.reload();
          });//end ajax post
        });//end edit ride
        //delete
        $("#submit-delete-ride").click(function(){
          $.post("ajax_php/update_ride.php/",
          {
            target: "DELETE",
            route_id_value: routeIdValue,
            radio_status_value: "CLOSED"
          }, function(data){
              $('#result').html(data);
              <?php
                $_SESSION['id'] = $login_email;
                $_SESSION['target'] = 'message';
                $_SESSION['action'] = 'list';
                echo 'window.location = "/php/cMyRides.php";';
              ?>
                // alert(data);
                // location.reload();
          });//end ajax post
        });//end delete ride
        $("#submit-complete-ride").click(function(){
          $.post("ajax_php/update_ride.php/",
          {
            target: "COMPLETE",
            route_id_value: routeIdValue,
            email_value: login_email,
            radio_status_value: "COMPLETED"
          }, function(data){
              $('#result').html(data);
              <?php
                $_SESSION['id'] = $login_email;
                $_SESSION['target'] = 'message';
                $_SESSION['action'] = 'list';
                echo 'window.location = "/php/cMyRides.php";';
              ?>
                // alert(data);
                // location.reload();
          });//end ajax post
        });//end delete ride
      });//end document.ready

    </script>

   
      
    <!-- Latest compiled and minified JavaScript needed for bootstrap-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</body>

</html>