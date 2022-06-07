
<section id="drive-slider"><img class="drive-bg" src="<?php echo $this->webroot ?>img/front/head-bg.jpg" alt="">
    <div class="container">
        <div class="slider-text-inner">
            One more step to complete your sign up process

        </div>
    </div>

</section>

<section id="drive-form">
    <div class="container">
        <div class="drive-form-main">
            <div class="drive-form-top">
                <span></span>
                <em><b><img src="<?php echo $this->webroot ?>img/front/user-drive-h.png" alt=""></b></em>

            </div>

           <form action="driver_registration" method="post" class="form" role="form" autocomplete="off" id="DriverRegistrationForm" enctype="multipart/form-data" accept-charset="utf-8" novalidate="novalidate">
            <div class="drive-user">

                <div class="drive-user-top">

				    <h6 style="font-size:13px">
					<?php echo $this->Session->flash(); ?>
					</h6>
                    <span> Pida For Drive</span>

                    <ul>
                        <li><input type="text" name="ssn" id="ssn" placeholder="Enter SSN number"></li>

                        <li><input type="text" name="license_number" id="license_number" placeholder="License number"></li>

                        <li><select name="country" id="CityCountryId" class="browser-default"><option value="">-- Select Country --</option>
						<?php foreach($countries as $key => $values){
						
						echo '<option value="'.$key.'">'.$values.'</option>';
						
						}?>
						</select>
                        </li>

                        <li>
						<select name="state" id="CityStateId" class="browser-default">
						<option value="">-- Select State --</option>
						</select>
						</li>

                        <li>
                                <input type="text" class="datepicker" name="dob" id="dob" placeholder="Select DOB">
                        </li>
                    </ul>
                </div>

                <div class="drive-user-middle">
                    <span> Add Vehicle Details</span>

                    <ul>
                        <li>
						<select name="category" class="browser-default" id="VehicleTypeCategoryID">
						<option value="">-- Select Vehicle Category --</option>
						<?php foreach($categories as $key => $values){
						
						echo '<option value="'.$key.'">'.$values.'</option>';
						
						}?>
						</select>
						</li>

                        <li>
						<select name="vehicle_type" class="browser-default" id="VehicleTypeID">
						<option value="">-- Select Vehicle Type --</option>
						</select></li>

                        <li><input type="type" name="make_year" id="make_year" placeholder="Make year"></li>

                        <li><select name="vehicle_make" class="browser-default" id="VehicleMakeModelID">
						<option value="">-- Select Vehicle Make --</option>
						<?php foreach($makes as $key => $values){
						
						echo '<option value="'.$key.'">'.$values.'</option>';
						
						}?>
						
						</select>
						</li>

                        <li>
						<select name="vehicle_model" class="browser-default" id="VehicleModelID">
						<option value="">-- Select Vehicle Model --</option>
						</select>
						</li>

                        <li><input type="text" name="vehicle_color" id="vehicle_color" placeholder="Enter vehicle color"></li>

                        <li><input type="text" name="plate_number" id="plate_number" placeholder="Enter plate number"></li>

                        <li>
                            <div class="file-btn">
                                <span>Select registration doc</span>
                                <input type="file" name="registration_doc" ></div>
                        </li>

                        <li>
                            <div class="file-btn">
                                <span>Select Insurance doc</span>
                                <input type="file" name="insurance_doc" ></div>
                        </li>


                        <li>
                            <div class="file-btn">
                                <span>Select License doc</span>
                                <input type="file" name="license_doc" ></div>
                        </li>

                    </ul>
                </div>

                <div class="drive-user-bottom">
                    <ul>

                        <li><span>1. Have you been convicted?</span>
						<input type="hidden" name="question1" value="Have you been convicted?" />
                            <p><input name="answer1" type="radio" id="test1" />
                                <label for="test1">Yes</label>
                            </p>

                            <p>
                                <input name="answer1" type="radio" id="test2" />
                                <label for="test2">No</label>
                            </p>

                        </li>


                        <li><span>2. Have you been in prison?</span>
						<input type="hidden" name="question2" value="Have you been in prison?" />
                            <p><input name="answer2" type="radio" id="test3" />
                                <label for="test3">Yes</label>
                            </p>

                            <p>
                                <input name="answer2" type="radio" id="test4" />
                                <label for="test4">No</label>
                            </p>

                        </li>

                        <li><span>3. Did you has a dui in last two year?</span>
						<input type="hidden" name="question3" value="Did you has a dui in last two year?" />
                            <p><input name="answer3" type="radio" id="test5" />
                                <label for="test5">Yes</label>
                            </p>

                            <p>
                                <input name="answer3" type="radio" id="test6" />
                                <label for="test6">No</label>
                            </p>

                        </li>

                        <li>By pressing process to sign up you will be agree for a background check</li>


                    </ul>
                </div>


                <span><button class="waves-effect">Proceed to signup</button></span>

            </div>
			</form>
        </div>
    </div>
</section>

<script>
$(document).ready(function () {
$("#CityCountryId").change(function(){

            var id = $(this).val();
            $.ajax({
                url: "<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'ajax', 'action' => 'get_states', 'admin' => false)); ?>",
                data: {'id' : id, 'lang' : 'en', 'show_inactive' : '1'},
                type: 'post',
                format: "json",
                success: function(r){
                    $("#CityStateId").html('');

                    var options_data = '<option value="">-- Select State --</option>';

                    obj = jQuery.parseJSON(r);
                    $.each(obj, function( key, value ) {
                        options_data += '<option value="' + key + '">' + value + '</option>';
                    });

                    $("#CityStateId").html(options_data);
                }

            });

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
            ssn: "required",
			license_number: "required",
            CityCountryId:"required",
			CityStateId:"required",
			dob:"required",
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
