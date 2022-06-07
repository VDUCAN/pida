<?php
$option_status = array('1' => 'Open', '2' => 'Assign', '3' => 'Cancel', '4' => 'Arrived', '5' => 'Completed', '6' => 'Paid');
$option_type = array('1' => 'Now', '2' => 'Scheduled');
?>
<section id="drive-slider"><img class="rider-bg" src="<?php echo $this->webroot ?>img/front/rider-page.jpg" alt="">
    <div class="diver-deatils">
        <div class="container">
            <div class="rider-dashbord">

                <span>
				<?php if($this->Session->read('User.photo') != '') { ?>
                    <img src="<?php echo $this->webroot.'uploads/user_photos/thumbnail/'.$this->Session->read('User.photo') ?>" alt="" id="eventbannerclone">
				<?php } else { ?>
					<img src="<?php echo $this->webroot.'images/noprofile.gif' ?>" alt="" id="eventbannerclone">
					
				<?php } ?>
                </span>

                <em>
                    <h2><?php echo $this->Session->read('User.first_name')." ".$this->Session->read('User.last_name')?> </h2>
                    <strong><div class="file-btn1"><input type="hidden" name="event_banner" id="event_banner" value="<?php echo $this->Session->read('User.photo') ?>">
						<input type="file" id="eventbannerupload" name="eventbanner"><i class="fa fa-camera"></i> Change Profile Photo</div></strong>
                    


                </em>







            </div>
        </div>


    </div>

</section>

<section id="rider-part">
    <div class="container">
        <div class="rider-main">


            <div id="horizontalTab" class="rider-tab">
                <ul class="resp-tabs-list">
                    <li><i class="fa fa-user"></i> Edit Profile</li>
                    <li><i class="fa fa-book"></i>  Bookings </li>
                    <li><i class="fa fa-dollar"></i>Add Payment</li>
                    <li><i class="fa fa-lock"></i> Change Password </li>
                </ul>
                <div class="resp-tabs-container">
                    <div class="tab-top">
                        <div class="tab-heading">
                            <h3><i class="fa fa-user"></i>Edit Profile</h3>
                        </div>
                        <div class="personal-details">

                            <div class="rider-form">
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
                                        <em><?php echo $this->Form->input('last_name', array('type' => 'text', 'div' => false, 'placeholder' => 'Last Name','label' => false, 'required' => true)); ?></em>
                                    </li>

                                    <li>
                                        <span>Email :</span>
                                        <em><?php echo $this->Form->input('email', array('type' => 'text', 'div' => false, 'placeholder' => 'Email','label' => false, 'required' => true)); ?></em>
                                    </li>

                                    <li>
                                        <span>Mobile :</span>
                                        <em><?php echo $this->Form->input('phone', array('type' => 'text', 'div' => false, 'placeholder' => 'Mobile','label' => false, 'required' => true)); ?></em>
                                    </li>

                                    <li>

                                       <?php echo $this->Form->button('Save Changes',array('class' => 'waves-effect','type' => 'submit','id' => 'customer_signup1'));  ?>

                                       <?php echo $this->Form->button('Cancel',array('class' => 'waves-effect','type' => 'button','id' => 'customer_signup1'));  ?>


                                    </li>


                                </ul>
								<?php echo $this->Form->end(); ?>
                            </div>


                        </div>


                    </div>


                    <div class="tab-top">
                        <div class="tab-heading">
                            <h3><i class="fa fa-book"></i>Booking List</h3>
                        </div>
                        <div class="earning">
                            <em>

                                <div class="input-field">
                                    <label>Records per page : </label>
                                    <select class="browser-default select-wrapper">
                                        <option value="" disabled selected>10</option>
                                        <option value="1">11</option>
                                        <option value="2">12</option>
                                        <option value="3">13</option>
                                    </select>

                                </div>
                            </em>

                            <div class="booking-scroll">
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tbody>
                                        <tr >
                                            <th>Booking ID</th>
                                            <th>Customer</th>
                                            <th>Driver</th>
                                            <th>Pickup Date</th>
                                            <th>Price</th>
                                            <th>Miles</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Created on</th>
                                            <!--<th>Action</th>-->
                                        </tr>
										<?php if (!empty($result_data1)) { ?>
                                <?php foreach ($result_data1 as $data) { ?>
								
								<tr>
                                            <td><?php echo $data['Booking']['id']; ?></td>
                                            <td> <?php echo ucfirst($data['User']['first_name'] . ' ' . $data['User']['last_name']); ?>    </td>
                                            <td> <?php echo ucfirst($data['Driver']['first_name'] . ' ' . $data['Driver']['last_name']); ?></td>
                                            <td><?php
											if (!empty($data['Booking']['pickup_date']) && date('Y-m-d', strtotime($data['Booking']['pickup_date'])) != '1970-01-01') {
												echo date(DATETIME_FORMAT, strtotime($data['Booking']['pickup_date']));
											} else {
												echo '-';
											}
													?></td>
												<td><?php echo $data['Booking']['price']; ?></td>
												<td><?php echo $data['Booking']['total_miles']; ?></td>
												<td><?php
													if ($data['Booking']['booking_type']) {
														echo $option_type[$data['Booking']['booking_type']];
													} else {
														echo '-';
													}
													?></td>
												<td><?php
													if ($data['Booking']['booking_status']) {
														echo $option_status[$data['Booking']['booking_status']];
													} else {
														echo '-';
													}
													?></td>
												<td><?php
											if (!empty($data['Booking']['created']) && date('Y-m-d', $data['Booking']['created']) != '1970-01-01') {
												echo date(DATETIME_FORMAT, $data['Booking']['created']);
											} else {
												echo '-';
											}
													?></td>
                                            <!--<td><a href="#_"><i class="fa fa-eye"></i></a></td>-->

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

                    <div>
                        <p>Login to mobile add to add payment method.</p>
                    </div>




                    <div class="tab-top">
                        <div class="tab-heading">
                            <h3><i class="fa fa-lock"></i>Change Password</h3>
                        </div>
                        <div class="personal-details">

                            <div class="rider-form">
                            <form action="change_password" method="post" class="form" role="form" autocomplete="off" id="home/changePasswordForm" enctype="multipart/form-data" accept-charset="utf-8">
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
</section>
