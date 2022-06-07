<section id="rider-part">
    <div class="container">
        <div class="rider-main">
<div class="personal-details">

                            <div class="rider-form">
                                <h3>Contact Us</h3>
								<?php echo $this->Form->create('ContactUs', array('method' => 'post', 'class' => 'form', 'role' => 'form', 'autocomplete' => 'off', 'type' => 'file')); ?>
								
								
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
                                        <span>Reason :</span>
                                        <em><?php echo $this->Form->input('reason', array('type' => 'text', 'div' => false, 'placeholder' => 'Reason','label' => false, 'required' => true)); ?></em>
                                    </li>
									
									<li>
                                        <span>Type :</span>
                                        <em>
										
										<select name="type" class="browser-default" id="type">
												<option value="driver">Driver</option>
												<option value="customer">Customer</option>
										</select>
										
										
										</em>
                                    </li>
									
									<li>
                                        <span>Message :</span>
                                        <em>
										
											<textarea name="message" style="height:100px"></textarea>
										
										
										</em>
                                    </li>

                                    <li>

                                       <?php echo $this->Form->button('Send Request',array('class' => 'waves-effect','type' => 'submit','id' => 'customer_signup1'));  ?>

                                       <?php echo $this->Form->button('Cancel',array('class' => 'waves-effect','type' => 'button','id' => 'customer_signup1'));  ?>


                                    </li>


                                </ul>

<p style="display: inline-block;width: 100%;text-align: center;">
									Email : info@pida.com
									&nbsp;&nbsp;Phone : 7867660935
								</p>

								<?php echo $this->Form->end(); ?>
                            </div>


                        </div>
						                        </div>
												                        </div>
																		                        </section>
