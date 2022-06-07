<?php 
    $option_status = array('1' => 'Open', '2' => 'Assign','3'=>'Cancel','4'=>'Arrived','5'=>'Completed','6'=>'Paid');
    $option_type = array('1' => 'Now', '2' => 'Scheduled');
?>
<style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #floating-panel {
        position: absolute;
        top: 10px;
        left: 25%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }
    </style>

<!-- start: PAGE TITLE -->
<section id="page-title">
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mainTitle">View Invoice</h1>
        </div>
    </div>
</section>
<!-- end: PAGE TITLE -->
<!-- Global Messages -->
<?php echo $this->Session->flash(); ?>
<!-- Global Messages End -->
<?php //pr($this->data['Booking']); ?>
<div class="container-fluid container-fullw bg-white">
  <div id="map-canvas" style="height:250px;margin-bottom: 30px;"></div>
<!--
    <script>
      function initMap() {
        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer;
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12,
          center: {lat: 41.85, lng: -87.65}
        });
        directionsDisplay.setMap(map);

       
          calculateAndDisplayRoute(directionsService, directionsDisplay);
      
       
      }

      function calculateAndDisplayRoute(directionsService, directionsDisplay) {
        directionsService.route({
          origin: 'Military Cantonment, Jaipur, Rajasthan',
          destination: 'Triton Mall, Jhotwara Road, Jhotwara, Jaipur, Rajasthan',
          travelMode: 'DRIVING'
        }, function(response, status) {
          if (status === 'OK') {
            directionsDisplay.setDirections(response);
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
      }
    </script>
	<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initMap" async defer></script>---->
   


    <div class="row">
        <div class="col-md-12">
	    <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Booking Id :</label>
                        <?php echo $this->data['Booking']['id']; ?>
                    </div>
                </div>
		
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Customer :</label>
		           <?php echo $this->data['User']['first_name'].' '.$this->data['User']['last_name']; ?>
                    </div>
                </div>
		
				<div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Customer Email :</label>
		           <?php echo $this->data['User']['email']; ?>
                    </div>
                </div>
		
				<div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Customer Phone:</label>
		           <?php echo $this->data['User']['phone']; ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Driver :</label>
                        <?php echo $this->data['Driver']['first_name'].' '.$this->data['Driver']['last_name']; ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Car plate :</label>
                        <?php echo $this->data['Vehicle']['plate_no']; ?>
                    </div>
                </div>
                
		
				<div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Driver Email :</label>
                        <?php echo $this->data['Driver']['email']; ?>
                    </div>
                </div>
		
				<div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Driver Phone:</label>
                        <?php echo $this->data['Driver']['phone']; ?>
                    </div>
                </div>
                
                

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Pickup date :</label>
                         <?php
			        if(!empty($this->data['Booking']['pickup_date']) && date('Y-m-d',strtotime($this->data['Booking']['pickup_date'])) != '1970-01-01'){
				    echo date(DATETIME_FORMAT,strtotime($this->data['Booking']['pickup_date']));
				}else{
				    echo '-';
				}
			 ?> 
                    </div>
                </div>
		
		<div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Total Charges :</label>
                         <?php echo '$ '.$this->data['Booking']['price']; ?> 
                    </div>
                </div>
		
		
		
		<div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Driver Amount :</label>
                         <?php echo '$ '.$this->data['Transaction']['driver_amount']; ?> 
                    </div>
                </div>
		
		<div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Pida Fee :</label>
                         <?php echo '$ '.$this->data['Transaction']['admin_pida_fee']; ?> 
                    </div>
                </div>
		
		<div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Pida Percentage :</label>
                         <?php echo '$ '.$this->data['Transaction']['admin_amount']; ?> 
                    </div>
                </div>
		
		<div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Total Pida charged  :</label>
                         <?php echo '$ '.$this->data['Transaction']['admin_total_amount']; ?> 
                    </div>
                </div>
		
		 <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Miles :</label>
                         <?php echo $this->data['Booking']['total_miles']; ?> 
                    </div>
                </div>
		
		 <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Booking Type :</label>
                         <?php 
				if($this->data['Booking']['booking_type']){
					echo $option_type[$this->data['Booking']['booking_type']];	
				}else{
				    echo '-';
				}
			 ?> 
                    </div>
                </div>
		
		 <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Vehicle Type :</label>
                        <?php if(!empty($this->data['CategoryLocale']['name'])){
					echo ucfirst($this->data['CategoryLocale']['name']);
			    }else{
					echo 'N/A';
			} ?>
                    </div>
                </div>
	
	
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Status :</label>
			<?php
				if($this->data['Booking']['booking_status']){
				    echo $option_status[$this->data['Booking']['booking_status']];
				}else{
				    echo '-';
				}
			?>	    
                    </div>
                </div>

            </div>
           
         
        </div>
    </div>
</div>


<?php if(!empty($this->data['BookingLocation'])) { ?>


  <?php
	$j = 1;
	foreach($this->data['BookingLocation'] as $location){ 
	
	//pr($location)
    ?>


<!-- start: PAGE TITLE -->
 <section id="page-title">
     <div class="row">
	 <div class="col-sm-8">
	     <h1 class="mainTitle"> Booking Location <?php echo $j;?>  </h1>
	 </div>
     </div>
 </section>

 <div class="clearfix"></div>



<div class="container-fluid container-fullw bg-white">
    
    <div class="row">
        <div class="col-md-12">
	   
	    <div class="row">
		
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label text-bold">Source Company :</label>
                        <?php echo $location['source_company_name']; ?>
                    </div>
                </div>
		
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label text-bold">Source Address :</label>
		         <?php echo $location['source_address']; ?>
                    </div>
                </div>
		 
		<div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label text-bold">Driver Arrived :</label>
		         <?php echo $location['driver_arrived_source']; ?>
                    </div>
                </div>

                <div class="clearfix"></div>
		
		
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label text-bold">Destination Company :</label>
                        <?php echo $location['destination_company_name']; ?>
                    </div>
                </div>
		
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label text-bold">Destination Address :</label>
		         <?php echo $location['destination_address']; ?>
                    </div>
                </div>
		 
		<div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label text-bold">Driver Arrived :</label>
		         <?php echo $location['driver_arrived_destination']; ?>
                    </div>
                </div>

                <div class="clearfix"></div>
	    </div>
           
         
        </div>
    </div>
    
</div>

<?php 
    $j++;
    } ?>

<?php  } ?>

<?php
//pr($this->data['BookingLocation']);


		$lat	=	$this->data['BookingLocation'][0]['source_lat'];
		$lng	=	$this->data['BookingLocation'][0]['source_long'];
	
	
		$route =  json_decode($this->data['BookingLocation'][0]['travel_route'], true);
		$travel_route[] = array(
			'latitude'=>$this->data['BookingLocation'][0]['source_lat'],
			'longitude'=>$this->data['BookingLocation'][0]['source_long']
			);
			
			
			
		if(!empty($route))
			foreach($route as $rs)
				$travel_route[] = array(
					'latitude'=>$rs['latitude'],
					'longitude'=>$rs['longitude']
					);

		$travel_route[] = array(
			'latitude'=>$this->data['BookingLocation'][0]['destination_lat'],
			'longitude'=>$this->data['BookingLocation'][0]['destination_long']
			);
		 // pr($travel_route); //die;
?>
<style>
	html, body, #map-canvas {
			height: 100%;
			margin: 0;
			padding: 0;
	}
</style> 
<script src="https://maps.googleapis.com/maps/api/js?&callback=initMap&signed_in=true" async defer></script>
<script>		
	 function initMap() {
			var directionsService = new google.maps.DirectionsService;
			var directionsDisplay = new google.maps.DirectionsRenderer;
			var map = new google.maps.Map(document.getElementById('map-canvas'), {
			  zoom: 11,
			  center: {lat: <?php echo $lat;?>, lng: <?php echo $lng; ?>}
			});			

			<?php 
			$waypoints = [];
			foreach ($route as $key => $value) {
				# code...
				$waypoints[] = "{ lat: ".$value['latitude'].", lng: ".$value['longitude']."}";
			}
			//pr($waypoints);
			?>
			var waypts = [<?php echo implode(", ", $waypoints) ?>];

			var flightPath = new google.maps.Polyline({
			  path: waypts,
			  geodesic: true,
			  strokeColor: '#0078d7',
			  strokeOpacity: 1.0,
			  strokeWeight: 3
			});

			flightPath.setMap(map);
		  	
			directionsDisplay.setMap(map);

			var myLatLng = {lat: <?php echo $route[0]['latitude'];?>, lng: <?php echo $route[0]['longitude']; ?>};
			var marker1 = new google.maps.Marker({
			  position: myLatLng,
			  map: map,
			  title: '<?php echo $this->data['BookingLocation'][0]['source_address'];?>'
			});
			
			var myLatLng = {lat: <?php echo $route[count($route) -1]['latitude'];?>, lng: <?php echo $route[count($route) -1]['longitude']; ?>};
			var marker2 = new google.maps.Marker({
			  position: myLatLng,
			  map: map,
			  title: '<?php echo $this->data['BookingLocation'][0]['destination_address'];?>'
			});
		    
			var bounds = new google.maps.LatLngBounds();
			bounds.extend(marker1.getPosition());
			bounds.extend(marker2.getPosition());
			map.fitBounds(bounds);
		  }
    </script>
<?php  ?>