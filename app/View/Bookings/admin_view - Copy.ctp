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
  <div id="map" style="height:250px;margin-bottom: 30px;"></div>
   
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
          origin: '5X Muthaiga Rd  Nairobi  Kenya',
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
	<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initMap" async defer></script>
   


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
                        <label class="control-label text-bold">Price :</label>
                         <?php echo $this->data['Booking']['price']; ?> 
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
                        <label class="control-label text-bold">Type :</label>
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

