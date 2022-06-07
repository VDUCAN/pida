<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#cancel_button").click(function () {
            window.location.href = '<?php echo $this->Html->url(array('plugin' => false, 'controller' => $this->params['type'], 'action' => 'index', 'admin' => true)); ?>';
        });
    });
</script>

<!-- start: PAGE TITLE -->
<section id="page-title">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mainTitle">Change Password: <?php echo ucfirst($user_data['User']['first_name']) . ' ' . ucfirst($user_data['User']['last_name']); ?></h1>
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
            <?php echo $this->Form->create('User', array('class' => 'form', 'role' => 'form', 'autocomplete' => 'off')); ?>
            <div class="row">

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
                    echo $this->Form->button('Cancel <i class="fa fa-times-circle"></i>',array('class' => 'btn btn-primary btn-wide pull-right','type' => 'button','id' => 'cancel_button')) ?>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>