
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
                    
                </div>

               

               


                

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
