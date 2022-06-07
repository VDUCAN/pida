<section id="drive-slider"><img class="drive-bg" src="<?php echo $this->webroot ?>img/front/head-bg.jpg" alt="">
    <div class="diver-deatils">
        <div class="container">
            <div class="diver-deatils-main">
                <div class="diver-deatils-main-left">
                    <span>
                        <?php if($this->Session->read('User.photo') != '') { ?>
                    <img src="<?php echo $this->webroot.'uploads/user_photos/thumbnail/'.$this->Session->read('User.photo') ?>" alt="" id="eventbannerclone">
				<?php } else { ?>
					<img src="<?php echo $this->webroot.'images/noprofile.gif' ?>" alt="" id="eventbannerclone">
					
				<?php } ?>

                        <div class="file-btn1"><input type="hidden" name="event_banner" id="event_banner" value="<?php echo $this->Session->read('User.photo') ?>">
						<input type="file" id="eventbannerupload" name="eventbanner"><i class="fa fa-camera"></i> Change Profile Photo</div>


                    </span>

                    <em>
                        <h2><?php echo $this->Session->read('User.first_name')." ".$this->Session->read('User.last_name')?> </h2>
                    </em>

                </div>
             

            </div>
        </div>


    </div>

</section>

<section id="edit-profile">
    <div class="container">
        <div class="edit-profile-main">


            <div id="verticalTab">
                <ul class="resp-tabs-list">
                    <li><i class="fa fa-user"></i> Edit Profile</li>
                    <li><i class="fa fa-history"></i>Job History </li>
                    <li><i class="fa fa-dollar"></i> My Earnings</li>
                    <li><i class="fa fa-lock"></i>Change Password </li>
                </ul>
                <div class="resp-tabs-container">


                    <div class="tab-top">
                        <div class="tab-heading">
                            <h3><i class="fa fa-user"></i>Edit Profile</h3>
                        </div>
                        <div class="personal-details">
                            <ul class="tabs">
								<li class="tab-link current" data-tab="tab-1">Personal Details</li>
								<li class="tab-link" data-tab="tab-2">Vehicle Details</li>
							</ul>
							<div id="tab-1" class="tab-content current">
								<div class="personal-details-main">
									<h3>Personal Details</h3>
									<?php echo $this->Form->create('User', array('method' => 'post', 'class' => 'form', 'role' => 'form', 'autocomplete' => 'off', 'type' => 'file')); ?>
									<?php echo $this->Form->input('id', array('type' => 'hidden', 'div' => false,'value'=>$user_id)); ?>
									<?php echo $this->Form->input('formtype', array('type' => 'hidden', 'div' => false,'value'=>'editprofile','name'=>'formtype')); ?>
									<h6><?php echo $this->Session->flash(); ?></h6>
									<ul>
										<li>
											<span>First Name :</span>
											<em><?php echo $this->Form->input('first_name', array('type' => 'text', 'div' => false, 'placeholder' => 'First Name','label' => false, 'required' => true)); ?></em>
										</li>

										<li>
											<span>Last Name :</span>
											<em><?php echo $this->Form->input('last_name', array('type' => 'text', 'div' => false, 'placeholder' => 'First Name','label' => false, 'required' => true)); ?></em>
										</li>

										<li>
											<span>Email :</span>
											<em><?php echo $this->Form->input('email', array('type' => 'text', 'div' => false, 'placeholder' => 'First Name','label' => false, 'required' => true)); ?></em>
										</li>

										<li>
											<span>Mobile :</span>
											<em><?php echo $this->Form->input('phone', array('type' => 'text', 'div' => false, 'placeholder' => 'First Name','label' => false, 'required' => true)); ?></em>
										</li>

										<li>
											<span>SSN Number :</span>
											<em><?php echo $this->Form->input('ssn', array('type' => 'text', 'div' => false, 'placeholder' => 'SSN','label' => false, 'required' => true,'value'=>$result_data['DriverDetail']['ssn'])); ?></em>
										</li>

										<li>
											<span>License Number :</span>
											<em><?php echo $this->Form->input('driving_license_no', array('type' => 'text', 'div' => false, 'placeholder' => 'License Number','label' => false, 'required' => true,'value'=>$result_data['DriverDetail']['driving_license_no'])); ?></em>
										</li>

										<li>
											<span>Country :</span>
											<em><?php echo $this->Form->input('country_id', array('options' => $countries, 'class' => 'browser-default', 'empty' => '-- Select Category --', 'label' => false, 'div' => false, 'required' => false)); ?></em>
										</li>

										<li>
											<span>State :</span>
											<em><?php echo $this->Form->input('state_id', array('options' => $states, 'class' => 'browser-default', 'empty' => '-- Select Category --', 'label' => false, 'div' => false, 'required' => false)); ?></em>
										</li>

										<li>
											<span>DOB :</span>
											<em><?php echo $this->Form->input('dob', array('type' => 'text', 'div' => false,'class' => 'datepicker', 'placeholder' => 'First Name','label' => false, 'required' => true,'value'=>date('d F, Y',strtotime($result_data['User']['dob'])))); ?></em>
										</li>



										<li>

											<?php echo $this->Form->button('Save Changes',array('class' => 'waves-effect','type' => 'submit','id' => 'customer_signup1'));  ?>

										   <?php echo $this->Form->button('Cancel',array('class' => 'waves-effect','type' => 'button','id' => 'customer_signup1'));  ?>


										</li>


									</ul>
									<?php echo $this->Form->end(); ?>
								</div>
							</div>
							
							<div id="tab-2" class="tab-content">
								 <div class="personal-details-main">
								 
									<h3>Vehicle Details</h3>
									
									<div class="earning">

										<div class="table-scroll">
											<table width="100%" cellspacing="0" cellpadding="0">
												<tbody>
													<tr >
														<th>Vehicle Make</th>
														<th>Vehicle Model</th>
														<th>Make Year</th>
														<th>Color</th>
														<th>Plate No.</th>
														
													</tr>
													<?php if (!empty($result_data['VehicleDetail'])) { ?>
														<?php foreach ($result_data['VehicleDetail'] as $data) { ?>
																<tr>
																	<td>
																		<?php echo $makes[$data['make_id']]?>
																	</td>
																	<td>
																		<?php echo $models[$data['model_id']]?>
																	</td>
																	<td><?php echo $data['make_year']?>
																	</td>
																	<td><?php echo $data['color']?>
																	</td>
																	<td><?php echo $data['plate_no']?>
																	</td>
																
																	
																</tr>

															   <?php } 
														} ?>

														<tr >
															<th colspan="6"><a href="#_" class="add-more">Add New</a></th>
														</tr>

												</tbody>
											</table>
										</div>
										

									</div>
									<?php echo $this->Form->create('User', array('method' => 'post', 'class' => 'form', 'role' => 'form', 'autocomplete' => 'off', 'type' => 'file')); ?>
									<?php echo $this->Form->input('formtype', array('type' => 'hidden', 'div' => false,'value'=>'addvehicle','name'=>'formtype')); ?>
									<ul style="display:none" id="add_vehicle">
										<li>
											<span>Vehicle Category :</span>
											<em>
												<select name="category" class="browser-default" id="VehicleTypeCategoryID">
												<option value="">-- Select Vehicle Category --</option>
												<?php foreach($categories as $key => $values){
												
												echo '<option value="'.$key.'">'.$values.'</option>';
												
												}?>
												</select>
											</em>
										</li>

										<li>
											<span>Vehicle Type :</span>
											<em>
												<select name="vehicle_type" class="browser-default" id="VehicleTypeID">
												<option value="">-- Select Vehicle Type --</option>
												</select>
											</em>
										</li>

										<li>
											<span>Make Year :</span>
											<em>
												<input type="type" name="make_year" id="make_year" placeholder="Make year">
											</em>
										</li>

										<li>
											<span>Vehicle Make :</span>
											<em>
												<select name="vehicle_make" class="browser-default" id="VehicleMakeModelID">
												<option value="">-- Select Vehicle Make --</option>
												<?php foreach($makes as $key => $values){
												
												echo '<option value="'.$key.'">'.$values.'</option>';
												
												}?>
												
												</select>
											</em>
										</li>

										<li>
											<span>Vehicle Model :</span>
											<em>
												<select name="vehicle_model" class="browser-default" id="VehicleModelID">
												<option value="">-- Select Vehicle Model --</option>
												</select>
											</em>
										</li>

										<li>
											<span>Vehicle Color :</span>
											<em>
												<input type="text" name="vehicle_color" id="vehicle_color" placeholder="Enter vehicle color">
											</em>
										</li>

										<li>
											<span>Plate Number :</span>
											<em>
												<input type="text" name="plate_number" id="plate_number" placeholder="Enter plate number">
											</em>
										</li>

										<li>
											<span>Registration doc :</span>
											<em>
												<div class="file-btn">
												<span>Select Registration doc</span>
													<input type="file" name="registration_doc" >
												</div>
											</em>
										</li>

										<li>
											<span>Insurance doc :</span>
											<em>
												<div class="file-btn">
												<span>Select Insurance doc</span>
													<input type="file" name="insurance_doc" >
												</div>
											</em>
										</li>
										
										<li>

											<?php echo $this->Form->button('Add Vehicle',array('class' => 'waves-effect','type' => 'submit'));  ?>

										  


										</li>

									</ul>
									<?php echo $this->Form->end(); ?>

								</div>
							</div>
							

                        </div>


                    </div>


                    <div class="tab-top">
                        <div class="tab-heading">
                            <h3><i class="fa fa-history"></i>  Job History</h3>
                        </div>
                        <div class="earning">
                            <em>

                                <div class="input-field">
                                    <!--<label>Records per page : </label>-->
                                    <select>
                                        <option value="" disabled selected>10</option>
                                        <option value="1">11</option>
                                        <option value="2">12</option>
                                        <option value="3">13</option>
                                    </select>

                                </div>
                            </em>

                            <div class="table-scroll">
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tbody>
                                        <tr >
                                            <th>Pickup Date   </th>
                                            <th>  Miles  </th>
                                            <th> Price</th>

                                        </tr>
										<?php if (!empty($result_data1)) { ?>
                                <?php foreach ($result_data1 as $data) { ?>
                                        <tr>
                                            <td><?php
											
												echo date('F d, Y h:i:s a', strtotime($data['Booking']['pickup_date']));
											
											?> 	</td>
                                            <td> <?php echo $data['Booking']['total_miles']; ?>  </td>
                                            <td>$<?php echo $data['Booking']['price']; ?></td>
                                        </tr>

                                       <?php } 
								} ?>


                                    </tbody>
                                </table>
                            </div>




                            <div class="tabel-page">
                                <?php echo $this->element('front_pagination'); ?>

                            </div>

                        </div>


                    </div>


                    <div class="tab-top">
                        <div class="tab-heading">
                            <h3><i class="fa fa-dollar"></i>My Earnings</h3>
                        </div>
                        <div class="earning">
                            <em>

                                <div class="input-field">
                                    <label>Records per page : </label>
                                    <select>
                                        <option value="" disabled selected>10</option>
                                        <option value="1">11</option>
                                        <option value="2">12</option>
                                        <option value="3">13</option>
                                    </select>

                                </div>
                            </em>

                         
                            <div class="earning-tabel-main">
                                <div class="earning-tabel">
                                    <ul class="tabel-head">
                                        <li>Date   </li>
                                        <li>Price  </li>
                                        <li>Status</li>
                                    </ul>

									<?php if (!empty($result_data2)) { ?>
                                <?php foreach ($result_data2 as $data) { ?>

                                    <ul>
                                        <li><?php
											echo $data[0]['date_range']?> </li>
                                        <li>$<?php echo $data['Transaction']['driver_amount'] ?></li>
                                        <li> <?php if($data['Transaction']['status'] == '1') echo 'Pending';
										else 
											echo 'Paid';
											
										?></li>
                                        <p>(Payment will be available up to five business day to appear in your bank)</p>

                                    </ul>

                                     <?php } 
								} ?>

                                </div>
                            </div>
                            <div class="tabel-page">
                                <?php echo $this->element('front_pagination'); ?>

                            </div>


                        </div>

                    </div>


                    <div class="tab-top">
                        <div class="tab-heading">
                            <h3><i class="fa fa-lock"></i>Change Password</h3>
                        </div>
                        <div class="personal-details">


                            <div class="personal-details-main contact">

                                <form action="change_password" method="post" class="form" role="form" autocomplete="off" id="home/changePasswordForm" enctype="multipart/form-data" accept-charset="utf-8">
                                <ul>
                                   <!-- <li>
                                        <span>Old Password :</span>
                                        <em><input type="password" placeholder=""></em>
                                    </li>-->

                                    <li>
                                        <span>New Password :</span>
                                        <em><?php echo $this->Form->input('password', array('type' => 'password', 'maxlength' => '50', 'div' => false, 'label' => false, 'required' => true,'value' => '' ,'name' => 'password')); ?></em>
                                    </li>

                                    <li>
                                        <span>Confirm Password :</span>
                                        <em><?php echo $this->Form->input('password', array('type' => 'password', 'maxlength' => '50', 'div' => false, 'label' => false, 'required' => true,'value' => '' ,'name' => 'cpassword')); ?></em>
                                    </li>

                                    <li>
                                        <?php echo $this->Form->button('Save Changes',array('class' => 'waves-effect','type' => 'submit','id' => 'customer_signup1'));  ?>

                                       <?php echo $this->Form->button('Cancel',array('class' => 'waves-effect','type' => 'button','id' => 'customer_signup1'));  ?>
                                    </li>


                                </ul>
                             </form>

                            </div>


                        </div>


                    </div>
                </div>
            </div>



        </div>

    </div>
