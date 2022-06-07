
<?php echo $this->Html->css(array('sweet-alert.css', 'ie9.css', 'toastr.min.css', 'select2.min.css', 'DT_bootstrap.css', 'bootstrap-datetimepicker'), null, array('inline' => false));
echo $this->Html->script(array('select2.min.js', 'jquery.dataTables.min.js', 'table-data.js', 'sweet-alert.min.js', 'ui-notifications.js', 'bootstrap-datepicker'), array('inline' => false)); ?>

<?php 
    $option_status = array('1' => 'Open', '2' => 'Assign','3'=>'Cancel','4'=>'Arrived','5'=>'Completed','6'=>'Paid');
    $option_type = array('1' => 'Now', '2' => 'Scheduled');
?>

<!-- start: PAGE TITLE -->
<section id="page-title">
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mainTitle">View</h1>
        </div>
    </div>
</section>
<!-- end: PAGE TITLE -->
<!-- Global Messages -->
<?php echo $this->Session->flash(); ?>
<!-- Global Messages End -->

<div class="container-fluid container-fullw bg-white">
    
    <?php if (!empty($result_total)) { ?>
    <div class="row">
        <div class="col-md-12">
	    <div class="row">
		
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Payment Date :</label>
                        <?php echo $date_range; ?>
                    </div>
                </div>
		 <div class="clearfix"></div>
		
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Driver Amount :</label>
		           <?php echo $result_total[0][0]['total_driver_amount']; ?>
                    </div>
                </div>
		  <div class="clearfix"></div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">Admin Amount :</label>
                        <?php echo $result_total[0][0]['total_admin_amount']; ?>
                    </div>
                </div>
		   <div class="clearfix"></div>
		
		<div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-bold">&nbsp;</label>
			<?php
			    $href_lnk = $this->Html->url(array('plugin' => false, 'controller' => 'payments', 'action' => 'pay_now',$encodedId, 'admin' => true));
			    echo $this->Html->link('Pay Now','javascript:void(0);',array('data-href' => $href_lnk, 'id' => 'pay-btn','class'=>'btn btn-primary btn-wide pull-left', 'title' => 'Click here to pay', 'escape' => false));
			    
			 ?>
                    </div>
                </div>
		    <div class="clearfix"></div>

            </div>
           
         
        </div>
    </div>
    <?php } ?>
    
</div>


<script type="text/javascript">
    jQuery(document).ready(function () {
        
        jQuery('#pay-btn').click(function () {
            var this_href = $(this).attr('data-href');
            swal({
                    title: "Are you sure?",
                    text: "You want to process payment",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes',
                    closeOnConfirm: false,
                },
                function () {
                    window.location.href = this_href;
                });
        });

    });
</script>