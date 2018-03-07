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
    // console.log("terst");
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

        // $connection = mysqli_connect('localhost:3306', 'root', 'btXFvI3ZotGt', 'app_carpool');//server connection
        $connection = new mysqli('localhost', 'root', '', 'app_carpool');//local connection 

        if($connection->connect_errno > 0){
          mysqli_close($connection);
            die('Unable to connect to database');
        }
        
      $_SESSION['user_type'] = "PASSENGER";
      ?>
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
  <!-- the side menu section -->
  <div id = "container-side-menu-bar">
    <div class = "close-side-menu side-menu-button">
      <a class = "close-side-menu-button" href="#">Close Menu X</a>
    </div>
    <hr>
    <div class = "side-menu-button side-menu-button-search" onclick="addSearchRideBox">
      <a id = "side-menu-button-search-toggle" href="#">Search for a Ride</a>
    </div>
    <div class = "side-menu-button side-menu-button-create" onclick="addCreateRideBox">
      <a id = "side-menu-button-create-toggle" href="#">Create a Ride</a>
    </div>
  </div>
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
  <!-- this section has the initial search bar, search ride, and create ride all
       in the top left corner of the map -->
  <div id = "container-map-nav">
    <!-- initial search bar with google search and button to open side menu -->
    <div id = "container-map-search">
      <a id = "map-menu-button" href="#"><img id = "map-menu-button-image" src="/images/menu_hamburger_icon.png"></a>
      <input name="search-input" id="search-input"  type="text" placeholder="Search"><!-- class="controls" -->
    </div>
    <!-- end search bar with menu button -->

    <!-- start search ride box -->
    <div id = "container-search-ride">
      <!-- button to close the search ride box -->
      <button type="button" class="btn btn-danger close-menu-box" aria-label="close box" style="float: right;">
        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>CLOSE
      </button>

      <h3>Search for a Ride</h3>

      <!-- radio buttons to search for a requested or offered ride -->
      <fieldset>
        <!-- <legend>Search for a Ride: </legend> -->
        <label id="radio-requested-label" for="radio-requested">Requested</label>
        <input type="radio" name="radio-search-box"  class = "input-radio-search" id="radio-requested" value="REQUEST" required>
        <label id="radio-offered-label" for="radio-offered">Offered</label>
        <input type="radio" name="radio-search-box" class = "input-radio-search" id="radio-offered" value="OFFER">
      </fieldset>

      <!-- google maps input field for search:start location -->
      <div>
        <h3>Starting Location</h3>
        <input name="search-box-origin-input" id="search-box-origin-input" class="controls" type="text" placeholder="Enter an origin location" required>
        <!-- checkbox to start at current location
             Note: currently not working -->
        <input id="checkbox-search" type="checkbox" name="checkbox-search-origin" value="startAtCurrentLocation">Start at current location (approximate)<br>
        <!-- slider to adjust range to look for rides from origin -->
        <h4>Range (miles within)</h4>
        <div id="slider-search-origin">
          <div id="slider-handle-search-origin" class="ui-slider-handle"></div>
        </div>
      </div>

      <!-- google maps input field for search:destination location -->
      <div>
        <h3>Destination</h3>
        <input name="search-box-destination-input" id="search-box-destination-input" class="controls" type="text" placeholder="Enter a destination" required>
        <!-- slider to adjust range to look for rides from destination -->
        <h4>Range (miles within)</h4>
        <div id="slider-search-destination">
          <div id="slider-handle-search-destination" class="ui-slider-handle"></div>
        </div>
      </div>

      <!-- jquery ui plugin to have the user select the date to filter for rides -->
      <div>
        <h4>Ride Date</h4>
        <input type="text" name="date-search-origin" id="date-search-origin" required>
      </div>
      <!-- timepicki plugin to have the user select the start time to filter for rides -->
      <div>
        <h4>Minimum Time to Leave</h4>
        <input id='timepicker-search-minimum' type='text' name='timepicker-search-minimum' required>
      </div>
      <!-- timepicki plugin to have the user select the end time to filter for rides -->
      <div>
        <h4>Maximum Time to Leave</h4>
        <input id='timepicker-search-maximum' type='text' name='timepicker-search-maximum' required>
      </div>
      <br>
      <!-- button to search for rides when clicked -->
      <button type="button" id="submit-search-inputs" class="btn btn-default" aria-label="submit search values">Search for a Ride</button>
    </div> 
    <!-- end container-search-ride -->

    <!-- start container-create-ride -->
    <div id = "container-create-ride">
      <!-- button to close the create ride box -->
      <button type="button" class="btn btn-danger close-menu-box" aria-label="close box" style="float: right;">
        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>CLOSE
      </button>
      
      <h3>Create a Ride</h3>
      <!-- radio button to classify your ride as as offered or requested -->
      <fieldset>
        <label id="radio-request-label" for="radio-request">Request</label>
        <input type="radio" name="radio-create-box" id="radio-request" class = "input-radio-create" value="REQUEST" required>
        <label id="radio-offer-label" for="radio-offer">Offer</label>
        <input type="radio" name="radio-create-box" id="radio-offer" class = "input-radio-create" value="OFFER">
      </fieldset>

      <!-- google maps input field for create:start location -->
      <div>
        <h3>Starting Location</h3>
        <input name="create-box-origin-input" id="create-box-origin-input" class="controls" type="text" placeholder="Enter an origin location" required>
        <!-- checkbox to start at current location
             Note: currently not working -->
        <input id="checkbox-create" type="checkbox" name="checkbox-create-origin" value="startAtCurrentLocation">Start at current location (approximate)<br>
        <!-- <h4>Range (miles within)</h4>
        <div id="slider-create-origin">
          <div id="slider-handle-create-origin" class="ui-slider-handle"></div>
        </div> -->
      </div>

      <!-- google maps input field for create:end location -->
      <div>
        <h3>Destination</h3>
        <input name="create-box-destination-input" id="create-box-destination-input" class="controls" type="text" placeholder="Enter a destination" required>
        <!-- <h4>Range (miles within)</h4>
        <div id="slider-create-destination">
          <div id="slider-handle-create-destination" class="ui-slider-handle"></div>
        </div> -->
      </div>

      <!-- jquery ui plugin to have the user select the date for their ride -->
      <div>
        <h4>Ride Date</h4>
        <input type="text" name="date-create-origin" id="date-create-origin" required>
      </div>
      <!-- timepicki plugin to have the user select the start time for their ride -->
      <div>
        <h4>Minimum Time to Leave</h4>
        <input id='timepicker-create-minimum' type='text' name='timepicker-create-minimum' required>
      </div>
      <!-- timepicki plugin to have the user select the end time for their ride -->
      <div>
        <h4>Maximum Time to Leave</h4>
        <input id='timepicker-create-maximum' type='text' name='timepicker-create-maximum' required>
      </div>
      <br>
      <!-- button to create ride when clicked -->
      <button type="button" id="submit-create-inputs" class="btn btn-default" aria-label="submit create values">Create a Ride</button>
    </div>
    <!-- end container-create-ride -->
  </div>
  <!-- end container-map-nav -->
    <!-- ----------------------------------------------------------------------------------------------------  -->

  <div id="message-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Send Message to <span class="name-modal"></span></h4>
        </div>
        <div class="modal-body">
          <textarea id="message-modal-textarea" rows="10" cols="80" style="width: 100%;"></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="send-message" type="button" class="btn btn-primary">Send Message</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <div id="profile-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><span class="name-modal"></span>Profile</h4>
        </div>
        <div class="modal-body">
          <div id = "profile-modal-content"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <!-- <button id="view-profile" type="button" class="btn btn-primary">View Profile</button> -->
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
    
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
      var create_box_origin_autocomplete = null;
      var create_box_destination_autocomplete = null;
      var search_box_origin_place = null;
      var search_box_destination_place = null;
      var create_box_origin_place = null;
      var create_box_destination_place = null;

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

        //create the map object
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

        //Search box inputs
        //search box origin inputs
        var search_box_origin_input = document.getElementById('search-box-origin-input');
        search_box_origin_autocomplete = new google.maps.places.Autocomplete(search_box_origin_input);
        search_box_origin_autocomplete.bindTo('bounds', map);
        search_box_origin_autocomplete.addListener('place_changed', function() {
          search_box_origin_place = search_box_origin_autocomplete.getPlace();
          if (!search_box_origin_place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
          }
          expandViewportToFitPlace(map, search_box_origin_place);

          setMapOnAll(null);
          directionsDisplay.setMap(null);
          directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
          directionsDisplay.setMap(map);

           search_box_origin_place_id = search_box_origin_place.place_id;
           if(originMarker != null) {
            originMarker.setMap(null);
           }
           originMarker = new google.maps.Marker({
              map: map,
              title: "Origin",
              place: {
                placeId: search_box_origin_place.place_id,
                location: search_box_origin_place.geometry.location
              },
              icon: "http://maps.google.com/mapfiles/ms/icons/red.png"
            });
          routeSearchInput(search_box_origin_place_id , search_box_destination_place_id, travel_mode,
                directionsService, directionsDisplaySearch);
        });

        //search box destination inputs
        var search_box_destination_input = document.getElementById('search-box-destination-input');
        search_box_destination_autocomplete = new google.maps.places.Autocomplete(search_box_destination_input);
        search_box_destination_autocomplete.bindTo('bounds', map);
        search_box_destination_autocomplete.addListener('place_changed', function() {
          search_box_destination_place = search_box_destination_autocomplete.getPlace();
          if (!search_box_destination_place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
          }
          expandViewportToFitPlace(map, search_box_destination_place);
          
          setMapOnAll(null);
          directionsDisplay.setMap(null);
          directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
          directionsDisplay.setMap(map);

           search_box_destination_place_id = search_box_destination_place.place_id;
           if(destinationMarker != null) {
            destinationMarker.setMap(null);
           }
           destinationMarker = new google.maps.Marker({
              map: map,
              title: "Destination",
              place: {
                placeId: search_box_destination_place.place_id,
                location: search_box_destination_place.geometry.location
              },
              icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
            });
          routeSearchInput(search_box_origin_place_id , search_box_destination_place_id, travel_mode,
                directionsService, directionsDisplaySearch);
        });
        //end search box inputs


        //Create box inputs
        var create_box_origin_input = document.getElementById('create-box-origin-input');
        create_box_origin_autocomplete = new google.maps.places.Autocomplete(create_box_origin_input);
        create_box_origin_autocomplete.bindTo('bounds', map);
        create_box_origin_autocomplete.addListener('place_changed', function() {
          create_box_origin_place = create_box_origin_autocomplete.getPlace();
          if (!create_box_origin_place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
          }
          expandViewportToFitPlace(map, create_box_origin_place);

          setMapOnAll(null);
          directionsDisplay.setMap(null);
          directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
          directionsDisplay.setMap(map);

           create_box_origin_place_id = create_box_origin_place.place_id;
           if(originMarker != null) {
            originMarker.setMap(null);
           }
            
           originMarker = new google.maps.Marker({
              map: map,
              title: "Origin",
              place: {
                placeId: create_box_origin_place.place_id,
                location: create_box_origin_place.geometry.location
              },
              icon: "http://maps.google.com/mapfiles/ms/icons/red.png"
            });
          routeSearchInput(create_box_origin_place_id , create_box_destination_place_id, travel_mode,
                directionsService, directionsDisplay);
        });


        var create_box_destination_input = document.getElementById('create-box-destination-input');
        create_box_destination_autocomplete = new google.maps.places.Autocomplete(create_box_destination_input);
        create_box_destination_autocomplete.bindTo('bounds', map);
        create_box_destination_autocomplete.addListener('place_changed', function() {
          create_box_destination_place = create_box_destination_autocomplete.getPlace();
          if (!create_box_destination_place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
          }
          expandViewportToFitPlace(map, create_box_destination_place);

          setMapOnAll(null);
          directionsDisplay.setMap(null);
          directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
          directionsDisplay.setMap(map);

           create_box_destination_place_id = create_box_destination_place.place_id;
           if(destinationMarker != null) {
            destinationMarker.setMap(null);
           }
           destinationMarker = new google.maps.Marker({
              map: map,
              title: "Destination",
              place: {
                placeId: create_box_destination_place.place_id,
                location: create_box_destination_place.geometry.location
              },
              icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
            });
          routeSearchInput(create_box_origin_place_id , create_box_destination_place_id, travel_mode,
                directionsService, directionsDisplay);
        });
        //end create box inputs

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
      function revGeoSearchInput() {
        var latlng = {lat: currentLat, lng: currentLong};
        geocoder.geocode({'location': latlng}, function(results, status) {
          // alert(results[1].formatted_address + "...");
          if (status === 'OK') {
            if (results[1]) {
              document.getElementById("search-box-origin-input").value = results[1].formatted_address;
            } else {
              window.alert('No results found');
            }
          } else {
            window.alert('Geocoder failed due to: ' + status);
          }
        });
      }
      function revGeoCreateInput() {
        var latlng = {lat: currentLat, lng: currentLong};
        geocoder.geocode({'location': latlng}, function(results, status) {
          if (status === 'OK') {
            if (results[1]) {
              document.getElementById("create-box-origin-input").value = results[1].formatted_address;
            } else {
              window.alert('No results found');
            }
          } else {
            window.alert('Geocoder failed due to: ' + status);
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
      function clearMarkersAndCircles() {
        setMapOnAll(null);
      }
      // Deletes all markers in the array by removing references to them.
      function deleteMarkersAndCircles() {
        // clearMarkersAndCircles();
        setMapOnAll(null);
        markers.length = 0;
        circles.length = 0;
      }
      function addMarker(originLat, originLong, destinationLat, destinationLong, startAddress, endAddress, startTime, endTime,startGooglePlaceID,endGooglePlaceID,rideType,rideID, rideOwnerEmail) {
        var contentString = "Ride Type: " + rideType + "<br>Start Address: " + startAddress + "<br>End Address: " + endAddress + "<br>Start Time: " + startTime + "<br>End Time: " + endTime + 
        "<br><button id=\"message-button\" type=\"button\" class=\"btn btn-default\" aria-label=\"send message\">Send Message</button>" + 
        "<br><br><button id=\"profile-button\" type=\"button\" class=\"btn btn-default\" aria-label=\"view profile\">View Profile</button>";
        var infowindow = new google.maps.InfoWindow({
          content: contentString
        });
        google.maps.event.addListener(infowindow,'domready',function(){
          $('#message-button').click(function() {
            $('.name-modal').html(rideOwnerEmail);
            sendMessageRideID = rideID;
            $('#message-modal').modal();
          });
          $('#profile-button').click(function() {
              // alert("test");
              var view_profile = rideOwnerEmail;
              $.post("ajax_php/profile_info.php",
              {
                  email_view_profile: view_profile
              }, function(data){
                  $('#profile-modal-content').html(data);
              });//end ajax post
              $('.name-modal').html(profileName);
              $('#profile-modal').modal();
          });
        });
        var imageDestination = "";
        var imageOrigin = "";
        if(rideType == "REQUEST") {
          imageDestination = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
          imageOrigin = "http://maps.google.com/mapfiles/ms/icons/blue.png"
        }
        else if(rideType == "OFFER") {
          imageDestination = "http://maps.google.com/mapfiles/ms/icons/green-dot.png"
          imageOrigin = "http://maps.google.com/mapfiles/ms/icons/green.png"
        }
        var marker = new google.maps.Marker({
          position: 
          {
            lat: destinationLat,
            lng: destinationLong
          },
          map: map,
          icon: imageDestination
        });
        marker.addListener('click', function() {
          infowindow.open(map, marker);
          route(startGooglePlaceID,endGooglePlaceID, travel_mode,
                directionsService, directionsDisplay);
          if(rideOriginMarker != null) {
            rideOriginMarker.setMap(null);
          }
          rideOriginMarker = new google.maps.Marker({
              map: map,
              position: {
                lat: originLat,
                lng: originLong
              },
              icon: imageOrigin
            });
          markers.push(rideOriginMarker);
        });
        markers.push(marker);
      }
    </script>
    
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.js"></script>

    

    <!-- import timepicki script -->
    <script type='text/javascript' src='/TimePicki/js/jquery.min.js'></script>
    <script type='text/javascript' src='/TimePicki/js/timepicki.js'></script>
    <script type='text/javascript'> 
      $('#timepicker-search-minimum').timepicki(); 
      $('#timepicker-search-maximum').timepicki(); 
      $('#timepicker-create-minimum').timepicki(); 
      $('#timepicker-create-maximum').timepicki(); 
    </script>

    <script>
      //When the use current location check box is checked, call a function to 
      //reverse geoencode the current coordinates to a google api place
      $("#checkbox-search").click(function() {
        if(this.checked) {
          revGeoSearchInput();
        }
        else {
          document.getElementById("search-box-origin-input").value = "";
        }
      });
      $("#checkbox-create").click(function() {
        if(this.checked) {
          revGeoCreateInput();
        }
        else {
          document.getElementById("create-box-origin-input").value = "";
        }
      });
    </script>

    <!-- import jquery UI -->
    <script src="/jquery-ui-custom/external/jquery/jquery.js"></script>
    <script src="/jquery-ui-custom/jquery-ui.min.js"></script>

    <!-- datepicker and radio inputs -->
    <script type="text/javascript">
      $( "#date-search-origin" ).datepicker();
      $( "#date-create-origin" ).datepicker();
      $( ".input-radio-search" ).checkboxradio();
      $( ".input-radio-create" ).checkboxradio();
    </script>

<!-- ----------------------------------------------------------------------------------------------------  -->
    <script>
    //functions for map menu sliders
    $( function() {
      //search box::origin slider
      var slider_handle_search_origin = $( "#slider-handle-search-origin" );
      $( "#slider-search-origin" ).slider({
        min: 5,
        create: function() {
          slider_handle_search_origin.text( $( this ).slider( "value" ) );
        },
        slide: function( event, ui ) {
          slider_handle_search_origin.text( ui.value );
        }
      });

      //search box::destination slider
      var slider_handle_search_destination = $( "#slider-handle-search-destination" );
      $( "#slider-search-destination" ).slider({
        min: 5,
        create: function() {
          slider_handle_search_destination.text( $( this ).slider( "value" ) );
        },
        slide: function( event, ui ) {
          slider_handle_search_destination.text( ui.value );
        }
      });

    } );
    </script>


    <script> 
      //functions to complete page setup
      $(function(){
        $("#includeHeader").load("/inserts/header.php"); 
        // $("#includeFooter").load("/inserts/footer.php"); 
      });
    </script> 

    <!-- GOOGLE MAPS API KEY GOES HERE -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-DZddl-gCY_8r8BjAGfk9DtrQVRUCa30&libraries=places,geometry"
      ></script>

    <script>
    $( document ).ready(function() {
      $('#send-message').on("click",function() {
        // alert("message sent");
        sendMessageThreadName = login_email;
        sendMessageUsername = login_email;
        sendMessageContent = $('#message-modal-textarea').val();
        // alert("message sent");
        $.post("php/cMyRides.php",
          {
              target: "message",
              action: "save",
              route_id: sendMessageRideID,
              message: sendMessageContent,
              thread_name: sendMessageThreadName,
              id: sendMessageUsername
          }, function(data){
              // $('#result').html(data);
              alert("Message Sent");
          });//end ajax post
      });
    });
    </script>

    <script>
      console.log("test111");
      // initMap();
      $.getJSON('http://ip-api.com/json/', function(data) {
        /* JSON Object returns these values keys with values
        use data object to retrieve values, Ex: data.key
        city:
        country_code:
        country_name:
        ip:
        latitude:
        longitude:
        metro_code:
        region_code:
        region_name:
        time_zone:
        zip_code:
        */
        currentLat = parseFloat(data.lat);
        currentLong = parseFloat(data.lon);
        // currentLat = 33.781881;
        // currentLong = -118.114893;
        // console.log(currentLat + "......" + currentLong);

        //initialize map after the user's location is found
        initMap();
        //populate map with markers of current rides
        <?php
        // search for rides
        date_default_timezone_set('America/Los_Angeles');
        $time_window_now = date("Y-m-d H:i:s");
        $statement = "select * from routes where time_window_start <= '$time_window_now' and time_window_end >= '$time_window_now' and status = 'VISIBLE';";
        $result = mysqli_query($connection, $statement);
        if(mysqli_num_rows($result) > 0) {
          $comma_first = 0;
          echo "var ridesFound = [";
          while($row = mysqli_fetch_assoc($result)) {
            if($comma_first == 1) {
              echo ',';
            }
            else {
              $comma_first = 1;
            }
            echo '["'.$row["route_id"].'", "'.$row["email"].'", "'.$row["start_address"].'", "'.$row["start_lat"].'", "'.$row["start_lng"].'","'.$row['start_google_place_id'].'", "'.$row["end_address"].'", "'.$row["end_lat"].'", "'.$row["end_lng"].'", "'.$row['end_google_place_id'].'", "'.$row["time_window_start"].'", "'.$row["time_window_end"].'", "'.$row["create_date"].'", "'.$row["status"].'", "'.$row["type"].'"]';
          }
          echo "];";
        }
        else {
          echo 'var ridesFound = [];';
        }
        ?>
        //set up circle object
        var initialLocationCircle = new google.maps.Circle({
          map: map,
          strokeColor: "#000000",
          strokeOpacity: 0.0,
          strokeWeight: 0,
          fillColor: "#ffffff",
          fillOpacity: 0.0,
          center: new google.maps.LatLng(currentLat, currentLong),
          radius:  1609 * 5
        });
        circles.push(initialLocationCircle);
        //add all rides that have been found to map
        for (var i = 0; i < ridesFound.length; i++) {
          if(google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(parseFloat(ridesFound[i][7]), parseFloat(ridesFound[i][8])), initialLocationCircle.getCenter()) <= initialLocationCircle.getRadius()) {

            addMarker(parseFloat(ridesFound[i][3]), parseFloat(ridesFound[i][4]),parseFloat(ridesFound[i][7]), parseFloat(ridesFound[i][8]), ridesFound[i][2],ridesFound[i][6],ridesFound[i][10],ridesFound[i][11], ridesFound[i][5],ridesFound[i][9],ridesFound[i][14],ridesFound[i][0],ridesFound[i][1]);
          }
        }
      });
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
        //search for rides based on search criteria
        $("#submit-search-inputs").click(function(){
          // setMapOnAll(null);
          deleteMarkersAndCircles();
          directionsDisplay.setMap(null);
          directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
          directionsDisplay.setMap(map);
          var radioValue = $("input[name='radio-search-box']:checked").val();
          var originValue = document.getElementById("search-box-origin-input").value;
          var originSliderValue = $("#slider-search-origin" ).slider( "value" );
          var destinationValue = document.getElementById("search-box-destination-input").value;
          var destinationSliderValue = $("#slider-search-destination" ).slider( "value" );
          var dateValue = document.getElementById("date-search-origin").value;
          dateValue = dateValue.substring(6,10) + "-" + dateValue.substring(0,2) + "-" + dateValue.substring(3,5) + " ";
          var timeMinValue = document.getElementById("timepicker-search-minimum").value;
          var timeMaxValue = document.getElementById("timepicker-search-maximum").value;
          var timeWindowStart = getDateTimeFormat(dateValue, timeMinValue);
          var timeWindowEnd = getDateTimeFormat(dateValue, timeMaxValue);
          // ajax function to post to php to query through database for rides
          $.post("ajax_php/search_rides.php",
          {
              radio_value: radioValue,
              time_window_start: timeWindowStart,
              time_window_end: timeWindowEnd
          }, function(data){
              $('#result').html(data);

              if(ridesFound.length > 0) {
                var originCircle = new google.maps.Circle({
                  map: map,
                  strokeColor: "#000000",
                  strokeOpacity: 0.4,
                  strokeWeight: 1,
                  fillColor: "#ffffff",
                  fillOpacity: 0.1,
                  center: search_box_origin_place.geometry.location,
                  radius:  1609 * parseInt(originSliderValue)
                });
                var destinationCircle = new google.maps.Circle({
                  map: map,
                  strokeColor: "#000000",
                  strokeOpacity: 0.4,
                  strokeWeight: 1,
                  fillColor: "#ffffff",
                  fillOpacity: 0.1,
                  center: search_box_destination_place.geometry.location,
                  radius:  1609 * parseInt(destinationSliderValue)
                });
                circles.push(originCircle);
                circles.push(destinationCircle);
                for (var i = 0; i < ridesFound.length; i++) {
                  if(google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(parseFloat(ridesFound[i][3]), parseFloat(ridesFound[i][4])), originCircle.getCenter()) <= originCircle.getRadius()) {

                    if(google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(parseFloat(ridesFound[i][7]), parseFloat(ridesFound[i][8])), destinationCircle.getCenter()) <= destinationCircle.getRadius()) {
                      addMarker(parseFloat(ridesFound[i][3]), parseFloat(ridesFound[i][4]),parseFloat(ridesFound[i][7]), parseFloat(ridesFound[i][8]), ridesFound[i][2],ridesFound[i][6],ridesFound[i][10],ridesFound[i][11], ridesFound[i][5],ridesFound[i][9],ridesFound[i][14],ridesFound[i][0],ridesFound[i][1]);
                    }
                  }
                }//end for loop
                var bounds2 = new google.maps.LatLngBounds();
                for (var i = 0; i < markers.length; i++) {
                 bounds2.extend(markers[i].getPosition());
                }

                map.fitBounds(bounds2);
              }//end if rides found > 0
          });//end ajax post
        });//end submit search inputs

        //create ride
        $("#submit-create-inputs").click(function(){
          var emailValue = <?php echo '"'.$_SESSION['login_email'].'";';?>
          var originValue = document.getElementById("create-box-origin-input").value;
          var originLatValue = create_box_origin_place.geometry.location.lat();
          var originLongValue = create_box_origin_place.geometry.location.lng();
          var originPlaceIDValue = create_box_origin_place.place_id;
          // var originSliderValue = $("#slider-search-origin" ).slider( "value" );
          var destinationValue = document.getElementById("create-box-destination-input").value;
          var destinationLatValue = create_box_destination_place.geometry.location.lat();
          var destinationLongValue = create_box_destination_place.geometry.location.lng();
          var destinationPlaceIDValue = create_box_destination_place.place_id;
          // var destinationSliderValue = $("#slider-search-destination" ).slider( "value" );
          var dateValue = document.getElementById("date-create-origin").value;
          dateValue = dateValue.substring(6,10) + "-" + dateValue.substring(0,2) + "-" + dateValue.substring(3,5) + " ";
          var timeMinValue = document.getElementById("timepicker-create-minimum").value;
          var timeMaxValue = document.getElementById("timepicker-create-maximum").value;
          var timeWindowStart = getDateTimeFormat(dateValue, timeMinValue);
          var timeWindowEnd = getDateTimeFormat(dateValue, timeMaxValue);
          var radioTypeValue = $("input[name='radio-create-box']:checked").val();

          // ajax post function to add rides to database
          $.post("ajax_php/create_ride.php/",
          {
              radio_type_value: radioTypeValue,
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
              time_window_end: timeWindowEnd
          }, function(data){
              $('#result').html(data);
                alert("Ride Created");
                location.reload();
          });//end ajax post
        });//end create ride
      });//end document.ready

    </script>



    <script>
      //script to animate map and side menu
      $(document).ready(function(){
        $("#container-search-ride").hide();
        $("#container-create-ride").hide();

        $("#map-menu-button").click(function(){
          // $("#container-side-menu-bar").toggle();
          $("#container-side-menu-bar").animate({left: '0px'}, 400);
          $("#map").animate({left: '240px'}, 400, function (){
            $("#map").addClass("decrease-map-width");
          });
          
        });
        $(".close-side-menu").click(function(){
          // $("#container-side-menu-bar").toggle();
          $("#map").removeClass("decrease-map-width");
          // $("#map").addClass("increase-map-width");
          $("#container-side-menu-bar").animate({left: '-240px'}, 400);
          $("#map").animate({left: '0px'}, 400);
          $("#container-map-search").show();
          $("#container-search-ride").hide();
          $("#container-create-ride").hide();
        });
        $(".side-menu-button-search").click(function(){
          $("#container-map-search").hide();
          $("#container-search-ride").show();
          $("#container-create-ride").hide();
        });
        $(".side-menu-button-create").click(function(){
          $("#container-map-search").hide();
          $("#container-search-ride").hide();
          $("#container-create-ride").show();
        });
        $(".close-menu-box").click(function(){
          $("#container-map-search").show();
          $("#container-search-ride").hide();
          $("#container-create-ride").hide();
        });
      }); 
    </script>    
      
    <!-- Latest compiled and minified JavaScript needed for bootstrap-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</body>

</html>
