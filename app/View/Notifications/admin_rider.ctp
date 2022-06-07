<?php echo $this->Html->script(array('ckeditor/ckeditor'));?>
<script type ="text/javascript" src="<?php echo APIURL . 'app/js/multiple-select.js'; ?>"></script>
<link href="<?php echo APIURL . 'app/css/multiple-select.css'; ?>" rel="stylesheet"/>
<!-- start: PAGE TITLE -->
<section id="page-title">
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mainTitle"> Send Notification To Riders </h1>
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
            <?php echo $this->Form->create('Notification', array('method' => 'post', 'class' => 'form', 'role' => 'form', 'autocomplete' => 'off'));?>
				<div class="row">

					 <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Subject <span class="symbol required"></span></label>
                            <?php echo $this->Form->input('subject',  array('type' => 'text', 'maxlength' => '100', 'class' =>'form-control validate[required]', 'div' => false, 'label' => false, 'required' => false));
                          
                            ?>
                        </div>
                    </div>
				</div>
				<div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Message <span class="symbol required"></span></label>
                            <?php echo $this->Form->textarea('message',array('id'=>'body', 'class' => 'form-control validate[required]'));
                            ?>
                        </div>
                    </div>                
				</div>
				<div class="row">
					<div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Select Rider <span class="symbol required"></span></label>
                            	<div class="riders_list" style="margin-bottom: 10px;">

									<select multiple="multiple"  name="data[Notification][Users][]">
									<?php
									if (!empty($users_list)) {
										$i = 0;
										foreach ($users_list as $udata) {
										?>
										<option value="<?php echo $udata['User']['id'] ?>"><?php echo $udata['User']['first_name']."  ".$udata['User']['last_name'] ?></option>
										<?php
										$i++;
										}
									}
									?>

									</select>

								</div>
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
                        array('plugin' => false,'controller' => 'pages','action' => 'index', 'admin' => true),
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
        $("#PageAdminAddEditForm").validationEngine();
    });
	
	
	
</script>
<script>
    $("select").multipleSelect({
	filter: true,
	multiple: true
    });
</script>