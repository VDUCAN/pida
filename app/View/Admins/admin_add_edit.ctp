<?php
$option_status = array('A' => 'Active', 'I' => 'Inactive');
?>

<!-- start: PAGE TITLE -->
<section id="page-title">
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mainTitle">
            <?php if($id == $this->Session->read('Admin.id')){
                echo 'Update Profile';
            }else{
                echo ('' == $id) ? 'Add' : 'Edit' . ' Admin User';
            } ?>
            </h1>
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
            <?php echo $this->Form->create('Admin', array('method' => 'post', 'class' => 'form', 'role' => 'form', 'autocomplete' => 'off', 'type' => 'file'));
            echo $this->Form->input('id', array('type' => 'hidden', 'required' => false)); ?>
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">First Name<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('firstname', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Last Name<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('lastname', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Email<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('email', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                    </div>
                </div>

                <?php if($id != $this->Session->read('Admin.id')){ ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Status</label>
                            <?php echo $this->Form->input('status', array('options' => $option_status, 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                        </div>
                    </div>

                    <div class="clearfix"></div>

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

                        <div class="clearfix"></div>
                    <?php } ?>

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

                    if($id == $this->Session->read('Admin.id')){
                        echo $this->Html->link('Cancel <i class="fa fa-times-circle"></i>',
                            array('plugin' => false,'controller' => 'admins','action' => 'dashboard', 'admin' => true),
                            array('class' => 'btn btn-primary btn-wide pull-right', 'escape' => false)
                        );
                    }else{
                        echo $this->Html->link('Cancel <i class="fa fa-times-circle"></i>',
                            array('plugin' => false,'controller' => 'admins','action' => 'users', 'admin' => true),
                            array('class' => 'btn btn-primary btn-wide pull-right', 'escape' => false)
                        );
                    }
                    ?>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>