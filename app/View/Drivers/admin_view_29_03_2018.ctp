


<?php $option_status = array('A' => 'Active', 'I' => 'Inactive'); ?>

<!-- start: PAGE TITLE -->
<section id="page-title">
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mainTitle">View Driver</h1>
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
                        <label class="control-label text-bold">First Name :</label>
                            <?php 
				$firstName = !empty($this->data['User']['first_name']) ? $this->data['User']['first_name'] : 'N/A';
				echo $firstName;
			    ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Last Name :</label>
		             <?php 
			    $lastName = !empty($this->data['User']['last_name']) ? $this->data['User']['last_name'] : 'N/A';
			    echo $lastName;
			    ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Email :</label>
                        <?php 
			    $email = !empty($this->data['User']['email']) ? $this->data['User']['email'] : 'N/A';
			    echo $email;
			 ?> 
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Phone Number :</label>
                         <?php 
			    $phoneNo = !empty($this->data['User']['phone']) ? $this->data['User']['phone'] : 'N/A';
			    echo $phoneNo;
			 ?> 
                    </div>
                </div>
                
                     <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">DOB :</label>
                         <?php 
				if(!empty($this->data['User']['dob'])){
				    echo date(DATE_FORMAT, strtotime($this->data['User']['dob']));	
				}else{
				    echo 'N/A';
				}
			  ?> 
                    </div>
                </div>
		
		 <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Country :</label>
			  <?php 
			    $countryName = !empty($this->data['CountryLocale']['name']) ? $this->data['CountryLocale']['name'] : 'N/A';
			    echo $countryName;
			 ?> 
                    </div>
                </div>
		
		 <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">State :</label>
			 <?php 
			    $stateName = !empty($this->data['StateLocale']['name']) ? $this->data['StateLocale']['name'] : 'N/A';
			    echo $stateName;
			 ?> 
                    </div>
                </div>
		
		 <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">City :</label>
			 <?php 
			    $cityName = !empty($this->data['CityLocale']['name']) ? $this->data['CityLocale']['name'] : 'N/A';
			    echo $cityName;
			 ?> 
                    </div>
                </div>
	
	
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">SSN :</label>
			<?php 
			    $ssn = !empty($this->data['DriverDetail']['ssn']) ? $this->data['DriverDetail']['ssn'] : 'N/A';
			    echo $ssn;
			 ?> 
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Driving License Number :</label>
			<?php 
			    $drivingLicenseNo = !empty($this->data['DriverDetail']['driving_license_no']) ? $this->data['DriverDetail']['driving_license_no'] : 'N/A';
			    echo $drivingLicenseNo;
			 ?>
                    </div>
                </div>
		
		 <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Last Login :</label>
			    <?php
				if(!empty($this->data['User']['last_login'])){
				    echo date(DATETIME_FORMAT, $this->data['User']['last_login']);	
				}else{
				    echo 'N/A';
				}
			    ?>
                    </div>
                </div>
		

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Status :</label>
			 <?php echo $option_status[$this->data['User']['driver_status']]; ?>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Driving License Document :</label>
                    </div>
                </div>

		<div class="col-md-6">
		    <div class="form-group">
			<?php $doc = $this->data['DriverDetail']['driving_license_doc'];
			if('' != $doc && file_exists(DRIVER_DOC_PATH . $doc)){ ?>
			    <label class="control-label">
				<img src="<?php echo DRIVER_DOC_URL . $doc; ?>" />
				<?php /* ?>
				<a href="<?php echo DRIVER_DOC_URL . $doc; ?>" target="_blank"><?php echo $doc;?></a>
				<?php */ ?>
			    </label>
			<?php }else{
			    echo 'N/A';
			} ?>
		    </div>
		</div>
            

                <div class="clearfix"></div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Photo :</label>
                    </div>
                </div>
                
		<div class="col-md-6">
		    <div class="form-group">
			<?php $photo = $this->data['User']['photo'];
			if('' != $photo && file_exists(USER_PHOTO_PATH . $photo)){ ?>
			    <label class="control-label">
				<img src="<?php echo USER_PHOTO_URL . $photo; ?>" />
			    </label>
			<?php }else{
			    echo 'N/A';
			} ?>
		    </div>
		</div>

            </div>
           
         
        </div>
    </div>
