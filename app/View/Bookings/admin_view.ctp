<?php 
    $option_status = array('1' => 'Open', '2' => 'Assign','3'=>'Cancel','4'=>'Arrived','5'=>'Completed','6'=>'Paid');
    $option_type = array('1' => 'Now', '2' => 'Scheduled');
    $checkStatus = array('2','4');
?>

<!-- start: PAGE TITLE -->
<section id="page-title">
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mainTitle">View Booking</h1>
        </div>
    </div>
</section>
<!-- end: PAGE TITLE -->
<!-- Global Messages -->
<?php echo $this->Session->flash(); ?>
<!-- Global Messages End -->

<div class="container-fluid container-fullw bg-white">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <?php
                /*    if (!empty($this->data['Booking']['driver_id'])) {
                        echo $this->Html->link('Reject', array(
                                'controller' => 'bookings', 'action' => 'rejectBookingRequest', 'admin' => true, $this->data['Booking']['driver_id'], $this->data['Booking']['id'], 'admin_cancelled'
                            ), array(
                                'class' => 'btn btn-primary btn-wide pull-right margin-right-10',
                                'type' => 'button',
                                'id' => 'reject'
                            )
                        );
                    } */

                    if (!empty($this->data['Booking']['booking_status']) && (in_array($this->data['Booking']['booking_status'],$checkStatus))) {
                        echo $this->Html->link('Complete', array(
                                'controller' => 'apis', 'action' => 'bookingComplete', 'admin' => true,  $this->data['Booking']['id']
                            ), array(
                                'class' => 'btn btn-primary btn-wide pull-right margin-right-10',
                                'type' => 'button'
                            )
                        );
                    }    
                ?>
            </div>
        </div>
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
		         <?php

                         if(in_array($this->data['Booking']['booking_status'],$checkStatus) && $location['driver_arrived_source'] == 'N'){

                                echo $this->Html->link('Arrived', array(
                                    'controller' => 'apis', 'action' => 'driverArrived', 'admin' => true, $this->data['Booking']['driver_id'] , $location['id'] , 'S' 
                                    ), array(
                                    'class' => 'btn btn-primary btn-wide pull-right margin-right-10',
                                    'type' => 'button'
                                    )
                                    );
                        }


                        echo $location['driver_arrived_source'];

                   ?>
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
		         <?php 

                        if(in_array($this->data['Booking']['booking_status'],$checkStatus) && $location['driver_arrived_destination'] == 'N'){

                                echo $this->Html->link('Arrived', array(
                                    'controller' => 'apis', 'action' => 'driverArrived', 'admin' => true, $this->data['Booking']['driver_id'], $location['id'], 'D' 
                                    ), array(
                                    'class' => 'btn btn-primary btn-wide pull-right margin-right-10',
                                    'type' => 'button'
                                    )
                                    );
                        }


                 echo $location['driver_arrived_destination']; ?>
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

<?php //   pr($this->data); ?>
