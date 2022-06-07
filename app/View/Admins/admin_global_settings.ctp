<!-- start: PAGE TITLE -->
<section id="page-title">
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mainTitle">Global Settings</h1>
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
            <?php echo $this->Form->create('GlobalSetting', array('method' => 'post', 'class' => 'form', 'role' => 'form', 'autocomplete' => 'off', 'type' => 'file'));
            echo $this->Form->input('id', array('type' => 'hidden', 'required' => false)); ?>
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Email to show in from email, for the emails sent by the application<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('from_email', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control validate[required, custom[email]]', 'div' => false, 'label' => false, 'required' => false)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Email on which admin will recieve emails from the application<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('to_email', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control validate[required, custom[email]]', 'div' => false, 'label' => false, 'required' => false)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Text to show in place of from email, for the emails sent by the application<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('from_email_text', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control validate[required]', 'div' => false, 'label' => false, 'required' => false)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Booking Cancel Timeframe Of Customer (In minutes)<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('customer_booking_cancel_timeframe', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control numericOnly validate[required]', 'div' => false, 'label' => false, 'required' => false)); ?>
                    </div>
                </div>

                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Admin Commission (In %)<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('admin_commission', array('type' => 'text', 'maxlength' => '5', 'class' =>'form-control floatOnly validate[required,max[99.99]]', 'div' => false, 'label' => false, 'required' => false)); ?>
                    </div>
                </div>
                

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Admin Pida Fee (In $)<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('admin_pida_fee', array('type' => 'text', 'maxlength' => '6', 'class' =>'form-control floatOnly validate[required]', 'div' => false, 'label' => false, 'required' => false)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Per Miles Fare (In $)<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('per_mile_fare', array('type' => 'text', 'maxlength' => '6', 'class' =>'form-control floatOnly validate[required]', 'div' => false, 'label' => false, 'required' => false)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Minimum Fare distance (In Miles)<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('minimum_fare_distance', array('type' => 'text', 'maxlength' => '6', 'class' =>'form-control floatOnly validate[required]', 'div' => false, 'label' => false, 'required' => false)); ?>
                    </div>
                </div>

		<?php /* ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Minimum Fare (In $)<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('minimum_fare', array('type' => 'text', 'maxlength' => '6', 'class' =>'form-control floatOnly validate[required]', 'div' => false, 'label' => false, 'required' => false)); ?>
                    </div>
                </div>
		<?php */ ?>
		
		<?php foreach($categories as $category){
		    $cName = $category['cl']['name'];
		    $key = $category['c']['id'];
		    $cFare = $category['c']['minimum_fare'];
		    ?>
		<div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Minimum Fare <?php echo $cName;?> (In $)<span class="symbol required"></span></label>
                        <?php 
			echo $this->Form->input('minimum_fare.' . $key . '.minimum_fare', array('type' => 'text', 'maxlength' => '6', 'class' =>'form-control floatOnly validate[required]', 'div' => false, 'label' => false, 'required' => false,'value'=>$cFare));
			echo $this->Form->input('minimum_fare.' . $key . '.id', array('type' => 'hidden', 'required' => false,'value'=>$key));
			?>
                    </div>
                </div>
		<?php } ?>

				<div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Payout Day<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('payout_day', array('type' => 'select', 'options' => $weekdays, 'class' =>'form-control validate[required]', 'div' => false, 'label' => false, 'required' => false)); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Waiting Time<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('waiting_time', array('type' => 'text', 'maxlength' => '6', 'class' =>'form-control floatOnly validate[required]', 'div' => false, 'label' => false, 'required' => false)); ?>
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

                    echo $this->Html->link('Cancel <i class="fa fa-times-circle"></i>',
                        array('plugin' => false,'controller' => 'admins','action' => 'dashboard', 'admin' => true),
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
    jQuery(document).ready(function(){
        jQuery('.numericOnly').keyup(function () {
            this.value = this.value.replace(/[^0-9]/g,'');
        });
        jQuery('.floatOnly').keyup(function () {
            this.value = this.value.replace(/[^0-9\.]/g,'');
        });

        jQuery("#GlobalSettingAdminGlobalSettingsForm").validationEngine();
    });
</script>