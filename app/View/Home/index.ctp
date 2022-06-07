<section id="home-slider">

            <div id="owl-demo">
                <div class="item slider-bg"style="background:url(<?php echo $this->webroot?>img/front/home-slider.jpg) no-repeat top center; ">
                    <div class="container">
                        <div class="slider-text">
                            Online Cargo Tracking & Booking 
                            <b>And Logistics services</b>
                        </div>
                    </div>
                </div>

                <div class="item slider-bg"style="background:url(<?php echo $this->webroot?>img/front/home-slider-1.jpg) no-repeat top center; ">
                    <div class="container">
                        <div class="slider-text">
                            Online Cargo Tracking & Booking 
                            <b>And Logistics services</b>
                        </div>
                    </div>
                </div>

            </div>
			<?php 
            if(!$this->Session->read('User')){
			?>
            <div class="user-main">
                <div class="container">
                    <div class="user">

                        <div class="user-top"></div>

                        <div id="horizontalTab">
                            <ul class="resp-tabs-list">
                                <li>
                                    <span><b><img class="user-active" src="<?php echo $this->webroot?>img/front/user-h.png" alt="">
                                            <img class="user-non-active" src="<?php echo $this->webroot?>img/front/user.png" alt="">
                                        </b></span>
                                    Sign Up as user</li>
                                <li>
                                    <span><b><img class="user-drive" src="<?php echo $this->webroot?>img/front/user-drive-h.png" alt="">

                                            <img class="user-non-drive" src="<?php echo $this->webroot?>img/front/user-drive.png" alt="">

                                        </b></span>
                                    Sign Up as Driver</li>

                            </ul>
                            <div class="resp-tabs-container">
                                <div class="as-user">
									<?php echo $this->Form->create('User', array('method' => 'post', 'id' => 'UserIndex', 'class' => 'form', 'role' => 'form', 'autocomplete' => 'off', 'type' => 'file')); ?>
										<ul>
											<li>
											<?php echo $this->Form->input('first_name', array('type' => 'text', 'div' => false, 'placeholder' => 'First Name','label' => false, 'required' => true)); ?>
											
											</li>

											<li>
											<?php echo $this->Form->input('last_name', array('type' => 'text', 'div' => false, 'placeholder' => 'Last Name','label' => false, 'required' => true)); ?>
											</li>

											<li>
											<?php echo $this->Form->input('email', array('type' => 'email', 'div' => false, 'placeholder' => 'Email','label' => false, 'required' => true)); ?>
											</li>

											<li>
											<?php echo $this->Form->input('phone', array('type' => 'text', 'maxlength' => '20','div' => false, 'placeholder' => 'Mobile', 'label' => false, 'required' => true)); ?>
											</li>

											<li>
											<?php echo $this->Form->input('password', array('type' => 'password', 'id' => 'UserPassword1', 'maxlength' => '50', 'placeholder' =>'Password', 'div' => false, 'label' => false, 'required' => true)); ?>
											</li>

											<li>
											<?php 
											 echo $this->Form->button('Sign Up',array('class' => 'waves-effect','type' => 'submit','id' => 'customer_signup1')); 
											 echo $this->Form->input('user_type', array('type' => 'hidden', 'id' => 'UserUserType', 'required' => true,'value'=>'N')); ?>
											<a href="javascript:void(0)" id="customer_signup"></a>
											</li>

										</ul>
                                    <?php echo $this->Form->end(); ?>
                                </div>

                                <div class="as-user">
								<?php echo $this->Form->create('User', array('method' => 'post', 'class' => 'form', 'id' => 'DriverIndex', 'role' => 'form', 'autocomplete' => 'off', 'type' => 'file')); ?>
                                    <ul>
                                        <li>
											<?php echo $this->Form->input('first_name', array('type' => 'text', 'id' => 'DriverFirstName', 'div' => false, 'placeholder' => 'First Name','label' => false, 'required' => true)); ?>
											
											</li>

											<li>
											<?php echo $this->Form->input('last_name', array('type' => 'text', 'id' => 'DriverLastName', 'div' => false, 'placeholder' => 'Last Name','label' => false, 'required' => true)); ?>
											</li>

											<li>
											<?php echo $this->Form->input('email', array('type' => 'email', 'div' => false, 'id' => 'DriverEmail', 'placeholder' => 'Email','label' => false, 'required' => true)); ?>
											</li>

											<li>
											<?php echo $this->Form->input('phone', array('type' => 'text', 'maxlength' => '20','div' => false, 'id' => 'DriverPhone', 'placeholder' => 'Mobile', 'label' => false, 'required' => true)); ?>
											</li>

											<li>
											<?php echo $this->Form->input('password', array('type' => 'password', 'maxlength' => '50', 'id' => 'DriverPassword', 'placeholder' =>'Password', 'div' => false, 'label' => false, 'required' => true)); ?>
											</li>

											<li>
											<?php 
											 echo $this->Form->button('Next',array('class' => 'waves-effect','type' => 'submit','id' => 'driver_signup1'));
											  echo $this->Form->input('user_type', array('type' => 'hidden', 'id' => 'DriverUserType', 'required' => true,'value'=>'D')); ?>
											<a href="javascript:void(0)" id="driver_signup"></a>
											</li>

                                    </ul>
									<?php echo $this->Form->end(); ?>

                                </div>


                            </div>
                        </div>








                    </div>
                </div>
            </div>
			<?php } ?>
        </section>


        <section id="about">
            <div class="container">
                <div class="about-main">
                    <h2><?php echo $about['PageLocale']['name']?></h2>
                    <span><?php echo $about['PageLocale']['name']?></span>
                    <p><?php echo substr(strip_tags($about['PageLocale']['body']),0,200)?></p>

                </div>
            </div>


            <div class="about-bottom">
                <div class="container">
                    <ul>
                        <li>

                            <div class="zoom_img">
                                <i class="fa fa-paper-plane"></i></div>
                            <h4><?php echo $about1['PageLocale']['name']?></h4>
                            <p><?php echo $about1['PageLocale']['body']?></p>

                        </li>

                        <li>
                            <b>
                                <div class="zoom_img">
                                    <i class="fa fa-map"></i></div>
                            </b>
                            <h4><?php echo $about2['PageLocale']['name']?> </h4>
                            <p><?php echo $about2['PageLocale']['body']?></p>

                        </li>

                        <li>
                            <div class="zoom_img"><i class="fa fa-compass"></i></div>

                            <h4><?php echo $about3['PageLocale']['name']?></h4>
                            <p><?php echo $about3['PageLocale']['body']?></p>

                        </li>

                    </ul>
                </div>
            </div>

            <div class="container">
                <div class="about-last">
                    <div class="about-img"><img src="<?php echo $this->webroot?>img/front/about-man.png" alt=""></div>
                </div>
            </div>

        </section>


        <section id="service">
            <div class="container">
                <div class="service-main">
                    <h2>Services</h2>
                    <span>Services</span>
                </div>

                <div class="service-list">
                    <ul>
                        <li>
                            <div>
                                <b><img src="<?php echo $this->webroot?>img/front/parking.png" alt=""></b>
                                <h4><?php echo $service1['PageLocale']['name']?></h4>
                                <p><?php echo $service1['PageLocale']['body']?></p>
                            </div>
                        </li>

                        <li>
                            <div>
                                <b><img src="<?php echo $this->webroot?>img/front/cargo.png" alt=""></b>
                                <h4><?php echo $service2['PageLocale']['name']?></h4>
                                <p><?php echo $service2['PageLocale']['body']?></p>
                            </div>
                        </li>

                        <li>
                            <div>
                                <b><img src="<?php echo $this->webroot?>img/front/transport.png" alt=""></b>
                                <h4><?php echo $service3['PageLocale']['name']?></h4>
                                <p><?php echo $service3['PageLocale']['body']?></p>
                            </div>
                        </li>

                        <li>
                            <div>
                                <b><img src="<?php echo $this->webroot?>img/front/housing.png" alt=""></b>
                                <h4><?php echo $service4['PageLocale']['name']?></h4>
                                <p><?php echo $service4['PageLocale']['body']?></p>
                            </div>
                        </li>

                        <li>
                            <div >
                                <b><img src="<?php echo $this->webroot?>img/front/groung.png" alt=""></b>
                                <h4><?php echo $service5['PageLocale']['name']?></h4>
                                <p><?php echo $service5['PageLocale']['body']?></p>
                            </div>
                        </li>


                    </ul>



                </div>

                <div class="container">
                    <div class="machin">
                        <span><img src="<?php echo $this->webroot?>img/front/machin.png" alt=""></span>
                    </div>
                </div>
            </div>
        </section>

        <section id="app">
            <div class="container">
                <div class="app-left"><img src="<?php echo $this->webroot?>img/front/app.jpg" alt=""></div>

                <div class="app-right">
                    <h2><?php echo $download_app_section['PageLocale']['name']?></h2>
                    <?php echo $download_app_section['PageLocale']['body']?>
                    <span>Download now:</span>
                    <ul>
                        <li><div class="zoom_img1"><i class="fa fa-apple"></i></div></li>
                        <li><div class="zoom_img1"><i class="fa fa-android"></i></div></li>
                        
                    </ul>


                </div>

            </div>
        </section>
		
		
		<script type="text/javascript">
    $(document).ready(function () {
        
        var appendthis = ("<div class='modal-overlay js-modal-close'></div>");
        $("#customer_signup").click(function(){
            
            var formData = {
				'first_name'        : $('#UserFirstName').val(),
				'last_name'         : $('#UserLastName').val(),
				'email'             : $('#UserEmail').val(),
				'phone'             : $('#UserPhone').val(),
				'password'          : $('#UserPassword1').val(),
				'user_type'         : $('#UserUserType').val(),
            };
            $.ajax({
                url: "<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'home', 'action' => 'customer_signup', 'admin' => false)); ?>",
                data: formData,
                type: 'post',
                format: "json",
                success: function(r){

                    obj = jQuery.parseJSON(r);
                    
					if(parseInt(obj.success) == 1){
						$('#UserFirstName').val('');;
						$('#UserLastName').val('');
						$('#UserEmail').val('');
						$('#UserPhone').val('');
						$('#UserPassword').val('');
						
						$("body").append(appendthis);
						$(".modal-overlay").fadeTo(500, 0.7);
						$('.message_txt').html(obj.message);
						$('#popup4').fadeIn($(this).data());
					}
					else{
					
						$("body").append(appendthis);
						$(".modal-overlay").fadeTo(500, 0.7);
						
						var html = '';
						if(typeof(obj.message.email) != "undefined"){
							html += obj.message.email[0]+'<br>';
						}
						
						if(typeof(obj.message.phone) != "undefined"){
							html += obj.message.phone[0]+'<br>';
						}	
						
						$('.message_txt').html(html);
						$('#popup4').fadeIn($(this).data());
					
					}
					
                }

            });

        });
		
		
		
		
		$("#driver_signup").click(function(){
            
            var formData = {
				'first_name'        : $('#DriverFirstName').val(),
				'last_name'         : $('#DriverLastName').val(),
				'email'             : $('#DriverEmail').val(),
				'phone'             : $('#DriverPhone').val(),
				'password'          : $('#DriverPassword').val(),
				'user_type'         : $('#DriverUserType').val(),
            };
            $.ajax({
                url: "<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'home', 'action' => 'customer_signup', 'admin' => false)); ?>",
                data: formData,
                type: 'post',
                format: "json",
                success: function(r){

                    obj = jQuery.parseJSON(r);
                    
					if(parseInt(obj.success) == 1){
					$('#DriverFirstName').val('');;
					$('#DriverLastName').val('');
					$('#DriverEmail').val('');
					$('#DriverPhone').val('');
					$('#DriverPassword').val('');
					window.location = '<?php echo SITEURL .'home/driver_registration' ?>';
					}
					
					else{
					
						$("body").append(appendthis);
						$(".modal-overlay").fadeTo(500, 0.7);
						
						var html = '';
						if(typeof(obj.message.email) != "undefined"){
							html += obj.message.email[0]+'<br>';
						}
						
						if(typeof(obj.message.phone) != "undefined"){
							html += obj.message.phone[0]+'<br>';
						}	
						
						$('.message_txt').html(html);
						$('#popup4').fadeIn($(this).data());
					
					}
					
                }

            });

        });
		
		
		
		
		
	$("#UserIndex").validate({
        rules: {
            UserFirstName: "required",
			UserLastName: "required",
            UserEmail: {
                required: true,
                email: true
            },
            UserPhone: "required",
            UserPassword: {
                required: true,
                minlength: 6
            },
            
        },
        //For custom messages
        messages: {
            UserEmail: {
                required: "Enter your email address",
                email: "Enter valid email address"
            },
            UserFirstName: {
                required: "Enter your first name"
            },
			UserLastName: {
                required: "Enter your first name"
            },
            UserPhone: {
                required: "Enter your mobile number"
            },
            UserPassword: {
                required: "Enter the password",
                minlength: "Password sould be atleast 6 char long"
            },
            
        },
        errorElement: 'div',
        errorPlacement: function (error, element) {
            var placement = $(element).data('error');
            if (placement) {
                $(placement).append(error)
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function () {
            $('#imgloder').hide();
            $('#customer_signup').trigger('click');
        }
    });
	
	
	
	
	$("#DriverIndex").validate({
        rules: {
            DriverFirstName: "required",
			DriverLastName: "required",
            DriverEmail: {
                required: true,
                email: true
            },
            DriverPhone: "required",
            DriverPassword: {
                required: true,
                minlength: 6
            },
            
        },
        //For custom messages
        messages: {
            DriverEmail: {
                required: "Enter your email address",
                email: "Enter valid email address"
            },
            DriverFirstName: {
                required: "Enter your first name"
            },
			DriverLastName: {
                required: "Enter your first name"
            },
            DriverPhone: {
                required: "Enter your mobile number"
            },
            DriverPassword: {
                required: "Enter the password",
                minlength: "Password sould be atleast 6 char long"
            },
            
        },
        errorElement: 'div',
        errorPlacement: function (error, element) {
            var placement = $(element).data('error');
            if (placement) {
                $(placement).append(error)
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function () {
            $('#imgloder').hide();
            $('#driver_signup').trigger('click');
        }
    });
		
    });


</script>
