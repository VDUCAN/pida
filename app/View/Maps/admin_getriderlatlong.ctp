<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAP_KEY;?>&v=3.exp&sensor=false"></script>  
<div id='map-canvas' style="width:100%;height:100%;"></div>
<script>
var map;
var infoWindow;
// markersData variable stores the information necessary to each marker
var markersData =  <?php echo $jval; ?>


function initialize() {
   var mapOptions = {
	  center: new google.maps.LatLng(41.8781,87.6298),
      zoom: 12,
      mapTypeId: 'roadmap',
   };

   map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

   // a new Info Window is created
   infoWindow = new google.maps.InfoWindow();

   // Event that closes the Info Window with a click on the map
   google.maps.event.addListener(map, 'click', function() {
      infoWindow.close();
   });

   // Finally displayMarkers() function is called to begin the markers creation
   displayMarkers();
}
//google.maps.event.addDomListener(window, 'load', initialize);


// This function will iterate over markersData array
// creating markers with createMarker function
function displayMarkers(){

   // this variable sets the map bounds according to markers position
   var bounds = new google.maps.LatLngBounds();
   
   // for loop traverses markersData array calling createMarker function for each marker 
   for (var i = 0; i < markersData.length; i++){

      var latlng = new google.maps.LatLng(markersData[i].lat, markersData[i].lng);
      var name = markersData[i].name;
      var plate_no = markersData[i].plate_no;
      var image = markersData[i].image;
      var mobile = markersData[i].mobile;

      createMarker(latlng, name, plate_no, image, mobile);

      // marker position is added to bounds variable
      bounds.extend(latlng);  
   }

   // Finally the bounds variable is used to set the map bounds
   // with fitBounds() function
   map.fitBounds(bounds);
}

// This function creates each marker and it sets their Info Window content
function createMarker(latlng, name, plate_no, image, mobile){

   var marker = new google.maps.Marker({
      map: map,
      position: latlng,
      //title: name
      icon:'<?php //echo WEBSITE_IMAGE_URL.'green-car.png'; ?>',
      title: 'title will go here'
   });

   // This event expects a click on a marker
   // When this event is fired the Info Window content is created
   // and the Info Window is opened.
   google.maps.event.addListener(marker, 'click', function() {
	   
	    var iwContent = '<div id="iw_container" style="max-width: 200px;"><div class="iw_title"><strong>' + name + '</strong></div>';
       
        iwContent += '</div></div>';
     

      /*var iwContent = '<div id="iw_container">' +
          '<div class="iw_title">Narendra</div>' +
          '<div class="iw_content">Jaipur<br />Rajasthan<br />3485</div></div>';*/
      
      // Creating the content to be inserted in the infowindow
     
      
      // including content to the Info Window.
      infoWindow.setContent(iwContent);

      // opening the Info Window in the current map and at the current marker location.
      infoWindow.open(map, marker);
   });
}

</script>

