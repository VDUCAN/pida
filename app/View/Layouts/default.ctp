<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
$cakeDescription = __d('cake_dev', 'Welcome to Tueeter');
?>
<!doctype html>
<html>
<head>
 <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php echo $this->Html->charset(); ?>
        <title>
          Pida -:
            <?php echo $title_for_layout; ?>
        </title>
        <?php echo $this->Html->meta('favicon.ico', 'images/favicon.ico', array('type' => 'icon')); ?>
		<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet"> 
        <?php
	     echo $this->Html->script(array('front/jquery-2.1.3.min.js', 'front/owl.carousel.js','front/easy-responsive-tabs.js','front/materialize.min.js','front/validation/jquery.validate.min.js','front/fileupload/vendor/jquery.ui.widget.js','front/fileupload/jquery.iframe-transport.js','front/fileupload/jquery.fileupload.js'));
	     echo $this->Html->css(array('front/style.css', 'front/font-awesome.css', 'front/owl.carousel.css', 'front/owl.theme.css', 'front/easy-responsive-tabs.css', 'front/hover.css', 'front/materialize.min.css', 'front/validation.css'));



        echo $this->fetch('meta');
        echo $this->fetch('app');
        echo $this->fetch('script');
        ?>
    </head>

    <body>

        <div class="menu-div ">
            <i class="fa fa-close menu-close"></i>
            <div class='menu-part'>
                <ul>                                                                                                               
                    <li><a href="<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'home', 'action' => 'index', 'admin' => false)); ?>" >Home </a></li>
                    <li><a href="<?php echo $this->webroot.'about-us'; ?>" >About Us</a></li>
                    <li><a href="<?php echo $this->webroot.'service'; ?>" >Services</a></li>
					<li><a href="<?php echo $this->webroot.'term-conditions'; ?>" >Terms & Conditions</a></li>
					<li><a href="<?php echo $this->webroot.'help'; ?>" >Help</a></li>
                    <li><a href="<?php echo $this->webroot.'contact-us'; ?>"> Contact Us </a></li>

                </ul>
            </div>
        </div>

        <div id="popup3" class="modal-box">
            <a href="#" class="js-modal-close close"><img src="<?php echo $this->webroot?>img/front/popup-close.png" alt=""></a>
            <h3><i class="fa fa-sign-in"></i> Log in</h3>

            <div class="email-popup">
			<?php echo $this->Form->create('User', array('type' => 'POST')) ?>
			 <h4 class="login_message"></h4>
                <div class="email-popup-inner">
                    <span>
                        <i class="fa fa-envelope"></i>
                        <?php echo $this->Form->input('username', array('type' => 'text', 'placeholder' => 'Email', 'required' => 'required', 'div' => false, 'label' => false)) ?>
                    </span>

                    <span>
                        <i class="fa fa-lock"></i>
                        <?php echo $this->Form->input('password', array('type' => 'password', 'placeholder' => 'Password', 'required' => 'required', 'div' => false, 'label' => false)) ?>
                    </span>

                    <strong>
                        <a href="#_"  data-modal-id="popup5">Forgot Password</a>
						<?php echo $this->Form->button('<i class="fa fa-sign-in"></i>Login',array('class' => 'waves-effect','type' => 'submit','id' => 'login_button')); ?>
                        
                    </strong>

                    <p>Haven't you registered yet? <a href="<?php echo $this->webroot?>">Register here</a></p>

                </div>
            <?php echo $this->Form->end(); ?>
    

            </div>
        </div>
		
		
		
		<div id="popup5" class="modal-box">
            <a href="#" class="js-modal-close close"><img src="<?php echo $this->webroot?>img/front/popup-close.png" alt=""></a>
            <h3><i class="fa fa-sign-in"></i> Forgot Password</h3>

            <div class="email-popup">
			<?php echo $this->Form->create('User', array('type' => 'POST')) ?>
			 <h4 class="forgot_message"></h4>
                <div class="email-popup-inner">
                    <span>
                        <i class="fa fa-envelope"></i>
                        <?php echo $this->Form->input('email', array('type' => 'text', 'placeholder' => 'Email', 'required' => 'required', 'id'=>'forget_email', 'div' => false, 'label' => false)) ?>
                    </span>

                    <strong>
                        
						<?php echo $this->Form->button('<i class="fa fa-sign-in"></i>Send',array('class' => 'waves-effect','type' => 'submit','id' => 'reset_password_button')); ?>
                        
                    </strong>

                    

                </div>
            <?php echo $this->Form->end(); ?>
    

            </div>
        </div>
		
		<div id="popup4" class="modal-box">
            <a href="#" class="js-modal-close close"><img src="<?php echo $this->webroot?>img/front/popup-close.png" alt=""></a>
            

            <div class="email-popup">
                <div class="email-popup-inner">
                   <p class="message_txt">
				   
				   
				   
				   </p>

                </div>

    

            </div>
        </div>

		<?php //echo $this->element('frontheader'); ?>

		
		<?php echo $this->fetch('content'); ?>
		
		<footer>
            <div class="container">
                <div class="footer-left">
                    <ul>
                        <li><a href="<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'home', 'action' => 'index', 'admin' => false)); ?>">Home</a></li>
                        <li><a href="<?php echo $this->webroot.'about-us'; ?>">About Us</a></li>
                        <li><a href="<?php echo $this->webroot.'service'; ?>">Services     </a></li>
                        <li><a href="<?php echo $this->webroot.'privacy-policy'; ?>">Privacy Policy</a></li>
                        <li><a href="<?php echo $this->webroot.'term-conditions'; ?>">Terms & Conditions</a></li>
                        <li><a href="<?php echo $this->webroot.'help'; ?>">Help </a></li>
                        <li><a href="<?php echo $this->webroot.'contact-us'; ?>">Contact Us </a></li>
                    </ul>

                    <span>© Copyrights 2017 PIDA. All rights reserved. </span>

                </div>
                <div class="footer-right">
                    <ul>
                        <li><a href="#_" class="hvr-buzz-out"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#_" class="hvr-buzz-out" ><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#_" class="hvr-buzz-out" ><i class="fa fa-google-plus"></i></a></li>

                    </ul>
                </div>

                <div class="footer-logo">
                    <span>
                        <img src="<?php echo $this->webroot ?>img/front/footer-logo.png" alt="">
                    </span>
                </div>
            </div>
        </footer>
		
		
	

        <script type="text/javascript">
            $(document).ready(function () {
               // $('select').material_select();
                var appendthis = ("<div class='modal-overlay js-modal-close'></div>");

                $('a[data-modal-id]').click(function (e) {
					$('.js-modal-close').trigger('click');
                    e.preventDefault();
                    
                    $("body").append(appendthis);
                    $(".modal-overlay").fadeTo(500, 0.7);
                    //$(".js-modalbox").fadeIn(500);
                    var modalBox = $(this).attr('data-modal-id');
                    $('#' + modalBox).fadeIn($(this).data());

                });


                $(".js-modal-close, .modal-overlay").click(function () {
                    $(".modal-box, .modal-overlay").fadeOut(500, function () {
                        $(".modal-overlay").remove();
                    });
                });

                $('#textarea1').val('');
                $('#textarea1').trigger('autoresize');
            });
			
			
			
			 $('.datepicker').pickadate({
                selectMonths: true, // Creates a dropdown to control month
                selectYears: 15, // Creates a dropdown of 15 years to control year
            });

        </script>

        <script>
            $(document).ready(function () {
                $(".menu").on('click', function (e) {
                    $(".menu-div").animate({
                        width: "toggle"
                    });

                    $(this).hide();
                    $('.menu').hide();
                    $(".menu-close").show();

                });

                $(".menu-close").on('click', function (e) {
                    $(".menu-div").animate({
                        width: "toggle"
                    });

                    $(this).hide();
                    $('.menu-close').hide();
                    $(".menu").show();
                });
            });
        </script>

        <script type="text/javascript">


            $(document).ready(function () {

                $("#owl-demo").owlCarousel({
                    autoPlay: 7000, //Set AutoPlay to 3 seconds
                    navigation: true, // Show next and prev buttons
                    slideSpeed: 300,
                    paginationSpeed: 400,
                    singleItem: true,
                    pagination: false,
                    navigation : false

                            // "singleItem:true" is a shortcut for:
                            // items : 1, 
                            // itemsDesktop : false,
                            // itemsDesktopSmall : false,
                            // itemsTablet: false,
                            // itemsMobile : false

                });

            });


        </script>

        <script>
            $(document).ready(function () {
				
				
				$('ul.tabs li').click(function(){
					var tab_id = $(this).attr('data-tab');

					$('ul.tabs li').removeClass('current');
					$('.tab-content').removeClass('current');

					$(this).addClass('current');
					$("#"+tab_id).addClass('current');
				})
				
				
                $('#horizontalTab').easyResponsiveTabs({
                    type: 'default', //Types: default, vertical, accordion           
                    width: 'auto', //auto or any width like 600px
                    fit: true, // 100% fit in a container
                    closed: 'accordion', // Start closed if in accordion view
                    activate: function (event) { // Callback function if tab is switched
                        var $tab = $(this);
                        var $info = $('#tabInfo');
                        var $name = $('span', $info);
                        $name.text($tab.text());
                        $info.show();
                    }
                });
                $('#verticalTab').easyResponsiveTabs({
                    type: 'vertical',
                    width: 'auto',
                    fit: true
                });
				
				
				
				$("#login_button").click(function(){
            
            var formData = {
				'username'        : $('#UserUsername').val(),
				'password'         : $('#UserPassword').val(),
				
            };
            $.ajax({
                url: "<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'users', 'action' => 'login', 'admin' => false)); ?>",
                data: formData,
                type: 'post',
                format: "json",
                success: function(r){

                    obj = jQuery.parseJSON(r);
					$('#UserUsername').val('');
					$('#UserPassword').val('');
					
                    if(obj.success == '1'){
						window.location = '<?php echo $this->webroot?>'+obj.redirect.controller+'/'+obj.redirect.action;
					}
					else
					{
						
						$('.login_message').html(obj.message);
					}
					
                }

            });
			
			return false;

        });
		
		
		$("#reset_password_button").click(function(){
            
            var formData = {
				'email'        : $('#forget_email').val(),
				
				
            };
            $.ajax({
                url: "<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'users', 'action' => 'forgot_password', 'admin' => false)); ?>",
                data: formData,
                type: 'post',
                format: "json",
                success: function(r){

                    obj = jQuery.parseJSON(r);
					$('#forget_email').val('');
					
					
                    if(obj.success == '1'){
						$('.forgot_message').html(obj.message);
					}
					else
					{
						$('.forgot_message').html(obj.message);
					}
					
                }

            });
			
			return false;

        });
		
		
		
		
		
		$('#eventbannerupload').fileupload({
			url: '<?php echo SITEURL;?>users/upload_photo',
			dataType: 'json',
			done: function (e, data) {

				$.each(data.result.eventbanner, function (index, file) {

					$('#event_banner').val(file.url);
					
					$('#eventbannerclone').prop('src', '<?php echo $this->webroot ?>uploads/user_photos/thumbnail/'+file.url);
					
					var formData = {
					'filename'        : file.url,
					};
					
					$.ajax({
					url: "<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'users', 'action' => 'save_photo', 'admin' => false)); ?>",
					data: formData,
					type: 'post',
					format: "json",
					success: function(r){
						obj = jQuery.parseJSON(r);
					}

				});
					

				});
			}
        }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');
				
            });
        </script>

        <script>
            jQuery(window).scroll(function () {
                var fromTopPx = 50; // distance to trigger
                var scrolledFromtop = jQuery(window).scrollTop();
                if (scrolledFromtop > fromTopPx) {
                    jQuery('.header-main').addClass('scrolled');
                } else {
                    jQuery('.header-main').removeClass('scrolled');
                }
            });
        </script> 

    </body>
</html>
