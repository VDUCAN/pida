<?php $option_status = array('A' => 'Active', 'I' => 'Inactive'); ?>

<!-- start: PAGE TITLE -->
<section id="page-title">
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mainTitle">View Rider</h1>
        </div>
    </div>
</section>
<!-- end: PAGE TITLE -->
<!-- Global Messages -->
<?php echo $this->Session->flash();  // pr($this->data); ?>
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
					<label class="control-label text-bold">Address:</label>
					 <?php 
							$phoneNo = !empty($this->data['User']['address']) ? $this->data['User']['address'] : 'N/A';
							echo $phoneNo;
					  ?> 
				</div>
			</div>
			
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label text-bold">Zip Code:</label>
					 <?php 
							$zip_code = !empty($this->data['User']['zip_code']) ? $this->data['User']['zip_code'] : 'N/A';
							echo $zip_code;
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
		
				

                <div class="clearfix"></div>



                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Photo :</label>
                    </div>
                </div>
                
				<div class="col-md-6">
					<div class="form-group">
					<?php $photo = $this->data['User']['photo'];
					if('' != $photo && file_exists(USER_PHOTO_PATH_THUMB . $photo)){ ?>
						<label class="control-label">
						<img src="<?php echo USER_PHOTO_URL_THUMB . $photo; ?>" />
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







<?php // pr($this->data); ?>