</section>


<script>
$(document).ready(function () {

		
		$('.add-more').click(function (){
			
			$('#add_vehicle').show();
		
		});
		
		$("#VehicleTypeCategoryID").change(function(){

            var id = $(this).val();
            $.ajax({
                url: "<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'ajax', 'action' => 'get_vehicle_type', 'admin' => false)); ?>",
                data: {'id' : id, 'lang' : 'en', 'show_inactive' : '1'},
                type: 'post',
                format: "json",
                success: function(r){
                    $("#VehicleTypeID").html('');

                    var options_data = '<option value="">-- Select Vehicle Type --</option>';

                    obj = jQuery.parseJSON(r);
                    $.each(obj, function( key, value ) {
                        options_data += '<option value="' + key + '">' + value + '</option>';
                    });

                    $("#VehicleTypeID").html(options_data);
                }

            });

        });		
		
		
		
		$("#VehicleMakeModelID").change(function(){

            var id = $(this).val();
            $.ajax({
                url: "<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'ajax', 'action' => 'get_vehicle_model', 'admin' => false)); ?>",
                data: {'id' : id, 'lang' : 'en', 'show_inactive' : '1'},
                type: 'post',
                format: "json",
                success: function(r){
                    $("#VehicleModelID").html('');

                    var options_data = '<option value="">-- Select Vehicle Model --</option>';

                    obj = jQuery.parseJSON(r);
                    $.each(obj, function( key, value ) {
                        options_data += '<option value="' + key + '">' + value + '</option>';
                    });

                    $("#VehicleModelID").html(options_data);
                }

            });

        });		
		
		
		
		
		$("#DriverRegistrationForm").validate({
        rules: {
         
			VehicleTypeCategoryID: "required",
            VehicleTypeID:"required",
			make_year:"required",
			VehicleMakeModelID:"required",
			VehicleModelID: "required",
			vehicle_color: "required",
			plate_number: "required",
			
            
        },
        
        errorElement: 'div',
        errorPlacement: function (error, element) {
            var placement = $(element).data('error');
            if (placement) {
                $(placement).append(error)
            } else {
                error.insertAfter(element);
            }
        }
    });
	
	});
</script>		