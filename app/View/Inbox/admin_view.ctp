

<!-- start: PAGE TITLE -->
<section id="page-title">
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mainTitle">View Message</h1>
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
            <?php echo $this->Form->create('FeedbackRequest', array('method' => 'post', 'class' => 'form', 'role' => 'form', 'autocomplete' => 'off', 'type' => 'file'));
            echo $this->Form->input('id', array('type' => 'hidden', 'required' => false));
            ?>
	    
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Feedback type :</label>
                        <?php echo $result_data['FeedbackTypeLocale']['name']; ?>
                    </div>
                </div>
		<div class="clearfix"></div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">User's name :</label>
                        <?php echo $result_data['User']['first_name'].' '.$result_data['User']['last_name']; ?>
                    </div>
                </div>
		<div class="clearfix"></div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Message :</label>
                        <?php echo $result_data['FeedbackRequest']['message']; ?>
                    </div>
                </div>
		<div class="clearfix"></div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Reply Message
			    <?php 
				if($result_data['FeedbackRequest']['status']=='P'){
				    echo '<span class="symbol required"></span>';
				}
			    ?>
			    :</label>
			
                        <?php
			if($result_data['FeedbackRequest']['status']=='P'){
			    echo $this->Form->input('reply_msg', array('type' => 'textarea', 'maxlength' => '500', 'class' =>'form-control validate[required]', 'div' => false, 'label' => false, 'required' => false));
			}else{
			    echo $result_data['FeedbackRequest']['reply_msg'];
			}
			?>
			
                    </div>
                </div>
		<div class="clearfix"></div>

            </div>
	    <?php
	    if($result_data['FeedbackRequest']['status']=='P'){ ?>
	    <div class="row">
                <div class="col-md-12">
                    <div>
                        <span class="symbol required"></span>Required Fields
                        <hr>
                    </div>
                </div>
            </div>
	    <?php } ?>
            <div class="row">
                <div class="col-md-7">
                </div>
                <div class="col-md-5">
                    <?php
		    if($result_data['FeedbackRequest']['status']=='P'){
			echo $this->Form->button('Save <i class="fa fa-arrow-circle-right"></i>',array('class' => 'btn btn-primary btn-wide pull-left_form','type' => 'submit','id' => 'submit_button'));
			echo $this->Html->link('Cancel <i class="fa fa-times-circle"></i>',
			    array('action' => 'index', 'admin' => true),
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

<?php
echo $this->Html->css(array('validationEngine.jquery'));
echo $this->Html->script(array('jquery.validationEngine-en', 'jquery.validationEngine'));
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#UserAdminAddEditForm").validationEngine();
    });
</script>