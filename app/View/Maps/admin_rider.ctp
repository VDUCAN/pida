<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAP_KEY;?>&v=3.exp&sensor=false"></script>  
<div class="container-fluid container-fullw bg-white">
  <div class="row">
        <div class="col-sm-12 col-md-12">
	  
	  <?php /* ?>  
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="row" style="height: 373px;">
                  <div style="position: relative; z-index: 10; left: 112px;">
                    <div class="col-sm-3" >
                      <?php echo $this->Form->select("country_id",$allcountries, array('empty' => "Select Country", 'class' => 'textbox form-control input-sm','id'=>'countryval')); ?>
                    </div>
                   
                    <div class="col-sm-3" >
                      <?php echo $this->Form->select("state_id",array(), array('empty' => "Select State", 'class' => 'textbox form-control input-sm','id'=>'stateval')); ?>
                    </div>
                    <div class="col-sm-3">
                      <?php echo $this->Form->select("cityid",array(), array('empty' => "Select City", 'class' => 'textbox form-control input-sm','id'=>'cityval1')); ?>
                    </div>
                  </div>
              
                <div id="map-canvas" style="width:100%; height:390px; top: -45px;"></div>
             
              </div>
              <!-- row -->
            </div>
            <!-- panel-body -->
          </div>
	   <?php */ ?> 
	    
          <!-- panel -->
        </div>
        <!-- col-sm-9 -->
       
      </div>
      <!-- row -->
    
    <div class="row">
        <div class="col-md-12">
       <div id="map-canvas" style="width:100%; height:390px; top: -4px;"></div>
       
        </div>
    </div>
</div>
<style>
      html, body, #map-canvas {
        height: 100%;
        width: 100%;
        margin: 0;
}
</style>


<?php /* ?>
<script>
    $(document).ready(function () { 
		initialize();

    });
var map;
lat=41.8781;
lng=87.6298;
function initialize() {
  var mapOptions = {
    zoom: 12
  };
  map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);

  
     var pos = new google.maps.LatLng(lat,lng);
  // alert(lat);
     GetAddress(lat, lng);
     
     var marker = new google.maps.Marker({
            position: pos,
            map: map,
            icon:'<?php //echo WEBSITE_IMAGE_URL.'green-car.png'; ?>'
            });

      map.setCenter(pos);
  
  }



 function GetAddress(lat, lng) {
  
        var latlng = new google.maps.LatLng(lat, lng);
        var geocoder = geocoder = new google.maps.Geocoder();
        geocoder.geocode({'latLng': latlng}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                  //  alert("Location: " + results[1].formatted_address);
                   // $('#DeliveryLocation').val(results[1].formatted_address);
                    var myOptions = {
                        zoom: 12,
                        center: latlng,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    }
                }
            }
        });
}

function codeAddress() {
var geocoder = new google.maps.Geocoder();

    var address = document.getElementById('DeliveryLocation').value;
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
        var marker = new google.maps.Marker({
            map: map,
           
            position: results[0].geometry.location,
             
        });
      } else {
        alert('Geocode was not successful for the following reason: ' + status);
      }
    });
  }


function handleNoGeolocation(errorFlag) {
  if (errorFlag) {
    var content = 'Error: The Geolocation service failed.';
  } else {
    var content = 'Error: Your browser doesn\'t support geolocation.';
  }

  var options = {
    map: map,
    position: new google.maps.LatLng(41.8781, 87.6298),
   content: content
  };

  var infowindow = new google.maps.InfoWindow(options);
  map.setCenter(options.position);
}


google.maps.event.addDomListener(window, 'load', initialize);
</script>

<script>
  $(document).ready(function(){ //alert('fsdfsf');
	
	$("#countryval").on('change',function(){
	    var countryid = $(this).val();
                  //alert(countryid);
                    $.ajax({
						type:'POST',
						dataType: 'json',
						url:'<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'maps', 'action' => 'admin_getstate')); ?>',
						data:{id:countryid},
						success:function(subcat_data){ //console.log(subcat_data);
							options = "<option value=''><?php echo __('Select State'); ?></option>";
							$.each(subcat_data, function (index, value) {
								options += "<option value='" + index + "'>" + value + "</option>";
							});
							$("#stateval").empty().html(options);
						}
					});
                    
                    
		
	});
	
	
	
	$("#stateval").on('change',function(){
		 var country_id = $('#countryval').val();
	     var stateid    = $(this).val();
                  
                    $.ajax({
						type:'POST',
						dataType: 'json',
						url:'<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'maps', 'action' => 'admin_getcity')); ?>',
						data:{country_id:country_id ,stateid:stateid},
						success:function(subcat_data){ 
							options = "<option value=''><?php echo __('Select City'); ?></option>";
							$.each(subcat_data, function (index, value) {
								options += "<option value='" + index + "'>" + value + "</option>";
							});
							$("#cityval1").empty().html(options);
						}
					});
    });
	
	
		$("#cityval1").on('change',function(){  
	    var cityval_id = $(this).val();
	    var stateval_id = $('#stateval').val();
	    var country_id = $('#countryval').val();
	    
	                $.ajax({
						type:'POST',
						dataType: 'html',
						url:'<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'maps', 'action' => 'admin_getriderlatlong')); ?>',
						data:{country_id:country_id,stateval_id:stateval_id,cityval_id:cityval_id},
						success:function(subcat){  
							//alert(subcat);

							$('#map-canvas').html('');
							$('#map-canvas').html(subcat);
						}
					});
                    
                    
		
	});   
	
	
	
});
</script>
<?php //echo $this->Html->script('http://maps.google.com/maps/api/js?sensor=true', false); ?>

<?php */ ?>

<script>
    
$(document).ready(function () { 
	initialize();
});
    
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