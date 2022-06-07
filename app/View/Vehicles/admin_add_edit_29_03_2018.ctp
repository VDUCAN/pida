<?php $option_status = array('A' => 'Active', 'I' => 'Inactive');
$required =  ('' == $id) ? true : false;
?>

<!-- start: PAGE TITLE -->
<section id="page-title">
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mainTitle"><?php echo ('' == $id) ? 'Add' : 'Edit'; ?> Vehicle</h1>
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
            <?php echo $this->Form->create('Vehicle', array('method' => 'post', 'class' => 'form', 'role' => 'form', 'autocomplete' => 'off', 'type' => 'file'));
            echo $this->Form->input('id', array('type' => 'hidden', 'required' => false)); ?>

            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Driver<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('user_id', array('options' => $users, 'class' => 'form-control validate[required]', 'empty' => '-- Select Driver --', 'label' => false, 'div' => false, 'required' => false)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Plate Number<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('plate_no', array('type' => 'text', 'maxlength' => '100', 'class' =>'form-control validate[required]', 'div' => false, 'label' => false, 'required' => false)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Vehicle Make<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('make_id', array('options' => $makes, 'class' => 'form-control', 'empty' => '-- Select Vehicle Make --', 'label' => false, 'div' => false, 'required' => false)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Vehicle Model<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('model_id', array('options' => $models, 'class' => 'form-control', 'empty' => '-- Select Vehicle Model --', 'label' => false, 'div' => false, 'required' => false)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Registration Number<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('registration_no', array('type' => 'text', 'maxlength' => '100', 'class' =>'form-control validate[required]', 'div' => false, 'label' => false, 'required' => false)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Make year<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('make_year', array('type' => 'text', 'maxlength' => '4', 'class' =>'form-control validate[required] numericOnly', 'div' => false, 'label' => false, 'required' => false)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Color</label>
                        <?php echo $this->Form->input('color', array('type' => 'text', 'maxlength' => '100', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => false)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Insurance Policy Number<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('insurance_policy_no', array('type' => 'text', 'maxlength' => '100', 'class' =>'form-control validate[required]', 'div' => false, 'label' => false, 'required' => false)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Insurance Expiry Date<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('insurance_expiry_date', array('type' => 'text', 'maxlength' => '100', 'class' =>'form-control validate[required] datepicker', 'div' => false, 'label' => false, 'required' => false)); ?>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label">Vehicle Type<span class="symbol required"></span></label>
                        <div class="clearfix"></div>
                        <?php
                            if(isset($this->data['Vehicle']['vehicle_type_id'])){
                                $vehicle_types_value = $this->data['Vehicle']['vehicle_type_id'];
                            }else{
                                $type_keys = array_keys($vehicle_types);
                                $vehicle_types_value = $type_keys[0];
                            } 
                            $attributes = array('label'=>false,'legend'=>false,'div' => 'input', 'type' => 'radio', 'options' => $vehicle_types,'value'=>$vehicle_types_value);
                            echo $this->Form->input('vehicle_type_id',$attributes);
                        ?>
                        <?php // echo $this->Form->input('vehicle_type', array('type'=>'select', 'multiple'=>'checkbox', 'options' => $vehicle_types, 'class' => 'form-control border-none width-auto pull-left', 'empty' => false, 'label' => false, 'div' => false)); ?>
                    </div>
                </div>

                <div class="clearfix padding-bottom-15"></div>

                <?php /*<div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Status</label>
                        <?php echo $this->Form->input('status', array('options' => $option_status, 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                    </div>
                </div>

                <div class="clearfix"></div>*/ ?>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Registration Docoument (jpg, jpeg, png, gif)<?php if('' == $id){ ?><span class="symbol required"></span><?php } ?></label>
                        <?php echo $this->Form->file('registration_doc_file', array('label' => false, 'div' => false, 'required' => $required)); ?>
                        <?php echo $this->Form->error('registration_doc_file', array('wrap' => false)); ?>
                    </div>
                </div>

                <?php if('' != $id){ ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php $doc = $this->request->data['Vehicle']['registration_doc'];
                            if('' != $doc && file_exists(VEHICLE_DOC_PATH_THUMB . $doc)){ ?>
                                <label class="control-label">
                                    <?php echo $this->Form->input('recent_registration_doc_file', array('type' => 'hidden', 'required' => false, 'value' => $doc)); ?>
                                    <img src="<?php echo VEHICLE_DOC_URL_THUMB . $doc; ?>" />
                                </label>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>

                <div class="clearfix"></div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Insurance Policy Docoument (jpg, jpeg, png, gif)<?php if('' == $id){ ?><span class="symbol required"></span><?php } ?></label>
                        <?php echo $this->Form->file('insurance_policy_doc_file', array('label' => false, 'div' => false, 'required' => $required)); ?>
                        <?php echo $this->Form->error('insurance_policy_doc_file', array('wrap' => false)); ?>
                    </div>
                </div>

                <?php if('' != $id){ ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php $doc1 = $this->request->data['Vehicle']['insurance_policy_doc'];
                            if('' != $doc1 && file_exists(VEHICLE_DOC_PATH_THUMB . $doc1)){ ?>
                                <label class="control-label">
                                    <?php echo $this->Form->input('recent_insurance_policy_doc_file', array('type' => 'hidden', 'required' => false, 'value' => $doc1)); ?>
                                    <img src="<?php echo VEHICLE_DOC_URL_THUMB . $doc1; ?>" />
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
                        array('plugin' => false,'controller' => 'vehicles','action' => 'index', 'admin' => true),
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
echo $this->Html->css(array('validationEngine.jquery', 'bootstrap-datetimepicker'));
echo $this->Html->script(array('jquery.validationEngine-en', 'jquery.validationEngine', 'bootstrap-datepicker'));
?>
<script type="text/javascript">
    $(document).ready(function () {

        $("#VehicleAdminAddEditForm").validationEngine();

        jQuery('.numericOnly').keyup(function () {
            this.value = this.value.replace(/[^0-9]/g,'');

        });

        jQuery('.datepicker').datepicker({
            format: 'yyyy-mm-dd'/*,
            endDate: '-18y'*/
        });

        $("#VehicleMakeId").change(function(){

            var id = $(this).val();
            $.ajax({
                url: "<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'ajax', 'action' => 'get_models', 'admin' => false)); ?>",
                data: {'id' : id, 'lang' : 'en', 'show_inactive' : '1'},
                type: 'post',
                format: "json",
                success: function(r){
                    $("#VehicleModelId").html('');

                    var options_data = '<option value="">-- Select Vehicle Model --</option>';

                    obj = jQuery.parseJSON(r);
                    $.each(obj, function( key, value ) {
                        options_data += '<option value="' + key + '">' + value + '</option>';
                    });

                    $("#VehicleModelId").html(options_data);
                }

            });

        });
    });
</script>