</div>




<?php if(!empty($this->data['DriverQuestion'])) { ?>

<!-- start: PAGE TITLE -->
 <section id="page-title">
     <div class="row">
	 <div class="col-sm-8">
	     <h1 class="mainTitle">View Questions </h1>
	 </div>
     </div>
 </section>


<div class="container-fluid container-fullw bg-white">
	
    <?php   $i = 1;
	    foreach($this->data['DriverQuestion'] as $questions){ 
    ?>
  
    <div class="row">
        <div class="col-md-12">
	    <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label text-bold">Question :</label>
                        <?php echo $questions['question']; ?>
                    </div>
                </div>
		
		 <div class="clearfix"></div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label text-bold">Answer :</label>
		         <?php echo $questions['answer']; ?>
                    </div>
                </div>

                <div class="clearfix"></div>

            </div>
           
        </div>
    </div>
    <?php 
    $i++;
    } ?>
</div>

<?php } ?>


<?php if(!empty($this->data['Vehicle'])) { ?>


  <?php
	$j = 1;
	foreach($this->data['Vehicle'] as $vehicles){ 
    ?>


<!-- start: PAGE TITLE -->
 <section id="page-title">
     <div class="row">
	 <div class="col-sm-8">
	     <h1 class="mainTitle"> Vehicle <?php echo $j;?>  </h1>
	 </div>
     </div>
 </section>

 <div class="clearfix"></div>



<div class="container-fluid container-fullw bg-white">
    
    <div class="row">
        <div class="col-md-12">
	   
	    <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Make :</label>
                        <?php echo $vehicles['MakeLocale']['name']; ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Model :</label>
		         <?php echo $vehicles['ModelLocale']['name']; ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Make Year :</label>
                        <?php echo $vehicles['Vehicle']['make_year']; ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Color :</label>
                         <?php echo $vehicles['Vehicle']['color']; ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">plate_no :</label>
                         <?php echo $vehicles['Vehicle']['plate_no']; ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Status :</label>
			 <?php echo $option_status[$vehicles['Vehicle']['status']]; ?>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Insurance policy doc :</label>
                    </div>
                </div>

		<div class="col-md-6">
		    <div class="form-group">
			<?php $doc = $vehicles['Vehicle']['insurance_policy_doc'];
			// echo VEHICLE_DOC_URL . $doc;
			if('' != $doc && file_exists(VEHICLE_DOC_PATH . $doc)){ ?>
			    <label class="control-label">
				<img src="<?php echo VEHICLE_DOC_URL . $doc; ?>" />
				
				<?php /* ?>
				<a href="<?php echo VEHICLE_DOC_URL . $doc; ?>" target="_blank"><?php echo $doc;?></a>
				<?php */ ?>
				
			    </label>
			<?php }else{
			    echo 'N/A';
			} ?>
		    </div>
		</div>
            

                <div class="clearfix"></div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Registration doc  :</label>
                    </div>
                </div>
                
		<div class="col-md-6">
		    <div class="form-group">
			<?php $doc = $vehicles['Vehicle']['registration_doc'];
			//echo VEHICLE_DOC_URL . $photo;
			if('' != $doc && file_exists(VEHICLE_DOC_PATH . $doc)){ ?>
			    <label class="control-label">
				<img src="<?php echo VEHICLE_DOC_URL . $doc; ?>" />
				<?php /* ?>
				<a href="<?php echo VEHICLE_DOC_URL . $doc; ?> " target="_blank" ><?php echo $doc;?></a>
				<?php */ ?>
			    </label>
			<?php }else{
			    echo 'N/A';
			} ?>
		    </div>
		</div>

            </div>
           
         
        </div>
    </div>
    
</div>

<?php 
    $j++;
    } ?>

<?php  } ?>



<?php // pr($this->data); ?>
