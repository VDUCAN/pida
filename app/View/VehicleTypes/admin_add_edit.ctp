<?php
$option_status = array('A' => 'Active', 'I' => 'Inactive');
?>

<!-- start: PAGE TITLE -->
<section id="page-title">
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mainTitle"><?php echo ('' == $id) ? 'Add' : 'Edit'; ?> Vehicle Type</h1>
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
            <?php echo $this->Form->create('VehicleType', array('method' => 'post', 'class' => 'form', 'role' => 'form', 'autocomplete' => 'off', 'type' => 'file'));
            echo $this->Form->input('id', array('type' => 'hidden', 'required' => false)); ?>
            <div class="row">

                <?php foreach ($languages as $key => $language){ ?>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Vehicle Type (<?php echo $language; ?>)<span class="symbol required"></span></label>
                            <?php echo $this->Form->input('VehicleTypeLocale.' . $key . '.name', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control validate[required]', 'div' => false, 'label' => false, 'required' => false));
                            echo $this->Form->input('VehicleTypeLocale.' . $key . '.id', array('type' => 'hidden', 'required' => false));
                            ?>
                        </div>
                    </div>

                <?php } ?>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Category<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('category_id', array('options' => $categories, 'class' => 'form-control validate[required]', 'empty' => '-- Select Category --', 'label' => false, 'div' => false, 'required' => false)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Status</label>
                        <?php echo $this->Form->input('status', array('options' => $option_status, 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Image (jpg, jpeg, png, gif)<?php if('' == $id){ ?><span class="symbol required"></span><?php } ?></label>
                        <?php $required =  ('' == $id) ? true : false;
                        echo $this->Form->file('vehicle_type_image', array('label' => false, 'div' => false, 'required' => $required)); ?>
                        <?php echo $this->Form->error('vehicle_type_image', array('wrap' => false)); ?>
                    </div>
                </div>

                <?php if('' != $id){ ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php $photo = $this->request->data['VehicleType']['image'];
                            if('' != $photo && file_exists(VEHICLE_TYPE_IMG_PATH_THUMB . $photo)){ ?>
                                <label class="control-label">
                                    <?php echo $this->Form->input('recent_photo', array('type' => 'hidden', 'required' => false, 'value' => $photo)); ?>
                                    <img src="<?php echo VEHICLE_TYPE_IMG_URL_THUMB . $photo; ?>" />
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
                        array('plugin' => false,'controller' => 'vehicle_types','action' => 'index', 'admin' => true),
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
        $("#VehicleTypeAdminAddEditForm").validationEngine();
    });
</script>