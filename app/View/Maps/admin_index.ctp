<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAP_KEY;?>&v=3.exp&sensor=false"></script> 
<div class="container-fluid container-fullw bg-white">
  <div class="row">
        <div class="col-sm-12 col-md-12">
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
              
		  <div id="map-canvas" style="width:100%; height:390px; top: -45px;">
		  </div>
             
              </div>
              <!-- row -->
            </div>
            <!-- panel-body -->
          </div>
          <!-- panel -->
        </div>
        <!-- col-sm-9 -->
       
      </div>
      <!-- row -->
    
    <div class="row">
        <div class="col-md-12">
<!--       <div id="map-canvas" style="width:100%; height:390px; top: -4px;"></div>-->
       
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
<script>
    $(document).ready(function () { 
         setMapMakers();
    });

    function setMapMakers(countryId=0,stateId=0,cityId=0){
   //   initialize();
      $.ajax({
              type:'POST',
              dataType: 'html',
              url:'<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'maps', 'action' => 'admin_getlatlong')); ?>',
              data:{country_id:countryId,stateval_id:stateId,cityval_id:cityId},
              success:function(subcat){  

               var data = $.parseJSON(subcat);

               if(data){
                //alert(data.Driver.id);
                //var markerArr;
                var boundsArr = new Array;
                $.each(data, function(i, item){
                  //console.log(item);
                  //alert(item.driverid);
                  var name = item.name;
                  var lat = item.lat;
                  var long = item.lng;
                  //var add =  locations[i][3]

                  latlngset = new google.maps.LatLng(lat, long);

                  var marker = new google.maps.Marker({  
                    map: map, title: name , position: latlngset  
                  });
                  map.setCenter(marker.getPosition())
                  //markerArr.push(marker);

                  var content = "Name: " + name ;  

                  var infowindow = new google.maps.InfoWindow()

                  google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
                    return function() {
                      infowindow.setContent(content);
                      infowindow.open(map,marker);
                    };
                  })(marker,content,infowindow)); 

                  var bounds = new google.maps.LatLngBounds();
                  bounds.extend(marker.getPosition());
                  boundsArr.push(bounds);
                });
                map.fitBounds(boundsArr);
                //alert(subcat);

                $('#map-canvas').html('');
                $('#map-canvas').html(subcat);
                }else{
                  initialize();
                }

              }
            });




    }



    var map;
    lat=41.8781;
    lng=87.6298; 

    function initialize() {

        var mapOptions = {
          zoom: 2
        };
        map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);

        var pos = new google.maps.LatLng(lat,lng);
        // alert(lat);
        GetAddress(lat, lng);

        /*
        var marker = new google.maps.Marker({
          position: pos,
          map: map,
          icon:'<?php //echo WEBSITE_IMAGE_URL.'green-car.png'; ?>'
        }); */

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
                        zoom: 7,
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

              initialize();

              setMapMakers(countryid);
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

        initialize();
        setMapMakers(country_id,stateid);

       }

    });
 });
	
	
  $("#cityval1").on('change',function(){  
   var cityval_id = $(this).val();
   var stateval_id = $('#stateval').val();
   var country_id = $('#countryval').val();

   initialize();
   setMapMakers(country_id,stateval_id,cityval_id);

 });   
	
	
	
});
</script>
<?php //echo $this->Html->script('http://maps.google.com/maps/api/js?sensor=true', false); ?>

