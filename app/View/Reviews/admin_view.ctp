<?php 
    echo $this->Html->css(array('star-rating.css',), null, array('inline' => false));
    echo $this->Html->script(array('star-rating.js'), array('inline' => false));
?>

<?php 
    $option_status = array('1' => 'Open', '2' => 'Assign','3'=>'Cancel','4'=>'Arrived','5'=>'Completed','6'=>'Paid');
    $option_type = array('1' => 'Now', '2' => 'Scheduled');
?>

<!-- start: PAGE TITLE -->
<section id="page-title">
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mainTitle">View Review Booking</h1>
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
                        <label class="control-label text-bold">Driver :</label>
                        <?php echo $this->data['Driver']['first_name'].' '.$this->data['Driver']['last_name']; ?>
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
		
		 <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold" style='float:left;'>Rating :</label>
			<div style='float:left;margin-top:-10px;margin-left:10px;'>
			<?php
				if($this->data['Rating']['rating']){
				    $rating = $this->data['Rating']['rating'];
				    echo "<input id=\"input-3\" name=\"input-3\" value=\"$rating\" class=\"rating-loading\" data-size=\"xs\">";
				}else{
				    echo '-';
				}
			?>
			</div>	
                    </div>
                </div>
		
		 <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Comment :</label>
			<?php
				if($this->data['Rating']['comment']){
				    echo $this->data['Rating']['comment'];
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

<script>
$(document).on('ready', function(){
    $('#input-3').rating({displayOnly: true, step: 0.01});
});
</script>


<?php /* ?>

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

<?php */ ?>


<?php //  pr($this->data); ?>
