<?php $option_status = array('A' => 'Active', 'I' => 'Inactive'); ?>

<!-- start: PAGE TITLE -->
<section id="page-title">
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mainTitle"><?php echo ('' == $id) ? 'Add' : 'Edit'; ?> Customer</h1>
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
            <?php echo $this->Form->create('User', array('method' => 'post', 'class' => 'form', 'role' => 'form', 'autocomplete' => 'off', 'type' => 'file'));
            echo $this->Form->input('id', array('type' => 'hidden', 'required' => false)); ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">First Name<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('first_name', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Last Name<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('last_name', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Email<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('email', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Phone Number<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('phone', array('type' => 'text', 'maxlength' => '20', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                    </div>
                </div>

                <?php if('' == $id){ ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Password<span class="symbol required"></span></label>
                            <?php echo $this->Form->input('password', array('type' => 'password', 'maxlength' => '50', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Confirm Password<span class="symbol required"></span></label>
                            <?php echo $this->Form->input('confirm_password', array('type' => 'password', 'maxlength' => '50', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                        </div>
                    </div>
                <?php } ?>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Status</label>
                        <?php echo $this->Form->input('customer_status', array('options' => $option_status, 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Photo (jpg, jpeg, png, gif)</label>
                        <?php echo $this->Form->file('user_photo', array('label' => false, 'div' => false, 'required' => false)); ?>
                        <?php echo $this->Form->error('user_photo', array('wrap' => false)); ?>
                    </div>
                </div>

                <?php if('' != $id){ ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php $photo = $this->request->data['User']['photo'];
                            if('' != $photo && file_exists(USER_PHOTO_PATH_THUMB . $photo)){ ?>
                                <label class="control-label">
                                    <?php echo $this->Form->input('recent_photo', array('type' => 'hidden', 'required' => false, 'value' => $photo)); ?>
                                    <img src="<?php echo USER_PHOTO_URL_THUMB . $photo; ?>" />
                                </label>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <div>
                        <span class="symbol required"></span>Required Fields
                        <hr>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7">
                </div>
                <div class="col-md-5">
                    <?php echo $this->Form->button('Save <i class="fa fa-arrow-circle-right"></i>',array('class' => 'btn btn-primary btn-wide pull-left_form','type' => 'submit','id' => 'submit_button'));

                    echo $this->Html->link('Cancel <i class="fa fa-times-circle"></i>',
                        array('plugin' => false,'controller' => 'customers','action' => 'index', 'admin' => true),
                        array('class' => 'btn btn-primary btn-wide pull-right', 'escape' => false)
                    );
                    ?>

                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>