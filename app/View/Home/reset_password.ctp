<section id="drive-slider"><img class="drive-bg" src="<?php echo $this->webroot?>img/front/head-bg.jpg" alt="">
	<div class="container">
	   <div class="slider-text-inner">Reset Password</div>
	</div>

</section>

<section id="drive-form">
	<div class="container">
		<div class="inner-page">
			 <div class="personal-details">

                            <div class="rider-form">
							<?php if(!$fail){?>
                            <form action="<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'users', 'action' => 'reset_password', 'admin' => false)); ?>" method="post" class="form" role="form" autocomplete="off" id="home/changePasswordForm" enctype="multipart/form-data" accept-charset="utf-8">
							<?php echo $this->Form->input('reset_code', array('type' => 'hidden','div' => false, 'label' => false, 'required' => true,'value' => $reset_code,'name' => 'reset_code')); ?>
                                <ul>
                                   <!-- <li>
                                        <span>Old Password :</span>
                                        <em><input type="password" placeholder=""></em>
                                    </li>-->

                                    <li>
                                        <span>New Password :</span>
                                        <em><?php echo $this->Form->input('password', array('type' => 'password', 'maxlength' => '50', 'div' => false, 'label' => false, 'required' => true,'value' => '','name' => 'password')); ?></em>
                                    </li>

                                    <li>
                                        <span>Confirm Password :</span>
                                        <em><?php echo $this->Form->input('password', array('type' => 'password', 'maxlength' => '50', 'div' => false, 'label' => false, 'required' => true,'value' => '' ,'name' => 'cpassword')); ?></em>
                                    </li>

                                    <li>
                                        <?php echo $this->Form->button('Change Password',array('class' => 'waves-effect','type' => 'submit','id' => 'customer_signup1'));  ?>

                                       
                                    </li>


                                </ul>
                             </form>
							 <?php } else {
							 
							 echo '<h6>'.$this->Session->flash().'</h6>';
							 
							 }?>
                            </div>


                        </div>

		</div>
	</div>
</section>