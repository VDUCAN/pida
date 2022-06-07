
<?php 
        echo $this->Html->css(array('lightbox.css'), null, array('inline' => false));
        echo $this->Html->script(array('lightbox.js'), array('inline' => false));
?>

<?php $option_status = array('A' => 'Active', 'I' => 'Inactive'); ?>

<!-- start: PAGE TITLE -->
<section id="page-title">
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mainTitle"><?php echo ('' == $id) ? 'Add' : 'Edit'; ?> Driver</h1>
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
            echo $this->Form->input('id', array('type' => 'hidden', 'required' => false));
            echo $this->Form->input('DriverDetail.id', array('type' => 'hidden', 'required' => false)); ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">First Name<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('first_name', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control validate[required]', 'div' => false, 'label' => false, 'required' => false)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Last Name<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('last_name', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control validate[required]', 'div' => false, 'label' => false, 'required' => false)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Email<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('email', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control validate[required, custom[email]]', 'div' => false, 'label' => false, 'required' => false)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Phone Number<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('phone', array('type' => 'text', 'maxlength' => '20', 'class' =>'form-control validate[required]', 'div' => false, 'label' => false, 'required' => false)); ?>
                    </div>
                </div>

                <?php if('' == $id){ ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Password<span class="symbol required"></span></label>
                            <?php echo $this->Form->input('password', array('type' => 'password', 'maxlength' => '50', 'class' =>'form-control validate[required]', 'div' => false, 'label' => false, 'required' => false)); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Confirm Password<span class="symbol required"></span></label>
                            <?php echo $this->Form->input('confirm_password', array('type' => 'password', 'maxlength' => '50', 'class' =>'form-control validate[required]', 'div' => false, 'label' => false, 'required' => false)); ?>
                        </div>
                    </div>
                <?php } ?>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">SSN<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('DriverDetail.ssn', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control validate[required]', 'div' => false, 'label' => false, 'required' => false)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Driving License Number<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('DriverDetail.driving_license_no', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control validate[required]', 'div' => false, 'label' => false, 'required' => false)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Status</label>
                        <?php echo $this->Form->input('driver_status', array('options' => $option_status, 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Driving License Docoument (jpg, jpeg, png, gif)<?php if('' == $id){ ?><span class="symbol required"></span><?php } ?></label>
                        <?php $required =  ('' == $id) ? true : false;
                        echo $this->Form->file('DriverDetail.dl_doc', array('label' => false, 'div' => false, 'required' => $required)); ?>
                        <?php echo $this->Form->error('DriverDetail.dl_doc', array('wrap' => false)); ?>
                    </div>
                </div>

                <?php if('' != $id){ ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php $doc = $this->request->data['DriverDetail']['driving_license_doc'];
                            if('' != $doc && file_exists(DRIVER_DOC_PATH . $doc)){ ?>
                                <label class="control-label">
                                    <?php echo $this->Form->input('DriverDetail.recent_dl_doc', array('type' => 'hidden', 'required' => false, 'value' => $doc)); ?>

                                     <a class="drive-image-link" href="<?php echo DRIVER_DOC_URL . $doc; ?>" data-lightbox="driver-doc" data-title="Driving License Document"><img class="driver-image" src="<?php echo DRIVER_DOC_URL . $doc; ?>" alt="Driving License Document" /></a>
                                    
                                </label>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>

                <div class="clearfix"></div>

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
                            if('' != $photo && file_exists(USER_PHOTO_PATH . $photo)){ ?>
                                <label class="control-label">
                                    <?php echo $this->Form->input('recent_photo', array('type' => 'hidden', 'required' => false, 'value' => $photo)); ?>
                                    <a class="drive-image-link" href="<?php echo USER_PHOTO_URL . $photo; ?>" data-lightbox="driver-doc" data-title="Driving License Document"><img class="driver-image" src="<?php echo USER_PHOTO_URL . $photo; ?>" alt="Driving License Document" /></a>
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
                        array('plugin' => false,'controller' => 'drivers','action' => 'index', 'admin' => true),
                        array('class' => 'btn btn-primary btn-wide pull-right', 'escape' => false)
                    );
                    ?>

                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<?php
echo $this->Html->css(array('validationEngine.jquery'));
echo $this->Html->script(array('jquery.validationEngine-en', 'jquery.validationEngine'));
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#UserAdminAddEditForm").validationEngine();
    });
</script>