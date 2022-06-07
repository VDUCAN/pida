<script type ="text/javascript" src="<?php echo APIURL . 'app/js/multiple-select.js'; ?>"></script>
<link href="<?php echo APIURL . 'app/css/multiple-select.css'; ?>" rel="stylesheet"/>

<section class="content-header">
    <div class="row">
	<div class="col-sm-3 breadcrumb1">
            <h1>
                Send Notification
            </h1>
        </div>
	<div class="col-sm-5 breadcrumb2">
	    <h2> Send Notification  To Riders</h2>
	</div>
	<div class="col-sm-4 breadcrumb">
	    <ol class="breadcrumb">
		<li><?php echo $this->Html->link('<i class="fa fa-dashboard"></i> Home', array('plugin' => false, 'controller' => 'users', 'action' => 'dashboard'), array('escape' => false)); ?></li>
		<li class="active">Send Notification To Riders</li>
	    </ol>
	</div>
    </div>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-body">
		    <?php echo $this->Session->flash(); ?>
		    <?php echo $this->Form->create(false, array('controller' => 'Notifications', 'action' => 'index', 'enctype' => 'multipart/form-data')); ?>
                    <div class="row">

                        <div style="clear:both;"></div>
                        <div class="col-md-12">
                            <div class="form-group">
				<?php echo $this->Form->textarea('body', array('id' => 'ckeditor', 'maxlength' => '2000', 'value' => "", 'label' => 'Description', 'rows'=>5, 'div' => false, 'class' => 'form-control', 'placeholder'=>'Enter your message here...', 'required'=>true)); ?>
                            </div>
                        </div>
                        <div style="clear:both;"></div>
			<div class="col-md-10">
                            <div class="form-group">
				<label for="NotificationMetaTitle">Riders <span class="mandatory">*</span></label>
				<div class="riders_list col-md-12" style="margin-bottom: 10px;">

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



                        <div style="clear:both;"></div>
                        <div class="box-footer">
			    <?php echo $this->Html->link("Cancel", array('plugin' => false, 'controller' => 'Notifications', 'action' => 'index'), array('escape' => false, 'class' => 'btn btn-default')); ?>
			    <?php echo $this->Form->button('Send', array('type' => 'submit', 'class' => 'btn btn-info pull-right', 'id' => 'submit_button')); ?>
                        </div>
                    </div>
		    <?php echo $this->Form->end(); ?>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section>


<script>
    $("select").multipleSelect({
	filter: true,
	multiple: true
    });
</script>
