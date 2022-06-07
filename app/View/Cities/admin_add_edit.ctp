<?php $option_status = array('A' => 'Active', 'I' => 'Inactive'); ?>

<!-- start: PAGE TITLE -->
<section id="page-title">
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mainTitle"><?php echo ('' == $id) ? 'Add' : 'Edit'; ?> City</h1>
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
            <?php echo $this->Form->create('City', array('method' => 'post', 'class' => 'form', 'role' => 'form', 'autocomplete' => 'off'));
            echo $this->Form->input('id', array('type' => 'hidden', 'required' => false)); ?>
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Country<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('country_id', array('options' => $countries, 'class' => 'form-control validate[required]', 'empty' => '-- Select Country --', 'label' => false, 'div' => false, 'required' => false)); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">State<span class="symbol required"></span></label>
                        <?php echo $this->Form->input('state_id', array('options' => $states, 'class' => 'form-control validate[required]', 'empty' => '-- Select State --', 'label' => false, 'div' => false, 'required' => false)); ?>
                    </div>
                </div>

                <?php foreach ($languages as $key => $language){ ?>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">City Name (<?php echo $language; ?>)<span class="symbol required"></span></label>
                            <?php echo $this->Form->input('CityLocale.' . $key . '.name', array('type' => 'text', 'maxlength' => '100', 'class' =>'form-control validate[required]', 'div' => false, 'label' => false, 'required' => false));
                            echo $this->Form->input('CityLocale.' . $key . '.id', array('type' => 'hidden', 'required' => false));
                            ?>
                        </div>
                    </div>

                <?php } ?>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Status</label>
                        <?php echo $this->Form->input('status', array('options' => $option_status, 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
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
                        array('plugin' => false,'controller' => 'cities','action' => 'index', 'admin' => true),
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
        $("#CityAdminAddEditForm").validationEngine();

        $("#CityCountryId").change(function(){

            var id = $(this).val();
            $.ajax({
                url: "<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'ajax', 'action' => 'get_states', 'admin' => false)); ?>",
                data: {'id' : id, 'lang' : 'en', 'show_inactive' : '1'},
                type: 'post',
                format: "json",
                success: function(r){
                    $("#CityStateId").html('');

                    var options_data = '<option value="">-- Select State --</option>';

                    obj = jQuery.parseJSON(r);
                    $.each(obj, function( key, value ) {
                        options_data += '<option value="' + key + '">' + value + '</option>';
                    });

                    $("#CityStateId").html(options_data);
                }

            });

        });
    });


</script>